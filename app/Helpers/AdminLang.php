<?php

namespace App\Helpers;

class AdminLang
{
    public static function get(string $key, ?string $default = null): string
    {
        return LocalizationHelper::get($key, $default);
    }

    public static function getLocale(): string
    {
        return LocalizationHelper::getLocale();
    }
}
