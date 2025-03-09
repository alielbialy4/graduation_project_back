<?php

namespace App\Modules\Auth\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{

    public function rules()
    {
        return [
            'old_password'     => ['required', 'string', 'min:6', 'max:255'],
            'new_password'     => ['required', 'string', 'min:6', 'max:255',  'confirmed'],
        ];
    }
}
