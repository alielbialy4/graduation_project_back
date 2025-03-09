<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class ImageInTemp implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $file = file_exists(public_path(DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . $value)) || file_exists(public_path($value));
        if ($file) {

            $validator = Validator::make([$file => $value], [
                $file => 'file|mimes:png,jpg,jpeg|max:2048',
            ]);
            if ($validator->fails()) {
                $fail("The file :attribute must be an Image file ( png,jpg or jpeg).");
            }
        } else {
            $fail("The file :attribute does not exist.");
        }
    }
}
