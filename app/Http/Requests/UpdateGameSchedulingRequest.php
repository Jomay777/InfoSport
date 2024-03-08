<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGameSchedulingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'time' => ['required', 'string'],
            'teamA' => [
                'required',
                'array',
/*                 function ($attribute, $value, $fail) {
                    if (count($value) !== 2) {
                        $fail("El campo $attribute debe contener exactamente 2 equipos.");
                    }
                }, */
            ],
            'teamB' => [
                'required',
                'array',
/*                 function ($attribute, $value, $fail) {
                    if (count($value) !== 2) {
                        $fail("El campo $attribute debe contener exactamente 2 equipos.");
                    }
                }, */
            ],
            'game_role' => ['required', 'array'],
        ];
    }
}
