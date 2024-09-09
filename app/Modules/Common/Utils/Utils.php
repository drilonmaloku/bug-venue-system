<?php

namespace App\Modules\Common\Utils;

class Utils
{
    public static function removePrefix($data, $prefix)
    {
        $newData = [];

        foreach ($data as $key => $value) {
            if (strpos($key, $prefix) === 0) {
                $newKey = substr($key, strlen($prefix));
                $newData[$newKey] = $value;
            } else {
                $newData[$key] = $value;
            }
        }

        return $newData;
    }
}