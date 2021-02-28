<?php
declare(strict_types=1);

namespace SMSolver\Payload;


class ResponseBuilder
{
    public static function internalError(string $message): void
    {
        http_response_code(500);
        $response = [];
        $response['success'] = false;
        $response['message'] = $message;
        echo json_encode($response);
    }

    /** @param array<mixed> $arrayOfData */
    public static function success(array $arrayOfData = []): void
    {
        http_response_code(200);
        self::setHeaders();
        $arrayOfData['success'] = true;
        echo json_encode($arrayOfData);
    }

    /**
     * @param string $errorMessage
     * @param string $errorCode
     * @param array<mixed> $baseArray
     */
    public static function error(string $errorMessage = '', string $errorCode = '', array $baseArray = []): void
    {
        http_response_code(200);
        self::setHeaders();
//        $json               = $baseArray;
        $json['success']    = false;

        if (!empty($errorMessage))
            $json['errormessage']   = $errorMessage;
        if (!empty($errorCode))
            $json['errorcode']      = $errorCode;

        echo json_encode($json);
    }

    public static function unauthorized(): void
    {
        header("HTTP/1.1 401 Unauthorized");
    }

    private static function setHeaders(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST,OPTIONS");
        header("Access-Control-Allow-Headers: *");
    }
}
