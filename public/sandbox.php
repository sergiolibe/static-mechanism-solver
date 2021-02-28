<?php

use SMSolver\Core\Models\Force;
use SMSolver\Core\Models\ForceType;
use SMSolver\Core\Models\Node;
use SMSolver\Core\Models\NodeType;
use SMSolver\Utils\OutputInfo;

require __DIR__ . '/../bootstrap.php';

$n = new Node(
    NodeType::FREE(),
    'n1',
    14.3,
    -5,
);

$f = new Force(
    ForceType::UNKNOWN(),
    $n,
    'f1',
    10.20,
    30);

$f2 = Force::constructFromArray(
    [
        'type' => 'UNKNOWN',
        'node' => $n,
        'id' => 'f1',
        'magnitude' => 10.20,
        'angle' => 30
    ]
);

OutputInfo::echoln($f);
OutputInfo::echoln($f2);
