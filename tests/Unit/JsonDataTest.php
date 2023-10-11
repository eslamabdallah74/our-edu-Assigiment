<?php

namespace Tests\Unit;

use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionJsonData;
use App\Services\UserJsonData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JsonDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_insert_users_json_data_into_database()
    {
        $jsonData = new UserJsonData();
        $jsonData->setJsonFilePath(config('paths.user_json_path'));

        $result = $jsonData->insertData();

        $this->assertTrue($result);
    }

    public function test_users_count_after_insert()
    {
        $usersCountBefore = User::count();

        $jsonData = new UserJsonData();
        $jsonData->setJsonFilePath(config('paths.user_json_path'));
        $jsonData->insertData();

        $usersCountAfter = User::count();

        $this->assertTrue($usersCountAfter > $usersCountBefore);
    }


    public function test_it_can_insert_transactions_json_data_into_database()
    {
        $jsonData = new TransactionJsonData();
        $jsonData->setJsonFilePath(config('paths.transaction_json_path'));

        $result = $jsonData->insertData();

        $this->assertTrue($result);
    }

    public function test_transactions_count_after_insert()
    {
        $transactionCountBefore = Transaction::count();

        $jsonData = new TransactionJsonData();
        $jsonData->setJsonFilePath(config('paths.transaction_json_path'));
        $jsonData->insertData();

        $transactionCountAfter = Transaction::count();

        $this->assertTrue($transactionCountAfter > $transactionCountBefore);
    }
}
