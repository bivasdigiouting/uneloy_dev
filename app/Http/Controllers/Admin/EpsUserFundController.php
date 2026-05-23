<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EpsUserFund;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class EpsUserFundController extends Controller
{
    /**
     * Show the E.P.S User Fund page
     */
    public function index(Request $request)
    {
        $fundTypes = [
            'Global Distribute User Fund' => 'Global Distribute User Fund',
        ];
        $userTypes = [
            'recharge_1' => 'RECHARGE 1',
            'recharge_2' => 'RECHARGE 2',
            'deactivate' => 'DEACTIVATE',
        ];

        return view('admin.membership.eps-user-fund.index', compact('fundTypes', 'userTypes'));
    }

    /**
     * Store a new fund setting entry
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fund_type' => ['required', 'in:Global Distribute User Fund'],
            'user_type' => ['required', 'in:recharge_1,recharge_2,deactivate'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $entry = new EpsUserFund;
        $entry->fund_type = $validated['fund_type'];
        $entry->user_type = $validated['user_type'];
        $entry->amount = $validated['amount'];
        $entry->added_by_user_id = optional(Auth::user())->id;
        $entry->save();

        return redirect()->route('admin.membership.eps-user-fund.index')
            ->with('success', 'Fund setting saved successfully.');
    }

    /**
     * DataTables endpoint: List users with latest fund for selected user type
     */
    public function data(Request $request)
    {
        $userType = $request->input('user_type');

        $query = Registration::query()
            ->select([
                'registrations.id',
                'registrations.user_id',
                'registrations.first_name',
                'registrations.middle_name',
                'registrations.last_name',
                'registrations.mobile_no',
                'registrations.status',
            ]);

        // Determine latest fund amount for the selected type (if any)
        $latestFund = 0.00;
        if (in_array($userType, ['recharge_1', 'recharge_2', 'deactivate'])) {
            $fundRow = EpsUserFund::where('user_type', $userType)
                ->orderByDesc('id')
                ->first();
            $latestFund = $fundRow ? (float) $fundRow->amount : 0.00;
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                return $row->status ?: 'N/A';
            })
            ->addColumn('user_id', function ($row) {
                return $row->user_id ?: 'N/A';
            })
            ->addColumn('user_name', function ($row) {
                $full = trim(($row->first_name ?: '').' '.($row->middle_name ?: '').' '.($row->last_name ?: ''));

                return $full !== '' ? $full : 'N/A';
            })
            ->addColumn('mobile_no', function ($row) {
                return $row->mobile_no ?: 'N/A';
            })
            ->addColumn('fund', function () use ($latestFund) {
                return number_format($latestFund, 2);
            })
            ->rawColumns([])
            ->make(true);
    }

    /**
     * Return fund history for a selected user type
     */
    public function history(Request $request)
    {
        $userType = $request->input('user_type');
        if (! in_array($userType, ['recharge_1', 'recharge_2', 'deactivate'])) {
            return response()->json(['items' => []]);
        }

        $items = EpsUserFund::where('user_type', $userType)
            ->orderByDesc('id')
            ->get()
            ->map(function ($row) {
                return [
                    'date' => optional($row->created_at)->format('d M Y, h:i A'),
                    'fund_type' => $row->fund_type,
                    'amount' => number_format((float) $row->amount, 2),
                ];
            });

        return response()->json(['items' => $items]);
    }
}
