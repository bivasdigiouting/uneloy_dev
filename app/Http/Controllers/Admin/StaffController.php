<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StaffExport;
use App\Http\Controllers\Controller;
use App\Imports\StaffImport;
use App\Mail\StaffRegistrationMail;
use App\Repositories\Interfaces\DesignationRepositoryInterface;
use App\Repositories\Interfaces\StaffRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class StaffController extends Controller
{
    protected StaffRepositoryInterface $staffRepository;

    protected DesignationRepositoryInterface $designationRepository;

    public function __construct(
        StaffRepositoryInterface $staffRepository,
        DesignationRepositoryInterface $designationRepository
    ) {
        $this->staffRepository = $staffRepository;
        $this->designationRepository = $designationRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $staff = $this->staffRepository->getAllWithDesignation();

            return datatables()->of($staff)
                ->addIndexColumn()
                ->addColumn('designation_name', function ($staff) {
                    return $staff->designation ? $staff->designation->designation_name : 'N/A';
                })
                ->addColumn('profile_image', function ($staff) {
                    if ($staff->profile_image) {
                        return '<img src="'.Storage::url($staff->profile_image).'" alt="Profile" class="rounded-circle" width="40" height="40">';
                    }

                    return '<div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-user text-white"></i></div>';
                })
                ->addColumn('status', function ($staff) {
                    return $staff->is_active ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($staff) {
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<a href="'.route('admin.staff.show', $staff->id).'" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>';
                    $btn .= '<a href="'.route('admin.staff.edit', $staff->id).'" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>';
                    $btn .= '<button type="button" class="btn btn-sm btn-warning toggle-status" data-url="'.route('admin.staff.toggle-status', $staff->id).'" title="Toggle Status"><i class="fas fa-toggle-on"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger delete-staff" data-url="'.route('admin.staff.destroy', $staff->id).'" title="Delete"><i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['profile_image', 'status', 'action'])
                ->make(true);
        }

        return view('admin.staff.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $designations = $this->designationRepository->getActive();
        $indianStates = $this->getIndianStates();
        $indianBanks = $this->getIndianBanks();

        return view('admin.staff.create', compact('designations', 'indianStates', 'indianBanks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Personal Details
            'staff_name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_of_joining' => 'required|date',
            'date_of_birth' => 'required|date|before:today',
            'designation_id' => 'required|exists:designations,id',
            'gender' => 'required|in:Male,Female,Other',

            // Contact Details
            'address_1' => 'required|string',
            'address_2' => 'nullable|string',
            'state' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'pincode' => 'required|string|size:6',
            'mobile_no' => 'required|string|max:15',
            'email_id' => 'required|email|unique:staff,email_id',
            'location' => 'nullable|string',

            // Bank Details
            'ifsc_code' => 'required|string|size:11',
            'bank_name' => 'required|string',
            'branch_name' => 'required|string',
            'account_no' => 'required|string',
            'pan_no' => 'required|string|size:10',
            'aadhar_no' => 'required|string|size:12',
            'salary' => 'required|numeric|min:0',

            // Login Details
            'user_id' => 'required|string|unique:staff,user_id',
            'password' => 'required|string|min:8',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $staffData = $request->except(['profile_image']);
        $staffData['is_active'] = $request->has('is_active');

        // Generate temporary password if not provided
        $temporaryPassword = $staffData['password'];
        $staffData['password'] = Hash::make($staffData['password']);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $staffData['profile_image'] = $request->file('profile_image')->store('staff/profiles', 'public');
        }

        $staff = $this->staffRepository->create($staffData);

        // Send welcome email
        try {
            Mail::to($staff->email_id)->send(new StaffRegistrationMail($staff, $temporaryPassword));
            $message = 'Staff created successfully and welcome email sent.';
        } catch (\Exception $e) {
            $message = 'Staff created successfully, but email could not be sent.';
        }

        return redirect()->route('admin.staff.index')
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $staff = $this->staffRepository->find($id);

        if (! $staff) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'Staff not found.');
        }

        return view('admin.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staff = $this->staffRepository->find($id);

        if (! $staff) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'Staff not found.');
        }

        $designations = $this->designationRepository->getActive();
        $indianStates = $this->getIndianStates();
        $indianBanks = $this->getIndianBanks();

        return view('admin.staff.edit', compact('staff', 'designations', 'indianStates', 'indianBanks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $staff = $this->staffRepository->find($id);

        if (! $staff) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'Staff not found.');
        }

        $validator = Validator::make($request->all(), [
            // Personal Details
            'staff_name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date_of_joining' => 'required|date',
            'date_of_birth' => 'required|date|before:today',
            'designation_id' => 'required|exists:designations,id',
            'gender' => 'required|in:Male,Female,Other',

            // Contact Details
            'address_1' => 'required|string',
            'address_2' => 'nullable|string',
            'state' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'pincode' => 'required|string|size:6',
            'mobile_no' => 'required|string|max:15',
            'email_id' => 'required|email|unique:staff,email_id,'.$id,
            'location' => 'nullable|string',

            // Bank Details
            'ifsc_code' => 'required|string|size:11',
            'bank_name' => 'required|string',
            'branch_name' => 'required|string',
            'account_no' => 'required|string',
            'pan_no' => 'required|string|size:10',
            'aadhar_no' => 'required|string|size:12',
            'salary' => 'required|numeric|min:0',

            // Login Details
            'user_id' => 'required|string|unique:staff,user_id,'.$id,
            'password' => 'nullable|string|min:8',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $staffData = $request->except(['profile_image', 'password']);
        $staffData['is_active'] = $request->has('is_active');

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($staff->profile_image) {
                Storage::disk('public')->delete($staff->profile_image);
            }
            $staffData['profile_image'] = $request->file('profile_image')->store('staff/profiles', 'public');
        }

        // Update password only if provided
        if ($request->filled('password')) {
            $staffData['password'] = $request->password;
        }

        $this->staffRepository->update($id, $staffData);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $staff = $this->staffRepository->find($id);

        if (! $staff) {
            return response()->json(['success' => false, 'message' => 'Staff not found.'], 404);
        }

        // Delete profile image if exists
        if ($staff->profile_image) {
            Storage::disk('public')->delete($staff->profile_image);
        }

        if ($this->staffRepository->delete($id)) {
            return response()->json(['success' => true, 'message' => 'Staff deleted successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to delete staff.'], 500);
    }

    /**
     * Toggle staff status
     */
    public function toggleStatus(string $id)
    {
        if ($this->staffRepository->toggleStatus($id)) {
            return response()->json(['success' => true, 'message' => 'Staff status updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to update staff status.'], 500);
    }

    /**
     * Export staff to Excel
     */
    public function export()
    {
        return Excel::download(new StaffExport, 'staff-'.date('Y-m-d').'.xlsx');
    }

    /**
     * Import staff from Excel
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Please select a valid Excel file.');
        }

        try {
            Excel::import(new StaffImport, $request->file('file'));

            return redirect()->route('admin.staff.index')
                ->with('success', 'Staff imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error importing staff: '.$e->getMessage());
        }
    }

    /**
     * Send welcome email to new staff
     */
    private function sendWelcomeEmail($staff)
    {
        try {
            $emailData = [
                'staff_name' => $staff->staff_name,
                'user_id' => $staff->user_id,
                'email_id' => $staff->email_id,
                'designation' => $staff->designation->designation_name ?? 'N/A',
            ];

            Mail::send('emails.staff-welcome', $emailData, function ($message) use ($staff) {
                $message->to($staff->email_id, $staff->staff_name)
                    ->subject('Welcome to Our Organization');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: '.$e->getMessage());
        }
    }

    /**
     * Get Indian states list
     */
    private function getIndianStates()
    {
        return [
            'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
            'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka',
            'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram',
            'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu',
            'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
            'Andaman and Nicobar Islands', 'Chandigarh', 'Dadra and Nagar Haveli and Daman and Diu',
            'Delhi', 'Jammu and Kashmir', 'Ladakh', 'Lakshadweep', 'Puducherry',
        ];
    }

    /**
     * Get Indian banks list
     */
    private function getIndianBanks()
    {
        return [
            'State Bank of India', 'HDFC Bank', 'ICICI Bank', 'Punjab National Bank',
            'Bank of Baroda', 'Canara Bank', 'Union Bank of India', 'Bank of India',
            'Indian Bank', 'Central Bank of India', 'Indian Overseas Bank',
            'UCO Bank', 'Bank of Maharashtra', 'Punjab & Sind Bank', 'Axis Bank',
            'Kotak Mahindra Bank', 'IndusInd Bank', 'Yes Bank', 'IDFC First Bank',
            'Federal Bank', 'South Indian Bank', 'Karur Vysya Bank', 'Tamilnad Mercantile Bank',
            'Bandhan Bank', 'ESAF Small Finance Bank', 'Equitas Small Finance Bank',
        ];
    }
}
