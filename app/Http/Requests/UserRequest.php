<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = $this->route('user')?->id;

        $rules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'telephone' => 'nullable|string|max:20',
            'addresse' => 'nullable|string',
            'sexe' => 'nullable|string|in:homme,femme',
            'role' => 'required|in:administrateur,enseignant,eleve_parent',
        ];

        if ($this->role === 'eleve_parent') {
            $rules['date_naissane'] = 'required|date';
            $rules['lieu'] = 'required|string|max:255';
        }

        return $rules;
    }
}
