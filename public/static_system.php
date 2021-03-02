<?php
declare(strict_types=1);

use SMSolver\Core\Math\MatrixSolver;
use SMSolver\Core\Math\VectorUtils;
use SMSolver\Payload\Request;
use SMSolver\Payload\ResponseBuilder;
use SMSolver\Payload\SystemRequest;

require __DIR__ . '/../bootstrap.php';

$jsonData = json_decode(file_get_contents('php://input'), true);
$request = new Request($jsonData??[]);
//$request = new Request($_REQUEST);

$httpMethod = $_SERVER['REQUEST_METHOD'];

if ($httpMethod !== 'POST') {
    ResponseBuilder::error('method ' . $httpMethod . ' not supported');
    return;
}

$action = $request->getAction();
$supportedActions = ['solve_system'];

if (!in_array($action, $supportedActions)) {
    ResponseBuilder::error('action ' . $action . ' not supported');
    return;
}

if ($action === 'solve_system') {

    $systemData = $request->getArrayParameter('system_data');

    if (is_null($systemData)) {
        ResponseBuilder::error('parameter system_data must be present and array_type');
        return;
    }

    $systemRequest = SystemRequest::constructFromArray($systemData);

    $Ax = $systemRequest->generateMatrix();
//    \SMSolver\Utils\OutputInfo::printMatrix($Ax);
//    var_dump($Ax);
//    die();
    $result = MatrixSolver::solve($Ax);
    VectorUtils::scalarMultiply($result, -1);
    VectorUtils::roundVector($result);

    $reactions = $systemRequest->mapReactionsWithResults($result);
    ResponseBuilder::success(['list_of_reactions' => $reactions]);
    return;
}