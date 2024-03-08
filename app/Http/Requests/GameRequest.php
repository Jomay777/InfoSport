<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
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
    public function rules(Request $request): array
    {
        $gameId = $this->route('game'); // Obtener el ID del juego actual
        $rules = [
            'observation' => ['nullable', 'max:300'],
            'result' => ['required', 'array', 'max:100'],
            'game_scheduling' => [
                'required',
                Rule::unique('games', 'game_scheduling_id')->ignore($gameId),
            ],
        ];
        
        if (in_array($this->input('result.id'), [1, 2, 3])) {
            $rules['goals_team_a'] = [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->result['id'] === 1 && $value <= $request->goals_team_b) {
                        $fail('Los goles del equipo A deben ser mayores que los del equipo B.');
                    }
                    if ($request->result['id'] === 3 && $value !== $request->goals_team_b) {
                        $fail('Los goles del equipo A deben ser iguales a los del equipo B.');
                    }
                },
            ];
        
            $rules['goals_team_b'] = [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->result['id'] === 2 && $value <= $request->goals_team_a) {
                        $fail('Los goles del equipo B deben ser mayores que los del equipo A.');
                    }
                    if ($request->result['id'] === 3 && $value !== $request->goals_team_a) {
                        $fail('Los goles del equipo B deben ser iguales a los del equipo A.');
                    }
                },
            ];
        } else {
            // Si result.id no es 1, 2 o 3, los campos goals_team_a y goals_team_b pueden ser nulos
            $rules['goals_team_a'] = ['nullable'];
            $rules['goals_team_b'] = ['nullable'];
        }
        
        return $rules;
    }  
}
