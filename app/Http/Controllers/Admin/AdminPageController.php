<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPageController extends Controller
{
    public function index()
    {
        $pages = CustomPage::latest()->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:custom_pages,slug',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_footer_link' => 'nullable|boolean',
            'status' => 'required|in:published,draft',
        ]);

        CustomPage::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug']),
            'content' => $validated['content'],
            'meta_title' => $validated['meta_title'],
            'meta_description' => $validated['meta_description'],
            'is_footer_link' => $request->has('is_footer_link'),
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'নতুন পলিসি পেজ সফলভাবে তৈরি হয়েছে! 📜');
    }

    public function edit($id)
    {
        $page = CustomPage::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = CustomPage::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:custom_pages,slug,' . $id,
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'status' => 'required|in:published,draft',
        ]);

        $page->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug']),
            'content' => $validated['content'],
            'meta_title' => $validated['meta_title'],
            'meta_description' => $validated['meta_description'],
            'is_footer_link' => $request->has('is_footer_link'),
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'পলিসি পেজ সফলভাবে আপডেট হয়েছে!');
    }

    public function destroy($id)
    {
        $page = CustomPage::findOrFail($id);
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'পেজ ডিলিট করা হয়েছে!');
    }
}
