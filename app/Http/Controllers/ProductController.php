<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Helpers\BanglaHelper;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand'])->where('status', 'active');

        // Search
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('name_bn', 'like', "%{$searchTerm}%")
                  ->orWhere('short_description', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Flash sale / in stock filter
        if ($request->boolean('flash_deals')) {
            $query->where('is_flash_deal', true);
        }
        if ($request->boolean('in_stock')) {
            $query->where('stock_quantity', '>', 0);
        }

        // Sorting
        switch ($request->get('sort', 'latest')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('sales_count', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();

        return view('shop.index', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'brand', 'reviews' => function ($q) {
            $q->latest();
        }])->where('slug', $slug)->firstOrFail();

        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $districts = BanglaHelper::getDistricts();

        return view('shop.show', compact('product', 'relatedProducts', 'districts'));
    }

    public function quickView($id)
    {
        $product = Product::with(['category', 'brand'])->findOrFail($id);
        return view('shop.partials.quickview_modal', compact('product'));
    }

    public function searchLive(Request $request)
    {
        $q = $request->get('query', '');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = Product::where('status', 'active')
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('name_bn', 'like', "%{$q}%")
                      ->orWhere('sku', 'like', "%{$q}%");
            })
            ->take(6)
            ->get(['id', 'name', 'name_bn', 'slug', 'thumbnail', 'price', 'sale_price', 'badge']);

        return response()->json($results);
    }

    public function submitReview(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:255',
            'reviewer_phone' => 'nullable|string|max:50',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        \App\Models\ProductReview::create([
            'product_id' => $product->id,
            'user_id' => auth()->id() ?? null,
            'reviewer_name' => $validated['reviewer_name'],
            'reviewer_phone' => $validated['reviewer_phone'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_verified_purchase' => true,
            'status' => 'approved',
        ]);

        return redirect()->back()->with('success', 'আপনার মূল্যবান রিভিউ ও রেটিং সফলভাবে জমা হয়েছে! ⭐');
    }
}
