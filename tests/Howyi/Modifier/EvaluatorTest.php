<?php

namespace Howyi\Modifier;

class EvaluatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider evaluateProvider
     */
    public function testEvaluate(array $array, array $expectedArray, bool $expectedResult)
    {
        putenv('EVITEST1=99');
        putenv('EVITEST2=99.99');
        $result = Evaluator::evaluate($array);
        $this->assertSame($expectedArray, $array);
        $this->assertSame($expectedResult, $result);
    }

    public function evaluateProvider(): array
    {
        return [
            [
                [
                    1 => 2,
                    3 => Evaluator::PHP_PREFIX . Evaluator::SPLT . 'range(0, 2)'
                ],
                [
                    1 => 2,
                    3 => [0, 1, 2]
                ],
                true
            ],
            [
                [
                    1 => 2,
                    3 => Evaluator::PHP_PREFIX . Evaluator::SPLT . '[0, 1, \'php:range(0, 2)\']'
                ],
                [
                    1 => 2,
                    3 => [
                        0,
                        1,
                        [
                            0,
                            1,
                            2
                        ]
                    ]
                ],
                true
            ],
            [
                [
                    1 => 2,
                    3 => Evaluator::ENV_PREFIX . Evaluator::SPLT . 'EVITEST1'
                ],
                [
                    1 => 2,
                    3 => 99
                ],
                true
            ],
            [
                [
                    1 => 2,
                    3 => Evaluator::ENV_PREFIX . Evaluator::SPLT . 'EVITEST2'
                ],
                [
                    1 => 2,
                    3 => 99.99
                ],
                true
            ],
            [
                [
                    1 => 2,
                    2 => [
                        3 => [
                            32,
                            53
                        ],
                        4 => [
                            45,
                        ]
                    ],
                ],
                [
                    1 => 2,
                    2 => [
                        3 => [
                            32,
                            53
                        ],
                        4 => [
                            45,
                        ]
                    ],
                ],
                false
            ],
        ];
    }
}
