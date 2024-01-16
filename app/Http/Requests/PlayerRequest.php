<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlayerRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'second_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'mother_last_name' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'c_i' => ['required', 'string', 'max:255'],
            'nacionality' => ['required', 'string', 'max:255'],
            'country_birth' => ['required', 'string', 'max:255'],
            'region_birth' => ['required', 'string', 'max:255'],
            'state' => ['required'],
            'team_id' => ['nullable', 'exists:teams,id'],
        ];
        
    }
}
