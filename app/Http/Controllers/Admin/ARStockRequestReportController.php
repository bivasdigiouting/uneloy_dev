<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ECardARProductStockRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ARStockRequestReportController extends Controller
{
    /** Show A & R Req. Stock Report page */
    public function index()
    {
        return view('admin.stock-ar-req.report');
    }

    /** Data endpoint for A & R Req. Stock Report */
    public function data(Request $request)
    {
        $query = ECardARProductStockRequest::query()->with('registration')->orderByDesc('created_at');

        // Filters
        if ($request->filled('member_id')) {
            $memberId = trim($request->input('member_id'));
            $query->whereHas('registration', function ($q) use ($memberId) {
                $q->where('member_id', $memberId);
            });
        }
        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%'.trim($request->input('product_name')).'%');
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('member_id', function ($row) {
                return optional($row->registration)->member_id ?? '-';
            })
            ->editColumn('quantity', function ($row) {
                return number_format((float) $row->quantity, 2).($row->unit ? ' '.$row->unit : '');
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('Y-m-d H:i') : '';
            })
            ->make(true);
    }
}
