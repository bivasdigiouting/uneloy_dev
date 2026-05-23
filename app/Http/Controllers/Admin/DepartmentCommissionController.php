<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentCommissionController extends Controller
{
    /**
     * Show Department Level Commission Master table.
     */
    public function index()
    {
        $levelDepartmentNames = [
            'State e-Card Seva',
            'District e-Card Seva',
            'Block - e-Card Seva',
            'G P M e-Card Seva',
            'e-Card Seva',
        ];

        $departments = collect($levelDepartmentNames)
            ->map(function (string $name) {
                return Department::firstOrCreate(
                    ['department_name' => $name],
                    ['is_active' => true]
                );
            })
            ->values();
        $commissions = DepartmentCommission::whereIn('department_id', $departments->pluck('id'))
            ->get()
            ->keyBy('department_id');

        return view('admin.department-commissions.index', [
            'departments' => $departments,
            'commissions' => $commissions,
        ]);
    }

    /**
     * Row-based update for a department commission.
     */
    public function update(Request $request, Department $department)
    {
        $data = $request->all();
        // Normalize empty strings to null so nullable numeric validation passes and DB stores nulls
        foreach ([
            'security_amount',
            'service_charge',
            'admin_charge',
            'tds_charge',
        ] as $field) {
            if (! array_key_exists($field, $data) || $data[$field] === '' || $data[$field] === null) {
                $data[$field] = null;
            }
        }

        $validator = Validator::make($data, [
            'security_amount' => ['nullable', 'numeric', 'min:0'],
            'service_charge' => ['nullable', 'numeric', 'min:0'],
            'admin_charge' => ['nullable', 'numeric', 'min:0'],
            'tds_charge' => ['nullable', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $commission = DepartmentCommission::firstOrNew([
            'department_id' => $department->id,
        ]);

        $commission->fill([
            'security_amount' => $data['security_amount'],
            'service_charge' => $data['service_charge'],
            'admin_charge' => $data['admin_charge'],
            'tds_charge' => $data['tds_charge'],
        ]);

        $commission->save();

        return response()->json([
            'success' => true,
            'message' => 'Department commission updated successfully',
        ]);
    }
}
