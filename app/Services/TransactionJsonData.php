<?php

namespace App\Services;

use App\Interfaces\JsonDataInterface;

class TransactionJsonData implements JsonDataInterface
{
    protected $jsonFilePath;

    public function setJsonFilePath(string $jsonFilePath): bool
    {
        $this->jsonFilePath = $jsonFilePath;
        return true;
    }

    public function insertData(): bool
    {
        return true;
    }

}
