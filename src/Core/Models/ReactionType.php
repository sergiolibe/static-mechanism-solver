<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;

use MyCLabs\Enum\Enum;

/**
 * @extends Enum<string>
 *
 * @method static ReactionType U1()
 * @method static ReactionType U2()
 * @method static ReactionType BEAM()
 * @method static ReactionType FORCE()
 * @method static ReactionType RESULT()
 *
 * @psalm-immutable
 */
class ReactionType extends Enum
{
    private const U1 = 'U1';
    private const U2 = 'U2';
    private const BEAM = 'BEAM';
    private const FORCE = 'FORCE';
    private const RESULT = 'RESULT';
}

