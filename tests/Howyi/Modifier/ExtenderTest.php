<?php

namespace Howyi\Modifier;

use Howyi\Evi;

class ExtenderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider inheritProvider
     */
    public function testExtend(string $path, array $expectedArray, bool $expectedResult)
    {
        $array = Evi::parse($path);
        $result = Extender::extend($array, $path, 'inherit');
        $this->assertSame($expectedArray, $array);
        $this->assertSame($expectedResult, $result);
    }

    public function inheritProvider()
    {
        return [
            [
                'tests/files/five.yml',
                [
                    'sushi' => 555,
                    'yakiniku'  => 88,
                    'unagi' => [
                        'ao' => 323,
                        'aka' => 333,
                        'murasaki'  => [
                            'morning',
                            'afternoon',
                            'midnight',
                        ],
                        'kiiro' => 999,
                    ],
                    'ramen' => 53,
                ],
                true
            ],
        ];
    }

    /**
     * @expectedException \ErrorException
     */
    public function testExpandFailedWhenNotExist()
    {
        $path = 'tests/files/nine.yml';
        $array = Evi::parse($path);
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
            E_WARNING
        );
        $result = Extender::extend($array, $path, 'inherit');
        restore_error_handler();
    }
}
