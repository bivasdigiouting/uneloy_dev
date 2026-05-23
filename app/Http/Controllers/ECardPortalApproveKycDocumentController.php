<?php

namespace App\Http\Controllers;

use App\Models\ECardRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ECardPortalApproveKycDocumentController extends Controller
{
    public function index(Request $request)
    {
        $criteriaOptions = [
            'all' => 'All',
            'id_no' => 'Member ID',
            'joining_date' => 'Joining Date',
            'approve_reject_date' => 'Approve/Reject Date',
            'upload_date' => 'Upload Date',
        ];

        $statusOptions = [
            'all' => 'All',
            'pending_docs' => 'Pending Docs',
            'uploaded_all_docs' => 'Uploaded All Docs',
            'rejected' => 'Rejected',
        ];

        return view('ecard.kyc.approve', compact('criteriaOptions', 'statusOptions'));
    }

    public function data(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $order = $request->input('order', []);
        $columns = $request->input('columns', []);

        $criteria = $request->input('criteria', 'all');
        $status = $request->input('status', 'all');
        $memberId = trim((string) $request->input('member_id', ''));
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $searchText = trim((string) $request->input('search_text', ''));

        $baseQuery = ECardRegistration::query()
            ->leftJoin('ecard_kyc_documents as kyc', 'kyc.user_id', '=', 'ecard_registrations.id')
            ->select([
                'ecard_registrations.id',
                DB::raw('COALESCE(ecard_registrations.member_id, ecard_registrations.id) as member_no'),
                'ecard_registrations.name',
                'ecard_registrations.email',
                'ecard_registrations.mobile_no',
                'kyc.pan_no',
                'kyc.aadhaar_no',
                'kyc.bank_name',
                'kyc.account_no',
                'kyc.ifsc_code',
                'ecard_registrations.status as reg_status',
                'ecard_registrations.created_at',
                'ecard_registrations.updated_at',
            ]);

        // Status filter logic similar to admin approval module
        $baseQuery->when($status && $status !== 'all', function ($q) use ($status) {
            if ($status === 'pending_docs') {
                $q->where(function ($w) {
                    $w->whereNull('kyc.pan_no')
                        ->orWhereNull('kyc.aadhaar_no')
                        ->orWhereNull('kyc.bank_name')
                        ->orWhereNull('kyc.account_no')
                        ->orWhereNull('kyc.ifsc_code');
                });
            } elseif ($status === 'uploaded_all_docs') {
                $q->whereNotNull('kyc.pan_no')
                    ->whereNotNull('kyc.aadhaar_no')
                    ->whereNotNull('kyc.bank_name')
                    ->whereNotNull('kyc.account_no')
                    ->whereNotNull('kyc.ifsc_code');
            } elseif ($status === 'rejected') {
                $q->where('ecard_registrations.status', 'rejected');
            }
        });

        // Criteria-based filters
        if ($criteria === 'id_no' && $memberId !== '') {
            $baseQuery->where(function ($w) use ($memberId) {
                $w->where('ecard_registrations.member_id', $memberId)
                    ->orWhere('ecard_registrations.id', $memberId);
            });
        }

        if (in_array($criteria, ['joining_date', 'approve_reject_date', 'upload_date'], true)) {
            $column = $criteria === 'joining_date' ? 'ecard_registrations.created_at'
                : ($criteria === 'approve_reject_date' ? 'ecard_registrations.updated_at' : 'kyc.updated_at');

            if ($fromDate) {
                $baseQuery->whereDate($column, '>=', Carbon::parse($fromDate)->toDateString());
            }
            if ($toDate) {
                $baseQuery->whereDate($column, '<=', Carbon::parse($toDate)->toDateString());
            }
        }

        if ($searchText !== '') {
            $baseQuery->where(function ($w) use ($searchText) {
                $w->where('ecard_registrations.name', 'like', "%$searchText%")
                    ->orWhere('ecard_registrations.email', 'like', "%$searchText%")
                    ->orWhere('ecard_registrations.mobile_no', 'like', "%$searchText%")
                    ->orWhere(DB::raw('COALESCE(ecard_registrations.member_id, ecard_registrations.id)'), 'like', "%$searchText%");
            });
        }

        $recordsTotal = (clone $baseQuery)->count();

        // Ordering
        if (! empty($order) && ! empty($columns)) {
            foreach ($order as $ord) {
                $colIdx = (int) $ord['column'];
                $dir = $ord['dir'] === 'desc' ? 'desc' : 'asc';
                $colName = $columns[$colIdx]['data'] ?? null;
                switch ($colName) {
                    case 'member_no':
                        $baseQuery->orderBy(DB::raw('COALESCE(ecard_registrations.member_id, ecard_registrations.id)'), $dir);
                        break;
                    case 'name':
                        $baseQuery->orderBy('ecard_registrations.name', $dir);
                        break;
                    case 'email':
                        $baseQuery->orderBy('ecard_registrations.email', $dir);
                        break;
                    case 'mobile_no':
                        $baseQuery->orderBy('ecard_registrations.mobile_no', $dir);
                        break;
                    case 'created_at':
                        $baseQuery->orderBy('ecard_registrations.created_at', $dir);
                        break;
                    case 'updated_at':
                        $baseQuery->orderBy('ecard_registrations.updated_at', $dir);
                        break;
                    default:
                        $baseQuery->orderBy('ecard_registrations.created_at', 'desc');
                        break;
                }
            }
        } else {
            $baseQuery->orderBy('ecard_registrations.created_at', 'desc');
        }

        $recordsFiltered = (clone $baseQuery)->count();
        $rows = $baseQuery->skip($start)->take($length)->get();

        $data = $rows->map(function ($row) {
            $kycStatus = 'Pending Docs';
            $hasAll = ! empty($row->pan_no) && ! empty($row->aadhaar_no) && ! empty($row->bank_name) && ! empty($row->account_no) && ! empty($row->ifsc_code);
            if ($row->reg_status === 'rejected') {
                $kycStatus = 'Rejected';
            } elseif ($hasAll) {
                $kycStatus = 'Uploaded All Docs';
            }

            return [
                'member_no' => $row->member_no,
                'name' => $row->name,
                'email' => $row->email,
                'mobile_no' => $row->mobile_no,
                'pan_no' => $row->pan_no,
                'aadhaar_no' => $row->aadhaar_no,
                'bank_name' => $row->bank_name,
                'account_no' => $row->account_no,
                'ifsc_code' => $row->ifsc_code,
                'kyc_status' => $kycStatus,
                'created_at' => optional($row->created_at)->format('Y-m-d'),
                'updated_at' => optional($row->updated_at)->format('Y-m-d'),
            ];
        });

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
}
