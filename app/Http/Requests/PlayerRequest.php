<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'first_name' => ['required', 'string', 'max:36'],
            'second_name' => ['nullable', 'string', 'max:36'],
            'last_name' => ['required', 'string', 'max:36'],
            'mother_last_name' => ['nullable', 'string', 'max:36'],
            'gender' => ['required', 'max:20'],
            'birth_date' => ['required', 'date'],
            'c_i' => ['required', 'string', 'max:20', Rule::unique('players', 'c_i')->ignore($this->player)],
            'nacionality' => ['required', 'string', 'max:20'],
            'country_birth' => ['required', 'string', 'max:40'],
            'region_birth' => ['required', 'string', 'max:50'],
            'state' => ['required'],
            'team_id' => ['nullable', 'exists:teams,id'],
        ];
        
    }
}
