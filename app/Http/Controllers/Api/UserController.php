<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserJsonData;
use App\Traits\apiTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use apiTrait;

    public function __construct(protected  UserJsonData $userJson)
    {
        //Automatic Injection
    }

    public function index(Request $request)
    {
        $users = User::with('transactions')->get();
        return response()->json($users);
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
