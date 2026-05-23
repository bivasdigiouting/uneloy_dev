<?php

namespace App\Http\Controllers;

use App\Models\ECardLoginHistory;
use App\Models\LevelWiseProductCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ECardPortalReportController extends Controller
{
    private function getCommissionMetrics($userId, $typeKeyword)
    {
        $baseQuery = \App\Models\ECardWalletTransaction::where('ecard_registration_id', $userId)
            ->where('transaction_type', 'add')
            ->where(function ($q) {
                $q->where('narration', 'like', '%commission%')
                  ->orWhere('reference_type', 'like', '%commission%');
            });

        if ($typeKeyword) {
            $baseQuery->where(function ($q) use ($typeKeyword) {
                $q->where('narration', 'like', "%$typeKeyword%")
                  ->orWhere('reference_type', 'like', "%$typeKeyword%");
            });
        }

        return [
            'totalCommission' => (clone $baseQuery)->sum('amount'),
            'thisMonthCommission' => (clone $baseQuery)->whereMonth('created_at', now()->month)->sum('amount'),
            'todayCommission' => (clone $baseQuery)->whereDate('created_at', now()->toDateString())->sum('amount'),
        ];
    }

    private function getCommissionData($userId, $typeKeyword, Request $request)
    {
        $query = \App\Models\ECardWalletTransaction::query()
            ->where('ecard_registration_id', $userId)
            ->where('transaction_type', 'add')
            ->where(function ($q) {
                $q->where('narration', 'like', '%commission%')
                  ->orWhere('reference_type', 'like', '%commission%');
            })
            ->orderByDesc('created_at');

        if ($typeKeyword) {
            $query->where(function ($q) use ($typeKeyword) {
                $q->where('narration', 'like', "%$typeKeyword%")
                  ->orWhere('reference_type', 'like', "%$typeKeyword%");
            });
        }

        if ($request->filled('operation')) {
            $operation = trim($request->input('operation'));
            $query->where(function ($q) use ($operation) {
                $q->where('narration', 'like', "%$operation%")
                  ->orWhere('reference_type', 'like', "%$operation%");
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('M d, Y H:i A') : '';
            })
            ->addColumn('operation_name', function ($row) {
                return $row->narration ?? ucwords(str_replace('_', ' ', $row->reference_type));
            })
            ->editColumn('amount', function ($row) {
                return '<span class="text-success fw-bold">+ ₹ '.number_format($row->amount, 2).'</span>';
            })
            ->rawColumns(['amount'])
            ->make(true);
    }

    // Single generic logic call for level commission
    public function levelCommissionIndex(Request $request) {
        $metrics = $this->getCommissionMetrics(Auth::guard('ecard')->id(), null);
        return view('ecard.report.level_commission', $metrics);
    }

    public function levelCommissionData(Request $request) {
        return $this->getCommissionData(Auth::guard('ecard')->id(), null, $request);
    }

    // Registration Commission
    public function registrationCommissionIndex(Request $request) {
        $metrics = $this->getCommissionMetrics(Auth::guard('ecard')->id(), 'registration');
        return view('ecard.report.registration_commission', $metrics);
    }
    public function registrationCommissionData(Request $request) {
        return $this->getCommissionData(Auth::guard('ecard')->id(), 'registration', $request);
    }

    // Wallet Commission
    public function walletCommissionIndex(Request $request) {
        $metrics = $this->getCommissionMetrics(Auth::guard('ecard')->id(), 'wallet');
        return view('ecard.report.wallet_commission', $metrics);
    }
    public function walletCommissionData(Request $request) {
        return $this->getCommissionData(Auth::guard('ecard')->id(), 'wallet', $request);
    }

    // Purchase Commission
    public function purchaseCommissionIndex(Request $request) {
        $metrics = $this->getCommissionMetrics(Auth::guard('ecard')->id(), 'purchase');
        return view('ecard.report.purchase_commission', $metrics);
    }
    public function purchaseCommissionData(Request $request) {
        return $this->getCommissionData(Auth::guard('ecard')->id(), 'purchase', $request);
    }

    // EPS Commission
    public function epsCommissionIndex(Request $request) {
        $metrics = $this->getCommissionMetrics(Auth::guard('ecard')->id(), 'eps');
        return view('ecard.report.eps_commission', $metrics);
    }
    public function epsCommissionData(Request $request) {
        return $this->getCommissionData(Auth::guard('ecard')->id(), 'eps', $request);
    }

    // Login History Report
    public function loginHistoryIndex(Request $request)
    {
        return view('ecard.report.login_history');
    }

    public function loginHistoryData(Request $request)
    {
        $userId = Auth::guard('ecard')->id();
        $query = ECardLoginHistory::query()
            ->where('ecard_registration_id', $userId)
            ->orderByDesc('logged_in_at');

        if ($request->filled('from_date')) {
            $query->whereDate('logged_in_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('logged_in_at', '<=', $request->input('to_date'));
        }
        if ($request->filled('ip')) {
            $query->where('ip_address', 'like', '%'.trim($request->input('ip')).'%');
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('logged_in_at', function ($row) {
                return $row->logged_in_at ? $row->logged_in_at->format('Y-m-d H:i') : '';
            })
            ->editColumn('logged_out_at', function ($row) {
                return $row->logged_out_at ? $row->logged_out_at->format('Y-m-d H:i') : '—';
            })
            ->make(true);
    }

    // TDS Report
    public function tdsReportIndex(Request $request)
    {
        return view('ecard.report.tds_report');
    }

    public function tdsReportData(Request $request)
    {
        $userId = Auth::guard('ecard')->id();
        $query = \App\Models\ECardWalletTransaction::query()
            ->where('ecard_registration_id', $userId)
            ->where('transaction_type', 'add')
            ->where(function ($q) {
                $q->where('narration', 'like', '%commission%')
                  ->orWhere('reference_type', 'like', '%commission%');
            })
            ->orderByDesc('created_at');

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('M d, Y H:i A') : '';
            })
            ->addColumn('source', function ($row) {
                return $row->narration ?? ucwords(str_replace('_', ' ', $row->reference_type));
            })
            ->addColumn('gross_amount', function ($row) {
                $gross = $row->amount / 0.95;
                return '₹ ' . number_format($gross, 2);
            })
            ->addColumn('tds_deducted', function ($row) {
                $tds = ($row->amount / 0.95) * 0.05;
                return '<span class="text-danger fw-bold">₹ ' . number_format($tds, 2) . '</span>';
            })
            ->addColumn('net_amount', function ($row) {
                return '<span class="text-success fw-bold">+ ₹ ' . number_format($row->amount, 2) . '</span>';
            })
            ->rawColumns(['tds_deducted', 'net_amount'])
            ->make(true);
    }
}
