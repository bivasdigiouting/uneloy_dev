<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\AdvertisementRequestRepositoryInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ApproveRejectAdvertisementReportController extends Controller
{
    protected AdvertisementRequestRepositoryInterface $repo;

    public function __construct(AdvertisementRequestRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return view('admin.reports.advertisements.approve_reject.index');
    }

    public function data(Request $request)
    {
        $filters = [
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'type' => $request->input('type'),
            'department' => $request->input('department'),
            'status' => $request->input('status'),
            'search' => $request->input('search'),
        ];

        $query = $this->repo->getForReport($filters);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-primary" data-id="'.$row->id.'">View</button>';
            })
            ->editColumn('from_date', function ($row) {
                return optional($row->from_date)->format('Y-m-d');
            })
            ->editColumn('to_date', function ($row) {
                return optional($row->to_date)->format('Y-m-d');
            })
            ->editColumn('status', function ($row) {
                return ucfirst($row->request_status);
            })
            ->editColumn('advertisement_type', function ($row) {
                return $row->advertisement_type;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
