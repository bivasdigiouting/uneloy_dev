<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\GstTaxRepositoryInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class GstTaxController extends Controller
{
    protected GstTaxRepositoryInterface $gstTaxRepository;

    public function __construct(GstTaxRepositoryInterface $gstTaxRepository)
    {
        $this->gstTaxRepository = $gstTaxRepository;
    }

    public function index()
    {
        return view('admin.gst_taxes.index');
    }

    public function data(Request $request)
    {
        $query = $this->gstTaxRepository->getForDataTable();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                return $row->is_active ? 'Active' : 'Inactive';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('admin.gst-taxes.edit', $row->id);
                $deleteUrl = route('admin.gst-taxes.destroy', $row->id);
                $toggleUrl = route('admin.gst-taxes.toggle-status', $row->id);

                return '<div class="btn-group">'
                    .'<a href="'.$editUrl.'" class="btn btn-sm btn-primary">Edit</a>'
                    .'<button class="btn btn-sm btn-danger btn-delete" data-url="'.$deleteUrl.'">Delete</button>'
                    .'<button class="btn btn-sm btn-warning btn-toggle" data-url="'.$toggleUrl.'">Toggle</button>'
                    .'</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.gst_taxes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tax_name' => 'required|string|max:100',
            'rate_percent' => 'required|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);
        $this->gstTaxRepository->create($validated);

        return redirect()->route('admin.gst-taxes.index')->with('success', 'GST Tax created successfully');
    }

    public function edit(int $id)
    {
        $tax = $this->gstTaxRepository->findById($id);
        if (! $tax) {
            return redirect()->route('admin.gst-taxes.index')->with('error', 'GST Tax not found');
        }

        return view('admin.gst_taxes.edit', compact('tax'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'tax_name' => 'required|string|max:100',
            'rate_percent' => 'required|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);
        $ok = $this->gstTaxRepository->update($id, $validated);

        return redirect()->route('admin.gst-taxes.index')->with($ok ? 'success' : 'error', $ok ? 'GST Tax updated successfully' : 'Update failed');
    }

    public function destroy(int $id)
    {
        $ok = $this->gstTaxRepository->delete($id);

        return response()->json(['success' => $ok]);
    }

    public function toggleStatus(int $id)
    {
        $ok = $this->gstTaxRepository->toggleStatus($id);

        return response()->json(['success' => $ok]);
    }
}
