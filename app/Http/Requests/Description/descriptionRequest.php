<?php

namespace App\Http\Requests\Description;

use Illuminate\Foundation\Http\FormRequest;

class descriptionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'text' => 'required|string|max:255',
        ];
    }
}
