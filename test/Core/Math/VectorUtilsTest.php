<?php

namespace SMSolver\Core\Math;

use PHPUnit\Framework\TestCase;

class VectorUtilsTest extends TestCase
{

    public function testRoundVector()
    {

        $notRoundVector =
            [1.76547, 2.37654367, -4.38765783];

        $expectedRoundVector =
            [1.765, 2.377, -4.388];

        VectorUtils::roundVector($notRoundVector, 3);

        self::assertEquals($expectedRoundVector, $notRoundVector);


        $expectedRoundVector2Precision =
            [1.77, 2.38, -4.39];
        VectorUtils::roundVector($notRoundVector, 2);
        self::assertEquals($expectedRoundVector2Precision, $notRoundVector);


        $expectedRoundVector1Precision =
            [1.8, 2.4, -4.4];
        VectorUtils::roundVector($notRoundVector, 1);
        self::assertEquals($expectedRoundVector1Precision, $notRoundVector);
    }
}
