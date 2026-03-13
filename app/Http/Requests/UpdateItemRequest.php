<?php

namespace App\Http\Requests;

use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateItemRequest extends FormRequest
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
            'status' => 'required|in:Lost,Found,Claimed',
            'contact' => 'required|string|max:255',
            'photos' => 'nullable|array|max:3',
            'photos.*' => 'image|mimes:jpeg,jpg,png,webp|max:10240',
            'remove_photo_ids' => 'nullable|array',
            'remove_photo_ids.*' => 'integer|exists:item_photos,id',
        ];
    }

    /**
     * Configure post-validation checks.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var Item|null $item */
            $item = $this->route('item');

            if (!$item) {
                return;
            }

            $item->loadMissing('photos');

            $removeIds = collect($this->input('remove_photo_ids', []))
                ->map(fn ($id) => (int) $id)
                ->filter();

            if ($removeIds->isNotEmpty()) {
                $validIds = $item->photos->pluck('id')->all();
                $hasInvalidId = $removeIds->contains(fn ($id) => !in_array($id, $validIds, true));

                if ($hasInvalidId) {
                    $validator->errors()->add('remove_photo_ids', 'One or more selected photos do not belong to this item.');
                }
            }

            $incomingPhotosCount = count($this->file('photos', []));
            $remainingPhotosCount = $item->photos->count() - $removeIds->count();

            if (($remainingPhotosCount + $incomingPhotosCount) > 3) {
                $validator->errors()->add('photos', 'Each item can have a maximum of 3 photos.');
            }
        });
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
            'status.in' => 'Status must be Lost, Found, or Claimed.',
            'photos.max' => 'You can upload up to 3 photos at a time.',
            'photos.*.image' => 'Each uploaded file must be an image.',
            'photos.*.mimes' => 'Photos must be JPEG, JPG, PNG, or WEBP.',
            'photos.*.max' => 'Each photo must be smaller than 10MB.',
        ];
    }
}
