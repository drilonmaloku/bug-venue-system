<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class BaseApiController extends Controller
{
    /**
     * Return a success response
     */
    protected function successResponse(
        mixed $data = null,
        string $message = 'Success',
        int $code = 200,
        array $meta = []
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => array_merge([
                'timestamp' => now()->toIso8601String(),
                'request_id' => request()->header('X-Request-ID', uniqid('req_')),
                'version' => 'v1',
            ], $meta),
        ];

        return response()->json($response, $code);
    }

    /**
     * Return an error response
     */
    protected function errorResponse(
        string $message,
        int $code = 400,
        ?string $errorCode = null,
        mixed $details = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
            'error' => [
                'code' => $errorCode ?? 'ERROR',
            ],
            'meta' => [
                'timestamp' => now()->toIso8601String(),
                'request_id' => request()->header('X-Request-ID', uniqid('req_')),
                'version' => 'v1',
            ],
        ];

        if ($details !== null) {
            $response['error']['details'] = $details;
        }

        return response()->json($response, $code);
    }

    /**
     * Return a paginated response
     */
    protected function paginatedResponse(
        mixed $resource,
        string $message = 'Data retrieved successfully'
    ): JsonResponse {
        $data = $resource->response()->getData(true);

        return $this->successResponse(
            $data['data'],
            $message,
            200,
            ['pagination' => $data['meta'] ?? null]
        );
    }

    /**
     * Return a no content response
     */
    protected function noContentResponse(): Response
    {
        return response()->noContent();
    }

    /**
     * Return a created response
     */
    protected function createdResponse(
        mixed $data = null,
        string $message = 'Resource created successfully'
    ): JsonResponse {
        return $this->successResponse($data, $message, 201);
    }
}
