<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'parentIdentification' => $this->parentIdentification,
            'paidAmount' => $this->paidAmount,
            'currency' => $this->currency,
            'parentEmail' => $this->parentEmail,
            'statusCode' => $this->statusCode,
            'paymentDate' => $this->paymentDate,
        ];
    }
}
