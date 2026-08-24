<?php

namespace App\Http\Controllers;

use App\Models\CustomPage;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = CustomPage::where('slug', $slug)->where('status', 'published')->firstOrFail();
        return view('pages.show', compact('page'));
    }
}
