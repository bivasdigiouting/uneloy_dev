@extends('vendor.layout')

@section('title', $title ?? 'Billing')

@section('content')
<!-- Include Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="billingPOS()" x-cloak class="h-full flex flex-col lg:flex-row gap-6">
    <!-- Left Area: Search & Products -->
    <div class="flex-1 flex flex-col gap-6">
        <!-- Quick Search -->
        <div class="relative group mt-2">
            <i data-lucide="search" class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors w-6 h-6"></i>
            <input type="text" x-model="searchQuery" placeholder="Quick Search Products..." class="w-full pl-16 pr-6 py-5 bg-white border border-slate-100 rounded-[2rem] text-lg font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all card-shadow">
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 overflow-y-auto pb-6 custom-scrollbar pr-2">

            <template x-if="filteredProducts().length === 0">
                <div class="col-span-full py-10 flex flex-col items-center justify-center text-slate-400">
                    <i data-lucide="package-search" class="w-16 h-16 mb-4 opacity-50"></i>
                    <p class="font-bold text-lg">No products found.</p>
                    <p class="text-sm">Try adjusting your search query.</p>
                </div>
            </template>

            <template x-for="product in filteredProducts()" :key="product.id">
                <button @click="addToCart(product)" class="bg-white p-5 rounded-[2.5rem] border border-slate-100 hover:border-indigo-500/50 hover:shadow-2xl hover:shadow-indigo-500/10 transition-all text-left flex flex-col relative overflow-hidden h-64 group">
                    <div class="absolute top-4 left-4 px-3 py-1.5 rounded-xl text-xs font-black z-10"
                         :class="product.stock > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'"
                         x-text="product.stock > 0 ? product.stock : 'Out of Stock'"></div>

                    <div class="w-20 h-20 mx-auto mt-4 rounded-full bg-indigo-50 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform overflow-hidden shrink-0 relative">
                        <template x-if="product.image">
                            <img :src="product.image.startsWith('http') ? product.image : '/storage/' + product.image" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!product.image">
                            <span>📦</span>
                        </template>
                    </div>

                    <div class="mt-auto" :class="{'opacity-50': product.stock <= 0}">
                        <h4 class="font-extrabold text-slate-800 text-sm leading-tight text-center truncate w-full" x-text="product.name" :title="product.name"></h4>
                        <p class="text-slate-400 text-xs mt-1 text-center font-bold uppercase tracking-widest truncate w-full" x-text="product.category || 'Standard'"></p>
                        <div class="mt-4 flex items-center justify-center">
                            <span class="text-indigo-600 font-black text-xl" x-text="'₹' + product.price"></span>
                        </div>
                    </div>
                </button>
            </template>
        </div>
    </div>

    <!-- Right Area: Cart Panel -->
    <div class="w-full xl:w-[450px] shrink-0">
        <div class="bg-white/80 rounded-[3rem] p-8 flex flex-col h-full border border-white card-shadow backdrop-blur-xl">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-black text-slate-800 flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center shadow-inner">
                        <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                    </div>
                    Current Order
                </h2>
                <span class="bg-white px-4 py-2 rounded-full text-xs font-black text-slate-400 border border-slate-100 uppercase tracking-widest shadow-sm" x-text="cartTotalItems() + ' ITEMS'"></span>
            </div>

            <!-- Cart Items Area -->
            <div class="flex-1 overflow-y-auto space-y-4 custom-scrollbar pr-2 min-h-[250px]">

                <template x-if="cart.length === 0">
                    <div class="h-full flex flex-col items-center justify-center text-slate-300 py-10">
                        <i data-lucide="shopping-bag" class="w-16 h-16 mb-4"></i>
                        <p class="font-bold text-sm uppercase tracking-widest">Cart is empty</p>
                    </div>
                </template>

                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="flex items-center justify-between p-4 bg-white rounded-[2rem] border border-slate-100 card-shadow transition-all hover:border-indigo-100 group">
                        <div class="flex items-center gap-4 overflow-hidden">
                            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-xl shrink-0 overflow-hidden">
                                <template x-if="item.image">
                                    <img :src="item.image" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!item.image">
                                    <span>📦</span>
                                </template>
                            </div>
                            <div class="truncate">
                                <h5 class="font-bold text-slate-800 text-sm leading-tight truncate" x-text="item.name"></h5>
                                <p class="text-indigo-600 font-black mt-1" x-text="'₹' + (item.price * item.quantity).toFixed(2)"></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-slate-50 p-1.5 rounded-xl border border-slate-100 shrink-0 ml-2">
                            <button @click="updateQuantity(item.id, -1)" class="w-8 h-8 rounded-xl bg-white text-slate-500 hover:bg-rose-50 hover:text-rose-600 transition-colors flex items-center justify-center shadow-sm">
                                <i data-lucide="minus" class="w-4 h-4"></i>
                            </button>
                            <span class="font-black text-slate-800 w-4 text-center" x-text="item.quantity"></span>
                            <button @click="updateQuantity(item.id, 1)" class="w-8 h-8 rounded-xl bg-white text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 transition-colors flex items-center justify-center shadow-sm" :disabled="item.quantity >= item.stock" :class="{'opacity-50 cursor-not-allowed': item.quantity >= item.stock}">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <!-- Payment Methods -->
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 px-2">Payment Method</p>
                <div class="grid grid-cols-3 gap-3">
                    <button @click="paymentMethod = 'mall_card'" :class="paymentMethod === 'mall_card' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'bg-white text-slate-400 hover:bg-slate-50 hover:text-slate-600 border border-slate-200'" class="py-4 rounded-[1.5rem] text-xs font-black uppercase tracking-widest leading-none transition-all active:scale-95">Mall Card</button>
                    <button @click="paymentMethod = 'mobile'" :class="paymentMethod === 'mobile' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'bg-white text-slate-400 hover:bg-slate-50 hover:text-slate-600 border border-slate-200'" class="py-4 rounded-[1.5rem] text-xs font-black uppercase tracking-widest leading-none transition-all active:scale-95">Mobile</button>
                    <button @click="paymentMethod = 'govt_id'" :class="paymentMethod === 'govt_id' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'bg-white text-slate-400 hover:bg-slate-50 hover:text-slate-600 border border-slate-200'" class="py-4 rounded-[1.5rem] text-xs font-black uppercase tracking-widest leading-none transition-all active:scale-95">Govt ID</button>
                </div>

                <!-- Input -->
                <div class="relative mt-4" x-show="paymentMethod !== 'none'">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i data-lucide="credit-card" class="w-5 h-5 text-slate-400"></i>
                    </div>
                    <input type="text" x-model="paymentReference" placeholder="Scan or enter reference..." class="w-full bg-white border border-slate-200 rounded-[1.5rem] pl-12 pr-6 py-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all card-shadow">
                </div>

                <!-- Totals Area -->
                <div class="mt-6 pt-6 border-t-2 border-dashed border-slate-200 space-y-3 font-bold text-slate-500 px-2 lg:-mx-2 lg:px-4">
                    <div class="flex justify-between items-center text-sm">
                        <span>SUBTOTAL</span>
                        <span class="text-slate-800" x-text="'₹' + getSubtotal().toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span>GST (18%)</span>
                        <span class="text-slate-800" x-text="'₹' + getGST().toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between items-center text-indigo-600 text-3xl font-black pt-4 pb-2">
                        <span>TOTAL</span>
                        <span x-text="'₹' + getTotal().toFixed(2)"></span>
                    </div>
                </div>

                <button class="w-full mt-4 py-5 rounded-[1.5rem] font-black text-lg shadow-xl transition-all flex items-center justify-center gap-3 group"
                        :class="cart.length === 0 ? 'bg-slate-300 text-slate-500 cursor-not-allowed shadow-none' : 'bg-slate-900 hover:bg-slate-800 text-white shadow-slate-900/20 active:scale-95'"
                        :disabled="cart.length === 0"
                        @click.prevent="payNow()">
                    <span>Pay Now</span> <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                </button>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('billingPOS', () => ({
            products: @json($products ?? []),
            searchQuery: '',
            cart: [],
            paymentMethod: 'mall_card',
            paymentReference: '',

            init() {
                // Ensure lucide icons re-render on Alpine updates where needed
                this.$watch('cart', () => {
                    setTimeout(() => { if(window.lucide) window.lucide.createIcons(); }, 10);
                });
                this.$watch('searchQuery', () => {
                    setTimeout(() => { if(window.lucide) window.lucide.createIcons(); }, 10);
                });
            },

            filteredProducts() {
                if (this.searchQuery === '') {
                    return this.products;
                }
                return this.products.filter(product => {
                    return product.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                           (product.category && product.category.toLowerCase().includes(this.searchQuery.toLowerCase()));
                });
            },

            addToCart(product) {
                if (product.stock <= 0) return; // Prevent adding out of stock items

                let existingItem = this.cart.find(item => item.id === product.id);
                if (existingItem) {
                    if (existingItem.quantity < product.stock) {
                        existingItem.quantity++;
                    }
                } else {
                    this.cart.push({
                        id: product.id,
                        name: product.name,
                        price: parseFloat(product.price) || 0,
                        image: product.image ? (product.image.startsWith('http') ? product.image : '/storage/' + product.image) : null,
                        stock: product.stock,
                        quantity: 1
                    });
                }
            },

            updateQuantity(id, change) {
                let existingItem = this.cart.find(item => item.id === id);
                if (existingItem) {
                    existingItem.quantity += change;
                    if (existingItem.quantity <= 0) {
                        this.cart = this.cart.filter(item => item.id !== id);
                    } else if (existingItem.quantity > existingItem.stock) {
                        existingItem.quantity = existingItem.stock;
                    }
                }
            },

            cartTotalItems() {
                return this.cart.reduce((total, item) => total + item.quantity, 0);
            },

            getSubtotal() {
                return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
            },

            getGST() {
                // Assuming standard 18% GST on subtotal logic as a placeholder
                return this.getSubtotal() * 0.18;
            },

            getTotal() {
                return this.getSubtotal() + this.getGST();
            },

            async payNow() {
                try {
                    if (this.cart.length === 0) return;

                    const payload = {
                        items: this.cart.map(i => ({
                            product_id: i.id,
                            name: i.name,
                            price: i.price,
                            quantity: i.quantity,
                            stock: i.stock
                        })),
                        payment_method: this.paymentMethod,
                        payment_reference: this.paymentReference || null,
                        subtotal: this.getSubtotal(),
                        gst: this.getGST(),
                        total: this.getTotal(),
                    };

                    const res = await fetch('{{ route('vendor.billing.pay') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(payload),
                    });

                    const data = await res.json().catch(() => ({}));
                    if (!res.ok || !data || !data.redirect_url) {
                        alert('Payment initiation failed: ' + (data?.message || res.status));
                        return;
                    }


                    window.location.href = data.redirect_url;
                } catch (e) {
                    console.error(e);
                    alert('Payment initiation error');
                }
            }
        }))
    });
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
