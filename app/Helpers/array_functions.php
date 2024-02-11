<?php
if (!function_exists('obj2Arr')) {
    function obj2Arr($obj)
    {
        return json_decode(json_encode($obj),TRUE);
    }
}

if (!function_exists('arrangeArrayPair')) {
    function arrangeArrayPair($mainArray,$keyLabel,$valueLabel)
    {
        $newArray = [];
        if(is_array($mainArray) && count($mainArray)>0)
        {
            $newArray = array_combine(
                array_map(
                    function ($value) use ($keyLabel) {
                        return $value[$keyLabel];
                    },
                    $mainArray
                ),
                array_map(
                    function ($value) use ($valueLabel) {
                        return isset($value[$valueLabel]) ? $value[$valueLabel] : '';
                    },
                    $mainArray
                )
            );
        }

        return $newArray;
    }
}



function cleanString($string)
{
    // allow only letters
    $res = preg_replace("/[^a-zA-Z_]/", "", $string);

    // make lowercase
    $res = strtolower($res);

    // return
    return $res;
}
