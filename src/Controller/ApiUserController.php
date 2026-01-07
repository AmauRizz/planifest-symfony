<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ApiErrorHandler;
use App\Service\ApiSuccessHandler;
use App\Service\ApiValidationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ApiUserController extends AbstractController
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

    #[Route('/api/users/me', name: 'api_get_current_user', methods: ['GET'])]
    public function getMe(Security $security): JsonResponse
    {
        $user = $security->getUser();

        if (!$user) {
            return $this->json(['message' => 'Unauthorized'], 401);
        }

        return $this->successHandler->handleSuccess('getMe', $user->toArray());
    }

    #[Route('/api/auth/token', name: 'api_login', methods: ['POST'])]

    #[Route('/api/users', name: 'api_create_user', methods: ['POST'])]
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'] ?? null;
        $email = $data['email'] ?? null;
        $plainPassword = $data['password'] ?? null;

        if (!$email || !$plainPassword || !$name) {
            return $this->errorHandler->handleBadRequest("vrai bad request");
        }

        $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            return $this->errorHandler->handleBadRequest("user existe déjà");
        }

        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword(
            $passwordHasher->hashPassword($user, $plainPassword)
        );

        $defaultRole = $em->getRepository(Role::class)->findOneBy(['name' => 'ROLE_0']);
        if (!$defaultRole) {
            return $this->errorHandler->handleBadRequest("pas de role");
        }
        $user->setRoleEntity($defaultRole);

        $em->persist($user);
        $em->flush();

        return $this->successHandler->handleCreated("test", $user->toArray());
    }

    #[Route('/api/users/{id}', name: 'api_get_user_by_id', methods: ['GET'])]
    public function getUserById($id, UserRepository $userRepository): JsonResponse
    {
        if (!$this->validationUtils->isValidId($id)) {
            return $this->errorHandler->handleBadRequest($id);
        }

        try {
            $user = $userRepository->find($id);
        } catch (\Exception $e) {
            return $this->errorHandler->handleInternalServerError($e);
        }

        if (!$user) {
            return $this->errorHandler->handleNotFound('user');
        }

        $data = $user->toArray();

        return $this->successHandler->handleSuccess('user', $data);
    }

    #[Route('/api/users/{id}', name: 'api_update_user', methods: ['PATCH'])]
    public function updateUser($id): JsonResponse
    {
        return $this->errorHandler->handleNotImplemented('createUser');
    }

    #[Route('/api/users/{id}', name: 'api_delete_user', methods: ['DELETE'])]
    public function deleteUser($id, Security $security,EntityManagerInterface $em): JsonResponse
    {
        if (!$this->validationUtils->isValidId($id)) {
            return $this->errorHandler->handleBadRequest($id);
        }

        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['message' => 'Not found'], 404);
        }

        $currentUser = $security->getUser();
        if (!$currentUser || $currentUser->getId() !== $user->getId()) {
            return $this->json(['message' => 'Unauthorized' . $currentUser?->getId() . " " . $user->getId()], 401);
        }

        try {
            foreach ($user->getImagesOwned() as $image) {
                $em->remove($image);
            }

            foreach ($user->getEventsOwned() as $event) {
                $em->remove($event);
            }

            $em->remove($user);
            $em->flush();
        } catch (\Exception $e) {
            return $this->errorHandler->handleInternalServerError($e);
        }

        return $this->successHandler->handleNoContent('deleteUser');
    }
}
