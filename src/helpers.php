<?php

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

if (! function_exists('XmlToObject')) {
    function XmlToObject($xml)
    {
        return Fluent(XmlToArray($xml));
    }
}
