<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\State;
use App\Repositories\Interfaces\HelplineRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class HelplineController extends Controller
{
    protected HelplineRepositoryInterface $helplineRepository;

    public function __construct(HelplineRepositoryInterface $helplineRepository)
    {
        $this->helplineRepository = $helplineRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->helplineRepository->getForDataTable();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('icon', function ($row) {
                    $url = $row->icon_url;

                    return $url ? '<img src="'.e($url).'" alt="icon" class="img-thumbnail" style="width:32px;height:32px">' : '-';
                })
                ->editColumn('state_id', function ($row) {
                    return $row->state ? e($row->state->state_name) : '-';
                })
                ->editColumn('district_id', function ($row) {
                    return $row->district ? e($row->district->district_name) : '-';
                })
                ->editColumn('city_id', function ($row) {
                    return $row->city ? e($row->city->city_name) : '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y, h:i A') : '-';
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.helplines.edit', $row->id);
                    $deleteBtn = '<button data-id="'.e($row->id).'" class="btn btn-sm btn-danger delete-helpline"><i class="ti ti-trash"></i></button>';

                    return '<a href="'.e($editUrl).'" class="btn btn-sm btn-primary me-1"><i class="ti ti-edit"></i></a>'.$deleteBtn;
                })
                ->rawColumns(['icon', 'actions'])
                ->make(true);
        }

        return view('admin.helplines.index');
    }

    public function create()
    {
        $states = State::where('status', 1)->orderBy('state_name')->get(['id', 'state_name']);

        return view('admin.helplines.create', compact('states'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'helpline_name' => 'required|string|max:255',
            'helpline_number' => 'required|string|max:30',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['helpline_name', 'helpline_number', 'state_id', 'district_id', 'city_id']);
        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon');
        }

        $this->helplineRepository->create($data);

        return redirect()->route('admin.helplines.index')->with('success', 'Helpline created successfully');
    }

    public function edit(int $id)
    {
        $helpline = $this->helplineRepository->findById($id);
        if (! $helpline) {
            return redirect()->route('admin.helplines.index')->with('error', 'Helpline not found');
        }

        $states = State::where('status', 1)->orderBy('state_name')->get(['id', 'state_name']);
        $districts = District::where('state_id', $helpline->state_id)->orderBy('district_name')->get(['id', 'district_name']);
        $cities = City::where('district_id', $helpline->district_id)->orderBy('city_name')->get(['id', 'city_name']);

        return view('admin.helplines.edit', compact('helpline', 'states', 'districts', 'cities'));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'helpline_name' => 'required|string|max:255',
            'helpline_number' => 'required|string|max:30',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['helpline_name', 'helpline_number', 'state_id', 'district_id', 'city_id']);
        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon');
        }

        $updated = $this->helplineRepository->update($id, $data);
        if (! $updated) {
            return redirect()->route('admin.helplines.index')->with('error', 'Helpline not found');
        }

        return redirect()->route('admin.helplines.index')->with('success', 'Helpline updated successfully');
    }

    public function destroy(int $id)
    {
        $deleted = $this->helplineRepository->delete($id);

        return response()->json(['success' => $deleted]);
    }
}
