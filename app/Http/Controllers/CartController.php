<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Coupon;
use App\Helpers\BanglaHelper;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = $this->calculateSubtotal($cart);
        $coupon = session()->get('coupon');
        $discount = 0;

        if ($coupon) {
            $couponModel = Coupon::where('code', $coupon['code'])->first();
            if ($couponModel && $couponModel->isValidForAmount($subtotal)) {
                $discount = $couponModel->calculateDiscount($subtotal);
            } else {
                session()->forget('coupon');
                $coupon = null;
            }
        }

        $total = max(0, $subtotal - $discount);

        return view('cart.index', compact('cart', 'subtotal', 'discount', 'coupon', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'variant' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = (int) ($request->quantity ?? 1);
        $variant = $request->variant ?? 'Default';

        $cartKey = $product->id . '-' . md5($variant);
        $cart = session()->get('cart', []);

        $price = $product->effective_price;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
            $cart[$cartKey]['total'] = $cart[$cartKey]['quantity'] * $price;
        } else {
            $cart[$cartKey] = [
                'id' => $product->id,
                'name' => $product->name,
                'name_bn' => $product->name_bn,
                'slug' => $product->slug,
                'price' => (float) $price,
                'original_price' => (float) $product->price,
                'thumbnail' => $product->thumbnail,
                'quantity' => $quantity,
                'variant' => $variant,
                'total' => $quantity * (float) $price,
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to your cart!',
                'cart_count' => $this->getCartCount(),
                'cart_subtotal' => BanglaHelper::formatTaka($this->calculateSubtotal($cart)),
                'cart' => $cart,
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $key = $request->cart_key;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = (int) $request->quantity;
            $cart[$key]['total'] = $cart[$key]['quantity'] * $cart[$key]['price'];
            session()->put('cart', $cart);
        }

        $subtotal = $this->calculateSubtotal($cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'cart_count' => $this->getCartCount(),
            'item_total' => isset($cart[$key]) ? BanglaHelper::formatTaka($cart[$key]['total']) : '৳0',
            'subtotal' => BanglaHelper::formatTaka($subtotal),
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        $key = $request->cart_key;

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        $subtotal = $this->calculateSubtotal($cart);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart!',
                'cart_count' => $this->getCartCount(),
                'subtotal' => BanglaHelper::formatTaka($subtotal),
            ]);
        }

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function clear()
    {
        session()->forget('cart');
        session()->forget('coupon');
        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('code', $code)->first();

        $cart = session()->get('cart', []);
        $subtotal = $this->calculateSubtotal($cart);

        if (!$coupon || !$coupon->isValidForAmount($subtotal)) {
            $errorMsg = !$coupon ? 'Invalid coupon code.' : "Minimum order of ৳{$coupon->min_spend} required for this coupon.";
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return redirect()->back()->with('error', $errorMsg);
        }

        $discount = $coupon->calculateDiscount($subtotal);

        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => $discount,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'description' => $coupon->description,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Coupon '{$coupon->code}' applied! You saved " . BanglaHelper::formatTaka($discount),
                'discount' => BanglaHelper::formatTaka($discount),
                'discount_val' => $discount,
                'total' => BanglaHelper::formatTaka(max(0, $subtotal - $discount)),
            ]);
        }

        return redirect()->back()->with('success', "Coupon '{$coupon->code}' applied!");
    }

    public function removeCoupon(Request $request)
    {
        session()->forget('coupon');

        if ($request->wantsJson() || $request->ajax()) {
            $cart = session()->get('cart', []);
            $subtotal = $this->calculateSubtotal($cart);
            return response()->json([
                'success' => true,
                'message' => 'Coupon removed.',
                'total' => BanglaHelper::formatTaka($subtotal),
            ]);
        }

        return redirect()->back()->with('success', 'Coupon removed.');
    }

    public function getDrawerData()
    {
        $cart = session()->get('cart', []);
        $subtotal = $this->calculateSubtotal($cart);
        return response()->json([
            'cart' => $cart,
            'cart_count' => $this->getCartCount(),
            'subtotal' => BanglaHelper::formatTaka($subtotal),
            'raw_subtotal' => $subtotal,
        ]);
    }

    private function calculateSubtotal(array $cart): float
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }
        return (float) $subtotal;
    }

    private function getCartCount(): int
    {
        $cart = session()->get('cart', []);
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }
}
