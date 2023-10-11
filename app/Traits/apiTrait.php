<?php

namespace App\Traits;

trait apiTrait
{
    public function successInsert(string $modelName)
    {
        return response()->json(
            [
                'message' => $modelName . ' have been added to the system using Json file.',
                'status' => true
            ],
            201
        );
    }

    public function failedInsert(string $modelName)
    {
        return response()->json(
            [
                'message' => $modelName . ' or user already exist.',
                'status' => false
            ],
            201
        );
    }
}
