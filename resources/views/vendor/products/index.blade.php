@extends('vendor.layout')

@section('title', $title ?? 'Products')

@section('content')
<div class="h-full flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-800">My Products</h2>
            <p class="text-slate-500 text-sm mt-1">Manage your inventory and track approval statuses.</p>
        </div>
        <button onclick="document.getElementById('addProductModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-600/30 transition-all active:scale-95 flex items-center gap-2">
            <i data-lucide="plus" class="w-5 h-5"></i> Add Product
        </button>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 px-6 py-4 rounded-2xl border border-emerald-100 font-bold flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] border border-slate-100 card-shadow overflow-hidden flex-1">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-xs uppercase tracking-widest font-black">
                        <th class="p-6 font-bold">Product</th>
                        <th class="p-6 font-bold">Category</th>
                        <th class="p-6 font-bold">Price</th>
                        <th class="p-6 font-bold">Stock</th>
                        <th class="p-6 font-bold">Status</th>
                        <th class="p-6 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="p-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-xl shrink-0 overflow-hidden">
                                        @if($product->image)
                                            <img src="{{ \Illuminate\Support\Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <span>📦</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $product->name }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">ID: #{{ $product->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6 font-bold text-sm text-slate-600">{{ $product->category }}</td>
                            <td class="p-6 font-black text-indigo-600">₹{{ number_format($product->price, 2) }}</td>
                            <td class="p-6">
                                <span class="px-3 py-1 rounded-full text-xs font-black {{ $product->stock > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    {{ $product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock' }}
                                </span>
                            </td>
                            <td class="p-6">
                                @if($product->admin_status === 'approved')
                                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-black uppercase tracking-widest flex items-center w-max gap-1"><i data-lucide="check" class="w-3 h-3"></i> Approved</span>
                                @elseif($product->admin_status === 'pending')
                                    <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-black uppercase tracking-widest flex items-center w-max gap-1"><i data-lucide="clock" class="w-3 h-3"></i> Pending</span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-xs font-black uppercase tracking-widest flex items-center w-max gap-1"><i data-lucide="x" class="w-3 h-3"></i> Rejected</span>
                                @endif
                            </td>
                            <td class="p-6 text-right">
                                <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete Product">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-slate-400">
                                <i data-lucide="package-x" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
                                <p class="text-lg font-bold">No products found.</p>
                                <p class="text-sm mt-1">Start by adding your first product to your inventory.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div id="addProductModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 pl-0 md:pl-72">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden animate-fade-in-up">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-xl font-black text-slate-800">New Product</h3>
            <button onclick="document.getElementById('addProductModal').classList.add('hidden')" class="text-slate-400 hover:bg-slate-50 hover:text-slate-600 p-2 rounded-xl transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Product Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Category <span class="text-rose-500">*</span></label>
                    <select name="category" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                        <option value="">Select Category</option>
                        <option value="Electronics">Electronics</option>
                        <option value="Fashion">Fashion</option>
                        <option value="Grocery">Grocery</option>
                        <option value="Medical">Medical</option>
                        <option value="Beauty">Beauty</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Price (₹) <span class="text-rose-500">*</span></label>
                    <input type="number" name="price" step="0.01" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Stock Qty <span class="text-rose-500">*</span></label>
                    <input type="number" name="stock" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Image</label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                </div>
            </div>

            <p class="text-[10px] text-slate-400 font-medium">Note: Newly added products must be verified by the admin before they appear in the POS billing grid.</p>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 mt-6">
                <button type="button" onclick="document.getElementById('addProductModal').classList.add('hidden')" class="px-5 py-2.5 rounded-xl font-bold text-slate-500 hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-600/30 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i> Submit for Approval
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
