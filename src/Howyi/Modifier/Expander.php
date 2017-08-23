<?php

namespace Howyi\Modifier;

use Howyi\Evi;

class Expander
{
    /**
     * @param array    $array
     * @param string   $path
     * @param string[] $referrerKeys
     * @return bool
     */
    public static function expand(array &$array, string $path, array $referrerKeys): bool
    {
        $isChanged = false;
        foreach ($array as $key => $value) {
            if (in_array($key, $referrerKeys, true)) {
                $path = dirname($path) . '/' . $value;
                if (!file_exists($path)) {
                    $path = $value;
                }
                unset($array[$key]);
                $array += Evi::parse($path);
                $isChanged = true;
            }
        }
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                if (self::expand($value, $path, $referrerKeys)) {
                    $isChanged = true;
                }
            }
        }
        return $isChanged;
    }
}
