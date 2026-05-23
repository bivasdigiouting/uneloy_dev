<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ECardApproveKycDocumentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    private array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $table = 'ecard_registrations';
        if (! Schema::hasTable($table)) {
            return collect();
        }

        $criteria = $this->filters['criteria'] ?? 'id_no';
        $status = $this->filters['status'] ?? 'All';
        $memberId = trim((string) ($this->filters['member_id'] ?? ''));
        $fromDate = trim((string) ($this->filters['from_date'] ?? ''));
        $toDate = trim((string) ($this->filters['to_date'] ?? ''));
        $searchText = trim((string) ($this->filters['search_text'] ?? ''));

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

        return collect($qb->orderBy('er.created_at', 'desc')->get());
    }

    public function headings(): array
    {
        return [
            'Member ID',
            'Member Name',
            'Email',
            'Mobile',
            'PAN No',
            'Aadhar No',
            'Bank Name',
            'Account No',
            'IFSC Code',
            'KYC Status',
            'Joining Date',
            'Last Update Date',
        ];
    }

    public function map($row): array
    {
        $hasAll = ! empty($row->pan_no) && ! empty($row->aadhaar_no) && ! empty($row->bank_name) && ! empty($row->account_no) && ! empty($row->ifsc_code);
        $status = strtolower((string) $row->status) === 'rejected' ? 'Rejected' : ($hasAll ? 'Uploaded All Docs' : 'Pending Docs');

        return [
            $row->member_id,
            $row->member_name,
            $row->email,
            $row->mobile_no,
            $row->pan_no,
            $row->aadhaar_no,
            $row->bank_name,
            $row->account_no,
            $row->ifsc_code,
            $status,
            $row->created_at ? Carbon::parse($row->created_at)->format('Y-m-d') : '',
            $row->updated_at ? Carbon::parse($row->updated_at)->format('Y-m-d') : '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
