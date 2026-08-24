<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThemeSetting;
use App\Models\SiteSection;
use App\Helpers\ThemeDefaults;
use Illuminate\Http\Request;

class AdminThemeController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.theme.studio');
    }

    public function studio()
    {
        $settings = ThemeSetting::pluck('value', 'key')->toArray();
        $sections = SiteSection::orderBy('sort_order')->get()->keyBy('section_key');

        return view('admin.theme.studio', compact('settings', 'sections'));
    }

    public function saveStudio(Request $request)
    {
        // 1. Save Global Settings
        $settingsInput = $request->input('settings', []);

        // Handle boolean toggles that might be absent if unchecked
        $booleanKeys = [
            'enable_top_ticker',
            'enable_language_switcher',
            'enable_bkash',
            'enable_nagad',
            'enable_rocket',
            'enable_cod',
            'enable_card',
            'enable_lucky_wheel',
            'enable_ai_assistant',
            'enable_social_proof',
        ];

        foreach ($booleanKeys as $bKey) {
            $settingsInput[$bKey] = isset($settingsInput[$bKey]) ? '1' : '0';
        }

        foreach ($settingsInput as $key => $val) {
            ThemeSetting::set($key, $val);
        }

        // 2. Save Section Contents & Active Toggles
        $sectionsInput = $request->input('sections', []);
        foreach ($sectionsInput as $sectionKey => $sectionData) {
            $section = SiteSection::where('section_key', $sectionKey)->first();
            if ($section) {
                $isActive = isset($sectionData['is_active']) && $sectionData['is_active'] == '1';
                $content = $sectionData['content'] ?? [];

                $section->update([
                    'is_active' => $isActive,
                    'content' => $content,
                ]);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All Theme, Logo, Branding & Sections saved and published live! 🚀'
            ]);
        }

        return redirect()->back()->with('success', 'All Theme, Logo, Branding & Sections saved and published live! 🚀');
    }

    public function updateSettings(Request $request)
    {
        return $this->saveStudio($request);
    }

    public function updateSection(Request $request, $sectionKey)
    {
        $section = SiteSection::where('section_key', $sectionKey)->firstOrFail();
        $content = $request->input('content', []);
        $isActive = $request->has('is_active') ? 1 : 0;

        $section->update([
            'is_active' => $isActive,
            'content' => $content,
        ]);

        return redirect()->back()->with('success', "Section '{$section->name}' updated!");
    }

    public function resetDefaults()
    {
        ThemeDefaults::seedDefaults();
        return redirect()->route('admin.theme.studio')->with('success', 'All settings and sections reset to Cyber Defaults! 🔄');
    }
}
