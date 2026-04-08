<?php

use Isotope\Metronic\Models\Setting;

if (!function_exists('settings')) {
    function settings() {
        return (object) Setting::pluck('text', 'option')->toArray();
    }
}

if (!function_exists('tenant')) {
    function tenant() {
        return true;
    }
}