<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiErrorHandler
{
    public function handleBadRequest(string $id): JsonResponse // httpCode: 400
    {
        return new JsonResponse([
            'success' => false,
            'error' => [
                'code' => 'BAD_REQUEST',
                'message' => 'The ID must be a positive integer.',
                'hint' => sprintf('Make sure the ID is a positive whole number (e.g., %s).', $id),
            ]
        ], 400);
    }

    public function handleUnauthorized(string $id): JsonResponse // httpCode: 401
    {
        return new JsonResponse([
            'success' => false,
            'error' => [
                'code' => 'UNAUTHORIZED',
                'message' => 'You are not authorized to access this resource.',
                'hint' => sprintf('Check if you have the right permissions for the resource with ID %s.', $id),
            ]
        ], 401);
    }

    public function handleForbidden(string $id): JsonResponse // httpCode: 403
    {
        return new JsonResponse([
            'success' => false,
            'error' => [
                'code' => 'FORBIDDEN',
                'message' => 'You do not have permission to access this resource.',
                'hint' => sprintf('Check if you have the right permissions for the resource with ID %s.', $id),
            ]
        ], 403);
    }

    public function handleNotFound(string $resource): JsonResponse // httpCode: 404
    {
        return new JsonResponse([
            'success' => false,
            'error' => [
                'code' => 'NOT_FOUND',
                'message' => sprintf('%s not found.', ucfirst($resource)),
                'hint' => sprintf('Check if the %s ID is correct.', $resource),
            ]
        ], 404);
    }

    public function handleGone(string $resource): JsonResponse // httpCode: 410
    {
        return new JsonResponse([
            'success' => false,
            'error' => [
                'code' => 'GONE',
                'message' => sprintf('%s is no longer available.', ucfirst($resource)),
                'hint' => sprintf('Check if the %s ID is correct.', $resource),
            ]
        ], 410);
    }

    public function handlePayloadTooLarge(): JsonResponse // httpCode: 413
    {
        return new JsonResponse([
            'success' => false,
            'error' => [
                'code' => 'PAYLOAD_TOO_LARGE',
                'message' => 'The payload is too large.',
                'hint' => 'Try reducing the size of the payload.',
            ]
        ], 413);
    }

    public function handleImATeapot(): JsonResponse // httpCode: 418 (easter egg)
    {
        return new JsonResponse([
            'success' => false,
            'error' => [
                'code' => 'IM_A_TEAPOT',
                'message' => 'I\'m a teapot. The requested operation cannot be performed on this resource.',
                'hint' => 'This is a playful HTTP status code. Try a different request.',
            ]
        ], 418);
    }

    public function handleInternalServerError(\Exception $e): JsonResponse // httpCode: 500
    {
        return new JsonResponse([
            'success' => false,
            'error' => [
                'code' => 'INTERNAL_SERVER_ERROR',
                'message' => 'An unexpected server error occurred.',
                'hint' => 'Try again later or contact support.',
                'debug' => $e->getMessage()
            ]
        ], 500);
    }

    public function handleNotImplemented(string $resource): JsonResponse // httpCode: 501
    {
        return new JsonResponse([
            'success' => false,
            'error' => [
                'code' => 'NOT_IMPLEMENTED',
                'message' => sprintf('%s is not implemented.', ucfirst($resource)),
                'hint' => sprintf('This %s endpoint is not available yet.', $resource),
            ]
        ], 501);
    }
}