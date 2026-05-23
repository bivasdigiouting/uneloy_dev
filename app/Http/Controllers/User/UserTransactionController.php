<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RechargeTransaction;
use App\Models\Registration;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facades\Excel;

class UserTransactionController extends Controller
{
    public function index(Request $request)
    {
        if (! Session::has('user_auth')) {
            return redirect()->route('user.login');
        }

        $userSession = Session::get('user_auth');
        $registrationId = $userSession['id'];

        $user = Registration::find($registrationId);

        $fromDate = $request->filled('from_date') ? (string) $request->input('from_date') : null;
        $toDate = $request->filled('to_date') ? (string) $request->input('to_date') : null;

        $all = $this->buildUnifiedTransactions($user, $fromDate, $toDate);

        if ($request->filled('export') && in_array($request->input('export'), ['csv', 'excel'], true)) {
            return $this->export($all, (string) $request->input('export'));
        }

        $transactions = $this->paginate($all, 10, $request);

        return view('user.transactions.index', compact('transactions'));
    }

    private function buildUnifiedTransactions(?Registration $user, ?string $fromDate, ?string $toDate): Collection
    {
        if (! $user) {
            return collect();
        }

        $wallet = $this->walletTransactions($user->id, $fromDate, $toDate);
        $recharges = $this->rechargeTransactions($user->id, $fromDate, $toDate);
        $purchases = $this->purchaseTransactions($user, $fromDate, $toDate);

        return $wallet
            ->merge($recharges)
            ->merge($purchases)
            ->sortByDesc(fn (array $t) => ($t['date'] instanceof Carbon) ? $t['date']->getTimestamp() : 0)
            ->values();
    }

    private function walletTransactions(int $registrationId, ?string $fromDate, ?string $toDate): Collection
    {
        $query = WalletTransaction::query()
            ->where('registration_id', $registrationId)
            ->orderByDesc('created_at');

        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        return $query->limit(500)->get()->map(function (WalletTransaction $t) {
            $direction = $t->transaction_type === 'add' ? 'credit' : 'debit';

            return [
                'date' => $t->created_at,
                'category' => 'Wallet',
                'type' => $direction,
                'amount' => (float) $t->amount,
                'balance' => $t->new_balance !== null ? (float) $t->new_balance : null,
                'title' => (string) ($t->narration ?? 'Wallet Transaction'),
                'reference' => null,
            ];
        });
    }

    private function rechargeTransactions(int $registrationId, ?string $fromDate, ?string $toDate): Collection
    {
        $query = RechargeTransaction::query()
            ->where('user_id', $registrationId)
            ->orderByDesc('created_at');

        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        return $query->limit(500)->get()->map(function (RechargeTransaction $t) {
            $service = trim((string) ($t->service_code ?? 'Recharge'));
            $number = trim((string) ($t->recharge_no ?? ''));

            $title = $service !== '' && $number !== ''
                ? "Recharge ($service): $number"
                : ($service !== '' ? "Recharge ($service)" : 'Recharge');

            return [
                'date' => $t->created_at,
                'category' => 'Recharge',
                'type' => 'debit',
                'amount' => (float) $t->amount,
                'balance' => null,
                'title' => $title,
                'reference' => $t->transaction_id ? (string) $t->transaction_id : null,
            ];
        });
    }

