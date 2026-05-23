<?php

namespace App\Http\Controllers;

use App\Models\ECardRegistration;
use App\Models\ECardUpgradeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ECardPortalUpgradeController extends Controller
{
    protected array $levels = [
        'customer',
        'village_level',
        'panchayat_level',
        'block_level',
        'district_level',
        'state_level',
    ];

    public function index()
    {
        $levels = $this->levels;

        return view('ecard.upgrade.index', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_no' => 'required',
            'to_level' => 'required|in:'.implode(',', $this->levels),
            'remark' => 'nullable|string|max:255',
        ]);

        // Find registration by member_id or primary key id
        $memberNo = trim((string) $request->input('member_no'));
        $registration = ECardRegistration::query()
            ->where('member_id', $memberNo)
            ->orWhere('id', $memberNo)
            ->first();

        if (! $registration) {
            return back()->with('error', 'Member not found. Please check the Member ID.')->withInput();
        }

        $from = $registration->department_level ?? 'customer';
        $to = $request->input('to_level');

        $order = array_flip($this->levels);
        if (($order[$to] ?? -1) < ($order[$from] ?? -1)) {
            return back()->with('error', 'Target level must be higher than current level.')->withInput();
        }

        DB::transaction(function () use ($registration, $from, $to, $request) {
            $registration->department_level = $to;
            $registration->save();

            ECardUpgradeLog::create([
                'ecard_registration_id' => $registration->id,
                'from_level' => $from,
                'to_level' => $to,
                'upgraded_by_id' => optional(auth('ecard')->user())->id,
                'remark' => $request->input('remark'),
            ]);
        });

        return back()->with('success', 'User upgraded successfully to '.str_replace('_', ' ', $to).'.');
    }

    public function reportIndex()
    {
        $levels = $this->levels;

        return view('ecard.upgrade.report', compact('levels'));
    }

    public function reportData(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $order = $request->input('order', []);
        $columns = $request->input('columns', []);

        $memberNo = trim((string) $request->input('member_no', ''));
        $level = $request->input('level');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $searchText = trim((string) $request->input('search_text', ''));

        $base = ECardUpgradeLog::query()
            ->leftJoin('ecard_registrations as er', 'er.id', '=', 'ecard_upgrade_logs.ecard_registration_id')
            ->leftJoin('ecard_registrations as upgr', 'upgr.id', '=', 'ecard_upgrade_logs.upgraded_by_id')
            ->select([
                'ecard_upgrade_logs.id',
                DB::raw('COALESCE(er.member_id, er.id) as member_no'),
                'er.first_name', 'er.middle_name', 'er.last_name',
                'er.email_id', 'er.mobile_no',
                'ecard_upgrade_logs.from_level', 'ecard_upgrade_logs.to_level',
                'ecard_upgrade_logs.remark', 'ecard_upgrade_logs.created_at',
                DB::raw("CONCAT(COALESCE(upgr.first_name,''),' ',COALESCE(upgr.last_name,'')) as upgraded_by_name"),
            ]);

        if ($memberNo !== '') {
            $base->where(function ($w) use ($memberNo) {
                $w->where('er.member_id', $memberNo)->orWhere('er.id', $memberNo);
            });
        }
        if ($level && in_array($level, $this->levels, true)) {
            $base->where('ecard_upgrade_logs.to_level', $level);
        }
        if ($fromDate) {
            $base->whereDate('ecard_upgrade_logs.created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $base->whereDate('ecard_upgrade_logs.created_at', '<=', $toDate);
        }
        if ($searchText !== '') {
            $base->where(function ($w) use ($searchText) {
                $w->where('er.first_name', 'like', "%$searchText%")
                    ->orWhere('er.last_name', 'like', "%$searchText%")
                    ->orWhere('er.email_id', 'like', "%$searchText%")
                    ->orWhere('er.mobile_no', 'like', "%$searchText%")
                    ->orWhere(DB::raw('COALESCE(er.member_id, er.id)'), 'like', "%$searchText%");
            });
        }

        // Ordering
        if (! empty($order) && ! empty($columns)) {
            foreach ($order as $ord) {
                $colIdx = (int) $ord['column'];
                $dir = $ord['dir'] === 'desc' ? 'desc' : 'asc';
                $colName = $columns[$colIdx]['data'] ?? null;
                switch ($colName) {
                    case 'member_no':
                        $base->orderBy(DB::raw('COALESCE(er.member_id, er.id)'), $dir);
                        break;
                    case 'to_level':
                        $base->orderBy('ecard_upgrade_logs.to_level', $dir);
                        break;
                    case 'created_at':
                        $base->orderBy('ecard_upgrade_logs.created_at', $dir);
                        break;
                    default:
                        $base->orderBy('ecard_upgrade_logs.created_at', 'desc');
                        break;
                }
            }
        } else {
            $base->orderBy('ecard_upgrade_logs.created_at', 'desc');
        }

        $recordsTotal = (clone $base)->count();
        $recordsFiltered = $recordsTotal;
        $rows = $base->skip($start)->take($length)->get();

        $data = $rows->map(function ($row) {
            $name = trim(($row->first_name ?? '').' '.($row->middle_name ?? '').' '.($row->last_name ?? ''));

            return [
                'member_no' => $row->member_no,
                'name' => $name,
                'email' => $row->email_id,
                'mobile_no' => $row->mobile_no,
                'from_level' => str_replace('_', ' ', (string) $row->from_level),
                'to_level' => str_replace('_', ' ', (string) $row->to_level),
                'upgraded_by' => (string) $row->upgraded_by_name,
                'remark' => (string) $row->remark,
                'created_at' => optional($row->created_at)->format('Y-m-d'),
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
