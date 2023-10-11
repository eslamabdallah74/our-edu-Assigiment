<?php

namespace App\Services;

use App\Interfaces\JsonDataInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserJsonData extends JsonData
{
    protected function getDataKey(): string
    {
        return 'users';
    }

    protected function prepareDataForInsert(array $item): array
    {
        return [
            'balance' => $item['balance'],
            'currency' => $item['currency'],
            'email' => $item['email'],
            'created_at' => date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $item['created_at']))),
            'id' => $item['id'],
        ];
    }

    protected function insertBatch(array $data)
    {
        User::insert($data);
    }


}
