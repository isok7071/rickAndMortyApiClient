<?php

require '../vendor/autoload.php';

use App\Backend\Controllers\ApiController;
use App\Backend\Helpers\ResponseHelper;
use App\Backend\Helpers\LogHelper;

$apiController = new ApiController();
$requestBody = json_decode(file_get_contents('php://input'), true);

try {
    if (isset($requestBody['method'], $requestBody['params'], $requestBody['id'])) {
        $method = $requestBody['method'];
        $params = $requestBody['params'];
        $requestId = $requestBody['id'];

        $result = $apiController->$method($params);

        echo ResponseHelper::renderResponse($result, $requestId);
    } else {
        $requestId = !empty($requestId) ?: null;
        echo ResponseHelper::renderErrorResponse(400, 'Bad data', $requestId);
    }
} catch (\Exception $e) {
    $requestId = !empty($requestId) ?: null;
    $logger = new LogHelper();
    $logger->log->error('An error occurred: ' . $e->getMessage());
    echo ResponseHelper::renderErrorResponse($e->getCode(), $e->getMessage(), $requestId);
}