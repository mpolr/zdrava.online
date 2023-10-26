<?php

namespace App\Http\Requests;

use App\Rules\WorkoutFileValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreWorkoutRequest extends FormRequest
{
    public function __construct()
    {
        parent::__construct();
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'workout' => 'required',
            'workout.*' => [
                'file',
                'max:25000',
                new WorkoutFileValidation()
            ],
        ];
    }
}
