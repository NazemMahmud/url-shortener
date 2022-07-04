<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class HttpHandler
{
    /**
     * API error message response
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function errorMessage(string $message = 'Something went wrong', int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'error' => $message,
            'status' => 'failed'
        ], $statusCode);
    }

    /**
     * API success response
     *
     * @param array|object $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function successResponse(array|object $data, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'status' => 'success'
        ], $statusCode);
    }

    /**
     * @param object $data
     * @return array
     */
    public static function formatExistUrlData(object $data): array
    {
        return [
            'data' => [
                'id' => $data->id,
                'original_url' => $data->original_url,
                'url_hash' => $data->url_hash,
                'message' => 'URL already exist'
            ],
            'status' => 200 // because if data exist, then it is not created, just found
        ];
    }

}
