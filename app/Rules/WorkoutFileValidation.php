<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WorkoutFileValidation implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        switch (strtolower($value->getClientOriginalExtension())) {
            case 'fit':
                if ($value->getMimeType() !== 'application/octet-stream') {
                    $fail('The :attribute must FIT file.');
                }
                break;
            case 'gpx':
                if ($value->getMimeType() !== 'text/xml') {
                    $fail('The :attribute must be GPX file.');
                }
                break;
            case 'tcx':
                if ($value->getMimeType() !== 'application/xml') {
                    $fail('The :attribute must be TCX file.');
                }
                break;
            default:
                $fail('The :attribute have unsupported file format.');
                break;
        }
    }
}
