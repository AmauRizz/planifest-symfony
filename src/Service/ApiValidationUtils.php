<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class ApiValidationUtils
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function isValidId($id): bool
    {
        return is_numeric($id) && (int)$id > 0;
    }

    public function isRequestValid($data, $requiredFields): bool
    {
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }

        return true;
    }
}