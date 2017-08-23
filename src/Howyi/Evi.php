<?php

namespace Howyi;

use Symfony\Component\Yaml\Yaml;
use Howyi\Modifier\Evaluator;
use Howyi\Modifier\Expander;
use Howyi\Modifier\Extender;

class Evi
{
    /**
     * @param string          $path
     * @param bool            $eval
     * @param string|string[] $referrerKeys
     * @param string|string[] $extendKeys
     * @throws \ErrorException
     * @return array
     */
    public static function parse(
        string $path,
        bool $eval = false,
        $referrerKeys = null,
        $extendKeys = null
    ): array {
        $pathinfo = pathinfo($path);
        $contents = file_get_contents($path);
        $parsed = [];
        switch (strtolower($pathinfo['extension'])) {
            case 'yml':
            case 'yaml':
                // エラー制御演算子によって表示されないキー重複エラーを出力させる
                set_error_handler(
                    function ($errno, $errstr, $errfile, $errline) {
                        throw new \ErrorException(
                            $errstr,
                            0,
                            $errno,
                            $errfile,
                            $errline
                        );
                    },
                    E_USER_DEPRECATED
                );
                $parsed = Yaml::parse($contents);
                restore_error_handler();
                break;
            case 'json':
                $parsed = json_decode($contents, true);
                break;
        }

        do {
            $changed = 0;
            if ($eval) {
                $changed += Evaluator::evaluate($parsed);
            }

            if (!is_null($referrerKeys)) {
                $changed += Expander::expand(
                    $parsed,
                    $path,
                    is_array($referrerKeys) ? $referrerKeys : [$referrerKeys]
                );
            }

            if (!is_null($extendKeys)) {
                $changed += Extender::extend(
                    $parsed,
                    $path,
                    is_array($extendKeys) ? $extendKeys : [$extendKeys]
                );
            }
        } while ($changed !== 0);

        return $parsed;
    }
}
