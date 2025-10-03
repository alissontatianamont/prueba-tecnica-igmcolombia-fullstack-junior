<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function expectsJson(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $this->route('user'),
            'password' => 'sometimes|string|min:8',
            'user_rol' => 'nullable|string|in:admin,salesman'
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'El nombre es demasiado largo',
            'email.email' => 'El email debe ser una dirección válida',
            'email.unique' => 'Este email ya está registrado',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'user_rol.in' => 'El rol debe ser administrador o vendedor'
        ];
    }
}