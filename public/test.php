<?php

use SMSolver\Core\Math\MatrixSolver;
use SMSolver\Core\Math\VectorUtils;
use SMSolver\Core\Models\ForceType;
use SMSolver\Core\Models\NodeType;
use SMSolver\Payload\SystemRequest;
use SMSolver\Utils\OutputInfo;

require __DIR__ . '/../bootstrap.php';


$data = [
    'nodes' => [
        [
            'id' => 'n1',
            'x' => 0,
            'y' => 0,
            'type' => NodeType::U1U2()
        ],
        [
            'id' => 'n2',
            'x' => -14,
            'y' => 14,
            'type' => NodeType::FREE()
        ],
        [
            'id' => 'n3',
            'x' => 50,
            'y' => -2,
            'type' => NodeType::JOINT()
        ],
        [
            'id' => 'n4',
            'x' => 17,
            'y' => 14,
            'type' => NodeType::U1U2()
        ],
        [
            'id' => 'n5',
            'x' => 52,
            'y' => -21,
            'type' => NodeType::FREE()
        ],
    ],
    'beams' => [
        [
            'id' => 'b1',
            'startNode' => 'n1',
            'endNode' => 'n2'
        ],
        [
            'id' => 'b2',
            'startNode' => 'n2',
            'endNode' => 'n3'
        ],
        [
            'id' => 'b3',
            'startNode' => 'n1',
            'endNode' => 'n3'
        ],
        [
            'id' => 'b4',
            'startNode' => 'n3',
            'endNode' => 'n5'
        ],
        [
            'id' => 'b5',
            'startNode' => 'n4',
            'endNode' => 'n5'
        ],
    ],
    'forces' => [
        [
            'id' => 'f1',
            'magnitude' => 50,
            'angle' => 0,
            'type' => ForceType::DEFINED(),
            'node' => 'n2'
        ],
        [
            'id' => 'fx',
            'angle' => 45,
            'type' => ForceType::UNKNOWN(),
            'node' => 'n5'
        ]
    ]
];

$systemRequest = SystemRequest::constructFromArray($data);
//OutputInfo::printJSONln($systemRequest, true);
$Ax = $systemRequest->generateMatrix();
OutputInfo::printMatrix($Ax);

$result = MatrixSolver::solve($Ax);
VectorUtils::scalarMultiply($result, -1);
VectorUtils::roundVector($result);

OutputInfo::printMatrix([
    array_keys($systemRequest->getReferenceSymbolMatrix()),
    $result
]);
//$systemRequest->generateMatrix();
