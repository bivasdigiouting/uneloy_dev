<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VendorNotificationMasterController extends Controller
{
    protected NotificationRepositoryInterface $notifications;

    public function __construct(NotificationRepositoryInterface $notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * Display Vendor Notification Master page or DataTables JSON when AJAX.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->data($request);
        }

        $states = State::active()->ordered()->get(['id', 'state_name']);

        return view('admin.vendor-notification.index', compact('states'));
    }

    /**
     * Store a new vendor notification (supports multi-state).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // type is fixed to 'vendor'
            'state_ids' => 'nullable|array',
            'state_ids.*' => 'integer|exists:states,id',
            'district_id' => 'nullable|exists:districts,id',
            'city_id' => 'nullable|exists:cities,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'message' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('notifications', 'public');
        }

        $baseData = [
            'type' => 'vendor',
            'district_id' => $validated['district_id'] ?? null,
            'city_id' => $validated['city_id'] ?? null,
            'from_date' => $validated['from_date'] ?? null,
            'to_date' => $validated['to_date'] ?? null,
            'message' => $validated['message'],
            'image' => $path,
            'status' => 'active',
        ];

        $stateIds = $validated['state_ids'] ?? [];
        $this->notifications->bulkCreateForStates($baseData, $stateIds);

        return redirect()->route('admin.vendor.notification-master.index')
            ->with('success', 'Vendor notification(s) created successfully.');
    }

    /**
     * DataTables server-side data for vendor notifications.
     */
    public function data(Request $request)
    {
        $query = $this->notifications->getForDataTable()->where('type', 'vendor');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('type', function ($n) {
                return 'Vendor';
            })
            ->addColumn('state_name', function ($n) {
                return $n->state ? $n->state->state_name : 'All';
            })
            ->addColumn('district_name', function ($n) {
                return $n->district ? $n->district->district_name : 'All';
            })
            ->addColumn('city_name', function ($n) {
                return $n->city ? $n->city->city_name : 'All';
            })
            ->addColumn('from_date', function ($n) {
                return $n->from_date ? $n->from_date->format('Y-m-d') : '';
            })
            ->addColumn('to_date', function ($n) {
                return $n->to_date ? $n->to_date->format('Y-m-d') : '';
            })
            ->addColumn('image', function ($n) {
                $url = $n->image_url;

                return $url ? '<img src="'.e($url).'" alt="Image" style="height:40px">' : '';
            })
            ->addColumn('message_short', function ($n) {
                return str($n->message)->limit(80);
            })
            ->addColumn('action', function ($n) {
                return '<button type="button" class="btn btn-sm btn-danger" onclick="deleteVendorNotification('.$n->id.')"><i class="ti ti-trash"></i></button>';
            })
            ->rawColumns(['image', 'action'])
            ->make(true);
    }

    /**
     * Delete a vendor notification.
     */
    public function destroy(int $id)
    {
        $deleted = $this->notifications->delete($id);

        return response()->json(['success' => $deleted]);
    }
}
