<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnseignantRequest extends FormRequest
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
        $emailRule = 'required|email|unique:users,email';
        
        if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
            $enseignantId = $this->route('enseignant')->user_id;
            $emailRule .= ','.$enseignantId;
        }
        
        return [
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required',
            'telephone' => 'required',
            'addresse' => 'required',
            'date_naissance' => 'required|date',
            'lieu' => 'required',
            'sexe' => ['required', Rule::in(['M', 'F'])],
            'cours' => 'required|array',
            'classrooms' => 'required|array'
        ];
    }
}
