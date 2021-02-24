<?php

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

$result = \SMSolver\Core\Math\MatrixSolver::solve($A, $x);

OutputInfo::pr($result);


$data = [
    'nodes' => [
        ['id' => 'n1', 'x' => 2.3, 'y' => -4.5],
        ['id' => 'n2', 'x' => 4.3, 'y' => -5.5],
        ['id' => 'n3', 'x' => 6.3, 'y' => -3.5],
    ],
    'beams' => [
        ['id' => 'b1', 'startNode' => 'n1', 'endNode' => 'n2'],
    ]
];

$systemRequest = SystemRequest::constructFromArray($data);
OutputInfo::printJSONln($systemRequest, true);

$scriptPath = get_included_files()[0];
OutputInfo::pr($scriptPath);
//2n=3+b
//