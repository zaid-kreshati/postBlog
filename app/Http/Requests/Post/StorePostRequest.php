<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\ValidationFailedException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\MaxTotalMedia;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\PostBlogException;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::id();
        return [
            'description' => 'required|string|max:255',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'nullable|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'photos' => [
                'nullable',
                'array',
                'max:3', // Ensure the total number of photos is less than 4
            ],
            'photos.*' => [
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048',
            ],
            'videos' => [
                'nullable',
                'array',
                'max:3', // Ensure the total number of videos is less than 4
            ],
            'videos.*' => [
                'mimes:mp4',
                'max:200000',
            ],
        ];
    }

    public function messages()
    {
        return [
            'photos.max' => 'You cannot upload more than 3 photos.',
            'videos.max' => 'You cannot upload more than 3 videos.',
            'photos.*.max' => 'Each photo must not exceed 2MB.',
            'videos.*.max' => 'Each video must not exceed 200MB.',
            'photos.*.mimes' => 'Photos must be in jpeg, png, jpg, or gif format.',
            'videos.*.mimes' => 'Videos must be in mp4 format.',
            'user_ids.*.exists' => 'The selected user_ids.* is invalid.',
            'category_id.exists' => 'The selected category_id is invalid.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errorMessage = $validator->errors()->first();
        throw new PostBlogException($validator, $errorMessage);
    }
}
