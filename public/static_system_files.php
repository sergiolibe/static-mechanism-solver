<?php
declare(strict_types=1);

use SMSolver\Payload\Request;
use SMSolver\Payload\ResponseBuilder;
use SMSolver\StaticSystem\StaticSystemRepository;

require __DIR__ . '/../bootstrap.php';

$jsonData = json_decode(file_get_contents('php://input'), true);
$request = new Request($jsonData ?? $_REQUEST);
//$request = new Request($_REQUEST);
//var_dump($request);
//die();
$httpMethod = $_SERVER['REQUEST_METHOD'];

$action = $request->getAction();
$supportedActions = ['fetch_static_system', 'fetch_static_systems', 'upload_static_system'];

if (!in_array($action, $supportedActions)) {
    ResponseBuilder::error('action ' . $action . ' not supported');
    return;
}

$staticSystemRepository = StaticSystemRepository::getInstance();

if ($action === 'fetch_static_system') {

    if ($httpMethod !== 'GET') {
        ResponseBuilder::error('method ' . $httpMethod . ' not supported');
        return;
    }

    $systemName = $request->getStringParameter('name');

    if (is_null($systemName)) {
        ResponseBuilder::error('parameter name must be present and string_type');
        return;
    }

    $systemData = $staticSystemRepository->getByName($systemName);
    ResponseBuilder::success(['system_data' => $systemData]);
    return;
} elseif ($action === 'fetch_static_systems') {

    if ($httpMethod !== 'GET') {
        ResponseBuilder::error('method ' . $httpMethod . ' not supported');
        return;
    }

    $files = $staticSystemRepository->getAll();
    ResponseBuilder::success(['list_of_static_systems' => $files]);
    return;
} elseif ($action === 'upload_static_system') {

    if ($httpMethod !== 'POST') {
        ResponseBuilder::error('method ' . $httpMethod . ' not supported');
        return;
    }

    $systemData = $request->getArrayParameter('system_data');

    if (is_null($systemData)) {
        ResponseBuilder::error('parameter system_data must be present and array_type');
        return;
    }

    $systemName = $request->getStringParameter('name');

    if (is_null($systemName)) {
        ResponseBuilder::error('parameter name must be present and string_type');
        return;
    }

    $success = $staticSystemRepository->saveStaticSystem($systemData, $systemName);
    if ($success)
        ResponseBuilder::success();
    else
        ResponseBuilder::error();

}