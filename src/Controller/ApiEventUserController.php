<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiEventUserController extends AbstractController
{
    #[Route('/api/events/{id}/users', name: 'api_users_by_event_id', methods: ['GET'])]
    public function getUsersByEventId($id, EventRepository $eventRepository, ImageRepository $imageRepository): JsonResponse
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

        try {
            $users = $event->getUsers()->toArray();
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => [
                    'code' => 'INTERNAL_SERVER_ERROR',
                    'message' => 'An error occurred while retrieving users.',
                    'hint' => 'Try again later or contact support.',
                    'debug' => $e->getMessage(),
                ]
            ], 500);
        }

        $data = array_map(fn($user) => $user->toArray(), $users);

        return $this->json([
            'success' => true,
            'message' => 'Users from event retrieved successfully',
            'data' => $data,
        ], 200);
    }
}