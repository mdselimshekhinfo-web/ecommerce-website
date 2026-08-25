@extends('layouts.admin')

@section('page-title', 'ম্যানুয়াল অর্ডার এন্ট্রি (Facebook / WhatsApp / POS)')

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="manualOrderForm(@js($products))">

    <!-- Top Header & Breadcrumb -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.orders.index') }}" class="p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h2 class="font-cyber font-bold text-lg text-white">নতুন ম্যানুয়াল অর্ডার এন্ট্রি</h2>
                <p class="text-xs text-slate-400 mt-0.5">Facebook, WhatsApp, Phone বা অফলাইন সেলসের অর্ডার এন্ট্রি করুন এবং স্বয়ংক্রিয়ভাবে স্টক সমন্বয় করুন।</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.orders.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- 1. Order Channel Selector -->
        <div class="admin-glass rounded-3xl p-6 border border-slate-800 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="font-cyber font-bold text-sm text-white flex items-center gap-2">
                    <i data-lucide="radio" class="w-4 h-4 text-cyan-400"></i>
                    <span>অর্ডারের উৎস বা চ্যানেল (Order Source Channel)</span>
                </h3>
                <span class="text-[11px] font-mono text-cyan-400">Auto Inventory Sync</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 select-none">
                <label class="p-3.5 rounded-2xl border cursor-pointer transition-all flex flex-col items-center justify-center text-center space-y-1.5"
                       :class="orderChannel === 'facebook' ? 'bg-blue-600/20 border-blue-500 text-blue-300 shadow-md font-bold' : 'bg-slate-900/60 border-slate-800 text-slate-400 hover:text-white'">
                    <input type="radio" name="order_channel" value="facebook" x-model="orderChannel" class="hidden">
                    <i data-lucide="message-square" class="w-5 h-5 text-blue-400"></i>
                    <span class="text-xs">Facebook Page</span>
                </label>

                <label class="p-3.5 rounded-2xl border cursor-pointer transition-all flex flex-col items-center justify-center text-center space-y-1.5"
                       :class="orderChannel === 'whatsapp' ? 'bg-emerald-600/20 border-emerald-500 text-emerald-300 shadow-md font-bold' : 'bg-slate-900/60 border-slate-800 text-slate-400 hover:text-white'">
                    <input type="radio" name="order_channel" value="whatsapp" x-model="orderChannel" class="hidden">
                    <i data-lucide="message-circle" class="w-5 h-5 text-emerald-400"></i>
                    <span class="text-xs">WhatsApp</span>
                </label>

                <label class="p-3.5 rounded-2xl border cursor-pointer transition-all flex flex-col items-center justify-center text-center space-y-1.5"
                       :class="orderChannel === 'phone' ? 'bg-purple-600/20 border-purple-500 text-purple-300 shadow-md font-bold' : 'bg-slate-900/60 border-slate-800 text-slate-400 hover:text-white'">
                    <input type="radio" name="order_channel" value="phone" x-model="orderChannel" class="hidden">
                    <i data-lucide="phone-call" class="w-5 h-5 text-purple-400"></i>
                    <span class="text-xs">Phone Call</span>
                </label>

                <label class="p-3.5 rounded-2xl border cursor-pointer transition-all flex flex-col items-center justify-center text-center space-y-1.5"
                       :class="orderChannel === 'pos' ? 'bg-amber-600/20 border-amber-500 text-amber-300 shadow-md font-bold' : 'bg-slate-900/60 border-slate-800 text-slate-400 hover:text-white'">
                    <input type="radio" name="order_channel" value="pos" x-model="orderChannel" class="hidden">
                    <i data-lucide="store" class="w-5 h-5 text-amber-400"></i>
                    <span class="text-xs">Store POS</span>
                </label>

                <label class="p-3.5 rounded-2xl border cursor-pointer transition-all flex flex-col items-center justify-center text-center space-y-1.5"
                       :class="orderChannel === 'manual' ? 'bg-cyan-600/20 border-cyan-500 text-cyan-300 shadow-md font-bold' : 'bg-slate-900/60 border-slate-800 text-slate-400 hover:text-white'">
                    <input type="radio" name="order_channel" value="manual" x-model="orderChannel" class="hidden">
                    <i data-lucide="edit-3" class="w-5 h-5 text-cyan-400"></i>
                    <span class="text-xs">Manual Entry</span>
                </label>
            </div>
        </div>

        <!-- 2. Customer & Delivery Information -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Customer Details (7 Cols) -->
            <div class="lg:col-span-7 admin-glass rounded-3xl p-6 border border-slate-800 space-y-4">
                <div class="flex items-center space-x-2 text-cyan-400 border-b border-slate-800 pb-3">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                    <h3 class="font-cyber font-bold text-sm text-white">গ্রাহক ও ডেলিভারির ঠিকানা</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">কাস্টমারের নাম <span class="text-pink-400">*</span></label>
                        <input type="text" name="customer_name" required placeholder="যেমন: তানভীর আহমেদ"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">মোবাইল নম্বর (১১ ডিজিট) <span class="text-pink-400">*</span></label>
                        <input type="text" name="customer_phone" required placeholder="017XXXXXXXX" pattern="01[3-9][0-9]{8}"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400 font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">জেলা / সিটি <span class="text-pink-400">*</span></label>
                        <select name="delivery_district" x-model="selectedDistrict" @change="onDistrictChange()" required
                                class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                            <option value="Dhaka">ঢাকা (Dhaka) - ৳৬০</option>
                            <option value="Chattogram">চট্টগ্রাম (Chattogram) - ৳১২০</option>
                            <option value="Sylhet">সিলেট (Sylhet) - ৳১২০</option>
                            <option value="Gazipur">গাজীপুর (Gazipur) - ৳১২০</option>
                            <option value="Narayanganj">নারায়ণগঞ্জ (Narayanganj) - ৳১২০</option>
                            <option value="Rajshahi">রাজশাহী (Rajshahi) - ৳১২০</option>
                            <option value="Khulna">খুলনা (Khulna) - ৳১২০</option>
                            <option value="Barishal">বরিশাল (Barishal) - ৳১২০</option>
                            <option value="Rangpur">রংপুর (Rangpur) - ৳১২০</option>
                            <option value="Mymensingh">ময়মনসিংহ (Mymensingh) - ৳১২০</option>
                            <option value="Cumilla">কুমিল্লা (Cumilla) - ৳১২০</option>
                            <option value="Other">অন্যান্য জেলা (Outside Dhaka) - ৳১২০</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-300">ইমেইল (ঐচ্ছিক)</label>
                        <input type="email" name="customer_email" placeholder="customer@example.com"
                               class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300">সম্পূর্ণ ডেলিভারি ঠিকানা (বাসা/রোড/এলাকা) <span class="text-pink-400">*</span></label>
                    <textarea name="delivery_address" required rows="2" placeholder="বাড়ি #, রোড #, সেক্টর/গ্রাম, থানা..."
                              class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-cyan-400 leading-relaxed"></textarea>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-300">অ্যাডমিন নোট / বিশেষ নির্দেশনা</label>
                    <input type="text" name="admin_notes" placeholder="যেমন: বিকেল ৫টার পর ডেলিভারি দিতে বলেছে"
                           class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                </div>
            </div>

            <!-- Payment & Financials (5 Cols) -->
            <div class="lg:col-span-5 admin-glass rounded-3xl p-6 border border-slate-800 space-y-4 flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2 text-emerald-400 border-b border-slate-800 pb-3">
                        <i data-lucide="credit-card" class="w-4 h-4"></i>
                        <h3 class="font-cyber font-bold text-sm text-white">পেমেন্ট ও ডেলিভারি বিল</h3>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-300">পেমেন্ট মেথড</label>
                            <select name="payment_method" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-emerald-400">
                                <option value="cod">ক্যাশ অন ডেলিভারি (COD)</option>
                                <option value="bkash">বিকাশ (bKash)</option>
                                <option value="nagad">নগদ (Nagad)</option>
                                <option value="card">কার্ড পেমেন্ট</option>
                                <option value="cash">হাতে ক্যাশ (POS)</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-300">পেমেন্ট স্ট্যাটাস</label>
                            <select name="payment_status" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-emerald-400">
                                <option value="unpaid">বাকি (Unpaid / COD)</option>
                                <option value="paid">পরিশোধিত (Paid)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-300">ডেলিভারি চার্জ (৳)</label>
                            <input type="number" name="shipping_cost" x-model.number="shippingCost" min="0" required
                                   class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-cyan-400 font-mono">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-300">ডিসকাউন্ট / ছাড় (৳)</label>
                            <input type="number" name="discount_amount" x-model.number="discountAmount" min="0"
                                   class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-pink-400 font-mono">
                        </div>
                    </div>

                    <!-- Bill Breakdown Summary -->
                    <div class="p-4 rounded-2xl bg-slate-950/80 border border-slate-800 space-y-2 text-xs font-mono">
                        <div class="flex justify-between text-slate-400">
                            <span>পণ্যের মোট মূল্য:</span>
                            <span class="text-white font-bold" x-text="'৳' + Number(subtotal).toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>ডেলিভারি ফি:</span>
                            <span class="text-cyan-400 font-bold" x-text="'+ ৳' + Number(shippingCost).toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between text-slate-400" x-show="discountAmount > 0">
                            <span>ছাড় (ডিসকাউন্ট):</span>
                            <span class="text-pink-400 font-bold" x-text="'- ৳' + Number(discountAmount).toLocaleString()"></span>
                        </div>
                        <div class="pt-2 border-t border-slate-800 flex justify-between items-center text-sm">
                            <span class="font-bold text-white">সর্বমোট প্রদেয় বিল:</span>
                            <span class="font-cyber font-black text-xl text-emerald-400" x-text="'৳' + Number(totalAmount).toLocaleString()"></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- 3. Product Item Selector (with Live Inventory Stock) -->
        <div class="admin-glass rounded-3xl p-6 border border-slate-800 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center space-x-2 text-indigo-400">
                    <i data-lucide="package" class="w-4 h-4"></i>
                    <h3 class="font-cyber font-bold text-sm text-white">অর্ডারের পণ্যসমূহ ও ইনভেন্টরি স্টক</h3>
                </div>
                <button type="button" @click="addItem()" 
                        class="px-3.5 py-1.5 rounded-xl bg-indigo-500/20 hover:bg-indigo-500/30 text-indigo-300 border border-indigo-500/40 text-xs font-bold transition-all flex items-center space-x-1.5 shadow-sm">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>আরও পণ্য যোগ করুন</span>
                </button>
            </div>

            <!-- Items Table/List -->
            <div class="space-y-3">
                <template x-for="(item, index) in items" :key="index">
                    <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        
                        <!-- Product Picker -->
                        <div class="flex-1 w-full sm:w-auto space-y-1">
                            <label class="text-[10px] text-slate-400 uppercase font-mono font-bold" x-text="'পণ্য #' + (index + 1)"></label>
                            <select :name="'items[' + index + '][product_id]'" x-model="item.productId" @change="onProductSelect(index)" required
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-cyan-400">
                                <option value="">-- পণ্য নির্বাচন করুন --</option>
                                <template x-for="prod in productList" :key="prod.id">
                                    <option :value="prod.id" x-text="prod.name + ' — ৳' + prod.price + ' (স্টক: ' + prod.stock + ')'"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Quantity Selector -->
                        <div class="w-32 space-y-1">
                            <label class="text-[10px] text-slate-400 uppercase font-mono font-bold">পরিমাণ (Qty)</label>
                            <div class="flex items-center space-x-1">
                                <input type="number" :name="'items[' + index + '][quantity]'" x-model.number="item.quantity" min="1" :max="item.stock" required
                                       @input="recalculate()"
                                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white font-mono text-center focus:outline-none focus:border-cyan-400">
                            </div>
                        </div>

                        <!-- Unit Price & Total Line -->
                        <div class="w-36 text-right space-y-0.5">
                            <span class="text-[10px] text-slate-500 font-mono block">মোট মূল্য</span>
                            <span class="font-mono font-bold text-white text-sm" x-text="'৳' + Number(item.lineTotal).toLocaleString()"></span>
                            <span class="text-[10px] text-slate-400 font-mono block" x-text="'@ ৳' + Number(item.price).toLocaleString()"></span>
                        </div>

                        <!-- Remove Item Button -->
                        <div class="pt-2 sm:pt-0">
                            <button type="button" @click="removeItem(index)" :disabled="items.length === 1"
                                    class="p-2 rounded-xl text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition-colors disabled:opacity-30">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>

                    </div>
                </template>
            </div>
        </div>

        <!-- Submit Button Banner -->
        <div class="p-6 rounded-3xl bg-gradient-to-r from-cyan-950/60 via-slate-950 to-indigo-950/60 border border-cyan-500/40 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-2xl">
            <div>
                <h4 class="font-cyber font-bold text-sm text-white">অর্ডার নিশ্চিত করতে প্রস্তুত?</h4>
                <p class="text-xs text-slate-400 mt-0.5">সেভ করার সাথে সাথে স্টক স্বয়ংক্রিয়ভাবে কমে যাবে এবং অর্ডারটি প্রসেসিংয়ে চলে যাবে।</p>
            </div>

            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <a href="{{ route('admin.orders.index') }}" class="px-5 py-3 rounded-2xl bg-slate-900 border border-slate-700 text-xs font-bold text-slate-300 hover:text-white transition-all text-center">
                    বাতিল
                </a>
                <button type="submit" 
                        class="flex-1 sm:flex-none px-8 py-3 rounded-2xl bg-gradient-to-r from-cyan-500 via-indigo-600 to-pink-500 hover:scale-[1.02] text-white font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-neon-cyan transition-all">
                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                    <span>⚡ অর্ডার নিশ্চিত করুন ও স্টক সমন্বয় করুন</span>
                </button>
            </div>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
    function manualOrderForm(rawProducts) {
        const productList = rawProducts.map(p => ({
            id: p.id,
            name: p.name,
            price: Number(p.sale_price || p.price),
            stock: Number(p.stock_quantity)
        }));

        return {
            orderChannel: 'facebook',
            selectedDistrict: 'Dhaka',
            shippingCost: 60,
            discountAmount: 0,
            subtotal: 0,
            totalAmount: 0,
            productList: productList,
            items: [
                {
                    productId: productList.length > 0 ? productList[0].id : '',
                    price: productList.length > 0 ? productList[0].price : 0,
                    stock: productList.length > 0 ? productList[0].stock : 10,
                    quantity: 1,
                    lineTotal: productList.length > 0 ? productList[0].price : 0
                }
            ],

            init() {
                this.recalculate();
                this.$nextTick(() => lucide.createIcons());
            },

            onDistrictChange() {
                if (this.selectedDistrict === 'Dhaka') {
                    this.shippingCost = 60;
                } else {
                    this.shippingCost = 120;
                }
                this.recalculate();
            },

            addItem() {
                const first = this.productList[0] || { id: '', price: 0, stock: 10 };
                this.items.push({
                    productId: first.id,
                    price: first.price,
                    stock: first.stock,
                    quantity: 1,
                    lineTotal: first.price
                });
                this.recalculate();
                this.$nextTick(() => lucide.createIcons());
            },

            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                    this.recalculate();
                }
            },

            onProductSelect(index) {
                const item = this.items[index];
                const found = this.productList.find(p => p.id == item.productId);
                if (found) {
                    item.price = found.price;
                    item.stock = found.stock;
                    item.quantity = 1;
                    item.lineTotal = found.price;
                }
                this.recalculate();
            },

            recalculate() {
                let sum = 0;
                this.items.forEach(item => {
                    item.lineTotal = (Number(item.price) || 0) * (Number(item.quantity) || 1);
                    sum += item.lineTotal;
                });
                this.subtotal = sum;
                this.totalAmount = Math.max(0, this.subtotal + (Number(this.shippingCost) || 0) - (Number(this.discountAmount) || 0));
            }
        }
    }
</script>
@endpush
