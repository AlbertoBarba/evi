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

        if (array_key_exists($callKey, $array)) {
            $calledPath = self::getPath($path, $array[$callKey]);
            unset($array[$callKey]);
            $called = Evi::parse($calledPath);
            foreach ($called as &$value) {
                if (is_array($value)) {
                    self::refer($value, $calledPath, $callKey, $inheritKey);
                }
            }
            $array += $called;
            $isChanged = true;
        }

        if (array_key_exists($inheritKey, $array)) {
            $inheritedPath = self::getPath($path, $array[$inheritKey]);
            unset($array[$inheritKey]);
            $inherited = Evi::parse($inheritedPath);
            foreach ($inherited as &$value) {
                if (is_array($value)) {
                    self::refer($value, $inheritedPath, $callKey, $inheritKey);
                }
            }
            self::merge($array, $inherited);
            $isChanged = true;
        }

        return $isChanged;
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
