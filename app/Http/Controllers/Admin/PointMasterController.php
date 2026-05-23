<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BenefitPoint;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PointMasterController extends Controller
{
    private array $types = [
        'Blood Donate Point',
        'Emergency Support Point',
        'E-Card Seva Point',
    ];

    public function index(Request $request)
    {
        $types = $this->types;

        return view('admin.benefits.points-master.index', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'point' => 'required|numeric|min:0',
        ]);

        $type = trim($request->input('type'));
        $point = (float) $request->input('point');

        if (! in_array($type, $this->types)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid point type'], 422);
        }

        BenefitPoint::create([
            'type' => $type,
            'point' => $point,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Point added successfully.']);
    }

    public function data(Request $request)
    {
        $type = trim((string) $request->input('type'));
        $query = BenefitPoint::query()->orderByDesc('created_at');
        if ($type !== '' && in_array($type, $this->types)) {
            $query->where('type', $type);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                return (string) $row->type;
            })
            ->addColumn('action', function ($row) {
                $id = (int) $row->id;

                return '<button type="button" class="btn btn-sm btn-danger delete-point" data-id="'.$id.'"><i class="ti ti-trash"></i></button>';
            })
            ->editColumn('point', function ($row) {
                return number_format((float) $row->point, 2);
            })
            ->rawColumns(['action'])
            ->setRowAttr(['style' => 'vertical-align: middle;'])
            ->toJson();
    }

    public function destroy($id)
    {
        BenefitPoint::where('id', (int) $id)->delete();

        return response()->json(['status' => 'success', 'message' => 'Point deleted successfully.']);
    }
}
