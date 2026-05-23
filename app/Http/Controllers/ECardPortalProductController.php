<?php

namespace App\Http\Controllers;

use App\Models\ECardARProductStockRequest;
use App\Models\ECardProductStockRequest;
use App\Models\ECardRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ECardPortalProductController extends Controller
{
    // Product Stock Request
    public function stockRequestIndex(Request $request)
    {
        return view('ecard.product.stock_request');
    }

    public function stockRequestStore(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'string'],
            'product_name' => ['required', 'string', 'max:150'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['nullable', 'string', 'max:50'],
            'remark' => ['nullable', 'string', 'max:255'],
        ]);

        $registration = ECardRegistration::query()->where('member_id', $validated['member_id'])->first();
        if (! $registration) {
            return back()->withErrors(['member_id' => 'Member ID not found'])->withInput();
        }

        ECardProductStockRequest::create([
            'ecard_registration_id' => $registration->id,
            'product_name' => $validated['product_name'],
            'quantity' => (float) $validated['quantity'],
            'unit' => $validated['unit'] ?? null,
            'status' => 'approved',
            'remark' => $validated['remark'] ?? null,
            'created_by_id' => Auth::guard('ecard')->id(),
            'approved_by_id' => Auth::guard('ecard')->id(),
        ]);

        return redirect()->route('ecard.product.stock.request.index')->with('success', 'Product stock request submitted successfully');
    }

    // A & R Product Stock Request
    public function arStockRequestIndex(Request $request)
    {
        return view('ecard.product.ar_stock_request');
    }

    public function arStockRequestStore(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'string'],
            'product_name' => ['required', 'string', 'max:150'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['nullable', 'string', 'max:50'],
            'remark' => ['nullable', 'string', 'max:255'],
        ]);

        $registration = ECardRegistration::query()->where('member_id', $validated['member_id'])->first();
        if (! $registration) {
            return back()->withErrors(['member_id' => 'Member ID not found'])->withInput();
        }

        ECardARProductStockRequest::create([
            'ecard_registration_id' => $registration->id,
            'product_name' => $validated['product_name'],
            'quantity' => (float) $validated['quantity'],
            'unit' => $validated['unit'] ?? null,
            'status' => 'approved',
            'remark' => $validated['remark'] ?? null,
            'created_by_id' => Auth::guard('ecard')->id(),
            'approved_by_id' => Auth::guard('ecard')->id(),
        ]);

        return redirect()->route('ecard.product.ar.stock.request.index')->with('success', 'A & R product stock request submitted successfully');
    }

    // A & R Product Stock Report
    public function arStockReportIndex(Request $request)
    {
        return view('ecard.product.ar_stock_report');
    }

    public function arStockReportData(Request $request)
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

    // Stock Report (combined standard + A&R)
    public function stockReportIndex(Request $request)
    {
        return view('ecard.product.stock_report');
    }

    public function stockReportData(Request $request)
    {
        // Union two tables with common columns
        $standard = ECardProductStockRequest::query()
            ->select(['product_name', 'quantity', 'unit', 'created_at']);
        $ar = ECardARProductStockRequest::query()
            ->select(['product_name', 'quantity', 'unit', 'created_at']);

        // Aggregate summary per product
        $union = DB::query()->fromSub($standard->unionAll($ar), 'u')
            ->select(['product_name', DB::raw('SUM(quantity) as total_quantity'), DB::raw('MAX(unit) as unit')])
            ->groupBy('product_name');

        if ($request->filled('product_name')) {
            $union->where('product_name', 'like', '%'.trim($request->input('product_name')).'%');
        }

        return DataTables::of($union)
            ->addIndexColumn()
            ->editColumn('total_quantity', function ($row) {
                return number_format((float) $row->total_quantity, 2).($row->unit ? ' '.$row->unit : '');
            })
            ->make(true);
    }
}
