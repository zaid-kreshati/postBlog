<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\ValidationFailedException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\MaxTotalMedia;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\PostBlogException;


class StoreCommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'text' => 'required|string|max:255',
            'post_id' => 'required|exists:posts,id',
            'parent_id' => 'nullable|exists:comments,id',
        ];
    }

    public function messages()
    {
        return [
            'text.required' => 'The comment text is required.',
            'post_id.required' => 'The post id is required.',
            'post_id.exists' => 'The post id is not exists.',
            'parent_id.exists' => 'The parent id is not exists.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errorMessage = $validator->errors()->first();
        throw new PostBlogException($validator, $errorMessage);
    }

}
