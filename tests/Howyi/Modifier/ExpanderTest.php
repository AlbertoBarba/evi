<?php

namespace Howyi\Modifier;

use Howyi\Evi;

class ExpanderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider expandProvider
     */
    public function testExpand(string $path, array $expectedArray, bool $expectedResult)
    {
        $array = Evi::parse($path);
        $result = Expander::expand($array, $path, ['call']);
        $this->assertSame($expectedArray, $array);
        $this->assertSame($expectedResult, $result);
    }

    public function expandProvider()
    {
        return [
            [
                'tests/files/one_d/one.yml',
                [
                    'one_hoge' => 433,
                    'two_foo'  => [
                        'sushi'    => 100,
                        'yakiniku' => 99,
                        'unagi'    => 5,
                    ],
                    'bar' => [
                        'kkd' => 5555,
                        'mdd' => 343,
                        'ex'  => [
                            'text'       => 'text',
                            'over_sushi' => 34343,
                            'gogogo'     => 555,
                            'roku'       => 6,
                        ],
                    ],
                ],
                true
            ],
            [
                'tests/files/three_d/four_d/four.yml',
                [
                    'over_sushi' => 2222,
                    'gogogo'     => 555,
                    'roku'       => 6,
                ],
                false
            ]
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
        $result = Expander::expand($array, $path, ['call']);
        restore_error_handler();
    }
}
