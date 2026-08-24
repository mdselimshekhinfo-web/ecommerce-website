<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Review;
use App\Models\SiteSection;
use App\Models\ThemeSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCategories = Category::where('is_featured', true)->orderBy('sort_order')->get();
        $flashDeals = Product::with(['category', 'brand'])
            ->where('is_flash_deal', true)
            ->where('status', 'active')
            ->take(4)
            ->get();

        $trendingProducts = Product::with(['category', 'brand'])
            ->where('is_trending', true)
            ->where('status', 'active')
            ->take(8)
            ->get();

        $latestProducts = Product::with(['category', 'brand'])
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();

        $brands = Brand::where('is_featured', true)->get();
        $recentReviews = Review::with('product')->where('status', true)->latest()->take(4)->get();

        $sections = SiteSection::all()->keyBy('section_key');
        $themeSettings = ThemeSetting::pluck('value', 'key')->toArray();

        return view('home', compact(
            'featuredCategories',
            'flashDeals',
            'trendingProducts',
            'latestProducts',
            'brands',
            'recentReviews',
            'sections',
            'themeSettings'
        ));
    }
}
