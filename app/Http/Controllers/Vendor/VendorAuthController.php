<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\TwoFactorOtp;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class VendorAuthController extends Controller
{
    /**
     * Show the vendor login form
     */
    public function showLoginForm()
    {
        return view('vendor.auth.login');
    }

    /**
     * Handle vendor login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Find vendor by email
        $vendor = Vendor::where('gmail_id', $request->email)
            ->where('status', 'active')
            ->first();

        if (! $vendor || ! Hash::check($request->password, $vendor->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $email = trim((string) ($vendor->gmail_id ?? ''));
        if ($email === '') {
            throw ValidationException::withMessages([
                'email' => ['Email is not registered for this vendor. Please contact admin.'],
            ]);
        }

        [$otp, $plainOtp] = TwoFactorOtp::issue('vendor', (int) $vendor->id, $email, 10);
        try {
            Mail::to($email)->send(new OtpMail($plainOtp));
        } catch (\Throwable $e) {
            Log::error('Vendor OTP mail send failed', [
                'vendor_id' => (int) $vendor->id,
                'message' => $e->getMessage(),
            ]);

            if (! app()->environment('local')) {
                throw ValidationException::withMessages([
                    'email' => ['Unable to send OTP email right now. Please try again later.'],
                ]);
            }

            $request->session()->put('twofa.vendor.dev_otp', $plainOtp);
        }

        $request->session()->put('twofa.vendor', [
            'otp_id' => (int) $otp->id,
            'vendor_id' => (int) $vendor->id,
            'email' => $email,
        ]);

        return redirect()->route('vendor.login.otp')->with('success', 'OTP sent to your registered email.');
    }

    public function showOtpForm(Request $request)
    {
        $pending = (array) $request->session()->get('twofa.vendor', []);
        $email = (string) ($pending['email'] ?? '');
        if ($email === '' || empty($pending['otp_id']) || empty($pending['vendor_id'])) {
            return redirect()->route('vendor.login')->with('error', 'Please login again.');
        }

        $maskedEmail = preg_replace('/(^.).*(@.*$)/', '$1***$2', $email) ?: $email;
        $devOtp = $request->session()->get('twofa.vendor.dev_otp');

        return view('vendor.auth.otp', [
            'maskedEmail' => $maskedEmail,
            'devOtp' => $devOtp,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $pending = (array) $request->session()->get('twofa.vendor', []);
        $otpId = (int) ($pending['otp_id'] ?? 0);
        $vendorId = (int) ($pending['vendor_id'] ?? 0);
        if ($otpId <= 0 || $vendorId <= 0) {
            return redirect()->route('vendor.login')->with('error', 'Please login again.');
        }

        $result = TwoFactorOtp::verifyAndConsume($otpId, 'vendor', (string) $request->otp);
        if (! ($result['success'] ?? false)) {
            return back()->withErrors(['otp' => $result['message'] ?? 'OTP verification failed'])->withInput();
        }

        $vendor = Vendor::where('id', $vendorId)->where('status', 'active')->first();
        if (! $vendor) {
            $request->session()->forget('twofa.vendor');
            return redirect()->route('vendor.login')->with('error', 'Vendor not found. Please login again.');
        }

        session(['vendor_id' => $vendor->id]);
        session(['vendor_data' => $vendor]);
        $request->session()->forget('twofa.vendor');

        return redirect()->route('vendor.dashboard')->with('success', 'Welcome to Vendor Portal!');
    }

    public function resendOtp(Request $request)
    {
        $pending = (array) $request->session()->get('twofa.vendor', []);
        $vendorId = (int) ($pending['vendor_id'] ?? 0);
        $email = (string) ($pending['email'] ?? '');
        if ($vendorId <= 0 || $email === '') {
            return redirect()->route('vendor.login')->with('error', 'Please login again.');
        }

        $existingOtpId = (int) ($pending['otp_id'] ?? 0);
        if ($existingOtpId > 0) {
            $existing = TwoFactorOtp::query()->find($existingOtpId);
            if ($existing && ! $existing->canResend(60)) {
                return back()->with('error', 'Please wait before requesting a new OTP.');
            }
        }

        [$otp, $plainOtp] = TwoFactorOtp::issue('vendor', $vendorId, $email, 10);
        try {
            Mail::to($email)->send(new OtpMail($plainOtp));
            $request->session()->forget('twofa.vendor.dev_otp');
        } catch (\Throwable $e) {
            Log::error('Vendor OTP resend mail send failed', [
                'vendor_id' => (int) $vendorId,
                'message' => $e->getMessage(),
            ]);

            if (! app()->environment('local')) {
                return back()->with('error', 'Unable to resend OTP email right now. Please try again later.');
            }

            $request->session()->put('twofa.vendor.dev_otp', $plainOtp);
        }

        $request->session()->put('twofa.vendor.otp_id', (int) $otp->id);

        return back()->with('success', 'OTP resent to your email.');
    }

    /**
     * Show vendor dashboard
     */
    public function dashboard()
    {
        $vendor = $this->getAuthenticatedVendor();

        if (! $vendor) {
            return redirect()->route('vendor.login')->with('error', 'Please login to access vendor portal.');
        }

        $activePage = 'Dashboard';

        // KPI metrics (DB-driven)
        $totalProducts = \App\Models\Product::where('vendor_id', $vendor->id)->count();

        // Orders: try vendor_orders, else fallback to orders/user_orders, else 0.
        $ordersTable = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('vendor_orders')) {
                $ordersTable = 'vendor_orders';
            } elseif (\Illuminate\Support\Facades\Schema::hasTable('orders')) {
                $ordersTable = 'orders';
            } elseif (\Illuminate\Support\Facades\Schema::hasTable('user_orders')) {
                $ordersTable = 'user_orders';
            }
        } catch (\Throwable $e) {
            $ordersTable = null;
        }

        $totalOrders = 0;
        $pendingOrders = 0;

        if ($ordersTable) {
            // Detect which status column exists (common candidates)
            $statusCol = 'status';
            if (! \Illuminate\Support\Facades\Schema::hasColumn($ordersTable, $statusCol)) {
                if (\Illuminate\Support\Facades\Schema::hasColumn($ordersTable, 'order_status')) {
                    $statusCol = 'order_status';
                } else {
                    $statusCol = null;
                }
            }

            $base = \Illuminate\Support\Facades\DB::table($ordersTable);
            if (\Illuminate\Support\Facades\Schema::hasColumn($ordersTable, 'seller_id')) {
                $base->where('seller_id', $vendor->id);
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn($ordersTable, 'vendor_id')) {
                $base->where('vendor_id', $vendor->id);
            }

            $totalOrders = (int) ($base->count() ?? 0);

            if ($statusCol) {
                // Pending heuristics based on typical order status values.
                $pendingOrders = (int) (\Illuminate\Support\Facades\DB::table($ordersTable)
                    ->when(\Illuminate\Support\Facades\Schema::hasColumn($ordersTable, 'seller_id'), function ($q) use ($vendor) {
                        $q->where('seller_id', $vendor->id);
                    })
                    ->when(\Illuminate\Support\Facades\Schema::hasColumn($ordersTable, 'vendor_id'), function ($q) use ($vendor) {
                        $q->where('vendor_id', $vendor->id);
                    })
                    ->whereIn($statusCol, ['Pending', 'pending', 'Awaiting', 'Processing', 'processing', 'Shipped'])
                    ->count());
            }
        }

        // Earnings: use vendor wallet transactions (credits - debits), DB-driven.
        $earnings = 0.0;
        if (\Illuminate\Support\Facades\Schema::hasTable('vendor_wallet_transactions')) {
            $earnings = (float) (\App\Models\VendorWalletTransaction::where('vendor_id', $vendor->id)
                ->selectRaw("SUM(CASE WHEN transaction_type = 'credit' THEN amount ELSE 0 END) - SUM(CASE WHEN transaction_type = 'debit' THEN amount ELSE 0 END) as net")
                ->value('net') ?? 0);
        }

        return view('vendor.dashboard', compact(
            'vendor',
            'activePage',
            'totalProducts',
            'totalOrders',
            'pendingOrders',
            'earnings'
        ));
    }


    /**
     * Handle vendor logout
     */
    public function logout(Request $request)
    {
        session()->forget(['vendor_id', 'vendor_data']);
        session()->flush();

        return redirect()->route('vendor.login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Get authenticated vendor
     */
    private function getAuthenticatedVendor()
    {
        $vendorId = session('vendor_id');

        if (! $vendorId) {
            return null;
        }

        return Vendor::where('id', $vendorId)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Vendor profile page
     */
    public function profile()
    {
        $vendor = $this->getAuthenticatedVendor();

        if (! $vendor) {
            return redirect()->route('vendor.login')->with('error', 'Please login to access vendor portal.');
        }

        $activePage = 'Profile';

        return view('vendor.profile', compact('vendor', 'activePage'));
    }

    public function page(Request $request)
    {
        $vendor = $this->getAuthenticatedVendor();

        if (! $vendor) {
            return redirect()->route('vendor.login')->with('error', 'Please login to access vendor portal.');
        }

        $page = (string) $request->route('page');

        $titles = [
            'billing' => 'Billing',
            'products' => 'Products',
            'inventory' => 'Inventory',
            'payments' => 'Payments',
            'ads' => 'Ads & Promotions',
            'camping' => 'Free Camping',
            'settlements' => 'Settlements',
            'staff' => 'Staff',
            'payroll' => 'Payroll',
            'reports' => 'Reports',
            'settings' => 'Settings',
        ];

        $title = $titles[$page] ?? 'Vendor';
        $activePage = $title;

        if ($page === 'staff' && view()->exists("vendor.staff")) {
            $vendorId = $vendor->id;
            // DB-driven staff list (no random/mock performance)
            $vendorStaffs = \App\Models\VendorStaff::where('vendor_id', $vendorId)->latest()->get();

            // If the staff table has performance_score / is_online columns, the blade will show them directly.
            // Otherwise, they will appear as null/empty which is acceptable for now.
            return view("vendor.staff", compact('vendor', 'title', 'activePage', 'vendorStaffs'));
        }


        if ($page === 'reports' && view()->exists("vendor.reports")) {
            $range = request('range', 'last_30_days');
            $modifier = $range === 'this_year' ? 4 : ($range === 'last_quarter' ? 2 : 1);

            // DB-driven reports.
            // This repo doesn't have a dedicated vendor sales table, so we derive analytics from available DB data:
            // - Revenue: use sum of vendor wallet credits grouped by month.
            // - Profit: if we cannot separate profit, keep it as 0 (no mock).
            // - Category split: derived from vendor products by category (count-based).

            $months = [];
            $revenueData = [];
            $profitData = [];

            // Apply selected range to chart aggregation.
            // We keep MONTH buckets to match the blade layout.
            $range = (string) $range;
            $rangeMonths = match ($range) {
                'last_30_days' => 1,   // last 30 days -> show last 1 month bucket
                'last_quarter' => 3,   // last 3 months
                'this_year' => 6,      // UI says last 6 months; keep it consistent
                default => 6,
            };

            // Build buckets: last N months inclusive (e.g., 6 => 6 buckets)
            for ($i = $rangeMonths - 1; $i >= 0; $i--) {
                $monthStart = now()->subMonths($i)->startOfMonth();
                $months[] = $monthStart->format('M Y');
                $revenueData[] = 0;
                $profitData[] = 0;
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('vendor_wallet_transactions')) {
                for ($i = $rangeMonths - 1; $i >= 0; $i--) {
                    $monthStart = now()->subMonths($i)->startOfMonth();
                    $monthEnd = $monthStart->copy()->endOfMonth();

                    $sum = \App\Models\VendorWalletTransaction::query()
                        ->where('vendor_id', $vendor->id)
                        ->where('transaction_type', 'credit')
                        ->whereBetween('created_at', [$monthStart, $monthEnd])
                        ->sum('amount');

                    $idx = ($rangeMonths - 1) - $i;
                    $revenueData[$idx] = (float) $sum;
                    // Profit not available => 0
                    $profitData[$idx] = 0;
                }
            }


            // Category split based on product counts in each category
            $categoryRows = \App\Models\Product::query()
                ->where('vendor_id', $vendor->id)
                ->when(\Illuminate\Support\Facades\Schema::hasColumn('products', 'category'), function ($q) {
                    $q->whereNotNull('category');
                })
                ->groupBy('category')
                ->selectRaw('category, COUNT(*) as cnt')
                ->get();

            $categories = [];
            $categoryData = [];
            $totalSalesCount = 0;

            if ($categoryRows && $categoryRows->count() > 0) {
                foreach ($categoryRows as $row) {
                    $cat = $row->category ?? 'Uncategorized';
                    $categories[] = $cat;
                    $categoryData[] = (int) $row->cnt;
                    $totalSalesCount += (int) $row->cnt;
                }
            } else {
                // Keep empty state without mock values
                $categories = [];
                $categoryData = [];
                $totalSalesCount = 0;
            }

            $chartData = [
                'months' => $months,
                'revenue' => $revenueData,
                'profit' => $profitData,
                'categories' => $categories,
                'categoryData' => $categoryData,
                'totalSalesCount' => $totalSalesCount,
            ];


            return view("vendor.reports", compact('vendor', 'title', 'activePage', 'chartData'));
        }

        if ($page === 'payroll' && view()->exists("vendor.payroll")) {
            $vendorId = $vendor->id;

            $vendorStaffs = \App\Models\VendorStaff::where('vendor_id', $vendorId)->get();
            $currentMonth = now()->startOfMonth();

            $payrolls = \App\Models\VendorPayroll::where('vendor_id', $vendorId)
                            ->where('month_year', $currentMonth->format('Y-m-d'))
                            ->get();

            // Ensure payroll rows exist for each staff for the current month.
            // Keep it DB-driven: do NOT generate random incentives.
            foreach($vendorStaffs as $staff) {
                if(!$payrolls->contains('vendor_staff_id', $staff->id)) {
                    $newPayroll = \App\Models\VendorPayroll::create([
                        'vendor_id' => $vendorId,
                        'vendor_staff_id' => $staff->id,
                        'month_year' => $currentMonth->format('Y-m-d'),
                        'base_salary' => $staff->base_salary,
                        'incentive' => 0,
                        'status' => 'pending'
                    ]);
                    $payrolls->push($newPayroll);
                }
            }


            $totalBasePaid = \App\Models\VendorPayroll::where('vendor_id', $vendorId)
                                    ->where('status', 'paid')
                                    ->sum('base_salary');

            $totalIncentivePaid = \App\Models\VendorPayroll::where('vendor_id', $vendorId)
                                    ->where('status', 'paid')
                                    ->sum('incentive');

            $totalDisbursement = $totalBasePaid + $totalIncentivePaid;

            $pendingQueue = $payrolls->where('status', 'pending');

            return view("vendor.payroll", compact(
                'vendor', 'title', 'activePage', 'payrolls', 'vendorStaffs',
                'totalDisbursement', 'totalBasePaid', 'totalIncentivePaid', 'pendingQueue', 'currentMonth'
            ));
        }

        if ($page === 'billing' && view()->exists("vendor.billing")) {
            $vendorId = $vendor->id;

            // Dynamic: show only products belonging to this vendor.
            // Keep approved/active filter if those columns exist.
            $productsQuery = \App\Models\Product::where('vendor_id', $vendorId);

            // These scopes exist in Product model; safe to call.
            $productsQuery->active()->approved();

            $products = $productsQuery->latest()->get();

            return view("vendor.billing", compact('vendor', 'title', 'activePage', 'products'));
        }


        if ($page === 'inventory' && view()->exists("vendor.inventory")) {
            $vendorId = $vendor->id;

            // Dynamic inventory metrics from DB.
            // Note: there is no dedicated sales/stock-movement table used elsewhere,
            // so we derive approximate metrics directly from current stock.
            $totalItems = \App\Models\Product::where('vendor_id', $vendorId)->sum('stock');
            $newToday = \App\Models\Product::where('vendor_id', $vendorId)->whereDate('created_at', today())->count();

            $lowStockCount = \App\Models\Product::where('vendor_id', $vendorId)
                ->where('stock', '<=', 20)
                ->count();

            // Approximation placeholders based on DB only (no mock random numbers)
            $soldStock = 0;
            $soldAvg = 0;
            $inbound = 0;
            $inboundDeliveries = 0;

            $criticalStockAlerts = \App\Models\Product::where('vendor_id', $vendorId)
                ->where('stock', '<=', 20)
                ->orderBy('stock', 'asc')
                ->take(5)
                ->get();

            // If the blade expects movement rows, show an empty list (no random data).
            $movements = collect();

            return view("vendor.inventory", compact(
                'vendor', 'title', 'activePage', 'totalItems', 'newToday',
                'soldStock', 'soldAvg', 'inbound', 'inboundDeliveries',
                'lowStockCount', 'criticalStockAlerts', 'movements'
            ));
        }


        if ($page === 'payments' && view()->exists("vendor.payments")) {
            $vendorId = $vendor->id;

            // Fetch transaction history
            $transactions = \App\Models\VendorWalletTransaction::where('vendor_id', $vendorId)->latest()->get();

            // Calculate variables
            $totalBalance = $transactions->first() ? $transactions->first()->new_balance : 0;

            // Dynamic split metrics is not reliably available from current wallet schema.
            // To keep it DB-driven (no random/mock values), derive totals from ledger itself.
            // If narration/method tags exist, you can refine these filters later.
            $creditTotal = (float) $transactions->where('transaction_type', 'credit')->sum('amount');

            // Fallback: show full credit total as UPI, and 0 as cash (no assumptions/random ratios).
            $upiTotal = $creditTotal;
            $cashTotal = 0;

            return view("vendor.payments", compact(
                'vendor', 'title', 'activePage', 'transactions', 'totalBalance', 'upiTotal', 'cashTotal'
            ));

        }

        if ($page === 'ads' && view()->exists("vendor.ads")) {
            $vendorId = $vendor->id;

            // In production, we would map this directly to an AdAnalytics DB.
            // Aggregating available generic Ad data logic
            $runningAds = \App\Models\AdvertisementRequest::where('requester_id', $vendorId)
                            ->where('requester_type', 'vendor') // Assuming polymorphic relations string convention
                            ->latest()->get();

            $activeCampaigns = $runningAds->where('request_status', 'Active')->count();

            // DB-driven analytics totals:
            // We don't have a dedicated impressions/clicks table here; use available columns if present.
            // If columns don't exist, keep totals as 0 (no random/mock).
            $totalImpressions = 0;
            $totalClicks = 0;
            $avgCtr = 0;
            $adWallet = 0;

            if (\Illuminate\Support\Facades\Schema::hasColumn('advertisement_requests', 'impressions')) {
                $totalImpressions = (int) $runningAds->sum('impressions');
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('advertisement_requests', 'clicks')) {
                $totalClicks = (int) $runningAds->sum('clicks');
            }

            if ($totalImpressions > 0) {
                $avgCtr = ($totalClicks / $totalImpressions) * 100;
            }

            // Keep adWallet as 0 because wallet schema isn't defined for ads in this repo.

            // Do NOT inject mock campaigns; just show empty state from the blade.


            return view("vendor.ads", compact(
                'vendor', 'title', 'activePage', 'runningAds', 'activeCampaigns',
                'totalImpressions', 'totalClicks', 'avgCtr', 'adWallet'
            ));
        }

        if ($page === 'settlements' && view()->exists("vendor.settlements")) {
            $vendorId = $vendor->id;

            // Retrieve actual ledger entries to calculate true available balance
            $walletTxns = \App\Models\VendorWalletTransaction::where('vendor_id', $vendorId)->latest()->get();
            $availableForPayout = $walletTxns->first() ? $walletTxns->first()->new_balance : 0;

            // Derive settlements from real wallet ledger debit transactions.
            // No mock cumulative numbers and no fallback hardcoded rows.
            // NOTE: We cannot distinguish settlement statuses without a dedicated field/table,
            // so we mark all derived entries as COMPLETED for now.

            $settlements = collect();

            // Most recent debit transactions (typically payouts)
            $payouts = $walletTxns->where('transaction_type', 'debit');
            $totalSettled = (float) $payouts->sum('amount');

            foreach($payouts->take(10) as $p) {
                $settlements->push((object)[
                    'id' => 'SET-' . str_pad((int) $p->id, 5, '0', STR_PAD_LEFT),
                    // Deterministic reference from txn id (no uniqid/random)
                    'reference' => 'REF' . str_pad((string) $p->id, 10, '0', STR_PAD_LEFT),
                    'date' => $p->created_at ? $p->created_at->format('M d, Y • h:i A') : '',
                    'destination' => $vendor->bank_name ? $vendor->bank_name . ' **** ' . substr((string) $vendor->account_no, -4) : 'Bank Account',
                    'amount' => $p->amount,
                    'status' => 'COMPLETED',
                ]);
            }


            return view("vendor.settlements", compact(
                'vendor', 'title', 'activePage', 'availableForPayout', 'totalSettled', 'settlements'
            ));
        }

        if ($page === 'settings' && view()->exists("vendor.settings")) {
            return view("vendor.settings", compact('vendor', 'title', 'activePage'));
        }

            // Optional: If a dedicated products view exists but this fallback was hit,
            // show vendor products dynamically.
            if ($page === 'products' && view()->exists('vendor.products.index')) {
                $products = \App\Models\Product::where('vendor_id', $vendor->id)->latest()->get();
                return view('vendor.products.index', [
                    'vendor' => $vendor,
                    'title' => $title,
                    'activePage' => $activePage,
                    'products' => $products,
                ]);
            }

            return view('vendor.page', compact('vendor', 'title', 'activePage'));

    }

    /**
     * Update vendor profile
     */
    public function updateProfile(Request $request)
    {
        $vendor = $this->getAuthenticatedVendor();

        if (! $vendor) {
            return redirect()->route('vendor.login')->with('error', 'Please login to access vendor portal.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'mobile_no' => 'required|string|max:20',
            'business_full_address' => 'nullable|string|max:500',
        ]);

        $vendor->update($request->only([
            'first_name', 'last_name', 'business_name', 'mobile_no', 'business_full_address',
        ]));

        return redirect()->route('vendor.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Change vendor password
     */
    public function changePassword(Request $request)
    {
        $vendor = $this->getAuthenticatedVendor();

        if (! $vendor) {
            return redirect()->route('vendor.login')->with('error', 'Please login to access vendor portal.');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($request->current_password, $vendor->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $vendor->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('vendor.profile')->with('success', 'Password changed successfully!');
    }

    /**
     * Store a new vendor staff member
     */
    public function storeStaff(Request $request)
    {
        $vendor = $this->getAuthenticatedVendor();

        if (! $vendor) {
            return redirect()->route('vendor.login')->with('error', 'Please login to access vendor portal.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'shift_start' => 'required|string',
            'shift_end' => 'required|string',
        ]);

        \App\Models\VendorStaff::create([
            'vendor_id' => $vendor->id,
            'name' => $request->name,
            'role' => $request->role,
            'phone' => $request->phone,
            'shift_start' => $request->shift_start,
            'shift_end' => $request->shift_end,
            'performance_score' => 80,
            'is_online' => false,
        ]);

        return redirect()->route('vendor.staff')->with('success', 'Staff member added successfully!');
    }

    /**
     * Process Payroll action
     */
    public function processPayroll(Request $request)
    {
        $vendor = $this->getAuthenticatedVendor();

        if (! $vendor) {
            return redirect()->route('vendor.login')->with('error', 'Please login to access vendor portal.');
        }

        $currentMonth = now()->startOfMonth()->format('Y-m-d');

        \App\Models\VendorPayroll::where('vendor_id', $vendor->id)
            ->where('month_year', $currentMonth)
            ->where('status', 'pending')
            ->update(['status' => 'paid']);

        return redirect()->route('vendor.payroll')->with('success', 'Payroll processed successfully. All staff have been paid.');
    }

    /**
     * Export Vendor Reports (CSV)
     */
    public function exportReport(Request $request, $type)
    {
        $vendor = $this->getAuthenticatedVendor();

        if (! $vendor) {
            return redirect()->route('vendor.login')->with('error', 'Please login to access vendor portal.');
        }

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$type}_report.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Date', 'Description', 'Amount'];

        $range = (string) ($request->query('range') ?? 'last_30_days');
        $rangeMonths = match ($range) {
            'last_30_days' => 1,
            'last_quarter' => 3,
            'this_year' => 6,
            default => 1,
        };

        $start = now()->subMonths($rangeMonths)->startOfMonth();
        $end = now()->endOfDay();

        $callback = function() use($columns, $type, $vendor, $start, $end) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Make exports DB-driven (no rand/mock numbers).
            // Since vendor-specific tables for each export type may not exist in this repo,
            // we derive values from existing DB tables:
            // - vendor_wallet_transactions (credits) => sales-like amounts
            // - products table => inventory counts (exported as amounts)

            if (\Illuminate\Support\Facades\Schema::hasTable('vendor_wallet_transactions')) {
                // Default sales-like export from wallet credits by day.
                if (in_array($type, ['daily_sales_audit', 'tax_liability_summary', 'staff_attendance'], true)) {
                    $rows = \App\Models\VendorWalletTransaction::query()
                        ->where('vendor_id', $vendor->id)
                        ->where('transaction_type', 'credit')
                        ->whereBetween('created_at', [$start, $end])
                        ->get(['amount', 'created_at'])
                        ->groupBy(function ($r) {
                            return $r->created_at ? $r->created_at->format('Y-m-d') : '';
                        })
                        ->sortKeys();

                    foreach ($rows as $date => $items) {
                        if ($date === '') {
                            continue;
                        }
                        $sum = $items->sum('amount');
                        fputcsv($file, [
                            $date,
                            strtoupper(str_replace('_', ' ', $type)),
                            (float) $sum,
                        ]);
                    }

                    fclose($file);
                    return;
                }
            }

            // Inventory/export fallback: derive amounts from product stock aggregated by day is not possible,
            // so we export current inventory categories as counts.
            if ($type === 'inventory_valuation') {
                $categories = \App\Models\Product::query()
                    ->where('vendor_id', $vendor->id)
                    ->when(\Illuminate\Support\Facades\Schema::hasColumn('products', 'category'), function ($q) {
                        $q->whereNotNull('category');
                    })
                    ->selectRaw('COALESCE(category, "Uncategorized") as category, SUM(stock) as total_stock')
                    ->groupBy('category')
                    ->get();

                foreach ($categories as $row) {
                    $desc = 'INVENTORY ' . (string) ($row->category ?? 'Uncategorized');
                    fputcsv($file, [
                        now()->format('Y-m-d'),
                        $desc,
                        (float) ($row->total_stock ?? 0),
                    ]);
                }

                fclose($file);
                return;
            }

            // Final fallback: empty export with headers only.
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    /**
     * Update Vendor Dynamic JSON Settings
     */
    public function updateSettings(Request $request)
    {
        $vendor = $this->getAuthenticatedVendor();

        if (! $vendor) {
            return redirect()->route('vendor.login')->with('error', 'Please login to access vendor portal.');
        }

        // Normalize settings to array (handles cases where DB stored JSON as string)
        $settingsRaw = $vendor->settings ?? [];
        $settings = is_array($settingsRaw) ? $settingsRaw : (is_string($settingsRaw) ? json_decode($settingsRaw, true) : []);
        if (! is_array($settings)) {
            $settings = [];
        }

        $incoming = $request->except(['_token', 'tab_source', 'password', 'password_confirmation', 'current_password']);

        foreach ($incoming as $key => $value) {
            // Checkbox values come as: 'on' when checked, missing when unchecked.
            $settings[$key] = $value === 'on' ? true : $value;
        }

        // Ensure checkboxes are persisted as false when unchecked.
        // This makes vendor settings behave dynamically/reliably across reloads.
        if ($request->input('tab_source') === 'security') {
            $toggles = ['two_factor_auth', 'remote_session', 'ip_restricted'];
            foreach ($toggles as $toggle) {
                if (! $request->has($toggle)) {
                    $settings[$toggle] = false;
                }
            }
        }

        // Persist explicitly as JSON-safe array
        $vendor->update(['settings' => $settings]);



        return redirect()->back()->with('success', 'Settings applied successfully.');
    }
}
