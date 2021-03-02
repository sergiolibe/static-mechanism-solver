<?php

use SMSolver\Core\Models\Force;
use SMSolver\Core\Models\ForceType;
use SMSolver\Core\Models\Node;
use SMSolver\Core\Models\NodeType;
use SMSolver\Core\Models\Reaction;
use SMSolver\Core\Models\ReactionType;
use SMSolver\Utils\OutputInfo;

require __DIR__ . '/../bootstrap.php';

$n = new Node(
    NodeType::U1U2(),
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

//OutputInfo::echoln($f);
//OutputInfo::echoln($f2);


$reactions = Reaction::constructFromNode($n);
OutputInfo::printJSONln(ReactionType::U1()->equals($reactions[0]->getType()));
OutputInfo::printJSONln($reactions);