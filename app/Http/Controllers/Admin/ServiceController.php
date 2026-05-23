<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\State;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    protected ServiceRepositoryInterface $serviceRepository;

    public function __construct(ServiceRepositoryInterface $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->serviceRepository->getForDataTable();

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
                    $editUrl = route('admin.services.edit', $row->id);
                    $deleteBtn = '<button data-id="'.e($row->id).'" class="btn btn-sm btn-danger delete-service"><i class="ti ti-trash"></i></button>';

                    return '<a href="'.e($editUrl).'" class="btn btn-sm btn-primary me-1"><i class="ti ti-edit"></i></a>'.$deleteBtn;
                })
                ->rawColumns(['icon', 'actions'])
                ->make(true);
        }

        return view('admin.services.index');
    }

    public function create()
    {
        $states = State::where('status', 1)->orderBy('state_name')->get(['id', 'state_name']);

        return view('admin.services.create', compact('states'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['service_name', 'state_id', 'district_id', 'city_id']);
        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon');
        }

        $this->serviceRepository->create($data);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully');
    }

    public function edit(int $id)
    {
        $service = $this->serviceRepository->findById($id);
        if (! $service) {
            return redirect()->route('admin.services.index')->with('error', 'Service not found');
        }

        $states = State::where('status', 1)->orderBy('state_name')->get(['id', 'state_name']);
        $districts = District::where('state_id', $service->state_id)->orderBy('district_name')->get(['id', 'district_name']);
        $cities = City::where('district_id', $service->district_id)->orderBy('city_name')->get(['id', 'city_name']);

        return view('admin.services.edit', compact('service', 'states', 'districts', 'cities'));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'district_id' => 'required|exists:districts,id',
            'city_id' => 'required|exists:cities,id',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['service_name', 'state_id', 'district_id', 'city_id']);
        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon');
        }

        $updated = $this->serviceRepository->update($id, $data);
        if (! $updated) {
            return redirect()->route('admin.services.index')->with('error', 'Service not found');
        }

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully');
    }

    public function destroy(int $id)
    {
        $deleted = $this->serviceRepository->delete($id);

        return response()->json(['success' => $deleted]);
    }
}
