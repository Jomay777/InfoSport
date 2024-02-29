<?php

namespace App\Http\Requests;

use App\Models\Team;
//use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Type\Integer;

use function PHPSTORM_META\map;

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
            'team' => [
                'nullable',
                //'exists:teams,id',
                function ($attribute, $value, $fail) {
                    // Obtener la fecha de nacimiento del jugador
                    $id = $this->team['id'];
                    $name = $value['name'];

                   // dd($id);

                    $birthDate = Carbon::parse($this->birth_date);
                    // Obtener la edad actual del jugador
                    $age = $birthDate->age;
                    // Obtener la categoría del equipo
                    $team = Team::findOrFail($id);
                    //$categoryAge = intval($team->category->name);
                    $categoryAge = ($team->category ? $team->category->name : 'libre');

                    //dd($age, $categoryAge);

                    // Verificar si la edad del jugador es menor o igual a la edad de la categoría del equipo
                    if ($age >= $categoryAge) {
                        $fail("El jugador no tiene la edad requerida para pertenecer al equipo $name $categoryAge .");
                    }
                },
            ],
        ];
        
    }
}
