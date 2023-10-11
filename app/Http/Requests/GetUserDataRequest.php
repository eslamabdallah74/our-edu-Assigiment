<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetUserDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status_code' => 'in:authorized,decline,refunded',
            'currency' => 'in:SAR,USD,EGP,AED,EUR',
            'min_amount' => 'numeric|min:0',
            'max_amount' => 'numeric|min:0|required_if:min_amount,!=,null|gte:min_amount',
            'start_date' => 'date|date_format:Y-m-d',
            'end_date' => 'date|date_format:Y-m-d|after_or_equal:start_date',
        ];
    }
}
