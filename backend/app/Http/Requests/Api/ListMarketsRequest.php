<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ListMarketsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vs_currency' => ['sometimes', 'string', 'in:usd,eur,btc,eth,ars'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:250'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'ids' => ['sometimes', 'string', 'regex:/^[0-9a-zA-Z,-]+$/'],
        ];
    }
}
