<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($user) {
                return [
                    'id' => $user->id,
                    'balance' => $user->balance,
                    'currency' => $user->currency,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'transactions' =>  TransactionResource::collection($user->transactions),
                ];
            }),
        ];
    }
}
