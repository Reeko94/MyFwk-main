<?php


namespace Fwk\Util;


class Debug
{
    public static function dump($var, $title = null, $return = false)
    {
        $content = '';
        if (is_string($title)) {
            $content .= '<strong>' . $title . '</strong>';
        }
        $content .= '<pre>' . print_r($var, true) . '</pre>';

        if($return)
            return $content;

        echo $content;
    }
}