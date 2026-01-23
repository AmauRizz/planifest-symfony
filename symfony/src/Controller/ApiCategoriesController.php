<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Service\ApiErrorHandler;
use App\Service\ApiSuccessHandler;
use App\Service\ApiValidationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiCategoriesController extends AbstractController
{
    private ApiErrorHandler $errorHandler;
    private ApiSuccessHandler $successHandler;

    public function __construct(ApiErrorHandler $errorHandler, ApiSuccessHandler $successHandler, ApiValidationUtils $validationUtils)
    {
        $this->errorHandler = $errorHandler;
        $this->successHandler = $successHandler;
    }

    #[Route('/api/categories', name: 'api_get_categories', methods: ['GET'])]
    public function getCategories(CategoryRepository $categoriesRepository): JsonResponse
    {
        try {
            $categories = $categoriesRepository->findAll();
        } catch (\Exception $e) {
            return $this->errorHandler->handleInternalServerError($e);
        }

        $data = array_map(fn($category) => $category->toArray(), $categories);

        return $this->successHandler->handleSuccess('categories', $data);
    }
}
