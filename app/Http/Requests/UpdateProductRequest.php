<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
                'category_id' => 'sometimes|required|exists:categories,id',
                'name' => 'sometimes|required|string|max:255',
                'price' => 'sometimes|required|numeric|min:0',
                'quantity' => 'sometimes|required|integer|min:0',
                'status' => 'sometimes|required|in:available,unavailable',
                //'image' => 'sometimes|nullable|image',
                'images'        => 'nullable|array',
                'images.*'      => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            ];
    }
     public function messages(): array
    {
        return [
            'name.required' => 'Product name is required',
            'price.required' => 'Price is required',
            'price.min' => 'Price cannot be negative',
            'discount_price.lt' => 'Compare price must be less than regular price',
            'category_id.required' => 'Please select a category',
            'quantity.min' => 'Quantity cannot be negative',
            'images.*.image' => 'Each file must be an image',
            'images.*.mimes' => 'Only JPG, PNG, WEBP are allowed',
            'images.*.max' => 'Each image max size is 2MB',
        ];
    }
}
