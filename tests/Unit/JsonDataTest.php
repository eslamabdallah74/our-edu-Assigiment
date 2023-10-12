<?php

namespace Tests\Unit;

use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionJsonData;
use App\Services\UserJsonData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Testing\Fluent\AssertableJson;
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

        $this->assertGreaterThanOrEqual($usersCountBefore, $usersCountAfter);
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

    public function test_if_there_is_no_transactions_return_empty_data()
    {
        $userData = new UserJsonData();
        $userData->setJsonFilePath(config('paths.user_json_path'));
        $userData->insertData();

        $response = $this->get(route('users-data'));
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [],
        ]);
    }

    public function test_can_not_add_same_users_files_twice()
    {
        $userData = new UserJsonData();
        $userData->setJsonFilePath(config('paths.user_json_path'));
        $userData->insertData();

        $userData = new UserJsonData();
        $userData->setJsonFilePath(config('paths.user_json_path'));
        $status = $userData->insertData();

        $this->assertFalse($status);
    }

    public function test_return_users_data()
    {
        $userData = new UserJsonData();
        $userData->setJsonFilePath(config('paths.user_json_path'));
        $userData->insertData();

        $transactionData = new TransactionJsonData();
        $transactionData->setJsonFilePath(config('paths.transaction_json_path'));
        $transactionData->insertData();

        $response = $this->get(route('users-data'));
        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    public function test_filter_users_by_random_status_code()
    {
        $userData = new UserJsonData();
        $userData->setJsonFilePath(config('paths.user_json_path'));
        $userData->insertData();

        $transactionData = new TransactionJsonData();
        $transactionData->setJsonFilePath(config('paths.transaction_json_path'));
        $transactionData->insertData();

        $availableStatusCodes = ['authorized', 'decline', 'refunded'];

        $statusCodeToFilter = Arr::random($availableStatusCodes);

        $response = $this->get(route('users-data', ['status_code' => $statusCodeToFilter]));
        $response->assertStatus(200);

        $responseData = $response->json('data');

        $this->assertNotEmpty(array_filter($responseData, function ($user) use ($statusCodeToFilter) {
            return collect($user['transactions'])->contains('statusCode', $statusCodeToFilter);
        }));
    }

    public function test_filter_users_by_random_currency()
    {
        $userData = new UserJsonData();
        $userData->setJsonFilePath(config('paths.user_json_path'));
        $userData->insertData();

        $transactionData = new TransactionJsonData();
        $transactionData->setJsonFilePath(config('paths.transaction_json_path'));
        $transactionData->insertData();
        $availableCurrencies = ['SAR', 'USD', 'EGP', 'AED', 'EUR'];

        $currencyToFilter = Arr::random($availableCurrencies);

        $response = $this->get(route('users-data', ['currency' => $currencyToFilter]));
        $response->assertStatus(200);

        $responseData = $response->json('data');

        $this->assertNotEmpty(array_filter($responseData, function ($user) use ($currencyToFilter) {
            return collect($user['transactions'])->contains('currency', $currencyToFilter);
        }));
    }

    public function test_filter_users_by_min_and_max_amount()
    {
        $userData = new UserJsonData();
        $userData->setJsonFilePath(config('paths.user_json_path'));
        $userData->insertData();

        $transactionData = new TransactionJsonData();
        $transactionData->setJsonFilePath(config('paths.transaction_json_path'));
        $transactionData->insertData();

        $minAmount = 400;
        $maxAmount = 600;

        $response = $this->get(route('users-data', ['min_amount' => $minAmount, 'max_amount' => $maxAmount]));
        $response->assertStatus(200);

        $responseData = $response->json('data');

        // Filter and keep users where the transactions array is not empty
        $filteredData = array_filter($responseData, function ($user) use ($minAmount, $maxAmount) {
            if (empty($user['transactions'])) {
                return false;
            }
            $transactions = collect($user['transactions']);
            return $transactions->min('paidAmount') >= $minAmount && $transactions->max('paidAmount') <= $maxAmount;
        });

        $this->assertNotEmpty($filteredData);
    }


    public function test_filter_users_by_date_range()
    {
        $userData = new UserJsonData();
        $userData->setJsonFilePath(config('paths.user_json_path'));
        $userData->insertData();

        $transactionData = new TransactionJsonData();
        $transactionData->setJsonFilePath(config('paths.transaction_json_path'));
        $transactionData->insertData();

        $startDate = '2022-01-01';
        $endDate = '2022-12-31';

        $response = $this->get(route('users-data', [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]));

        $responseData = $response->json('data');

        $this->assertNotEmpty($responseData);

        foreach ($responseData as $user) {
            if (!empty($user['transactions'])) {
                foreach ($user['transactions'] as $transaction) {
                    $transactionDate = $transaction['paymentDate'];
                    $this->assertTrue($transactionDate >= $startDate && $transactionDate <= $endDate);
                }
            }
        }
    }
}
