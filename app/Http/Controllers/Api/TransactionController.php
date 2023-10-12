<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TransactionJsonData;
use App\Traits\apiTrait;

class TransactionController extends Controller
{
    use apiTrait;

    public function __construct(protected  TransactionJsonData $transactionJson)
    {
        //Automatic Injection
    }

    public function store()
    {
        $this->transactionJson->setJsonFilePath(config('paths.transaction_json_path'));
        $this->transactionJson->insertData();
        return $this->successInsert('Transaction');
    }
}
