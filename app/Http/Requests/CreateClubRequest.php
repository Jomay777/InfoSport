<?php

namespace App\Http\Requests;

use App\Models\Club;
use Illuminate\Foundation\Http\FormRequest;

class CreateClubRequest extends FormRequest
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
        //dd(request()->all());

        return [
            'name' => ['required', 'string', 'max:100', 'unique:' . Club::class],
            'coach' => ['nullable', 'string', 'max:80'],
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            //'logo_path' => ['nullable', 'string'],
            'users' => ['sometimes', 'array']
        ];
    }
}
