<?php

namespace App\Services;

use App\Interfaces\JsonDataInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserJsonData implements JsonDataInterface
{
    protected $jsonFilePath;

    public function setJsonFilePath(string $path): bool
    {
        $this->jsonFilePath = $path;
        return true;
    }

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

            $userData = json_decode(file_get_contents($this->jsonFilePath), true);

            if (isset($userData['users'])) {
                foreach ($userData['users'] as $user) {
                    $data[] = $this->prepareUserInsertData($user);

                    if (count($data) >= $batchSize) {
                        $this->insertUserBatch($data);
                        $data = [];
                    }
                }
            }

            $this->insertRemainingUserData($data);

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

    private function prepareUserInsertData(array $userData)
    {
        return [
            'balance' => $userData['balance'],
            'currency' => $userData['currency'],
            'email' => $userData['email'],
            'created_at' => $userData['created_at'],
            'id' => $userData['id'],
        ];
    }

    private function insertUserBatch(array $data)
    {
        $test = User::insert($data);
    }

    private function insertRemainingUserData(array $data)
    {
        if (!empty($data)) {
            $this->insertUserBatch($data);
        }
    }

    private function closeJsonFile($file)
    {
        fclose($file);
    }

    private function handleDataInsertError(\Exception $e, $file)
    {
        Log::channel('insert')->error("Error during data insertion: {$e->getMessage()}");
        DB::rollBack();
        $this->closeJsonFile($file);
    }
}
