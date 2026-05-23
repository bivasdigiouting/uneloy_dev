<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ECardRegistration;
use App\Models\ECardWalletRequest;
use App\Models\ECardWalletTransaction;
use App\Models\Registration;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class WalletRequestReportController extends Controller
{
    private function normalizeSource(string $source): string
    {
        $s = strtolower(trim($source));
        if (! in_array($s, ['user', 'ecard'], true)) {
            abort(404);
        }

        return $s;
    }

    private function getWalletFundRequestRow(int $id): ?object
    {
        if (! Schema::hasTable('wallet_fund_requests')) {
            return null;
        }

        $q = DB::table('wallet_fund_requests')->where('id', $id);

        return $q->first();
    }

    private function getECardWalletRequestRow(int $id): ?ECardWalletRequest
    {
        if (! Schema::hasTable('ecard_wallet_requests')) {
            return null;
        }

        return ECardWalletRequest::query()->find($id);
    }

    /**
     * Show the Level Wallet Req. Report page
     */
    public function index(Request $request)
    {
        return view('admin.wallet.request-report');
    }

    /**
     * Show the User Wallet Request page
     */
    public function indexUser(Request $request)
    {
        return view('admin.wallet.user-request');
    }

    public function show(Request $request, string $source, int $id): JsonResponse
    {
        $source = $this->normalizeSource($source);

        if ($source === 'user') {
            $row = $this->getWalletFundRequestRow($id);
            if (! $row) {
                return response()->json(['success' => false, 'message' => 'Request not found'], 404);
            }

            $registration = null;
            if (Schema::hasColumn('wallet_fund_requests', 'registration_id')) {
                $registration = Registration::query()->find((int) ($row->registration_id ?? 0));
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'source' => 'user',
                    'id' => (int) ($row->id ?? $id),
                    'id_no' => $registration ? ($registration->user_id ?: $registration->id) : null,
                    'name' => $registration ? trim(implode(' ', array_filter([$registration->first_name, $registration->middle_name, $registration->last_name]))) : null,
                    'amount' => (float) ($row->amount ?? 0),
                    'status' => (string) ($row->status ?? ''),
                    'transaction_id' => (string) ($row->transaction_id ?? ''),
                    'remark' => (string) ($row->remark ?? ''),
                    'admin_remark' => (string) ($row->admin_remark ?? ''),
                    'req_date' => (string) ($row->created_at ?? ''),
                ],
            ]);
        }

        $req = $this->getECardWalletRequestRow($id);
        if (! $req) {
            return response()->json(['success' => false, 'message' => 'Request not found'], 404);
        }
        $registration = ECardRegistration::query()->find((int) ($req->ecard_registration_id ?? 0));

        return response()->json([
            'success' => true,
            'data' => [
                'source' => 'ecard',
                'id' => (int) $req->id,
                'id_no' => $registration ? (($registration->member_id ?? null) ?: ($registration->user_id ?: $registration->id)) : null,
                'name' => $registration ? trim(implode(' ', array_filter([$registration->first_name, $registration->middle_name, $registration->last_name]))) : null,
                'amount' => (float) $req->amount,
                'status' => (string) $req->status,
                'transaction_id' => '',
                'remark' => (string) ($req->remark ?? ''),
                'admin_remark' => '',
                'req_date' => (string) ($req->created_at ?? ''),
            ],
        ]);
    }

    public function approve(Request $request, string $source, int $id): JsonResponse
    {
        $source = $this->normalizeSource($source);
        $adminRemark = trim((string) $request->input('admin_remark', ''));

        if ($source === 'user') {
            if (! Schema::hasTable('wallet_fund_requests') || ! Schema::hasColumn('wallet_fund_requests', 'registration_id')) {
                return response()->json(['success' => false, 'message' => 'Wallet request table is not available'], 422);
            }

            return DB::transaction(function () use ($id, $adminRemark) {
                $row = DB::table('wallet_fund_requests')->lockForUpdate()->where('id', $id)->first();
                if (! $row) {
                    return response()->json(['success' => false, 'message' => 'Request not found'], 404);
                }
                if (($row->status ?? 'pending') !== 'pending') {
                    return response()->json(['success' => false, 'message' => 'Only pending requests can be approved'], 422);
                }

                $registrationId = (int) ($row->registration_id ?? 0);
                $amount = (float) ($row->amount ?? 0);
                if ($registrationId <= 0 || $amount <= 0) {
                    return response()->json(['success' => false, 'message' => 'Invalid request data'], 422);
                }

                $registration = Registration::query()->lockForUpdate()->find($registrationId);
                if (! $registration) {
                    return response()->json(['success' => false, 'message' => 'User not found'], 404);
                }

                $previous = (float) ($registration->wallet_balance ?? 0);
                $newBalance = $previous + $amount;
                $registration->wallet_balance = $newBalance;
                $registration->save();

                $txData = [
                    'registration_id' => $registration->id,
                    'transaction_type' => 'add',
                    'amount' => $amount,
                    'previous_balance' => $previous,
                    'new_balance' => $newBalance,
                    'narration' => 'Wallet request approved (#'.$id.')',
                    'performed_by_user_id' => Auth::id(),
                ];
                if (Schema::hasColumn('wallet_transactions', 'credit_note')) {
                    $txData['credit_note'] = $adminRemark !== '' ? $adminRemark : null;
                }
                if (Schema::hasColumn('wallet_transactions', 'debit_note')) {
                    $txData['debit_note'] = null;
                }
                WalletTransaction::create($txData);

                $update = ['status' => 'approved'];
                if (Schema::hasColumn('wallet_fund_requests', 'admin_remark')) {
                    $update['admin_remark'] = $adminRemark !== '' ? $adminRemark : null;
                }
                DB::table('wallet_fund_requests')->where('id', $id)->update($update);

                return response()->json(['success' => true, 'message' => 'Request approved and wallet credited']);
            });
        }

        if (! Schema::hasTable('ecard_wallet_requests')) {
            return response()->json(['success' => false, 'message' => 'E-Card wallet request table is not available'], 422);
        }

        return DB::transaction(function () use ($id, $adminRemark) {
            $req = ECardWalletRequest::query()->lockForUpdate()->find($id);
            if (! $req) {
                return response()->json(['success' => false, 'message' => 'Request not found'], 404);
            }
            if (($req->status ?? 'pending') !== 'pending') {
                return response()->json(['success' => false, 'message' => 'Only pending requests can be approved'], 422);
            }

            $registration = ECardRegistration::query()->lockForUpdate()->find((int) $req->ecard_registration_id);
            if (! $registration) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            $amount = (float) ($req->amount ?? 0);
            if ($amount <= 0) {
                return response()->json(['success' => false, 'message' => 'Invalid request amount'], 422);
            }

            $previous = (float) ($registration->wallet_balance ?? 0);
            $newBalance = $previous + $amount;
            $registration->wallet_balance = $newBalance;
            $registration->save();

            $txData = [
                'ecard_registration_id' => $registration->id,
                'transaction_type' => 'add',
                'amount' => $amount,
                'previous_balance' => $previous,
                'new_balance' => $newBalance,
                'narration' => 'Wallet request approved (#'.$id.')',
                'performed_by_id' => Auth::id(),
                'reference_type' => 'ecard_wallet_requests',
                'reference_id' => $id,
            ];
            if (Schema::hasColumn('ecard_wallet_transactions', 'payment_status')) {
                $txData['payment_status'] = 'success';
            }
            if (Schema::hasColumn('ecard_wallet_transactions', 'payment_meta')) {
                $txData['payment_meta'] = $adminRemark !== '' ? ['admin_remark' => $adminRemark] : null;
            }
            ECardWalletTransaction::create($txData);

            $req->status = 'approved';
            if (Schema::hasColumn('ecard_wallet_requests', 'approved_by_id')) {
                $req->approved_by_id = Auth::id();
            }
            $req->save();

            return response()->json(['success' => true, 'message' => 'Request approved and wallet credited']);
        });
    }

    public function reject(Request $request, string $source, int $id): JsonResponse
    {
        $source = $this->normalizeSource($source);
        $adminRemark = trim((string) $request->input('admin_remark', ''));

        if ($source === 'user') {
            if (! Schema::hasTable('wallet_fund_requests')) {
                return response()->json(['success' => false, 'message' => 'Wallet request table is not available'], 422);
            }

            return DB::transaction(function () use ($id, $adminRemark) {
                $row = DB::table('wallet_fund_requests')->lockForUpdate()->where('id', $id)->first();
                if (! $row) {
                    return response()->json(['success' => false, 'message' => 'Request not found'], 404);
                }
                if (($row->status ?? 'pending') !== 'pending') {
                    return response()->json(['success' => false, 'message' => 'Only pending requests can be rejected'], 422);
                }

                $update = ['status' => 'rejected'];
                if (Schema::hasColumn('wallet_fund_requests', 'admin_remark')) {
                    $update['admin_remark'] = $adminRemark !== '' ? $adminRemark : null;
                }
                DB::table('wallet_fund_requests')->where('id', $id)->update($update);

                return response()->json(['success' => true, 'message' => 'Request rejected']);
            });
        }

        if (! Schema::hasTable('ecard_wallet_requests')) {
            return response()->json(['success' => false, 'message' => 'E-Card wallet request table is not available'], 422);
        }

        return DB::transaction(function () use ($id, $adminRemark) {
            $req = ECardWalletRequest::query()->lockForUpdate()->find($id);
            if (! $req) {
                return response()->json(['success' => false, 'message' => 'Request not found'], 404);
            }
            if (($req->status ?? 'pending') !== 'pending') {
                return response()->json(['success' => false, 'message' => 'Only pending requests can be rejected'], 422);
            }

            $req->status = 'rejected';
            if (Schema::hasColumn('ecard_wallet_requests', 'approved_by_id')) {
                $req->approved_by_id = Auth::id();
            }
            $req->save();

            return response()->json(['success' => true, 'message' => 'Request rejected']);
        });
    }

    /**
     * DataTables endpoint: User Fund Request list with filters and totals
     */
    public function data(Request $request)
    {
        $hasWalletFundRequests = Schema::hasTable('wallet_fund_requests') && Schema::hasTable('registrations');
        $hasEcardWalletRequests = Schema::hasTable('ecard_wallet_requests') && Schema::hasTable('ecard_registrations');

        if (! $hasWalletFundRequests && ! $hasEcardWalletRequests) {
            return DataTables::of(collect())
                ->with([
                    'totals' => [
                        'pending' => '₹0.00',
                        'approved' => '₹0.00',
                        'rejected' => '₹0.00',
                    ],
                ])
                ->make(true);
        }

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $requestStatus = strtolower(trim((string) $request->input('request_status', '')));
        if (! in_array($requestStatus, ['pending', 'approved', 'rejected'], true)) {
            $requestStatus = '';
        }
        $searchText = trim((string) $request->input('search_by', ''));

        $memberIdExpr = Schema::hasColumn('ecard_registrations', 'member_id')
            ? 'er.member_id'
            : 'NULL';

        $walletQuery = null;
        $walletTotalsBase = null;
        if ($hasWalletFundRequests) {
            $walletQuery = DB::table('wallet_fund_requests as wfr')
                ->leftJoin('registrations as r', 'r.id', '=', 'wfr.registration_id')
                ->selectRaw(
                    'wfr.id as id,'.
                    'COALESCE(r.user_id, r.id) as id_no,'.
                    'CONCAT_WS(" ", r.first_name, r.middle_name, r.last_name) as name,'.
                    'wfr.amount as amount,'.
                    'wfr.status as payment_status,'.
                    'wfr.transaction_id as transaction_id,'.
                    'wfr.remark as remark,'.
                    'wfr.admin_remark as admin_remark,'.
                    'wfr.created_at as req_date,'.
                    'wfr.created_at as created_at,'.
                    '"user" as request_source'
                );

            if ($fromDate) {
                $walletQuery->whereDate('wfr.created_at', '>=', $fromDate);
            }
            if ($toDate) {
                $walletQuery->whereDate('wfr.created_at', '<=', $toDate);
            }
            if ($requestStatus !== '') {
                $walletQuery->where('wfr.status', $requestStatus);
            }
            if ($searchText !== '') {
                $walletQuery->where(function ($q) use ($searchText) {
                    $q->where('r.id', $searchText)
                        ->orWhere('r.user_id', $searchText)
                        ->orWhere('r.email_id', 'like', '%'.$searchText.'%')
                        ->orWhere('r.gmail_id', 'like', '%'.$searchText.'%')
                        ->orWhere('r.mobile_no', 'like', '%'.$searchText.'%')
                        ->orWhere('r.phone_no', 'like', '%'.$searchText.'%');
                });
            }

            $walletTotalsBase = DB::table('wallet_fund_requests as wfr')
                ->leftJoin('registrations as r', 'r.id', '=', 'wfr.registration_id');
            if ($fromDate) {
                $walletTotalsBase->whereDate('wfr.created_at', '>=', $fromDate);
            }
            if ($toDate) {
                $walletTotalsBase->whereDate('wfr.created_at', '<=', $toDate);
            }
            if ($requestStatus !== '') {
                $walletTotalsBase->where('wfr.status', $requestStatus);
            }
            if ($searchText !== '') {
                $walletTotalsBase->where(function ($q) use ($searchText) {
                    $q->where('r.id', $searchText)
                        ->orWhere('r.user_id', $searchText)
                        ->orWhere('r.email_id', 'like', '%'.$searchText.'%')
                        ->orWhere('r.gmail_id', 'like', '%'.$searchText.'%')
                        ->orWhere('r.mobile_no', 'like', '%'.$searchText.'%')
                        ->orWhere('r.phone_no', 'like', '%'.$searchText.'%');
                });
            }
        }

        $ecardQuery = null;
        $ecardTotalsBase = null;
        if ($hasEcardWalletRequests) {
            $ecardQuery = DB::table('ecard_wallet_requests as ewr')
                ->leftJoin('ecard_registrations as er', 'er.id', '=', 'ewr.ecard_registration_id')
                ->selectRaw(
                    'ewr.id as id,'.
                    'COALESCE('.$memberIdExpr.', er.user_id, er.id) as id_no,'.
                    'CONCAT_WS(" ", er.first_name, er.middle_name, er.last_name) as name,'.
                    'ewr.amount as amount,'.
                    'ewr.status as payment_status,'.
                    'NULL as transaction_id,'.
                    'ewr.remark as remark,'.
                    'NULL as admin_remark,'.
                    'ewr.created_at as req_date,'.
                    'ewr.created_at as created_at,'.
                    '"ecard" as request_source'
                );

            if ($fromDate) {
                $ecardQuery->whereDate('ewr.created_at', '>=', $fromDate);
            }
            if ($toDate) {
                $ecardQuery->whereDate('ewr.created_at', '<=', $toDate);
            }
            if ($requestStatus !== '') {
                $ecardQuery->where('ewr.status', $requestStatus);
            }
            if ($searchText !== '') {
                $ecardQuery->where(function ($q) use ($searchText, $memberIdExpr) {
                    $q->where('er.id', $searchText)
                        ->orWhere('er.user_id', $searchText);
                    if ($memberIdExpr !== 'NULL') {
                        $q->orWhereRaw($memberIdExpr.' = ?', [$searchText]);
                    }
                    $q->orWhere('er.email_id', 'like', '%'.$searchText.'%')
                        ->orWhere('er.gmail_id', 'like', '%'.$searchText.'%')
                        ->orWhere('er.mobile_no', 'like', '%'.$searchText.'%')
                        ->orWhere('er.phone_no', 'like', '%'.$searchText.'%');
                });
            }

            $ecardTotalsBase = DB::table('ecard_wallet_requests as ewr')
                ->leftJoin('ecard_registrations as er', 'er.id', '=', 'ewr.ecard_registration_id');
            if ($fromDate) {
                $ecardTotalsBase->whereDate('ewr.created_at', '>=', $fromDate);
            }
            if ($toDate) {
                $ecardTotalsBase->whereDate('ewr.created_at', '<=', $toDate);
            }
            if ($requestStatus !== '') {
                $ecardTotalsBase->where('ewr.status', $requestStatus);
            }
            if ($searchText !== '') {
                $ecardTotalsBase->where(function ($q) use ($searchText, $memberIdExpr) {
                    $q->where('er.id', $searchText)
                        ->orWhere('er.user_id', $searchText);
                    if ($memberIdExpr !== 'NULL') {
                        $q->orWhereRaw($memberIdExpr.' = ?', [$searchText]);
                    }
                    $q->orWhere('er.email_id', 'like', '%'.$searchText.'%')
                        ->orWhere('er.gmail_id', 'like', '%'.$searchText.'%')
                        ->orWhere('er.mobile_no', 'like', '%'.$searchText.'%')
                        ->orWhere('er.phone_no', 'like', '%'.$searchText.'%');
                });
            }
        }

        $unionBase = $walletQuery ?? $ecardQuery;
        if ($unionBase && $walletQuery && $ecardQuery) {
            $unionBase = $walletQuery->unionAll($ecardQuery);
        }

        $query = DB::query()->fromSub($unionBase, 'wr')->orderByDesc('created_at');

        $sumPending = 0.0;
        $sumApproved = 0.0;
        $sumRejected = 0.0;
        if ($walletTotalsBase) {
            $sumPending += (float) ((clone $walletTotalsBase)->where('wfr.status', 'pending')->sum('wfr.amount') ?? 0);
            $sumApproved += (float) ((clone $walletTotalsBase)->where('wfr.status', 'approved')->sum('wfr.amount') ?? 0);
            $sumRejected += (float) ((clone $walletTotalsBase)->where('wfr.status', 'rejected')->sum('wfr.amount') ?? 0);
        }
        if ($ecardTotalsBase) {
            $sumPending += (float) ((clone $ecardTotalsBase)->where('ewr.status', 'pending')->sum('ewr.amount') ?? 0);
            $sumApproved += (float) ((clone $ecardTotalsBase)->where('ewr.status', 'approved')->sum('ewr.amount') ?? 0);
            $sumRejected += (float) ((clone $ecardTotalsBase)->where('ewr.status', 'rejected')->sum('ewr.amount') ?? 0);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('amount', function ($row) {
                return '₹'.number_format((float) $row->amount, 2);
            })
            ->editColumn('payment_status', function ($row) {
                $label = ucfirst($row->payment_status ?? 'pending');
                $color = match ($row->payment_status) {
                    'approved' => 'success',
                    'rejected' => 'danger',
                    default => 'warning',
                };

                return '<span class="badge bg-'.$color.'">'.e($label).'</span>';
            })
            ->editColumn('req_date', function ($row) {
                try {
                    return $row->req_date ? \Carbon\Carbon::parse($row->req_date)->format('d M Y, h:i A') : '';
                } catch (\Throwable $e) {
                    return (string) $row->req_date;
                }
            })
            ->addColumn('action', function ($row) {
                $source = e((string) ($row->request_source ?? 'user'));
                $id = (int) ($row->id ?? 0);
                $status = strtolower((string) ($row->payment_status ?? 'pending'));
                $isPending = $status === 'pending';

                $viewBtn = '<button type="button" class="btn btn-sm btn-info js-wr-view" data-source="'.$source.'" data-id="'.$id.'"><i class="ti ti-eye"></i> View</button>';
                $approveBtn = '<button type="button" class="btn btn-sm btn-success ms-1 js-wr-approve" data-source="'.$source.'" data-id="'.$id.'"'.($isPending ? '' : ' disabled').'><i class="ti ti-check"></i> Approve</button>';
                $rejectBtn = '<button type="button" class="btn btn-sm btn-danger ms-1 js-wr-reject" data-source="'.$source.'" data-id="'.$id.'"'.($isPending ? '' : ' disabled').'><i class="ti ti-x"></i> Reject</button>';

                return $viewBtn.' '.$approveBtn.' '.$rejectBtn;
            })
            ->rawColumns(['payment_status', 'action'])
            ->with([
                'totals' => [
                    'pending' => '₹'.number_format($sumPending, 2),
                    'approved' => '₹'.number_format($sumApproved, 2),
                    'rejected' => '₹'.number_format($sumRejected, 2),
                ],
            ])
            ->make(true);
    }
}
