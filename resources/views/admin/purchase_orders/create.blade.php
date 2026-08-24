@extends('layouts.admin')

@section('page-title', 'নতুন পারচেজ অর্ডার তৈরি (Create Purchase Order)')

@section('content')
<div class="max-w-5xl mx-auto" x-data="poBuilder()">

    <div class="admin-glass rounded-3xl p-6 sm:p-8 border border-slate-800 space-y-6">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <div>
                <h3 class="font-cyber font-bold text-base text-white">নতুন পারচেজ অর্ডার (ক্রয়াদেশ) তৈরি</h3>
                <p class="text-xs text-slate-400 font-mono">অর্ডার নম্বর: <b class="text-cyan-400">{{ $nextPONumber }}</b></p>
            </div>
            <a href="{{ route('admin.purchase_orders.index') }}" class="text-xs text-slate-400 font-mono hover:text-white">← ফিরে যান</a>
        </div>

        <form action="{{ route('admin.purchase_orders.store') }}" method="POST" class="space-y-6 font-mono text-xs">
            @csrf

            <!-- Supplier & Status Selection -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label class="text-slate-300">সাপ্লায়ার নির্বাচন করুন *</label>
                    <select name="supplier_id" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-cyan-400">
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->company_name ?: $sup->name }} (বর্তমান বকেয়া: ৳{{ number_format($sup->current_due, 0) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-slate-300">অর্ডার স্ট্যাটাস *</label>
                    <select name="status" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none">
                        <option value="ordered">⏳ Ordered (মাল আসার অপেক্ষায়)</option>
                        <option value="received">✓ Received (মাল গোডাউনে রিসিভ হয়েছে ও স্টক বৃদ্ধি পাবে)</option>
                        <option value="draft">Draft (ড্রাফট)</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-slate-300">মালের পরিবহন খরচ (Shipping Cost ৳)</label>
                    <input type="number" step="0.01" name="shipping_cost" x-model.number="shippingCost" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                </div>
            </div>

            <!-- Items Table (Dynamic Rows) -->
            <div class="space-y-3 pt-4 border-t border-slate-800">
                <div class="flex items-center justify-between">
                    <h4 class="font-cyber font-bold text-xs text-cyan-400 uppercase tracking-wider">ক্রয়কৃত প্রোডাক্ট আইটেমসমূহ</h4>
                    <button type="button" @click="addItem()" class="px-3 py-1.5 rounded-lg bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 text-xs font-bold flex items-center space-x-1">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>+ নতুন আইটেম যোগ করুন</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-500 border-b border-slate-800 uppercase text-[10px]">
                                <th class="pb-2">প্রোডাক্ট</th>
                                <th class="pb-2 w-32">কেনা দাম (৳ Unit Cost)</th>
                                <th class="pb-2 w-24 text-center">পরিমাণ (Qty)</th>
                                <th class="pb-2 w-32 text-right">মোট (৳ Subtotal)</th>
                                <th class="pb-2 w-10 text-right"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            <template x-for="(item, index) in items" :key="index">
                                <tr>
                                    <td class="py-2 pr-2">
                                        <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">
                                        <input type="text" :name="'items[' + index + '][product_name]'" x-model="item.product_name" required placeholder="প্রোডাক্টের নাম লিখুন" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white">
                                    </td>
                                    <td class="py-2 px-2">
                                        <input type="number" step="0.01" :name="'items[' + index + '][unit_cost]'" x-model.number="item.unit_cost" required class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white font-bold">
                                    </td>
                                    <td class="py-2 px-2">
                                        <input type="number" :name="'items[' + index + '][quantity]'" x-model.number="item.quantity" min="1" required class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white text-center font-bold">
                                    </td>
                                    <td class="py-2 px-2 text-right font-bold text-cyan-300">
                                        ৳<span x-text="(item.unit_cost * item.quantity).toLocaleString()"></span>
                                    </td>
                                    <td class="py-2 pl-2 text-right">
                                        <button type="button" @click="removeItem(index)" class="text-slate-500 hover:text-red-400 p-1">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Totals & Payment Breakdown -->
                <div class="pt-4 border-t border-slate-800 flex justify-end">
                    <div class="w-72 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-400">
                            <span>প্রোডাক্ট সাব-টোটাল:</span>
                            <span class="text-white font-bold" x-text="'৳' + getSubtotal().toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>পরিবহন খরচ:</span>
                            <span class="text-slate-300 font-bold" x-text="'৳' + (shippingCost || 0).toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between text-sm font-bold text-cyan-400 pt-2 border-t border-slate-800">
                            <span>সর্বমোট ক্রয় বিল:</span>
                            <span x-text="'৳' + getTotal().toLocaleString()"></span>
                        </div>

                        <div class="pt-2">
                            <label class="text-slate-300 text-[11px] block mb-1">অগ্রিম / পরিশোধিত টাকা (Paid Amount ৳):</label>
                            <input type="number" step="0.01" name="paid_amount" x-model.number="paidAmount" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-2.5 py-1.5 text-white text-sm font-bold">
                        </div>

                        <div class="flex justify-between text-pink-400 font-bold pt-1">
                            <span>বকেয়া থাকবে:</span>
                            <span x-text="'৳' + Math.max(0, getTotal() - (paidAmount || 0)).toLocaleString()"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="space-y-1.5">
                <label class="text-slate-300">অর্ডার নোট / মন্তব্য</label>
                <textarea name="notes" rows="2" placeholder="e.g. Bulk lot received from supplier" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white"></textarea>
            </div>

            <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                পারচেজ অর্ডার কনফার্ম করুন 📄
            </button>
        </form>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function poBuilder() {
        return {
            shippingCost: 0,
            paidAmount: 0,
            items: [
                { product_id: null, product_name: 'AuraBlade ANC Cyber Earbuds Pro', unit_cost: 1750, quantity: 20 }
            ],

            addItem() {
                this.items.push({ product_id: null, product_name: '', unit_cost: 0, quantity: 1 });
                this.$nextTick(() => {
                    lucide.createIcons();
                });
            },

            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            },

            getSubtotal() {
                return this.items.reduce((sum, it) => sum + (it.unit_cost * it.quantity), 0);
            },

            getTotal() {
                return this.getSubtotal() + (this.shippingCost || 0);
            }
        }
    }
</script>
@endpush
