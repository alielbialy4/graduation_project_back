<?php

namespace App\Modules\AdminAuth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{

    public function rules()
    {
        return [
            'email'   => [
                'required',
                Rule::exists('users', 'email')->where(function ($query) {
                    $query->where('guard', 'admin');
                }),
            ],
            'password' => ['required', 'string', 'min:6'],
        ];
    }
}
