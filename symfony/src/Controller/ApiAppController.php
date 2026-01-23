<?php

namespace App\Controller;

use App\Service\ApiErrorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiAppController extends AbstractController
{
    #[Route('/api/tea', name: 'api_tea', methods: ['GET'])]
    public function easterEgg(ApiErrorHandler $errorHandler): JsonResponse
    {
        return $errorHandler->handleImATeapot();
    }
}
