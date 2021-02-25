<?php
declare(strict_types=1);

namespace SMSolver\Core;

use MyCLabs\Enum\Enum;

/**
 * @extends Enum<string>
 *
 * @method static Axis X()
 * @method static Axis Y()
 *
 * @psalm-immutable
 */
class Axis extends Enum
{
    private const X = 'X';
    private const Y = 'Y';
}

