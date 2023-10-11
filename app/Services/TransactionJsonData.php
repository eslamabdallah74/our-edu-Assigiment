<?php

namespace App\Services;

use App\Interfaces\JsonDataInterface;
use App\Models\Transaction;

class TransactionJsonData extends JsonData
{
    protected function getDataKey(): string
    {
        return 'transactions';
    }

    protected function prepareDataForInsert(array $item): array
    {
        return [
            'paidAmount' => $item['paidAmount'],
            'Currency' => $item['Currency'],
            'parentEmail' => $item['parentEmail'],
            'statusCode' => $item['statusCode'],
            'paymentDate' => $item['paymentDate'],
            'parentIdentification' => $item['parentIdentification'],
        ];
    }

    protected function insertBatch(array $data)
    {
        Transaction::insert($data);
    }


}
