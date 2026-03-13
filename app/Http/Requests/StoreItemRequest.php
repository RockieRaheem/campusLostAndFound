<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
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
            'item_name' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'location' => 'required|string|max:255',
            'status' => 'required|in:Lost,Found',
            'contact' => 'required|string|max:255',
            'photos' => 'nullable|array|max:3',
            'photos.*' => 'image|mimes:jpeg,jpg,png,webp|max:10240',
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'description.min' => 'Description should be at least 10 characters.',
            'status.in' => 'Status must be Lost or Found when creating a report.',
            'photos.max' => 'You can upload up to 3 photos per item report.',
            'photos.*.image' => 'Each uploaded file must be an image.',
            'photos.*.mimes' => 'Photos must be JPEG, JPG, PNG, or WEBP.',
            'photos.*.max' => 'Each photo must be smaller than 10MB.',
        ];
    }
}
