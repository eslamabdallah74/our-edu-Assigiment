<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserJsonData;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(UserJsonData $userJsonData)
    {
        $userJsonData->setJsonFilePath(config('paths.user_json_path'));
        $userJsonData->insertData();
        return response()->json(['message' => 'Users have been added to the system using Json file'], 201);
    }
}
