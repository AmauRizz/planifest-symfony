<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiUserController extends AbstractController
{
    #[Route('/api/users/{id}', name: 'api_user_by_id', methods: ['GET'])]
    public function getUserById($id, UserRepository $userRepository): JsonResponse
    {
        if (!ctype_digit((string)$id) || (int)$id <= 0) {
            return $this->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_ID',
                    'message' => 'The user ID must be a positive integer.',
                    'hint' => 'Make sure the ID is a positive whole number (e.g., 1, 2, 3...).',
                ]
            ], 400);
        }

        try {
            $user = $userRepository->find($id);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => [
                    'code' => 'INTERNAL_SERVER_ERROR',
                    'message' => 'An unexpected error occurred.',
                    'hint' => 'Try again later or contact support.',
                    'debug' => $e->getMessage(),
                ]
            ], 500);
        }

        if (!$user) {
            return $this->json([
                'success' => false,
                'error' => [
                    'code' => 'RESOURCE_NOT_FOUND',
                    'message' => 'User not found.',
                    'hint' => 'Check if the user ID is correct.',
                ]
            ], 404);
        }

        return $this->json([
            'success' => true,
            'message' => 'User retrieved successfully.',
            'data' => $user->toArray(),
        ], 200);
    }
}
