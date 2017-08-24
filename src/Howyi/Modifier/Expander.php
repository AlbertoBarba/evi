<?php

namespace Howyi\Modifier;

use Howyi\Evi;

class Expander
{
    /**
     * @param array  $array
     * @param string $path
     * @param string $callKey
     * @return bool
     */
    public static function expand(array &$array, string $path, string $callKey): bool
    {
        $isChanged = false;
        foreach ($array as $key => $value) {
            if ($callKey === $key) {
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
                if (self::expand($value, $path, $callKey)) {
                    $isChanged = true;
                }
            }
        }
        return $isChanged;
    }
}
