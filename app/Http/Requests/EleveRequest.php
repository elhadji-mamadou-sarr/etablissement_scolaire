<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; 
class EleveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $eleveId = $this->route('eleve') ? $this->route('eleve')->id : null;
        $userId = $eleveId ? \App\Models\Eleve::find($eleveId)->user_id : null;

        return [
            'nom' => 'required',
            'prenom' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'telephone' => 'required',
            'addresse' => 'required',
            'date_naissance' => 'required|date',
            'lieu' => 'required',
            'sexe' => ['required', 'in:M,F'],
            'classroom_id' => 'required|exists:classrooms,id',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png'
        ];
    }
}