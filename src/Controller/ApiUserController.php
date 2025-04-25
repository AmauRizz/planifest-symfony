<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\ApiErrorHandler;
use App\Service\ApiSuccessHandler;
use App\Service\ApiValidationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiUserController extends AbstractController
{
    #[Route('/api/users/{id}', name: 'api_get_user_by_id', methods: ['GET'])]
    public function getUserById($id, UserRepository $userRepository, ApiErrorHandler $errorHandler, ApiSuccessHandler $successHandler, ApiValidationUtils $validationUtils): JsonResponse
    {
        if (!$validationUtils->isValidId($id)) {
            return $errorHandler->handleBadRequest($id);
        }

        try {
            $user = $userRepository->find($id);
        } catch (\Exception $e) {
            return $errorHandler->handleInternalServerError($e);
        }

        if (!$user) {
            return $errorHandler->handleNotFound('user');
        }

        $data = $user->toArray();

        return $successHandler->handleSuccess('user', $data);
    }

    #[Route('/api/users', name: 'api_create_user', methods: ['POST'])]
    public function createUser(ApiErrorHandler $errorHandler): JsonResponse
    {
        return $errorHandler->handleNotImplemented('createUser');
    }

    #[Route('/api/users/{id}', name: 'api_update_user', methods: ['PUT'])]
    public function updateUser($id ,ApiErrorHandler $errorHandler): JsonResponse
    {
        return $errorHandler->handleNotImplemented('updateUser');
    }

    #[Route('/api/users/{id}', name: 'api_delete_user', methods: ['DELETE'])]
    public function deleteUser($id ,ApiErrorHandler $errorHandler): JsonResponse
    {
        return $errorHandler->handleNotImplemented('deleteUser');
    }

    #[Route('/api/users/{id}', name: 'api_partial_update_user', methods: ['PATCH'])]
    public function partialUpdateUser($id, ApiErrorHandler $errorHandler): JsonResponse
    {
        return $errorHandler->handleNotImplemented('partialUpdateUser');
    }
}
