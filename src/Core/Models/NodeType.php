<?php
declare(strict_types=1);

namespace SMSolver\Core\Models;

use MyCLabs\Enum\Enum;

/**
 * @extends Enum<string>
 *
 * @method static NodeType JOINT()
// * @method static NodeType RIGID_JOINT()
// * @method static NodeType U1U2M()
 * @method static NodeType U1U2()
 * @method static NodeType U1()
 * @method static NodeType U2()
 * @method static NodeType FREE()
 *
 * @psalm-immutable
 */
class NodeType extends Enum
{
    private const JOINT = 'JOINT';
//    private const RIGID_JOINT = 'RIGID_JOINT';
//    private const U1U2M = 'U1U2M';
    private const U1U2 = 'U1U2';
    private const U1 = 'U1';
    private const U2 = 'U2';
    private const FREE = 'FREE';
}

