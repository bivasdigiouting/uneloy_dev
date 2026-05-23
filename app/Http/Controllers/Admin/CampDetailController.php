<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Camp;
use App\Models\City;
use App\Models\District;
use App\Models\State;
use App\Repositories\Interfaces\CampDetailRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CampDetailController extends Controller
{
    protected CampDetailRepositoryInterface $campDetailRepo;

    public function __construct(CampDetailRepositoryInterface $campDetailRepo)
    {
        $this->campDetailRepo = $campDetailRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->campDetailRepo->getForDataTable();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('banner', function ($row) {
                    $url = $row->banner_url ?? null;

                    return $url ? '<img src="'.e($url).'" alt="Banner" style="height:40px;width:60px;object-fit:cover;" />' : '-';
                })
                ->editColumn('camp_id', function ($row) {
                    return $row->camp->camp_name ?? '-';
                })
                ->editColumn('state_id', function ($row) {
                    return $row->state->state_name ?? '-';
                })
                ->editColumn('district_id', function ($row) {
                    return $row->district->district_name ?? '-';
                })
                ->editColumn('city_id', function ($row) {
                    return $row->city->city_name ?? '-';
                })
                ->editColumn('from_date', function ($row) {
                    return $row->from_date ? $row->from_date->format('Y-m-d') : '-';
                })
                ->editColumn('to_date', function ($row) {
                    return $row->to_date ? $row->to_date->format('Y-m-d') : '-';
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.camp-details.edit', $row->id);
                    $deleteUrl = route('admin.camp-details.destroy', $row->id);

                    return view('admin.camps._partials.actions', compact('editUrl', 'deleteUrl'));
                })
                ->rawColumns(['banner', 'actions'])
                ->make(true);
        }

        return view('admin.camp-details.index');
    }

    public function create()
    {
        $states = State::orderBy('state_name')->get(['id', 'state_name']);
        $camps = Camp::orderBy('camp_name')->get(['id', 'camp_name']);

        return view('admin.camp-details.create', compact('states', 'camps'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'camp_id' => 'required|exists:camps,id',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'title' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'from_date' => 'required|date|before:to_date',
            'to_date' => 'required|date|after:from_date',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'short_description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['_token']);
        $bannerFile = $request->file('banner');
        $this->campDetailRepo->create($data, $bannerFile);

        return redirect()->route('admin.camp-details.index')->with('success', 'Camp detail created successfully');
    }

    public function edit(int $id)
    {
        $campDetail = $this->campDetailRepo->findById($id);
        $states = State::orderBy('state_name')->get(['id', 'state_name']);
        $districts = District::where('state_id', $campDetail->state_id)->orderBy('district_name')->get(['id', 'district_name']);
        $cities = City::where('district_id', $campDetail->district_id)->orderBy('city_name')->get(['id', 'city_name']);
        $camps = Camp::orderBy('camp_name')->get(['id', 'camp_name']);

        return view('admin.camp-details.edit', compact('campDetail', 'states', 'districts', 'cities', 'camps'));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'camp_id' => 'required|exists:camps,id',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'title' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'from_date' => 'required|date|before:to_date',
            'to_date' => 'required|date|after:from_date',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'short_description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['_token', '_method']);
        $bannerFile = $request->file('banner');
        $this->campDetailRepo->update($id, $data, $bannerFile);

        return redirect()->route('admin.camp-details.index')->with('success', 'Camp detail updated successfully');
    }

    public function destroy(int $id)
    {
        $this->campDetailRepo->delete($id);

        return response()->json(['success' => true]);
    }
}
