<?php

namespace Howyi\Modifier;

class Evaluator
{
    const SPLT = ':';
    const PHP_PREFIX = 'php';
    const ENV_PREFIX = 'env';

    /**
     * @param array $array
     * @return bool
     */
    public static function evaluate(array &$array): bool
    {
        $isChanged = false;
        array_walk_recursive(
            $array,
            function (&$value) use (&$isChanged) {
                if (is_string($value)) {
                    switch (explode(':', $value)[0]) {
                        case self::PHP_PREFIX:
                            $evalCode = sprintf(
                                'return (%s);',
                                ltrim($value, self::PHP_PREFIX . self::SPLT)
                            );
                            $value = eval($evalCode);
                            $isChanged = true;
                            if (is_array($value)) {
                                self::evaluate($value);
                            }
                            break;
                        case self::ENV_PREFIX:
                            $value = getenv(ltrim($value, self::ENV_PREFIX . self::SPLT));
                            if (ctype_digit($value)) {
                                $value = intval($value);
                            } elseif (is_numeric($value)) {
                                $value = doubleval($value);
                            }
                            $isChanged = true;
                            break;
                    }
                }
            }
        );
        return $isChanged;
    }
}
