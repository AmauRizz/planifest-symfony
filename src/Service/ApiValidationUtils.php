<?php

namespace App\Service;

class ApiValidationUtils
{
    public function isValidId($id): bool
    {
        return is_numeric($id) && (int)$id > 0;
    }
}