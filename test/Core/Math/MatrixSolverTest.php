<?php

namespace SMSolver\Core\Math;

use PHPUnit\Framework\TestCase;

class MatrixSolverTest extends TestCase
{

    public function testCorrectSolutionOutput()
    {

        $A = [
            [7]
        ];
        $x = [
            3
        ];

        $result = MatrixSolver::solve($A, $x);
        self::assertEquals([3 / 7], $result);


        $A = [
            [7, 1],
            [5, 13]
        ];
        $x = [
            1,
            3
        ];
        $result = MatrixSolver::solve($A, $x);
        self::assertEquals([(5 / 43), (8 / 43)], $result);


        $A = [
            [7, 1, 6, 3],
            [5, 13, 0, 2],
            [4, 9, 2, 1],
            [-1, -8, 1.5, 10],
        ];
        $x = [
            1,
            3,
            13,
            4,
        ];
        $result = MatrixSolver::solve($A, $x);
        $expectedResult = [
            (-18680 / 2647),
            (7461 / 2647),
            (19904 / 2647),
            (2174 / 2647),
        ];
        self::assertEquals($expectedResult, $result);
    }
}
