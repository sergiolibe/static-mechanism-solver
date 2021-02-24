<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;

use MyCLabs\Enum\Enum;

/**
 * @extends Enum<string>
 *
 * @method static ForceType DEFINED()
 * @method static ForceType UNKNOWN()
 *
 * @psalm-immutable
 */
class ForceType extends Enum
{
    private const DEFINED = 'DEFINED';
    private const UNKNOWN = 'UNKNOWN';
}

