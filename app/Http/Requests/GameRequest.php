<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GameRequest extends FormRequest
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
        $gameId = $this->route('game'); // Obtener el ID del juego actual

        return [
            'observation' => ['nullable', 'max:300'],
            'result' => ['required', 'array', 'max:100'],
            'game_scheduling' => [
                'required',
                Rule::unique('games', 'game_scheduling_id')->ignore($gameId),
            ],
        ];
    }
}
