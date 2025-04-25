<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Service\ApiErrorHandler;
use App\Service\ApiSuccessHandler;
use App\Service\ApiValidationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiEventUserController extends AbstractController
{
    #[Route('/api/events/{id}/users', name: 'api_get_users_by_event_id', methods: ['GET'])]
    public function getUsersByEventId($id, EventRepository $eventRepository, ApiErrorHandler $errorHandler, ApiSuccessHandler $successHandler, ApiValidationUtils $validationUtils): JsonResponse
    {
        if (!$validationUtils->isValidId($id)) {
            return $errorHandler->handleBadRequest($id);
        }

        try {
            $event = $eventRepository->find($id);
        } catch (\Exception $e) {
            return $errorHandler->handleInternalServerError($e);
        }

        if (!$event) {
            return $errorHandler->handleNotFound('event');
        }

        try {
            $users = $event->getUsers()->toArray();
        } catch (\Exception $e) {
            return $errorHandler->handleInternalServerError($e);
        }

        $data = array_map(fn($user) => $user->toArray(), $users);

        return $successHandler->handleSuccess('users', $data);
    }

    #[Route('/api/events/{eventId}/users', name: 'api_add_user_in_event', methods: ['POST'])]
    public function addUser($eventId, ApiErrorHandler $errorHandler): JsonResponse
    {
        return $errorHandler->handleNotImplemented('addUser');
    }

    #[Route('/api/events/{eventId}/users/{userId}', name: 'api_remove_user_in_event', methods: ['DELETE'])]
    public function removeUser($eventId, $userId ,ApiErrorHandler $errorHandler): JsonResponse
    {
        return $errorHandler->handleNotImplemented('removeUser');
    }
}