<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class ProductRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:4|max:255',

            'price' => 'required|numeric|min:0',

            'categories' => 'nullable|array',

            'categories.*' => [
                'integer',
                Rule::exists('categories', 'id'),
            ],
        ];
    }
}
