<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductReview::with('product')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reviews = $query->paginate(15);
        $totalReviews = ProductReview::count();
        $pendingReviews = ProductReview::where('status', 'pending')->count();
        $products = Product::where('status', 'active')->get();

        return view('admin.reviews.index', compact('reviews', 'totalReviews', 'pendingReviews', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'reviewer_name' => 'required|string|max:255',
            'reviewer_phone' => 'nullable|string|max:50',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        ProductReview::create([
            'product_id' => $validated['product_id'],
            'reviewer_name' => $validated['reviewer_name'],
            'reviewer_phone' => $validated['reviewer_phone'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_verified_purchase' => true,
            'status' => 'approved',
        ]);

        return redirect()->back()->with('success', 'নতুন কাস্টমার রিভিউ সফলভাবে যুক্ত হয়েছে! ⭐');
    }

    public function updateStatus(Request $request, $id)
    {
        $review = ProductReview::findOrFail($id);
        $review->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'রিভিউ স্ট্যাটাস আপডেট হয়েছে!');
    }

    public function destroy($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->delete();
        return redirect()->back()->with('success', 'রিভিউ মুছে ফেলা হয়েছে!');
    }
}
