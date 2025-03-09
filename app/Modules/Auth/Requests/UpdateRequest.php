<?php

namespace App\Modules\Auth\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'first_name'  => 'required|string|max:255|min:3',
            'last_name'   => 'required|string|max:255|min:3',
            'email' => [
                'required',
                'email',
                'unique:users,email,' . auth()->id()
            ],
        ];
    }
}
