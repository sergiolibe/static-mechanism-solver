<?php

use SMSolver\Core\Models\ForceType;
use SMSolver\Core\Models\NodeType;
use SMSolver\Payload\SystemRequest;
use SMSolver\Utils\OutputInfo;

include __DIR__ . '/../bootstrap.php';


$fa = 5;
$dx = 123;
$dy = 103;

// x1 y1 x3 fb(y3)
$A = [
    [1, 0, 1,],//Sum X
    [0, 1, 0],//Sum Y
    [($dy + 0), ($dx + 0), ($dy - 1)],
];

$x = [
    $fa,
    0,
    $fa * (1),
];

$result = \SMSolver\Core\Math\MatrixSolver::solveAx($A, $x);

OutputInfo::pr($result);


$data = [
    'nodes' => [
        ['id' => 'n1', 'x' => 0, 'y' => 0, 'type' => NodeType::U1U2()],
        ['id' => 'n2', 'x' => 2, 'y' => 0, 'type' => NodeType::U2()],
        ['id' => 'n3', 'x' => 0, 'y' => 1, 'type' => NodeType::FREE()],
    ],
    'beams' => [
        ['id' => 'b1', 'startNode' => 'n1', 'endNode' => 'n2'],
        ['id' => 'b2', 'startNode' => 'n2', 'endNode' => 'n3'],
        ['id' => 'b3', 'startNode' => 'n1', 'endNode' => 'n3'],
    ],
    'forces' => [
        ['id' => 'f1', 'magnitude' => 1, 'angle' => 0, 'type' => ForceType::DEFINED(), 'node' => 'n3']
    ]
];

$systemRequest = SystemRequest::constructFromArray($data);
OutputInfo::printJSONln($systemRequest, true);

$scriptPath = get_included_files()[0];
OutputInfo::pr($scriptPath);
//2n=3+b
//