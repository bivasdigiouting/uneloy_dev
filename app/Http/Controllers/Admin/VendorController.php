<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\VendorWelcomeMail;
use App\Models\Vendor;
use App\Repositories\Interfaces\ProductCategoryRepositoryInterface;
use App\Repositories\Interfaces\VendorRepositoryInterface;
use App\Repositories\Interfaces\VendorTypeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    protected $vendorRepository;

    protected $vendorTypeRepository;

    protected $productCategoryRepository;

    public function __construct(
        VendorRepositoryInterface $vendorRepository,
        VendorTypeRepositoryInterface $vendorTypeRepository,
        ProductCategoryRepositoryInterface $productCategoryRepository
    ) {
        $this->vendorRepository = $vendorRepository;
        $this->vendorTypeRepository = $vendorTypeRepository;
        $this->productCategoryRepository = $productCategoryRepository;
    }

    /**
     * Display a listing of the vendors.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $vendors = $this->vendorRepository->getForDataTables();

            return DataTables::of($vendors)
                ->addIndexColumn()
                ->addColumn('status', function ($vendor) {
                    return $vendor->status === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($vendor) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="'.route('admin.vendors.edit', $vendor->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-'.($vendor->status === 'active' ? 'warning' : 'success').'" onclick="toggleVendorStatus('.$vendor->id.')" title="'.($vendor->status === 'active' ? 'Deactivate' : 'Activate').'"><i class="ti ti-'.($vendor->status === 'active' ? 'eye-off' : 'eye').'"></i></button>';
                    $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteVendor('.$vendor->id.')" title="Delete"><i class="ti ti-trash"></i></button>';
                    $actions .= '</div>';

                    return $actions;
                })
                ->editColumn('created_at', function ($vendor) {
                    return $vendor->created_at->format('d M Y, h:i A');
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.vendors.index');
    }

    /**
     * Show the form for creating a new vendor.
     */
    public function create(): View
    {
        $vendorTypes = $this->vendorTypeRepository->getActive();
        $productCategories = $this->productCategoryRepository->getActive();

        return view('admin.vendors.create', compact('vendorTypes', 'productCategories'));
    }

    /**
     * Store a newly created vendor in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // Business Details
            'business_registration_category' => 'required|in:Private Limited,Proprietorship,Partnership,Limited,NGO',
            'business_name' => 'required|string|max:255',
            'mobile_country_code' => 'required|string|max:5',
            'mobile_no' => 'required|string|max:15|unique:vendors,mobile_no',
            'whatsapp_country_code' => 'nullable|string|max:5',
            'whatsapp_no' => 'nullable|string|max:15',
            'gmail_id' => 'required|email|max:255|unique:vendors,gmail_id',
            'business_full_address' => 'required|string',
            'business_gst_no' => 'nullable|string|max:15',
            'contact_person' => 'required|string|max:255',
            'contact_person_designation' => 'required|string|max:255',
            'facility' => 'nullable|string',
            'about_us' => 'nullable|string',
            'business_location' => 'required|string',

            // Business Product
            'product_categories' => 'nullable|array',
            'product_categories.*' => 'string',

            // Business Bank Details
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'account_no' => 'required|string|max:20',
            'ifsc_code' => 'required|string|max:11',
            'pan_no' => 'required|string|max:10|unique:vendors,pan_no',
            'aadhar_no' => 'required|string|max:12|unique:vendors,aadhar_no',
            'upi_no' => 'required|string|max:255',

            // Personal Details
            'vendor_type' => 'required|string',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'fathers_name' => 'nullable|string|max:255',
            'mothers_name' => 'nullable|string|max:255',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'marital_status' => 'required|in:Single,Married,Others',

            // Contact Details
            'current_address' => 'required|string',
            'permanent_address' => 'required|string',
            'nationality' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'pincode' => 'required|string|max:6',
            'contact_mobile_country_code' => 'required|string|max:5',
            'contact_mobile_no' => 'required|string|max:15|unique:vendors,contact_mobile_no',
            'contact_whatsapp_country_code' => 'nullable|string|max:5',
            'contact_whatsapp_no' => 'nullable|string|max:15',
            'contact_gmail_id' => 'required|email|max:255|unique:vendors,contact_gmail_id',
            'current_live_location' => 'nullable|string',

            // Education & Qualification Details
            'last_qualification' => 'required|string|max:255',
            'work_type' => 'required|string|max:255',
            'work_experience' => 'required|string|max:255',

            // Terms & Conditions
            'terms_accepted' => 'required|accepted',

            'status' => 'required|in:active,inactive',
        ]);

        try {
            $data = $request->all();
            $data['product_categories'] = $request->input('product_categories', []);
            $data['vendor_name'] = $data['vendor_name']
                ?? $data['business_name']
                ?? trim((string) (($data['first_name'] ?? '').' '.($data['last_name'] ?? '')));
            if (! Schema::hasColumn('vendors', 'vendor_name')) {
                unset($data['vendor_name']);
            }

            // Generate unique 8-digit vendor number
            do {
                $vendorNumber = str_pad(random_int(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            } while (Vendor::where('vendor_number', $vendorNumber)->exists());

            // Generate random password
            $password = Str::random(12);

            // Add generated fields to data
            $data['vendor_number'] = $vendorNumber;
            $data['password'] = Hash::make($password);

            $vendor = $this->vendorRepository->createVendor($data);

            // Send welcome email with login credentials
            try {
                Mail::to($vendor->gmail_id)->send(new VendorWelcomeMail($vendor, $password));
            } catch (\Exception $mailException) {
                // Log email error but don't fail the registration
                \Log::error('Failed to send vendor welcome email: '.$mailException->getMessage());
            }

            return redirect()->route('admin.vendors.index')
                ->with('success', 'Vendor registered successfully. Login credentials have been sent to the registered email.');
        } catch (\Exception $e) {
            \Log::error('Vendor registration error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to register vendor. Please try again. Error: '.$e->getMessage());
        }
    }

    /**
     * Display the specified vendor.
     */
    public function show(int $id): View
    {
        $vendor = $this->vendorRepository->findVendor($id);

        if (! $vendor) {
            abort(404, 'Vendor not found');
        }

        return view('admin.vendors.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified vendor.
     */
    public function edit(int $id): View
    {
        $vendor = $this->vendorRepository->findVendor($id);

        if (! $vendor) {
            abort(404, 'Vendor not found');
        }

        $vendorTypes = $this->vendorTypeRepository->getActive();
        $productCategories = $this->productCategoryRepository->getActive();

        return view('admin.vendors.edit', compact('vendor', 'vendorTypes', 'productCategories'));
    }

    /**
     * Update the specified vendor in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $vendor = $this->vendorRepository->findVendor($id);

        if (! $vendor) {
            return redirect()->route('admin.vendors.index')
                ->with('error', 'Vendor not found.');
        }

        $request->validate([
            // Business Details
            'business_registration_category' => 'required|in:Private Limited,Proprietorship,Partnership,Limited,NGO',
            'business_name' => 'required|string|max:255',
            'mobile_country_code' => 'required|string|max:5',
            'mobile_no' => [
                'required',
                'string',
                'max:15',
                Rule::unique('vendors', 'mobile_no')->ignore($id),
            ],
            'whatsapp_country_code' => 'nullable|string|max:5',
            'whatsapp_no' => 'nullable|string|max:15',
            'gmail_id' => [
                'required',
                'email',
                'max:255',
                Rule::unique('vendors', 'gmail_id')->ignore($id),
            ],
            'business_full_address' => 'required|string',
            'business_gst_no' => 'nullable|string|max:15',
            'contact_person' => 'required|string|max:255',
            'contact_person_designation' => 'required|string|max:255',
            'facility' => 'nullable|string',
            'about_us' => 'nullable|string',
            'business_location' => 'required|string',

            // Business Product
            'product_categories' => 'nullable|array',
            'product_categories.*' => 'string',

            // Business Bank Details
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'account_no' => 'required|string|max:20',
            'ifsc_code' => 'required|string|max:11',
            'pan_no' => [
                'required',
                'string',
                'max:10',
                Rule::unique('vendors', 'pan_no')->ignore($id),
            ],
            'aadhar_no' => [
                'required',
                'string',
                'max:12',
                Rule::unique('vendors', 'aadhar_no')->ignore($id),
            ],
            'upi_no' => 'required|string|max:255',

            // Personal Details
            'vendor_type' => 'required|string',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'fathers_name' => 'nullable|string|max:255',
            'mothers_name' => 'nullable|string|max:255',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'marital_status' => 'required|in:Single,Married,Others',

            // Contact Details
            'current_address' => 'required|string',
            'permanent_address' => 'required|string',
            'nationality' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'pincode' => 'required|string|max:6',
            'contact_mobile_country_code' => 'required|string|max:5',
            'contact_mobile_no' => [
                'required',
                'string',
                'max:15',
                Rule::unique('vendors', 'contact_mobile_no')->ignore($id),
            ],
            'contact_whatsapp_country_code' => 'nullable|string|max:5',
            'contact_whatsapp_no' => 'nullable|string|max:15',
            'contact_gmail_id' => [
                'required',
                'email',
                'max:255',
                Rule::unique('vendors', 'contact_gmail_id')->ignore($id),
            ],
            'current_live_location' => 'nullable|string',

            // Education & Qualification Details
            'last_qualification' => 'required|string|max:255',
            'work_type' => 'required|string|max:255',
            'work_experience' => 'required|string|max:255',

            'status' => 'required|in:active,inactive',
        ]);

        try {
            $data = $request->all();
            $data['product_categories'] = $request->input('product_categories', []);
            $data['vendor_name'] = $data['vendor_name']
                ?? $data['business_name']
                ?? trim((string) (($data['first_name'] ?? '').' '.($data['last_name'] ?? '')));
            if (! Schema::hasColumn('vendors', 'vendor_name')) {
                unset($data['vendor_name']);
            }
            if (! array_key_exists('terms_accepted', $data)) {
                $data['terms_accepted'] = $vendor->terms_accepted;
            }

            $this->vendorRepository->updateVendor($id, $data);

            return redirect()->route('admin.vendors.index')
                ->with('success', 'Vendor updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update vendor. Please try again.');
        }
    }

    /**
     * Remove the specified vendor from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $vendor = $this->vendorRepository->findVendor($id);

            if (! $vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found.',
                ], 404);
            }

            $this->vendorRepository->deleteVendor($id);

            return response()->json([
                'success' => true,
                'message' => 'Vendor deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vendor. Please try again.',
            ], 500);
        }
    }

    /**
     * Toggle vendor status
     */
    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $vendor = $this->vendorRepository->findVendor($id);

            if (! $vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found.',
                ], 404);
            }

            $this->vendorRepository->toggleStatus($id);

            // Get updated vendor
            $updatedVendor = $this->vendorRepository->findVendor($id);

            return response()->json([
                'success' => true,
                'message' => 'Vendor status updated successfully.',
                'status' => $updatedVendor->status,
                'formatted_status' => $updatedVendor->formatted_status,
                'status_badge_class' => $updatedVendor->status_badge_class,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vendor status. Please try again.',
            ], 500);
        }
    }
}
