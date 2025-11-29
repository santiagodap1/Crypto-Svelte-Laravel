<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreWatchlistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coin_id' => ['required', 'string', 'max:255', 'regex:/^[0-9a-zA-Z-]+$/'],
        ];
    }
}
