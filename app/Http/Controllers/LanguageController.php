<?php

namespace App\Http\Controllers;

use App\Helpers\LocalizationHelper;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function toggle(Request $request)
    {
        $newLocale = LocalizationHelper::toggle();
        $msg = ($newLocale === 'bn') ? 'ভাষা পরিবর্তন: বাংলা 🇧🇩' : 'Language switched to English 🇬🇧';
        
        return redirect()->back()->with('success', $msg);
    }

    public function switch(Request $request, $locale)
    {
        LocalizationHelper::setLocale($locale);
        $msg = ($locale === 'bn') ? 'ভাষা পরিবর্তন: বাংলা 🇧🇩' : 'Language switched to English 🇬🇧';

        return redirect()->back()->with('success', $msg);
    }
}
