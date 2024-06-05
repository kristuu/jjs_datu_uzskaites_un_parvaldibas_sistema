<?php

if (!function_exists('sanitize_filename')) {
    function sanitize_filename($filename)
    {
        $search = [' ', 'ņ', 'ū', 'ā', 'č', 'ē', 'ģ', 'ī', 'ķ', 'ļ', 'ņ', 'š', 'ž'];
        $replace = ['_', 'n', 'u', 'a', 'c', 'e', 'g', 'i', 'k', 'l', 'n', 's', 'z'];
        return str_replace($search, $replace, $filename);
    }
}
