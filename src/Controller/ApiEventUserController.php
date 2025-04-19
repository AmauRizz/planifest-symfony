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
    public function getUsersByEventId(int $id, EventRepository $eventRepository, ImageRepository $imageRepository): JsonResponse
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            return $this->json(['message' => 'No event found'], 404);
        }

        $users = $event->getUsers()->toArray();

        // Filter out false values (if any)
        $users = array_filter($users, fn($user) => $user !== false && method_exists($user, 'toArray'));

        return $this->json(array_map(fn($user) => $user->toArray(), $users));
    }
}
