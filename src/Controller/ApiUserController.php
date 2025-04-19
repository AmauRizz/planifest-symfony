<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiUserController extends AbstractController
{
    #[Route('/api/users/{id}', name: 'api_user_by_id', methods: ['GET'])]
    public function getUserById(int $id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json(['message' => 'No event found'], 404);
        }

        return $this->json($user->toArray());
    }
}
