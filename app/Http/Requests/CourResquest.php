<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourResquest extends FormRequest
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
            'libelle' => ['required'],
            'credit' => ['required', 'decimal:0,6'],
            'volume' => ['required', 'integer'],
            'semestre' => ['nullable'],
        ];
    }
}
