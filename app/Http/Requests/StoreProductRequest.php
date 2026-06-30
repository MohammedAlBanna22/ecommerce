<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
         'category_id' => 'required|exists:categories,id',

        'name' => 'required|string|max:255',

        'description' => 'nullable|string',

        'price' => 'required|numeric|min:0',

        'discount_price' => 'nullable|numeric|min:0|lt:price',

        'quantity' => 'required|integer|min:0',

        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',


        'primary_image_index' => 'nullable|integer|min:0',
        ];
    }
}
