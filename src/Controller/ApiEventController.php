<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiEventController extends AbstractController
{
    #[Route('/api/events', name: 'api_events', methods: ['GET'])]
    public function getEvents(EventRepository $eventRepository): JsonResponse
    {
        try {
            $events = $eventRepository->findAll();
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

        $data = array_map(fn($event) => $event->toArray(), $events);

        return $this->json([
            'success' => true,
            'message' => 'Events retrieved successfully',
            'data' => $data,
        ], 200);
    }

    #[Route('/api/events/{id}', name: 'api_event_by_id', methods: ['GET'])]
    public function getEventById($id, EventRepository $eventRepository): JsonResponse
    {
        if (!ctype_digit((string)$id) || (int)$id <= 0) {
            return $this->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_ID',
                    'message' => 'The event ID must be a positive integer.',
                    'hint' => 'Make sure the ID is a positive whole number (e.g., 1, 2, 3...).',
                ]
            ], 400);
        }

        try {
            $event = $eventRepository->find($id);
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

        if (!$event) {
            return $this->json([
                'success' => false,
                'error' => [
                    'code' => 'RESOURCE_NOT_FOUND',
                    'message' => 'Event not found.',
                    'hint' => 'Check if the event ID is correct.',
                ]
            ], 404);
        }

        return $this->json([
            'success' => true,
            'message' => 'Event retrieved successfully',
            'data' => $event->toArray(),
        ], 200);
    }
}