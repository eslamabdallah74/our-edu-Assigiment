<?php

namespace App\Services;

use App\Interfaces\JsonDataInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class JsonData implements JsonDataInterface
{
    protected $jsonFilePath;

    public function setJsonFilePath(string $path): bool
    {
        $this->jsonFilePath = $path;
        return true;
    }

    abstract protected function getDataKey(): string;
    abstract protected function prepareDataForInsert(array $item): array;
    abstract protected function insertBatch(array $data);

    public function insertData(): bool
    {
        $file = $this->openJsonFile();
        if ($file === false) {
            return false;
        }

        DB::beginTransaction();

        try {
            $batchSize = 100;
            $data = [];

            $jsonData = json_decode(file_get_contents($this->jsonFilePath), true);

            if (isset($jsonData[$this->getDataKey()])) {
                foreach ($jsonData[$this->getDataKey()] as $item) {
                    $data[] = $this->prepareDataForInsert($item);

                    if (count($data) >= $batchSize) {
                        $this->insertBatch($data);
                        $data = [];
                    }
                }
            }

            $this->insertRemainingData($data);

            DB::commit();
            $this->closeJsonFile($file);

            return true;
        } catch (\Exception $e) {
            $this->handleDataInsertError($e, $file);
            return false;
        }
    }

    private function openJsonFile()
    {
        $file = fopen($this->jsonFilePath, 'r');

        if ($file === false) {
            Log::channel('insert')->error("Failed to open JSON file for insertion: {$this->jsonFilePath}");
        }

        return $file;
    }

    protected function insertRemainingData(array $data)
    {
        if (!empty($data)) {
            $this->insertBatch($data);
        }
    }

    protected function closeJsonFile($file)
    {
        fclose($file);
    }

    protected function handleDataInsertError(\Exception $e, $file)
    {
        Log::channel('insert')->error("Error during data insertion: {$e->getMessage()}");
        DB::rollBack();
        $this->closeJsonFile($file);
    }
}
