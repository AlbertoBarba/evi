<?php

namespace Howyi\Modifier;

use Howyi\Evi;

class Referrer
{
    /**
     * @param array       $array
     * @param string      $path
     * @param string|null $callKey
     * @param string|null $inheritKey
     * @return bool
     */
    public static function refer(array &$array, string $path, $callKey, $inheritKey): bool
    {
        $isChanged = false;

        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                if (self::refer($value, $path, $callKey, $inheritKey)) {
                    $isChanged = true;
                }
            }
        }

        self::referParse(
            $array,
            $path,
            $isChanged,
            $callKey,
            $callKey,
            $inheritKey,
            function (&$array, $called) {
                $array += $called;
            }
        );

        self::referParse(
            $array,
            $path,
            $isChanged,
            $inheritKey,
            $callKey,
            $inheritKey,
            function (&$array, $inherited) {
                self::merge($array, $inherited);
            }
        );

        return $isChanged;
    }

    /**
     * @param array    $array
     * @param string   $parsed
     */
    private static function referParse(
        array &$array,
        string $path,
        bool &$isChanged,
        $key,
        $callKey,
        $inheritKey,
        callable $combine
    ) {
        if (array_key_exists($key, $array)) {
            $referredPath = self::getPath($path, $array[$key]);
            unset($array[$key]);
            $referred = Evi::parse($referredPath);
            foreach ($referred as &$value) {
                if (is_array($value)) {
                    self::refer($value, $referredPath, $callKey, $inheritKey);
                }
            }
            $combine($array, $referred);
            $isChanged = true;
        }
    }

    /**
     * @param string $path
     * @param string $value
     * @return string
     */
    private static function getPath(string $path, string $value): string
    {
        $generatedPath = dirname($path) . '/' . $value;
        if (!file_exists($generatedPath)) {
            $generatedPath = $value;
        }
        return $generatedPath;
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
