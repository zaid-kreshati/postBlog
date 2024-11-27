<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\ValidationFailedException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\MaxTotalMedia;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\PostBlogException;

class SearchFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::id();
        return [
            'query' => 'nullable|string|max:255',
            'page' => 'nullable|integer',
            'filter' => 'required|string|in:posts,users,all,posts_with_photo,posts_with_video',
        ];
    }

    public function messages()
    {
        return [
            'filter.in' => 'The filter must be one of the following: posts, users, all, posts_with_photo, posts_with_video.',

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errorMessage = $validator->errors()->first();
        throw new PostBlogException($validator, $errorMessage);
    }
}
