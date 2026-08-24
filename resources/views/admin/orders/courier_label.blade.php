<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Courier Shipping Label #{{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-900 font-sans p-6 min-h-screen flex flex-col items-center justify-center">

    <!-- Action Bar -->
    <div class="no-print mb-4 flex items-center space-x-3">
        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-xs text-cyan-400 font-mono hover:underline">← অর্ডারে ফিরে যান</a>
        <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-bold text-xs shadow-lg font-mono">
            🖨️ Print Courier Shipping Slip
        </button>
    </div>

    <!-- Thermal Label Box (4x6 Aspect) -->
    <div class="w-[420px] bg-white border-2 border-black rounded-2xl p-6 text-xs font-mono space-y-4 shadow-2xl">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b-2 border-black pb-3">
            <div>
                <h1 class="font-black text-base tracking-wider uppercase">NEXUS DOKAN BD</h1>
                <p class="text-[10px] text-gray-700">Gulshan Hub, Dhaka-1212 | Hotline: 01711000111</p>
            </div>
            <div class="text-right">
                <span class="text-xs px-2 py-0.5 border border-black font-black uppercase">{{ $order->courier_name }}</span>
            </div>
        </div>

        <!-- Simulated Barcode -->
        <div class="text-center py-2 border-b-2 border-black space-y-1">
            <div class="h-12 w-full bg-[repeating-linear-gradient(90deg,#000,#000_2px,transparent_2px,transparent_4px,#000_4px,#000_7px,transparent_7px,transparent_9px,#000_9px,#000_13px,transparent_13px,transparent_16px)]"></div>
            <p class="font-bold text-xs tracking-widest">{{ $order->tracking_code ?: 'TRK-9812401' }}</p>
            <p class="text-[10px] text-gray-600">Consignment: {{ $order->courier_consignment_id ?: 'STF-BD-90812' }}</p>
        </div>

        <!-- COD Collection Amount Box -->
        <div class="p-3 bg-gray-100 border-2 border-black text-center space-y-0.5">
            <span class="text-[10px] font-bold uppercase block text-gray-700">কুরিয়ার ক্যাশ কালেকশন (COD Amount):</span>
            <span class="font-black text-2xl tracking-wider">
                @if($order->payment_method === 'cod' && $order->payment_status !== 'paid')
                    {{ \App\Helpers\BanglaHelper::formatTaka($order->total_amount) }}
                @else
                    ৳0.00 (PREPAID / PAID)
                @endif
            </span>
        </div>

        <!-- Deliver To -->
        <div class="border-b-2 border-black pb-3 space-y-1">
            <span class="text-[10px] uppercase font-bold text-gray-500">DELIVER TO (প্রাপক):</span>
            <p class="font-bold text-sm text-black">{{ $order->customer_name }}</p>
            <p class="font-black text-base">{{ $order->customer_phone }}</p>
            <p class="text-xs leading-relaxed text-gray-800">{{ $order->delivery_address }}</p>
            <p class="font-bold text-xs uppercase">জেলা: {{ $order->delivery_district }}</p>
        </div>

        <!-- Items Brief -->
        <div class="space-y-1 text-[11px]">
            <span class="text-[10px] font-bold text-gray-500 uppercase">প্যাকেজের পণ্য:</span>
            @foreach($order->items as $it)
                <div class="flex justify-between">
                    <span class="truncate max-w-[280px]">{{ $it->product_name }}</span>
                    <span class="font-bold">x{{ $it->quantity }}</span>
                </div>
            @endforeach
        </div>

        <!-- Footer -->
        <div class="pt-2 border-t border-dashed border-gray-400 text-center text-[9px] text-gray-500">
            <span>Order #{{ $order->order_number }} • Handle with Care • Fragile Cyber Electronics</span>
        </div>

    </div>

</body>
</html>
