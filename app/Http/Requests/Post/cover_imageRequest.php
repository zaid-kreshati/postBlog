<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;


class cover_imageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
