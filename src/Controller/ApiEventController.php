<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Service\ApiErrorHandler;
use App\Service\ApiSuccessHandler;
use App\Service\ApiValidationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiEventController extends AbstractController
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

    #[Route('/api/events', name: 'api_get_events', methods: ['GET'])]
    public function getEvents(EventRepository $eventRepository): JsonResponse
    {
        try {
            $events = $eventRepository->findAll();
        } catch (\Exception $e) {
            return $this->errorHandler->handleInternalServerError($e);
        }

        $data = array_map(fn($event) => $event->toArray(), $events);

        return $this->successHandler->handleSuccess('events', $data);
    }

    #[Route('/api/events/{id}', name: 'api_get_event_by_id', methods: ['GET'])]
    public function getEventById($id, EventRepository $eventRepository): JsonResponse
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

        return $this->successHandler->handleSuccess('event', $event->toArray());
    }

    #[Route('/api/events', name: 'api_create_event', methods: ['POST'])]
    public function createEvent(Request $request, Security $security, CategoryRepository $categorieRepository, UserRepository $userRepository, EntityManagerInterface $em): JsonResponse
    {
        try {
            $user = $security->getUser();
            if (!$user) {
                return $this->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {
            return $this->errorHandler->handleInternalServerError($e);
        }

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->errorHandler->handleBadRequest('Invalid JSON');
        }

        if (!$this->validationUtils->isRequestValid($data, ['name', 'description', 'startingDate', 'endingDate', 'categoryId'])) {
            return $this->errorHandler->handleBadRequest('Missing required fields');
        }

        try {
            $category = $categorieRepository->find($data['categoryId']);
        } catch (\Exception $e) {
            return $this->errorHandler->handleInternalServerError($e);
        }

        if (!$category) {
            return $this->errorHandler->handleBadRequest($data['categoryId']);
        }

        if (!$user) {
            return $this->errorHandler->handleBadRequest($data['author']);
        }

        try {
            $event = new Event();
            $event->setName($data['name']);
            $event->setDescription($data['description']);
            $event->setStartingDate(new \DateTime($data['startingDate']));
            $event->setEndingDate(new \DateTime($data['endingDate']));
            $event->setCategory($category);
            $event->setAuthor($user);

            $em->persist($event);
            $em->flush();
        } catch (\Exception $e) {
            return $this->errorHandler->handleInternalServerError($e);
        }

        return $this->successHandler->handleCreated('createEvent', $event->toArray());
    }

    #[Route('/api/events/{id}', name: 'api_update_event', methods: ['PATCH'])]
    public function updateEvent(Request $request, $id, EventRepository $eventRepository, EntityManagerInterface $em): JsonResponse
    {
        return $this->errorHandler->handleNotImplemented('createUser');
    }

    #[Route('/api/events/{id}', name: 'api_delete_event', methods: ['DELETE'])]
    public function deleteEvent($id, Security $security, EventRepository $eventRepository, EntityManagerInterface $em): JsonResponse
    {
        if (!$this->validationUtils->isValidId($id)) {
            return $this->errorHandler->handleBadRequest($id);
        }

        try {
            $user = $security->getUser();
            if (!$user) {
                return $this->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {
            return $this->errorHandler->handleInternalServerError($e);
        }

        try {
            $event = $eventRepository->find($id);
        } catch (\Exception $e) {
            return $this->errorHandler->handleInternalServerError($e);
        }

        if (!$event) {
            return $this->errorHandler->handleBadRequest($id);
        }

        if ($event->getAuthor()->getId() !== $user->getId()) {
            return $this->json(['message' => 'Unauthorized' . $currentUser?->getId() . " " . $user->getId()], 401);
        }

        try {
            foreach ($event->getImages() as $image) {
                $em->remove($image);
            }

            $em->remove($event);
            $em->flush();
        } catch (\Exception $e) {
            return $this->errorHandler->handleInternalServerError($e);
        }

        return $this->successHandler->handleNoContent('event_deleted');
    }
}