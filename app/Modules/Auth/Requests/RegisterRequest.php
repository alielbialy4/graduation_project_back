<?php

namespace App\Modules\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    public function rules()
    {
        return [
            'first_name'       => 'nullable|string|max:255|min:3',
            'last_name'        => 'nullable|string|max:255|min:3',
            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],
            'password'   => ['required', 'string', 'min:6', 'max:255',  'confirmed'],
        ];
    }
}
