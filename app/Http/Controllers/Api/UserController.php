<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetUserDataRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserJsonData;
use App\Traits\apiTrait;

class UserController extends Controller
{
    use apiTrait;

    public function __construct(protected  UserJsonData $userJson)
    {
        //Automatic Injection
    }

    public function index(GetUserDataRequest $request)
    {
        $filters = $request->only(['status_code', 'currency', 'min_amount', 'max_amount', 'start_date', 'end_date']);

        $users = User::with('transactions')
            ->whereHas('transactions', function ($query) use ($filters) {
                $query->applyFilters($filters);
            })
            ->get();

        return  UserResource::collection($users);
    }



    public function store()
    {
        $this->userJson->setJsonFilePath(config('paths.user_json_path'));
        $success = $this->userJson->insertData();
        if ($success) {
            return $this->successInsert('Users');
        } else {
            return $this->failedInsert('Users');
        }
    }
}
