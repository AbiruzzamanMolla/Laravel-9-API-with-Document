<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ResponseTrait
{
    /**
     * success json response function
     *
     * @param array|object $data
     * @param string $message
     * @param integer $code
     * @param string $type
     * @return JsonResponse
     */
    public function responseSuccess(array|object $data = null, string $message = 'success', int $code = 200, string $type = 'json', array|object $errors = null): JsonResponse
    {
        return response()->json([
            'status' => true,
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'errors' => $errors,
        ], $code);
    }

    /**
     * error json response function
     * @param array|object|null $data
     * @param string $message
     * @param array|object|string|null $errors
     * @param int $code
     * @param string $type
     *
     * @return JsonResponse
     */
    public function responseError(array|object $data = null, string $message = 'invalid', array|object|string $errors = null, int $code = 404, string $type = 'json'): JsonResponse
    {
        return response()->json([
            'status' => false,
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'errors' => $errors,
        ], $code);
    }
}
