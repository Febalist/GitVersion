<?php

if (!function_exists('version')) {
    function version()
    {
        return new Febalist\GitVersion\Version();
    }
}
