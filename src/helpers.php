<?php

if (!function_exists('git_version')) {
    function git_version($path = null)
    {
        return new Febalist\GitVersion\Version($path);
    }
}

if (!function_exists('version')) {
    function version($without_prefix = false)
    {
        return git_version()->current($without_prefix);
    }
}

if (!function_exists('version')) {
    function version_hash($length = 10)
    {
        return git_version()->hash($length);
    }
}

if (!function_exists('version')) {
    function version_date()
    {
        return git_version()->date();
    }
}
