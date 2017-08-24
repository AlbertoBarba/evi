<?php

namespace Howyi\Modifier;

use Howyi\Evi;

class ReferrerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider referProvider
     */
    public function testRefer(string $path, array $expectedArray, bool $expectedResult)
    {
        $array = Evi::parse($path, false, null, null);
        $result = Referrer::refer($array, $path, '$ref', '$ext');
        $this->assertSame($expectedArray, $array);
        $this->assertSame($expectedResult, $result);
    }

    public function referProvider()
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
    public function testReferFailedWhenNotExist()
    {
        $path = 'tests/files/nine.yml';
        $array = Evi::parse($path, false, null, null);
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
        $result = Referrer::refer($array, $path, '$ref', '$ext');
        restore_error_handler();
    }
}
