<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\ValidationFailedException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\MaxTotalMedia;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\PostBlogException;


class profile_imageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'profile_image.max' => 'The profile image must not exceed 2MB.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $errorMessage = $validator->errors()->first();
        throw new PostBlogException($validator, $errorMessage);
    }
}
