<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminLanguageController extends Controller
{
    public function switchLanguage($locale)
    {
        if (in_array($locale, ['bn', 'en'])) {
            session(['admin_locale' => $locale]);
        }

        return redirect()->back()->with('success', $locale === 'bn' ? 'ভাষা পরিবর্তন: বাংলা 🇧🇩' : 'Language switched to English 🇬🇧');
    }
}
