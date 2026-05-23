<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ECardSevaEmergencyOtherPointsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ECardSevaEmergencyOtherPointsReportController extends Controller
{
    protected ECardSevaEmergencyOtherPointsRepositoryInterface $repo;

    public function __construct(ECardSevaEmergencyOtherPointsRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $statuses = ['All', 'Pending', 'Approved', 'Send Point'];

        return view('admin.benefits.emergency-ecard-seva-other-points-report.index', compact('statuses'));
    }

    public function data(Request $request)
    {
        $filters = [
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'status' => $request->input('status'),
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
            ->addColumn('image_link', function ($row) {
                $path = $row->image;
                if ($path && Storage::disk('public')->exists($path)) {
                    $url = Storage::disk('public')->url($path);

                    return '<a href="'.e($url).'" target="_blank" class="btn btn-sm btn-outline-primary">View</a>';
                }

                return '<span class="text-muted">—</span>';
            })
            ->addColumn('action', function ($row) {
                $buttons = [];
                if ($row->image && Storage::disk('public')->exists($row->image)) {
                    $buttons[] = '<a href="'.e(Storage::disk('public')->url($row->image)).'" target="_blank" class="btn btn-sm btn-light">Image</a>';
                }

                return empty($buttons) ? '<span class="text-muted">—</span>' : implode(' ', $buttons);
            })
            ->rawColumns(['image_link', 'action'])
            ->toJson();
    }
}
