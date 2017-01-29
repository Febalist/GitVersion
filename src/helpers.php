<?php

if (!function_exists('version')) {
    function version($path = null)
    {
        return new Febalist\GitVersion\Version($path);
    }
}

if (!function_exists('version_current')) {
    function version_current($without_prefix = false)
    {
        return version()->current($without_prefix);
    }
}

if (!function_exists('version_last')) {
    function version_last($without_prefix = false)
    {
        return version()->last($without_prefix);
    }
}

if (!function_exists('version_hash')) {
    function version_hash($length = 10)
    {
        return version()->hash($length);
    }
}

if (!function_exists('version_date')) {
    function version_date()
    {
        return version()->date();
    }
}
