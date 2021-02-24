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

        $result = MatrixSolver::solveAx($A, $x);
        self::assertEquals([3 / 7], $result);


        $A = [
            [7, 1],
            [5, 13]
        ];
        $x = [
            1,
            3
        ];
        $result = MatrixSolver::solveAx($A, $x);
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
        $result = MatrixSolver::solveAx($A, $x);
        $expectedResult = [
            (-18680 / 2647),
            (7461 / 2647),
            (19904 / 2647),
            (2174 / 2647),
        ];
        self::assertEquals($expectedResult, $result);
    }

    public function testCorrectOutputMiddleMatrix()
    {

        $F = 1;
        $dX = 2;
        $dY = 1;
        $H = sqrt($dX * $dX + $dY * $dY);
        $cosAlpha = $dX / $H;
        $sinAlpha = $dY / $H;

        $Ax = [
            [0, $cosAlpha, 0, 0, 0, 0, $F],
            [0, $sinAlpha, 1, 0, 0, 0, 0],
            [1, 0, 0, 1, 0, 0, 0],
            [0, 0, 1, 0, 1, 0, 0],
            [1, $cosAlpha, 0, 0, 0, 0, 0],
            [0, $sinAlpha, 0, 0, 0, 1, 0],
        ];

//        $expectedResult = ['F12','F23','F13','S1X','S1Y','S2Y'];
        $expectedResult = [-1.0, 1.118, -0.5, 1.0, 0.5, -0.5,];

        $result = MatrixSolver::solve($Ax);
        VectorUtils::roundVector($result);

        self::assertEquals($expectedResult, $result);
    }

    public function testCorrectOutputBigMatrix()
    {
        $Ax = [
            [0.5547, 0.0000, 0.0000, 0.0000, 1.0000, 0.0000, 0.0000, 0.0000, 1.0000, 0.0000, 0.0000],
            [0.8321, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 1.0000, 0.0000, 0.0000, 0.0000],
            [-.5547, 1.0000, 0.0000, 0.0000, 0.0000, 0.5547, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000],
            [-.8321, 0.0000, 0.0000, 0.0000, 0.0000, -.8321, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000],
            [0.0000, -1.000, 0.5547, 0.0000, 0.0000, 0.0000, -.5547, 0.0000, 0.0000, 0.0000, 0.0000],
            [0.0000, 0.0000, -.8321, 0.0000, 0.0000, 0.0000, -.8321, 0.0000, 0.0000, 0.0000, 0.0000],
            [0.0000, 0.0000, 0.0000, 1.0000, -1.000, -.5547, 0.5547, 0.0000, 0.0000, 0.0000, 0.0000],
            [0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.8321, 0.8321, 0.0000, 0.0000, 0.0000, 4.0000],
            [0.0000, 0.0000, -.5547, -1.000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000],
            [0.0000, 0.0000, 0.8321, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 0.0000, 1.0000, 0.0000]
        ];

        $expectedResult = [-2.404, -2.667, -2.404, 1.333, 1.333, 2.404, 2.404, 2.0, 0.0, 2.0,];

        $result = MatrixSolver::solve($Ax);
        VectorUtils::roundVector($result);

        self::assertEquals($expectedResult, $result);
    }
}
