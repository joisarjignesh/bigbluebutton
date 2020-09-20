<?php

use JoisarJignesh\Bigbluebutton\Bbb;
use JoisarJignesh\Bigbluebutton\Bigbluebutton;

if (! function_exists('Fluent')) {
    function Fluent($array)
    {
        return new \Illuminate\Support\Fluent($array);
    }
}

if (! function_exists('XmlToArray')) {
    function XmlToArray($xml, $out = [])
    {
        $out = json_decode(
            json_encode((array) $xml),
            1
        );

        return $out;
    }
}

if (! function_exists('bigbluebutton')) {
    function bigbluebutton($serverName = null)
    {
        if (is_null($serverName)) {
            return new Bbb(new Bigbluebutton(
                config('bigbluebutton.BBB_SERVER_BASE_URL'),
                config('bigbluebutton.BBB_SECURITY_SALT')
            ));
        }

        return (new Bbb(new Bigbluebutton(null, null)))->server($serverName);
    }
}

if (! function_exists('XmlToObject')) {
    function XmlToObject($xml)
    {
        return Fluent(XmlToArray($xml));
    }
}
