<?php

namespace Howyi\Modifier;

use Howyi\Evi;

class Extender
{
    /**
     * @param array  $array
     * @param string $path
     * @param string $inheritKey
     * @return bool
     */
    public static function extend(array &$array, string $path, string $inheritKey): bool
    {
        $isChanged = false;
        foreach ($array as $key => $value) {
            if ($inheritKey === $key) {
                $path = dirname($path) . '/' . $value;
                if (!file_exists($path)) {
                    $path = $value;
                }
                unset($array[$key]);
                $parsed = Evi::parse($path);
                self::merge($array, $parsed);
                $isChanged = true;
            }
        }
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                if (self::extend($value, $path, $inheritKey)) {
                    $isChanged = true;
                }
            }
        }
        return $isChanged;
    }

    /**
     * @param array    $array
     * @param string   $parsed
     */
    private static function merge(array &$array, array $parsed)
    {
        if (array_values($array) === $array and array_values($parsed) === $parsed) {
            $array = array_values(array_unique(array_merge($array, $parsed)));
        } else {
            $intersected = array_intersect_key($array, $parsed);
            $array += array_diff_key($parsed, $array);
            foreach ($intersected as $key => $value) {
                if (is_array($array[$key]) and is_array($parsed[$key])) {
                    self::merge($array[$key], $parsed[$key]);
                }
            }
        }
    }
}
