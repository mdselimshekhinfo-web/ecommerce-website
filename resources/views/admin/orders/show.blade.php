@extends('layouts.admin')

@section('page-title', 'Order Management #' . $order->order_number)

@section('content')
<div class="space-y-8">
    
    <!-- Top Action Bar -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('admin.orders.index') }}" class="text-xs text-slate-400 font-mono hover:text-white flex items-center space-x-1">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Back to Orders List</span>
        </a>

        <div class="flex flex-wrap items-center gap-3">
            @if(!$order->courier_consignment_id)
                <form action="{{ route('admin.orders.book_courier', $order->id) }}" method="POST" onsubmit="return confirm('Steadfast Courier-এ ১-ক্লিকে পার্সেল বুক করতে চান?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all flex items-center space-x-1.5">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>Send to Steadfast Courier 🚀</span>
                    </button>
                </form>
            @else
                <a href="{{ route('admin.orders.courier_label', $order->id) }}" target="_blank" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider flex items-center space-x-1.5 shadow-lg transition-all">
                    <i data-lucide="barcode" class="w-4 h-4"></i>
                    <span>Print Courier Label 🏷️</span>
                </a>
            @endif

            <a href="{{ route('order.invoice', $order->order_number) }}" target="_blank" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-700 hover:border-cyan-400 text-cyan-300 text-xs font-mono font-bold flex items-center space-x-2 transition-all">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Print Invoice (৳ BDT)</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Order Details & Items (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Items Table -->
            <div class="admin-glass rounded-3xl p-6 border border-slate-800 space-y-4">
                <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">
                    Purchased Items ({{ $order->items->count() }})
                </h3>

                <div class="divide-y divide-slate-800/80">
                    @foreach($order->items as $it)
                        <div class="py-3 flex items-center justify-between text-xs font-mono">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $it->product_image ?: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=100&auto=format&fit=crop&q=80' }}" class="w-12 h-12 object-cover rounded-xl border border-slate-700">
                                <div>
                                    <h4 class="font-sans font-bold text-white text-xs">{{ $it->product_name }}</h4>
                                    <p class="text-[10px] text-cyan-400 font-mono">{{ $it->variant_info }}</p>
                                    <span class="text-slate-400">{{ \App\Helpers\BanglaHelper::formatTaka($it->price) }} x {{ $it->quantity }}</span>
                                </div>
                            </div>
                            <span class="font-bold text-white text-sm">{{ \App\Helpers\BanglaHelper::formatTaka($it->total) }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Financial Calculation Breakdown -->
                <div class="pt-4 border-t border-slate-800 space-y-2 text-xs font-mono">
                    <div class="flex justify-between text-slate-400">
                        <span>Subtotal:</span>
                        <span class="text-white font-bold">{{ \App\Helpers\BanglaHelper::formatTaka($order->subtotal) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-emerald-400">
                            <span>Voucher Discount ({{ $order->coupon_code }}):</span>
                            <span>-{{ \App\Helpers\BanglaHelper::formatTaka($order->discount_amount) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-slate-400">
                        <span>Courier Shipping Charge ({{ $order->shipping_zone }}):</span>
                        <span class="text-cyan-300 font-bold">{{ \App\Helpers\BanglaHelper::formatTaka($order->shipping_cost) }}</span>
                    </div>
                    <div class="pt-2 border-t border-slate-800 flex justify-between text-sm font-bold text-white">
                        <span>Grand Total:</span>
                        <span class="text-cyan-400 text-lg font-black">{{ \App\Helpers\BanglaHelper::formatTaka($order->total_amount) }}</span>
                    </div>
                </div>

            </div>

            <!-- Customer & Delivery Information -->
            <div class="admin-glass rounded-3xl p-6 border border-slate-800 space-y-4">
                <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">
                    Customer & Destination Info
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-mono">
                    <div class="p-3.5 rounded-xl bg-slate-900/60 border border-slate-800 space-y-1">
                        <span class="text-slate-500 uppercase text-[10px]">RECIPIENT:</span>
                        <p class="font-sans font-bold text-white text-sm">{{ $order->customer_name }}</p>
                        <p class="text-cyan-400">{{ $order->customer_phone }}</p>
                        <p class="text-slate-400">{{ $order->customer_email ?: 'No email' }}</p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-900/60 border border-slate-800 space-y-1">
                        <span class="text-slate-500 uppercase text-[10px]">DELIVERY ADDRESS:</span>
                        <p class="text-white font-medium">{{ $order->delivery_district }} District</p>
                        <p class="text-slate-300 leading-relaxed">{{ $order->delivery_address }}</p>
                        @if($order->delivery_notes)
                            <p class="text-amber-300 text-[11px] mt-1">Note: {{ $order->delivery_notes }}</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Right: Status Control & WhatsApp Verification (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- AI Fraud & Trust Risk Analysis Card -->
            @php
                $fraudAnalysis = \App\Services\AiFraudScoreService::analyzeOrder($order);
            @endphp
            <div class="admin-glass rounded-3xl p-6 border {{ $fraudAnalysis['level'] === 'safe' ? 'border-emerald-500/40 bg-emerald-950/20' : ($fraudAnalysis['level'] === 'moderate' ? 'border-amber-500/40 bg-amber-950/20' : 'border-red-500/50 bg-red-950/30') }} space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-cyber font-bold text-xs text-white uppercase tracking-wider flex items-center gap-1.5">
                        <i data-lucide="shield-check" class="w-4 h-4 {{ $fraudAnalysis['level'] === 'safe' ? 'text-emerald-400' : ($fraudAnalysis['level'] === 'moderate' ? 'text-amber-400' : 'text-red-400') }}"></i>
                        <span>AI ফ্রড ও ট্রাস্ট স্কোর</span>
                    </h3>
                    <span class="px-2.5 py-1 rounded-full text-xs font-mono font-bold uppercase {{ $fraudAnalysis['level'] === 'safe' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : ($fraudAnalysis['level'] === 'moderate' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : 'bg-red-500/20 text-red-300 border border-red-500/40') }}">
                        {{ $fraudAnalysis['score'] }}% Trust
                    </span>
                </div>

                <p class="text-xs text-slate-200 font-medium">
                    <b>পরামর্শ:</b> {{ $fraudAnalysis['recommendation'] }}
                </p>

                <div class="space-y-1 pt-1 border-t border-slate-800 text-[11px] text-slate-300 font-mono">
                    @foreach($fraudAnalysis['reasons'] as $reason)
                        <p>{{ $reason }}</p>
                    @endforeach
                </div>
            </div>

            <!-- WhatsApp Verification & Auto-Courier Card -->
            <div class="admin-glass rounded-3xl p-6 border {{ $order->verification_status === 'whatsapp_verified' ? 'border-emerald-500/40 bg-emerald-950/20' : 'border-purple-500/30' }} space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-cyber font-bold text-xs text-white uppercase tracking-wider flex items-center gap-1.5">
                        <i data-lucide="message-square" class="w-4 h-4 text-emerald-400"></i>
                        <span>AI WhatsApp Verification</span>
                    </h3>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase {{ $order->verification_status === 'whatsapp_verified' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}">
                        {{ $order->verification_status === 'whatsapp_verified' ? '✓ Verified' : '⏳ Pending' }}
                    </span>
                </div>

                <p class="text-xs text-slate-300 font-mono leading-relaxed">
                    {{ $order->verification_status === 'whatsapp_verified' ? 'কাস্টমার হোয়াটসঅ্যাপে কনফার্ম করেছেন এবং পার্সেলটি স্বয়ংক্রিয়ভাবে Steadfast কুরিয়ারে বুক করা হয়েছে।' : 'কাস্টমারের হোয়াটসঅ্যাপে ১-ক্লিকে কনফার্মেশন ও ভয়েস নোট পাঠান:' }}
                </p>

                <!-- 1-Click WhatsApp Direct Open Button -->
                <a href="{{ \App\Services\WhatsAppVerificationService::generateWhatsAppDirectUrl($order) }}" target="_blank"
                   class="w-full py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs uppercase tracking-wider flex items-center justify-center space-x-2 shadow-lg transition-all">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Send WhatsApp Prompt 💬</span>
                </a>
            </div>

            <!-- 1-Click Multi-App Calling & IP TSP Hub -->
            <div class="admin-glass rounded-3xl p-6 border border-purple-500/30 space-y-4" x-data="{
                speaking: false,
                script: '{{ addslashes(\App\Services\VoiceCallingService::generateVoiceScript($order)['voice_script']) }}',
                speakScript() {
                    if ('speechSynthesis' in window) {
                        this.speaking = true;
                        const u = new SpeechSynthesisUtterance(this.script);
                        u.lang = 'bn-BD';
                        u.onend = () => this.speaking = false;
                        window.speechSynthesis.speak(u);
                    }
                }
            }">
                <div class="flex items-center justify-between">
                    <h3 class="font-cyber font-bold text-xs text-white uppercase tracking-wider flex items-center gap-1.5">
                        <i data-lucide="phone-call" class="w-4 h-4 text-purple-400"></i>
                        <span>IP অ্যাপ ও এআই ভয়েস কল হাব</span>
                    </h3>
                    <span class="text-[10px] font-mono text-purple-300 font-bold">Caller: {{ \App\Models\ThemeSetting::get('bd_ip_number', '+8809678831374') }}</span>
                </div>

                @php
                    $rawPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                    if (str_starts_with($rawPhone, '8801')) {
                        $waPhone = $rawPhone;
                    } elseif (str_starts_with($rawPhone, '01')) {
                        $waPhone = '88' . $rawPhone;
                    } else {
                        $waPhone = '880' . $rawPhone;
                    }
                @endphp

                <div class="grid grid-cols-2 gap-2">
                    <button type="button" @click="
                        navigator.clipboard.writeText('{{ $order->customer_phone }}');
                        alert('📞 কাস্টমারের ফোন নম্বর ({{ $order->customer_phone }}) কপি করা হয়েছে!\nআপনার Dial App বা ফোনে পেস্ট করে কল করুন।');
                        window.location.href = 'tel:{{ $order->customer_phone }}';
                    " class="py-2.5 px-3 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-[11px] flex items-center justify-center space-x-1.5 transition-all text-center" title="Dial App বা ফোনে সরাসরি কল">
                        <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                        <span>Dial App এ কল 📞</span>
                    </button>

                    <a href="https://api.whatsapp.com/send?phone={{ $waPhone }}" target="_blank"
                       class="py-2.5 px-3 rounded-xl bg-emerald-600/30 hover:bg-emerald-600 text-emerald-300 hover:text-white border border-emerald-500/40 font-bold text-[11px] flex items-center justify-center space-x-1.5 transition-all text-center" title="WhatsApp চ্যাট ও কল খুলুন">
                        <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                        <span>WhatsApp কল</span>
                    </a>
                </div>

                <!-- AI Speech Prompt Player -->
                <button type="button" @click="speakScript()"
                        class="w-full py-2 rounded-xl bg-slate-900 border border-slate-700 hover:border-purple-400 text-slate-200 hover:text-purple-300 text-[11px] font-mono flex items-center justify-center space-x-2 transition-all">
                    <i data-lucide="volume-2" class="w-3.5 h-3.5 text-purple-400"></i>
                    <span x-text="speaking ? 'এআই ভয়েস পড়ছে...' : '🔊 এআই বাংলা ভয়েস স্ক্রিপ্ট শুনুন'"></span>
                </button>
            </div>

            <!-- Status Control Form -->
            <div class="admin-glass rounded-3xl p-6 border border-slate-800 space-y-4">
                <h3 class="font-cyber font-bold text-sm text-white uppercase tracking-wider">
                    Order Status & Logistics
                </h3>

                <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST" class="space-y-4 text-xs font-mono">
                    @csrf
                    
                    <!-- Status Dropdown -->
                    <div class="space-y-1.5">
                        <label class="text-slate-300">Order Progress</label>
                        <select name="order_status" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-cyan-400">
                            <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending (Received)</option>
                            <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="packed" {{ $order->order_status === 'packed' ? 'selected' : '' }}>Packed & Ready</option>
                            <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped (In Transit)</option>
                            <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered (Complete)</option>
                            <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Payment Status Dropdown -->
                    <div class="space-y-1.5">
                        <label class="text-slate-300">Payment Status</label>
                        <select name="payment_status" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2.5 text-white focus:outline-none focus:border-cyan-400">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>

                    <!-- Courier Partner -->
                    <div class="space-y-1.5">
                        <label class="text-slate-300">Courier Partner</label>
                        <input type="text" name="courier_name" value="{{ $order->courier_name }}" placeholder="e.g. Steadfast Courier, Pathao" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                    </div>

                    <!-- Tracking Code -->
                    <div class="space-y-1.5">
                        <label class="text-slate-300">Courier Tracking Code</label>
                        <input type="text" name="tracking_code" value="{{ $order->tracking_code }}" placeholder="e.g. STF-BD-904812" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                    </div>

                    <!-- Trx ID -->
                    <div class="space-y-1.5">
                        <label class="text-slate-300">Payment TrxID</label>
                        <input type="text" name="transaction_id" value="{{ $order->transaction_id }}" placeholder="e.g. BKS9X84N72A" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">
                    </div>

                    <!-- Admin Notes -->
                    <div class="space-y-1.5">
                        <label class="text-slate-300">Internal Admin Notes</label>
                        <textarea name="admin_notes" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3.5 py-2 text-white">{{ $order->admin_notes }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 font-cyber font-bold text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-all">
                        Update Order Status
                    </button>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection
