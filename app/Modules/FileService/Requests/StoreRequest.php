<?php

namespace App\Modules\FileService\Requests;

use App\Enums\FileType;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        $file = request()->file;
        $extension = $file->getClientOriginalExtension();
        if(FileType::isAllowedExtension($extension)){
            return Response::allow();
        }
        return Response::deny('The file type is not supported. Allowed types are 3ds, 3mf, ai, amf, bgcode, etc.');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {

        return [
            'file' => [
                'required',
                'file'
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'file.required' => 'Please upload a file.',
            'file.file' => 'The selected file must be a valid file.',
            'file.mimes' => 'The file type is not supported. Allowed types are 3ds, 3mf, ai, amf, bgcode, etc.',
        ];
    }



}
