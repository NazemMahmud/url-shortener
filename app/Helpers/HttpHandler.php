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
    public static function errorMessage(string $message = 'Something went wrong', int $statusCode = 404): JsonResponse
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

}
