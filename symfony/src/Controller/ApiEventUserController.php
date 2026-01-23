<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Service\ApiErrorHandler;
use App\Service\ApiSuccessHandler;
use App\Service\ApiValidationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiEventUserController extends AbstractController
{
    private ApiErrorHandler $errorHandler;
    private ApiSuccessHandler $successHandler;
    private ApiValidationUtils $validationUtils;

    public function __construct(ApiErrorHandler $errorHandler, ApiSuccessHandler $successHandler, ApiValidationUtils $validationUtils)
    {
        $this->errorHandler = $errorHandler;
        $this->successHandler = $successHandler;
        $this->validationUtils = $validationUtils;
    }

    #[Route('/api/events/{id}/users', name: 'api_get_users_by_event_id', methods: ['GET'])]
    public function getUsersByEventId($id, EventRepository $eventRepository): JsonResponse
    {
        if (!$this->validationUtils->isValidId($id)) {
            return $this->errorHandler->handleBadRequest($id);
        }

        try {
            $event = $eventRepository->find($id);
        } catch (\Exception $e) {
            return $this->errorHandler->handleInternalServerError($e);
        }

        if (!$event) {
            return $this->errorHandler->handleNotFound('event');
        }

        try {
            $users = $event->getUsers()->toArray();
        } catch (\Exception $e) {
            return $this->errorHandler->handleInternalServerError($e);
        }

        $data = array_map(fn($user) => $user->toArray(), $users);

        return $this->successHandler->handleSuccess('users', $data);
    }

    #[Route('/api/events/{eventId}/users', name: 'api_add_user_in_event', methods: ['POST'])]
    public function addUserInEvent($eventId): JsonResponse
    {
        return $this->errorHandler->handleNotImplemented('addUser');
    }

    #[Route('/api/events/{eventId}/users/{userId}', name: 'api_remove_user_in_event', methods: ['DELETE'])]
    public function removeUserInEvent($eventId, $userId): JsonResponse
    {
        return $this->errorHandler->handleNotImplemented('removeUser');
    }
}