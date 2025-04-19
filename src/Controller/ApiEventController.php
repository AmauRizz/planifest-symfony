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
        $events = $eventRepository->findAll();

        if (!$events) {
            return $this->json(['message' => 'No events found'], 404);
        }

        return $this->json(array_map(fn($event) => $event->toArray(), $events));
    }

    #[Route('/api/events/{id}', name: 'api_event_by_id', methods: ['GET'])]
    public function getEventById(int $id, EventRepository $eventRepository): JsonResponse
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            return $this->json(['message' => 'No event found'], 404);
        }

        return $this->json($event->toArray());
    }
}