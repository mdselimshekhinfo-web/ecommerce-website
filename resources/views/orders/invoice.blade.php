<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->order_number }} // NEXUS DOKAN BD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; }
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 font-sans p-6 sm:p-12 min-h-screen">

    <div class="max-w-3xl mx-auto bg-slate-950 border border-slate-800 rounded-3xl p-8 sm:p-12 space-y-8 shadow-2xl">
        
        <!-- Print Header & Controls -->
        <div class="flex items-center justify-between no-print pb-4 border-b border-slate-800">
            <a href="{{ route('home') }}" class="text-xs text-cyan-400 font-mono hover:underline">← Back to NEXUS DOKAN</a>
            <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 text-xs font-bold font-mono transition-colors shadow-lg">
                🖨️ Print / Save PDF Invoice
            </button>
        </div>

        <!-- Invoice Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pb-6 border-b border-slate-800">
            <div>
                <h1 class="text-2xl font-black text-cyan-400 tracking-wider">NEXUS DOKAN BD</h1>
                <p class="text-xs text-slate-400 mt-1">Next-Gen Cyber eCommerce & Electronics</p>
                <p class="text-xs text-slate-500 font-mono mt-0.5">Level 6, Cyber Hub, Gulshan-2, Dhaka-1212</p>
                <p class="text-xs text-slate-500 font-mono">Hotline: +880 1711-000111 | BIN/VAT: 00491823-0101</p>
            </div>

            <div class="text-left sm:text-right font-mono text-xs">
                <span class="text-xs px-2.5 py-1 rounded bg-emerald-500/20 text-emerald-300 font-bold uppercase">PAID INVOICE</span>
                <h3 class="text-base font-bold text-white mt-2">#{{ $order->order_number }}</h3>
                <p class="text-slate-400">Date: {{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <!-- Bill To & Logistics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs font-mono">
            <div class="p-4 rounded-xl bg-slate-900 border border-slate-800 space-y-1">
                <span class="text-slate-500 uppercase text-[10px]">BILL & SHIP TO:</span>
                <p class="font-bold text-white text-sm">{{ $order->customer_name }}</p>
                <p class="text-slate-300">{{ $order->customer_phone }}</p>
                <p class="text-slate-400 leading-relaxed">{{ $order->delivery_address }}, {{ $order->delivery_district }}</p>
            </div>

            <div class="p-4 rounded-xl bg-slate-900 border border-slate-800 space-y-1">
                <span class="text-slate-500 uppercase text-[10px]">PAYMENT & COURIER:</span>
                <p class="text-slate-300">Method: <b class="text-pink-400 uppercase">{{ $order->payment_method }}</b></p>
                <p class="text-slate-300">TrxID: <b class="text-cyan-300">{{ $order->transaction_id ?: 'CASH_ON_DELIVERY' }}</b></p>
                <p class="text-slate-300">Courier: <b class="text-white">{{ $order->courier_name }}</b> ({{ $order->tracking_code }})</p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 text-slate-400 font-mono uppercase text-[10px]">
                        <th class="py-3">Item Description</th>
                        <th class="py-3 text-center">Qty</th>
                        <th class="py-3 text-right">Price</th>
                        <th class="py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 font-mono">
                    @foreach($order->items as $it)
                        <tr>
                            <td class="py-3">
                                <span class="font-bold text-white">{{ $it->product_name }}</span>
                                @if($it->variant_info)
                                    <p class="text-[10px] text-cyan-400">{{ $it->variant_info }}</p>
                                @endif
                            </td>
                            <td class="py-3 text-center">{{ $it->quantity }}</td>
                            <td class="py-3 text-right">{{ \App\Helpers\BanglaHelper::formatTaka($it->price) }}</td>
                            <td class="py-3 text-right font-bold text-white">{{ \App\Helpers\BanglaHelper::formatTaka($it->total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals Breakdown -->
        <div class="pt-4 border-t border-slate-800 flex justify-end">
            <div class="w-64 space-y-2 text-xs font-mono">
                <div class="flex justify-between text-slate-400">
                    <span>Subtotal:</span>
                    <span class="text-white font-bold">{{ \App\Helpers\BanglaHelper::formatTaka($order->subtotal) }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="flex justify-between text-emerald-400">
                        <span>Discount:</span>
                        <span>-{{ \App\Helpers\BanglaHelper::formatTaka($order->discount_amount) }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-slate-400">
                    <span>Delivery Fee:</span>
                    <span class="text-cyan-300 font-bold">{{ \App\Helpers\BanglaHelper::formatTaka($order->shipping_cost) }}</span>
                </div>
                <div class="pt-2 border-t border-slate-800 flex justify-between text-sm font-bold text-white">
                    <span>Grand Total:</span>
                    <span class="text-cyan-400 text-base font-black">{{ \App\Helpers\BanglaHelper::formatTaka($order->total_amount) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="pt-8 border-t border-slate-800/80 text-center text-slate-500 text-[10px] font-mono space-y-1">
            <p>Thank you for choosing NEXUS DOKAN BD • This is a computer generated digital invoice.</p>
            <p>For any warranty inquiry, quote Order #{{ $order->order_number }} to our support desk.</p>
        </div>

    </div>

</body>
</html>
