<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class NavigationCacheService
{
    public static function getCachedSidebar()
    {
        return Cache::remember('sidebar_navigation', 3600, function () {
            return View::make('partials.sidebar')->render();
        });
    }

    public static function getCachedNavbar()
    {
        return Cache::remember('navbar_navigation', 3600, function () {
            return View::make('partials.navbar')->render();
        });
    }

    public static function clearNavigationCache()
    {
        Cache::forget('sidebar_navigation');
        Cache::forget('navbar_navigation');
    }
}
