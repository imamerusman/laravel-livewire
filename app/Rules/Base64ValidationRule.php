<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Base64ValidationRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $failed = base64_encode(base64_decode($value, true)) === $value;
        if ($failed) {
            $fail('The :attribute must be a valid base64 string.');
        }
    }
}
