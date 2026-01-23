<?php

namespace App\Controller;

use App\Service\ApiErrorHandler;
use App\Service\ApiSuccessHandler;
use App\Service\ApiValidationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Image;

final class ApiImageController extends AbstractController
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

    #[Route('/api/images', name: 'api_create_image', methods: ['POST'])]
    public function upload(Request $request, Security $security, EntityManagerInterface $em): JsonResponse
    {
        $image = $request->files->get('image');
        $type = $request->request->get('type');

        $user = $security->getUser();
        if (!$user) {
            return $this->errorHandler->handleUnauthorized();
        }

        if (!$image) {
            return $this->errorHandler->handleBadRequest('caca');
        }

        if (!in_array($type, ['user', 'event'])) {
            return $this->errorHandler->handleBadRequest('pipi');
        }

        if ($type === 'event') {
            $eventId = $request->request->get('eventId');
            if (!$eventId || !is_numeric($eventId)) {
                return $this->errorHandler->handleBadRequest('prout');
            }

            $event = $em->getRepository(\App\Entity\Event::class)->find($eventId);
            if (!$event) {
                return $this->errorHandler->handleNotFound('event');
            }
        }

        $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/uploads/images';
        $imageName = $image->getClientOriginalName();
        $imageSize = $image->getSize();
        $imageSlug = uniqid() . '.' . $image->guessExtension();
        $imageURL = "http://127.0.0.1:8000/uploads/images/" . $imageSlug;

        try {
            $image->move($uploadsDir, $imageSlug);

            $image = new Image();
            $image->setName($imageName);
            $image->setSlug($imageURL);
            $image->setSize($imageSize);
            $image->setAuthor($user);
            if ($type === 'user') {
                $image->setUserEntity($user);
            } else if ($type === 'event') {
                $image->setEventEntity($event);
            }

            $em->persist($image);
            $em->flush();
        } catch (FileException $e) {
            return $this->errorHandler->handleInternalServerError('');
        } catch (\Exception $e) {
            return $this->errorHandler->handleInternalServerError('');
        }

        return $this->successHandler->handleCreated("image",$image->toArray());
    }
}