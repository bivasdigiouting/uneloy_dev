<?php

namespace App\Http\Controllers;

use App\Models\ECardBankSettlement;
use App\Models\ECardRegistration;
use App\Models\ECardWalletRequest;
use App\Models\ECardWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ECardPortalWalletController extends Controller
{
    /**
     * Show Wallet Fund Request form
     */
    public function requestIndex(Request $request)
    {
        $user = Auth::guard('ecard')->user();
        $requests = ECardWalletRequest::query()
            ->where('ecard_registration_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('ecard.wallet.request', compact('user', 'requests'));
    }

    /**
     * Submit Wallet Fund Request (creates pending request for admin approval)
     */
    public function requestStore(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'remark' => ['nullable', 'string', 'max:255'],
        ]);

        $user = Auth::guard('ecard')->user();

        ECardWalletRequest::create([
            'ecard_registration_id' => $user->id,
            'amount' => (float) $validated['amount'],
            'payment_mode' => null,
            'reference_number' => null,
            'status' => 'pending',
            'remark' => $validated['remark'] ?? null,
            'created_by_id' => $user->id,
            'approved_by_id' => null,
        ]);

        return redirect()->route('ecard.wallet.request.index')->with('success', 'Wallet fund request submitted successfully');
    }

    /**
     * Show Bank Settlement Request form
     */
    public function settlementIndex(Request $request)
    {
        return view('ecard.wallet.settlement');
    }

    /**
     * Submit Bank Settlement (deducts immediately if balance available)
     */
    public function settlementStore(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:1'],
            'settlement_mode' => ['nullable', 'string', 'max:100'],
            'bank_name' => ['nullable', 'string', 'max:150'],
            'account_number' => ['nullable', 'string', 'max:100'],
            'remark' => ['nullable', 'string', 'max:255'],
        ]);

        $registration = ECardRegistration::query()
            ->where('member_id', $validated['member_id'])
            ->first();

        if (! $registration) {
            return back()->withErrors(['member_id' => 'Member ID not found'])->withInput();
        }

        $current = (float) ($registration->wallet_balance ?? 0);
        $amount = (float) $validated['amount'];
        if ($amount > $current) {
            return back()->withErrors(['amount' => 'Insufficient balance'])->withInput();
        }

        DB::transaction(function () use ($registration, $validated, $current, $amount) {
            $new = $current - $amount;

            // Settlement log (approved by default for immediate apply)
            ECardBankSettlement::create([
                'ecard_registration_id' => $registration->id,
                'amount' => $amount,
                'settlement_mode' => $validated['settlement_mode'] ?? null,
                'bank_name' => $validated['bank_name'] ?? null,
                'account_number' => $validated['account_number'] ?? null,
                'status' => 'approved',
                'remark' => $validated['remark'] ?? null,
                'created_by_id' => Auth::guard('ecard')->id(),
                'approved_by_id' => Auth::guard('ecard')->id(),
            ]);

            // Ledger transaction
            ECardWalletTransaction::create([
                'ecard_registration_id' => $registration->id,
                'transaction_type' => 'remove',
                'amount' => $amount,
                'previous_balance' => $current,
                'new_balance' => $new,
                'narration' => 'Bank Settlement Request',
                'performed_by_id' => Auth::guard('ecard')->id(),
                'reference_type' => 'bank_settlement',
                'reference_id' => null,
            ]);

            // Apply balance
            $registration->update(['wallet_balance' => $new]);
        });

        return redirect()->route('ecard.wallet.settlement.index')->with('success', 'Settlement processed successfully');
    }

    /**
     * Show Wallet Transactions page
     */
    public function transactionsIndex(Request $request)
    {
        return view('ecard.wallet.transactions');
    }

    /**
     * DataTables: Wallet Transactions list with filters
     */
    public function transactionsData(Request $request)
    {
        $query = ECardWalletTransaction::query()
            ->with('registration')
            ->orderByDesc('created_at');

        // Filter by member ID
        if ($request->filled('member_id')) {
            $memberId = trim($request->input('member_id'));
            $query->whereHas('registration', function ($q) use ($memberId) {
                $q->where('member_id', $memberId);
            });
        }

        // Filter by type
        if ($request->filled('type') && in_array($request->input('type'), ['add', 'remove'], true)) {
            $query->where('transaction_type', $request->input('type'));
        }

        // Date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('amount', function ($row) {
                return '₹'.number_format((float) $row->amount, 2);
            })
            ->addColumn('member_id', function ($row) {
                return optional($row->registration)->member_id ?? '-';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('Y-m-d H:i') : '';
            })
            ->make(true);
    }
}
