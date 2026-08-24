<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('name', 'like', "%{$s}%")->orWhere('sku', 'like', "%{$s}%");
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'short_description' => 'nullable|string',
            'short_description_bn' => 'nullable|string',
            'description' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'thumbnail' => 'nullable|url',
            'badge' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'is_flash_deal' => 'nullable|boolean',
            'status' => 'required|in:active,draft,out_of_stock',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_trending'] = $request->boolean('is_trending');
        $validated['is_flash_deal'] = $request->boolean('is_flash_deal');
        
        if (empty($validated['thumbnail'])) {
            $validated['thumbnail'] = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80';
        }

        $product = Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' created successfully!");
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'short_description' => 'nullable|string',
            'short_description_bn' => 'nullable|string',
            'description' => 'nullable|string',
            'description_bn' => 'nullable|string',
            'thumbnail' => 'nullable|url',
            'badge' => 'nullable|string',
            'status' => 'required|in:active,draft,out_of_stock',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_trending'] = $request->boolean('is_trending');
        $validated['is_flash_deal'] = $request->boolean('is_flash_deal');

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' updated successfully!");
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
