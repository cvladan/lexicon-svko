<?php

if (!function_exists('mb_convert_encoding')) {
    function mb_convert_encoding($str, $to_encoding, $from_encoding = null)
    {
        if ($from_encoding == 'UTF-8' && $to_encoding == 'HTML-ENTITIES') {
            return html_entity_decode( htmlentities($str, ENT_QUOTES | ENT_HTML5, 'UTF-8', false) );
        } else {
            return @iconv($from_encoding, $to_encoding, $str);
        }
    }
}

if (!function_exists('mb_strpos')) {
    function mb_strpos($haystack, $needle, $offset = 0)
    {
        return strpos($haystack, $needle, $offset);
    }
}

if (!function_exists('mb_substr')) {
    function mb_substr($string, $start, $length = null)
    {
        return substr($string, $start, $length);
    }
}

if (!function_exists('mb_strlen')) {
    function mb_strlen($string)
    {
        return strLen($string);
    }
}

if (!function_exists('mb_strtoupper')) {
    function mb_strtoupper($string)
    {
        return strtoupper($string);
    }
}
