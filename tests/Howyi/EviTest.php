<?php

namespace Howyi;

use Symfony\Component\Yaml\Yaml;

class EviTest extends \PHPUnit\Framework\TestCase
{
    public function testNormalParse()
    {
        $path = 'tests/files/five.yml';
        $array = Evi::parse($path, false, null, null);
        $this->assertSame(Yaml::parse(file_get_contents($path)), $array);
        $path = 'tests/files/two.yaml';
        $array = Evi::parse($path, false, null, null);
        $this->assertSame(Yaml::parse(file_get_contents($path)), $array);
        $path = 'tests/files/one.json';
        $array = Evi::parse($path, false, null, null);
        $this->assertSame(json_decode(file_get_contents($path), true), $array);
    }

    /**
     * @expectedException \ErrorException
     */
    public function testParseFailed()
    {
        $path = 'tests/files/seven.yml';
        $array = Evi::parse($path);
        $this->assertSame(Yaml::parse(file_get_contents($path)), $array);
    }

    public function testParseWhenFullOption()
    {
        $eval = true;
        $path = 'tests/files/eight.yml';
        putenv('EVITEST1=99');
        $array = Evi::parse($path, $eval);
        $expected = [
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
                'sushi' => 22,
                'ramen' => 53,
                'unagi' => [
                    'aka' => 999,
                    'kiiro' => 999,
                    'murasaki' => [
                        'morning',
                        'midnight',
                    ]
                ]
            ],
            'e' => [
                'array' => [
                    0,
                    1,
                    2,
                    3,
                ],
                'env' => 99,
            ]
        ];
        $this->assertEquals($expected, $array);
    }
}
