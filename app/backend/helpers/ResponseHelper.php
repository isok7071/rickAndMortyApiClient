<?php

namespace App\Backend\Helpers;

class ResponseHelper
{
    /**
     * Возворащает json с результатом
     * @param mixed $data
     * @param int $requestId
     * @return string
     */
    public static function renderResponse(?array $data, int $requestId): string
    {
        header('Content-Type: application/json');
        header('Cache-Control: public, max-age=300');
        return json_encode([
            'jsonrpc' => '2.0',
            'result' => $data,
            'id' => $requestId,
        ]);
    }

    /**
     * Возворащает json с описанием ошибки
     *
     * @param integer $code
     * @param string $message
     * @param integer|null $requestId
     * @return string
     */
    public static function renderErrorResponse(int $code, string $message, ?int $requestId): string
    {
        header('Content-Type: application/json');
        return json_encode([
            'jsonrpc' => '2.0',
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
            'id' => $requestId,
        ]);
    }
}