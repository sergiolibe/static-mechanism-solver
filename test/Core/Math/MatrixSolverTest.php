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

    public function testCorrectOutputMiddleMatrix2()
    {

        $F1 = 50;
        $x1=0;$y1=0;
        $x2=-14;$y2=14;
        $x3=50;$y3=-2;
        $x4=17;$y4=14;
        $x5=52;$y5=-21;

        $dX_12 = -14;
        $dY_12 = 14;
        $H_12 = +sqrt(($dX_12 * $dX_12) + ($dY_12 * $dY_12));
        $cosAlpha12 = $dX_12 / $H_12;
        $sinAlpha12 = $dY_12 / $H_12;

        $dX_13 = 50;
        $dY_13 = -2;
        $H_13 = +sqrt(($dX_13 * $dX_13) + ($dY_13 * $dY_13));
        $cosAlpha13 = $dX_13 / $H_13;
        $sinAlpha13 = $dY_13 / $H_13;

        $dX_23 = 64;
        $dY_23 = -16;
        $H_23 = +sqrt(($dX_23 * $dX_23) + ($dY_23 * $dY_23));
        $cosAlpha23 = $dX_23 / $H_23;
        $sinAlpha23 = $dY_23 / $H_23;

        $dX_35 = 2;
        $dY_35 = -19;
        $H_35 = +sqrt(($dX_35 * $dX_35) + ($dY_35 * $dY_35));
        $cosAlpha35 = $dX_35 / $H_35;
        $sinAlpha35 = $dY_35 / $H_35;

        $dX_45 = 35;
        $dY_45 = -35;
        $H_45 = +sqrt(($dX_45 * $dX_45) + ($dY_45 * $dY_45));
        $cosAlpha45 = $dX_45 / $H_45;
        $sinAlpha45 = $dY_45 / $H_45;

        $cosAlphaFx = cos(pi()/4);
        $sinAlphaFx = sin(pi()/4);

        $Ax = [
            [(($x2-$x1)/$H_12), (($x3-$x1)/$H_13),0,0,0,1,0,0,0,0,0],//1x
            [(($y2-$y1)/$H_12), (($y3-$y1)/$H_13),0,0,0,0,1,0,0,0,0],//1y

            [(($x1-$x2)/$H_12),0,(($x3-$x2)/$H_23),0,0,0,0,0,0,0,$F1],//2x
            [(($y1-$y2)/$H_12),0,(($y3-$y2)/$H_23),0,0,0,0,0,0,0,0],//2y

            [0,(($x1-$x3)/$H_13),(($x2-$x3)/$H_23),(($x5-$x3)/$H_35),0,0,0,0,0,0,0],//3x
            [0,(($y1-$y3)/$H_13),(($y2-$y3)/$H_23),(($y5-$y3)/$H_35),0,0,0,0,0,0,0],//3y

            [0,0,0,0,(($x5-$x4)/$H_45),0,0,1,0,0,0],//4x
            [0,0,0,0,(($y5-$y4)/$H_45),0,0,0,1,0,0],//4y

            [0,0,0,(($x3-$x5)/$H_35),(($x4-$x5)/$H_45),0,0,0,0,$cosAlphaFx,0],//5x
            [0,0,0,(($y3-$y5)/$H_35),(($y4-$y5)/$H_45),0,0,0,0,$sinAlphaFx,0],//5y
        ];

//        echo 'Ax: '. count($Ax).PHP_EOL;
//        foreach ($Ax as $i=>$row) {
//            echo 'Ax.'.$i.': '. count($row).PHP_EOL;
//        }

//        $expectedResult = ['F12','F13','F23','F35','F45','S1X','S1Y','S4X','S4Y','Fx'];
        $expectedResult = [23.57,65.239,-68.718,-14.137,10.988,-48.52,-14.059,-7.77,7.77,8.895];

        $result = MatrixSolver::solve($Ax);
        VectorUtils::scalarMultiply($result,-1);
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
