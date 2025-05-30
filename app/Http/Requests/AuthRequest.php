<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'userName'=>['required','string','min:2'],
            'password'=>['required']
        ];
    }

    public function messages()
    {
        return [
            'userName.required' => 'El campo Usuario es obligatorio',
            'userName.min' => 'El campo Usuario debe ser mayor a 2 caráteres',
            'password.required'=>'La contraseña es requerida',
        ];
    }
}
