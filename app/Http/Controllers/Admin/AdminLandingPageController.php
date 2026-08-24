<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminLandingPageController extends Controller
{
    public function index()
    {
        $landingPages = LandingPage::with('product')->latest()->paginate(10);
        return view('admin.landing_pages.index', compact('landingPages'));
    }

    public function create()
    {
        $products = Product::where('status', 'active')->get();
        return view('admin.landing_pages.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:landing_pages,slug',
            'product_id' => 'nullable|exists:products,id',
            'headline' => 'nullable|string|max:255',
            'subheadline' => 'nullable|string',
            'offer_price' => 'required|numeric|min:0',
            'regular_price' => 'nullable|numeric|min:0',
            'video_url' => 'nullable|string',
            'banner_image' => 'nullable|string',
            'features' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ]);

        LandingPage::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug']),
            'product_id' => $validated['product_id'],
            'headline' => $validated['headline'],
            'subheadline' => $validated['subheadline'],
            'offer_price' => $validated['offer_price'],
            'regular_price' => $validated['regular_price'],
            'video_url' => $validated['video_url'],
            'banner_image' => $validated['banner_image'],
            'features_list' => $request->features ?: [],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.landing_pages.index')->with('success', 'নতুন ল্যান্ডিং পেজ সফলভাবে তৈরি হয়েছে! 🚀');
    }

    public function edit($id)
    {
        $landingPage = LandingPage::findOrFail($id);
        $products = Product::where('status', 'active')->get();
        return view('admin.landing_pages.edit', compact('landingPage', 'products'));
    }

    public function update(Request $request, $id)
    {
        $landingPage = LandingPage::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:landing_pages,slug,' . $id,
            'product_id' => 'nullable|exists:products,id',
            'headline' => 'nullable|string|max:255',
            'subheadline' => 'nullable|string',
            'offer_price' => 'required|numeric|min:0',
            'regular_price' => 'nullable|numeric|min:0',
            'video_url' => 'nullable|string',
            'banner_image' => 'nullable|string',
            'features' => 'nullable|array',
            'status' => 'required|in:active,inactive',
        ]);

        $landingPage->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug']),
            'product_id' => $validated['product_id'],
            'headline' => $validated['headline'],
            'subheadline' => $validated['subheadline'],
            'offer_price' => $validated['offer_price'],
            'regular_price' => $validated['regular_price'],
            'video_url' => $validated['video_url'],
            'banner_image' => $validated['banner_image'],
            'features_list' => $request->features ?: [],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.landing_pages.index')->with('success', 'ল্যান্ডিং পেজ আপডেট হয়েছে!');
    }

    public function destroy($id)
    {
        $landingPage = LandingPage::findOrFail($id);
        $landingPage->delete();
        return redirect()->route('admin.landing_pages.index')->with('success', 'ল্যান্ডিং পেজ ডিলিট করা হয়েছে!');
    }
}