    private function purchaseTransactions(Registration $user, ?string $fromDate, ?string $toDate): Collection
    {
        $table = null;
        if (Schema::hasTable('orders')) {
            $table = 'orders';
        } elseif (Schema::hasTable('user_orders')) {
            $table = 'user_orders';
        }

        if (! $table) {
            return collect();
        }

        $dateColumn = Schema::hasColumn($table, 'order_date') ? 'order_date' : (Schema::hasColumn($table, 'created_at') ? 'created_at' : 'id');
        $orderNoColumn = Schema::hasColumn($table, 'order_no') ? 'order_no' : (Schema::hasColumn($table, 'order_number') ? 'order_number' : null);
        $totalColumn = Schema::hasColumn($table, 'billing_amount') ? 'billing_amount' : (Schema::hasColumn($table, 'total_amount') ? 'total_amount' : (Schema::hasColumn($table, 'amount') ? 'amount' : null));
        $discountColumn = Schema::hasColumn($table, 'discount_amount') ? 'discount_amount' : (Schema::hasColumn($table, 'discount_amt') ? 'discount_amt' : null);
        $couponColumn = Schema::hasColumn($table, 'apply_coupon_amount') ? 'apply_coupon_amount' : (Schema::hasColumn($table, 'apply_coupon_amt') ? 'apply_coupon_amt' : null);

        $qb = DB::table($table);

        $qb->addSelect($orderNoColumn ? "$table.$orderNoColumn as order_no" : "$table.id as order_no");
        $qb->addSelect("$table.$dateColumn as order_date");
        $qb->addSelect($totalColumn ? "$table.$totalColumn as total_amount" : DB::raw('0 as total_amount'));
        $qb->addSelect($discountColumn ? "$table.$discountColumn as discount_amount" : DB::raw('0 as discount_amount'));
        $qb->addSelect($couponColumn ? "$table.$couponColumn as coupon_amount" : DB::raw('0 as coupon_amount'));

        $userId = trim((string) ($user->user_id ?? ''));
        $mobile = trim((string) ($user->mobile_no ?? ''));

        if ($userId !== '' && Schema::hasColumn($table, 'user_id')) {
            $qb->where("$table.user_id", $userId);
        } elseif ($mobile !== '') {
            $matched = false;
            foreach (['user_mobile', 'mobile', 'mobile_no', 'buyer_mobile', 'customer_mobile'] as $col) {
                if (Schema::hasColumn($table, $col)) {
                    $qb->where("$table.$col", $mobile);
                    $matched = true;
                    break;
                }
            }
            if (! $matched && Schema::hasColumn($table, 'purchase_id')) {
                $qb->where("$table.purchase_id", (int) $user->id);
            }
        }

        if ($fromDate) {
            $qb->whereDate("$table.$dateColumn", '>=', $fromDate);
        }
        if ($toDate) {
            $qb->whereDate("$table.$dateColumn", '<=', $toDate);
        }

        $rows = $qb->orderByDesc("$table.$dateColumn")->limit(500)->get();

        return collect($rows)->map(function ($row) {
            $total = (float) ($row->total_amount ?? 0);
            $discount = (float) ($row->discount_amount ?? 0);
            $coupon = (float) ($row->coupon_amount ?? 0);
            $net = $total - $discount - $coupon;
            if ($net < 0) {
                $net = 0;
            }

            $orderNo = trim((string) ($row->order_no ?? ''));
            $dateRaw = $row->order_date ?? null;
            $date = $dateRaw ? Carbon::parse((string) $dateRaw) : Carbon::now();

            return [
                'date' => $date,
                'category' => 'Purchase',
                'type' => 'debit',
                'amount' => $net,
                'balance' => null,
                'title' => $orderNo !== '' ? "Purchase (Order: $orderNo)" : 'Purchase',
                'reference' => $orderNo !== '' ? $orderNo : null,
            ];
        });
    }

    private function paginate(Collection $items, int $perPage, Request $request): LengthAwarePaginator
    {
        $page = Paginator::resolveCurrentPage('page');
        $total = $items->count();
        $results = $items->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    }

    private function export(Collection $transactions, string $type)
    {
        $filenameBase = 'transactions-'.date('Y-m-d');

        if ($type === 'excel') {
            $rows = $transactions->values();

            $export = new class($rows) implements FromCollection, WithHeadings, WithMapping
            {
                public function __construct(private Collection $rows) {}

                public function collection(): Collection
                {
                    return $this->rows;
                }

                public function headings(): array
                {
                    return ['Date', 'Category', 'Type', 'Amount', 'Balance', 'Details', 'Reference'];
                }

                public function map($row): array
                {
                    $date = $row['date'] instanceof Carbon ? $row['date']->format('Y-m-d H:i') : '';
                    $balance = $row['balance'] !== null ? number_format((float) $row['balance'], 2) : '';

                    return [
                        $date,
                        (string) ($row['category'] ?? ''),
                        (string) ($row['type'] ?? ''),
                        number_format((float) ($row['amount'] ?? 0), 2),
                        $balance,
                        (string) ($row['title'] ?? ''),
                        (string) ($row['reference'] ?? ''),
                    ];
                }
            };

            return Excel::download($export, $filenameBase.'.xlsx');
        }

        $fileName = $filenameBase.'.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = ['Date', 'Category', 'Type', 'Amount', 'Balance', 'Details', 'Reference'];

        $callback = function () use ($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $row) {
                $date = $row['date'] instanceof Carbon ? $row['date']->format('Y-m-d H:i') : '';
                fputcsv($file, [
                    $date,
                    (string) ($row['category'] ?? ''),
                    (string) ($row['type'] ?? ''),
                    (float) ($row['amount'] ?? 0),
                    $row['balance'] !== null ? (float) $row['balance'] : '',
                    (string) ($row['title'] ?? ''),
                    (string) ($row['reference'] ?? ''),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
