<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Repositories\Interfaces\BloodDonateOtherPointsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BloodDonateOtherPointsReportController extends Controller
{
    protected BloodDonateOtherPointsRepositoryInterface $repo;

    public function __construct(BloodDonateOtherPointsRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $statuses = ['All', 'Pending', 'Approved', 'Send Point'];
        $bloodGroups = array_keys(Vendor::getBloodGroupOptions());

        return view('admin.benefits.blood-donate-other-points-report.index', compact('statuses', 'bloodGroups'));
    }

    public function data(Request $request)
    {
        $filters = [
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'status' => $request->input('status'),
            'blood_group' => $request->input('blood_group'),
            'search' => $request->input('search_text'),
        ];

        $query = $this->repo->getForDataTable($filters);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('approved', function ($row) {
                return method_exists($row, 'getApprovedCompositeAttribute') ? $row->approved_composite : trim(($row->approved_id_no ?? '').(($row->approved_id_no ?? '') && ($row->approved_name ?? '') ? ', ' : '').($row->approved_name ?? ''));
            })
            ->editColumn('approved_date', function ($row) {
                return $row->approved_date ? date('d-M-Y', strtotime($row->approved_date)) : '';
            })
            ->editColumn('request_date', function ($row) {
                return $row->request_date ? date('d-M-Y', strtotime($row->request_date)) : '';
            })
            ->editColumn('send_points_date', function ($row) {
                return $row->send_points_date ? date('d-M-Y', strtotime($row->send_points_date)) : '';
            })
            ->addColumn('proof_document_link', function ($row) {
                $path = $row->proof_document;
                if ($path && Storage::disk('public')->exists($path)) {
                    $url = Storage::disk('public')->url($path);

                    return '<a href="'.e($url).'" target="_blank" class="btn btn-sm btn-outline-primary">View</a>';
                }

                return '<span class="text-muted">—</span>';
            })
            ->addColumn('upload_proof_document_link', function ($row) {
                $path = $row->upload_proof_document;
                if ($path && Storage::disk('public')->exists($path)) {
                    $url = Storage::disk('public')->url($path);

                    return '<a href="'.e($url).'" target="_blank" class="btn btn-sm btn-outline-secondary">Download</a>';
                }

                return '<span class="text-muted">—</span>';
            })
            ->addColumn('action', function ($row) {
                // View-only actions; no create/update per requirement
                $buttons = [];
                if ($row->proof_document && Storage::disk('public')->exists($row->proof_document)) {
                    $buttons[] = '<a href="'.e(Storage::disk('public')->url($row->proof_document)).'" target="_blank" class="btn btn-sm btn-light">Proof</a>';
                }
                if ($row->upload_proof_document && Storage::disk('public')->exists($row->upload_proof_document)) {
                    $buttons[] = '<a href="'.e(Storage::disk('public')->url($row->upload_proof_document)).'" target="_blank" class="btn btn-sm btn-light">Uploaded</a>';
                }

                return empty($buttons) ? '<span class="text-muted">—</span>' : implode(' ', $buttons);
            })
            ->rawColumns(['proof_document_link', 'upload_proof_document_link', 'action'])
            ->toJson();
    }
}
