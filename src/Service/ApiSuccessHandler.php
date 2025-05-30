<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiSuccessHandler
{
    public function handleSuccess(string $resource, array $data): JsonResponse // httpCode: 200
    {
        return new JsonResponse([
            'success' => true,
            'message' => sprintf('%s retrieved successfully.', ucfirst($resource)),
            'data' => $data,
        ], 200);
    }

    public function handleCreated(string $resource, array $data): JsonResponse // httpCode: 201
    {
        return new JsonResponse([
            'success' => true,
            'message' => sprintf('%s created successfully.', ucfirst($resource)),
            'data' => $data,
        ], 201);
    }

    public function handleNoContent(string $resource): JsonResponse // httpCode: 204
    {
        return new JsonResponse(null, 204);
    }
}