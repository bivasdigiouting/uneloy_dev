<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ECardApproveKycDocumentsExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ECardApproveKycDocumentController extends Controller
{
    public function index(Request $request)
    {
        $criteriaOptions = [
            'id_no' => 'ID No',
            'joining_date' => 'Joining Date',
            'approve_reject_date' => 'Approve/Reject Date',
            'upload_date' => 'Upload Date',
        ];
        $statusOptions = [
            'All' => 'All',
            'Pending Docs' => 'Pending Docs',
            'Uploaded All Docs' => 'Uploaded All Docs',
            'Rejected' => 'Rejected',
        ];

        return view('admin.ecard.approve_kyc_documents.index', compact('criteriaOptions', 'statusOptions'));
    }

    public function data(Request $request)
    {
        $table = 'ecard_registrations';
        if (! Schema::hasTable($table)) {
            return DataTables::of(collect())->make(true);
        }

        $criteria = $request->get('criteria', 'id_no');
        $status = $request->get('status', 'All');
        $memberId = trim((string) $request->get('member_id', ''));
        $fromDate = trim((string) $request->get('from_date', ''));
        $toDate = trim((string) $request->get('to_date', ''));
        $searchText = trim((string) $request->get('search_text', ''));

        $qb = DB::table($table.' as er');
        $qb->select([
            'er.id',
            DB::raw('COALESCE(er.user_id, er.id) as member_id'),
            DB::raw("CONCAT_WS(' ', COALESCE(er.first_name,''), COALESCE(er.middle_name,''), COALESCE(er.last_name,'')) as member_name"),
            DB::raw('COALESCE(er.gmail_id, er.email_id) as email'),
            'er.mobile_no',
            'er.pan_no',
            'er.aadhaar_no',
            'er.bank_name',
            'er.account_no',
            'er.ifsc_code',
            'er.status',
            'er.created_at',
            'er.updated_at',
        ]);

        // Status filter
        if ($status && $status !== 'All') {
            if ($status === 'Rejected') {
                $qb->whereRaw('LOWER(er.status) = ?', ['rejected']);
            } elseif ($status === 'Uploaded All Docs') {
                $qb->where(function ($q) {
                    $q->whereNotNull('er.pan_no')->where('er.pan_no', '!=', '')
                        ->whereNotNull('er.aadhaar_no')->where('er.aadhaar_no', '!=', '')
                        ->whereNotNull('er.bank_name')->where('er.bank_name', '!=', '')
                        ->whereNotNull('er.account_no')->where('er.account_no', '!=', '')
                        ->whereNotNull('er.ifsc_code')->where('er.ifsc_code', '!=', '');
                });
            } elseif ($status === 'Pending Docs') {
                $qb->where(function ($q) {
                    $q->orWhereNull('er.pan_no')->orWhere('er.pan_no', '=', '')
                        ->orWhereNull('er.aadhaar_no')->orWhere('er.aadhaar_no', '=', '')
                        ->orWhereNull('er.bank_name')->orWhere('er.bank_name', '=', '')
                        ->orWhereNull('er.account_no')->orWhere('er.account_no', '=', '')
                        ->orWhereNull('er.ifsc_code')->orWhere('er.ifsc_code', '=', '');
                });
            }
        }

        // Criteria filters
        if ($criteria === 'id_no') {
            if ($memberId !== '') {
                $qb->where(function ($q) use ($memberId) {
                    if (is_numeric($memberId)) {
                        $q->orWhere('er.id', (int) $memberId);
                    }
                    $q->orWhere('er.user_id', $memberId);
                });
            }
        } else {
            $col = null;
            if ($criteria === 'joining_date' && Schema::hasColumn($table, 'created_at')) {
                $col = 'er.created_at';
            } elseif ($criteria === 'approve_reject_date' && Schema::hasColumn($table, 'updated_at')) {
                $col = 'er.updated_at';
            } elseif ($criteria === 'upload_date' && Schema::hasColumn($table, 'updated_at')) {
                $col = 'er.updated_at';
            }
            if ($col) {
                try {
                    if ($fromDate !== '' && $toDate !== '') {
                        $start = Carbon::parse($fromDate)->startOfDay();
                        $end = Carbon::parse($toDate)->endOfDay();
                        $qb->whereBetween($col, [$start, $end]);
                    } elseif ($fromDate !== '') {
                        $start = Carbon::parse($fromDate)->startOfDay();
                        $qb->where($col, '>=', $start);
                    } elseif ($toDate !== '') {
                        $end = Carbon::parse($toDate)->endOfDay();
                        $qb->where($col, '<=', $end);
                    }
                } catch (\Throwable $e) {
                    // ignore
                }
            }
        }

        // General search
        if ($searchText !== '') {
            $text = $searchText;
            $qb->where(function ($q) use ($text) {
                if (is_numeric($text)) {
                    $q->orWhere('er.id', (int) $text);
                }
                $q->orWhere('er.user_id', 'like', "%$text%")
                    ->orWhere(DB::raw("CONCAT_WS(' ', COALESCE(er.first_name,''), COALESCE(er.middle_name,''), COALESCE(er.last_name,''))"), 'like', "%$text%")
                    ->orWhere('er.gmail_id', 'like', "%$text%")
                    ->orWhere('er.email_id', 'like', "%$text%");
            });
        }

        $rows = $qb->orderBy('er.created_at', 'desc')->get();

        return DataTables::of($rows)
            ->addIndexColumn()
            ->addColumn('kyc_status', function ($row) {
                $hasAll = ! empty($row->pan_no) && ! empty($row->aadhaar_no) && ! empty($row->bank_name) && ! empty($row->account_no) && ! empty($row->ifsc_code);
                if (strtolower((string) $row->status) === 'rejected') {
                    return 'Rejected';
                }

                return $hasAll ? 'Uploaded All Docs' : 'Pending Docs';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? Carbon::parse($row->created_at)->format('Y-m-d') : '';
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at ? Carbon::parse($row->updated_at)->format('Y-m-d') : '';
            })
            ->rawColumns(['kyc_status'])
            ->make(true);
    }

    public function export(Request $request)
    {
        $filters = [
            'criteria' => $request->get('criteria'),
            'status' => $request->get('status'),
            'member_id' => $request->get('member_id'),
            'from_date' => $request->get('from_date'),
            'to_date' => $request->get('to_date'),
            'search_text' => $request->get('search_text'),
        ];

        $fileName = 'approve_kyc_documents_'.Carbon::now()->format('Ymd_His').'.xlsx';

        return Excel::download(new ECardApproveKycDocumentsExport($filters), $fileName);
    }
}
