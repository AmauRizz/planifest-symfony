<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Service\ApiErrorHandler;
use App\Service\ApiSuccessHandler;
use App\Service\ApiValidationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiEventController extends AbstractController
{
    #[Route('/api/events', name: 'api_get_events', methods: ['GET'])]
    public function getEvents(EventRepository $eventRepository, ApiErrorHandler $errorHandler, ApiSuccessHandler $successHandler): JsonResponse
    {
        try {
            $events = $eventRepository->findAll();
        } catch (\Exception $e) {
            return $errorHandler->handleInternalServerError($e);
        }

        $data = array_map(fn($event) => $event->toArray(), $events);

        return $successHandler->handleSuccess('events', $data);
    }

    #[Route('/api/events/{id}', name: 'api_get_event_by_id', methods: ['GET'])]
    public function getEventById($id, EventRepository $eventRepository, ApiErrorHandler $errorHandler, ApiSuccessHandler $successHandler, ApiValidationUtils $validationUtils): JsonResponse
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

        $data = $event->toArray();

        return $successHandler->handleSuccess('event', $data);
    }

    #[Route('/api/events', name: 'api_create_event', methods: ['POST'])]
    public function createEvent(ApiErrorHandler $errorHandler): JsonResponse
    {
        return $errorHandler->handleNotImplemented('createEvent');
    }

    #[Route('/api/events/{id}', name: 'api_update_event', methods: ['PUT'])]
    public function updateEvent($id ,ApiErrorHandler $errorHandler): JsonResponse
    {
        return $errorHandler->handleNotImplemented('updateEvent');
    }

    #[Route('/api/events/{id}', name: 'api_delete_event', methods: ['DELETE'])]
    public function deleteEvent($id ,ApiErrorHandler $errorHandler): JsonResponse
    {
        return $errorHandler->handleNotImplemented('deleteEvent');
    }

    #[Route('/api/events/{id}', name: 'api_partial_update_event', methods: ['PATCH'])]
    public function partialUpdateEvent($id, ApiErrorHandler $errorHandler): JsonResponse
    {
        return $errorHandler->handleNotImplemented('partialUpdateEvent');
    }
}