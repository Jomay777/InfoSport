<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlayerSanctionRequest extends FormRequest
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
            'yellow_cards' => 'nullable|integer|min:0|max:2',
            'red_card' => 'nullable|integer|min:0|max:1',
            'state' => 'required|array|max:20',
            'sanction' => 'nullable|string',
            'games' => 'required',
            'players' => 'required',
        ];
    }
}
