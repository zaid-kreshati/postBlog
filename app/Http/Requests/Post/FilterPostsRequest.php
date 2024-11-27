<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\ValidationFailedException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\MaxTotalMedia;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\PostBlogException;

class FilterPostsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::id();
        return [
            'status' => 'required|string|in:published,archived,draft',
        ];
    }

    public function messages()
    {
        return [
            'status.in' => 'The status must be either published, archived, or draft.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errorMessage = $validator->errors()->first();
        throw new PostBlogException($validator, $errorMessage);
    }
}
