<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
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
            'cli_first_name' => 'required|string|max:255',
            'cli_middle_name' => 'nullable|string|max:255',
            'cli_last_name' => 'required|string|max:255',
            'cli_second_last_name' => 'nullable|string|max:255',
            'cli_document_type' => 'required|string|in:CC,CE,TI,PP,NIT',
            'cli_document_number' => [
                'required',
                'string',
                'max:50',
                \Illuminate\Validation\Rule::unique('clients')->where(function ($query) {
                    return $query->where('cli_document_type', $this->cli_document_type);
                })
            ],
            'cli_email' => 'required|email|unique:clients,cli_email',
            'cli_phone' => 'required|string|max:20',
            'cli_address' => 'nullable|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'cli_first_name.required' => 'El primer nombre es requerido',
            'cli_last_name.required' => 'El primer apellido es requerido',
            'cli_document_type.required' => 'El tipo de documento es requerido',
            'cli_document_type.in' => 'El tipo de documento debe ser CC, CE, TI, PP o NIT',
            'cli_document_number.required' => 'El número de documento es requerido',
            'cli_document_number.unique' => 'Ya existe un cliente con este tipo y número de documento',
            'cli_email.required' => 'El email es requerido',
            'cli_email.email' => 'El email debe ser una dirección válida',
            'cli_email.unique' => 'Este email ya está registrado',
            'cli_phone.required' => 'El teléfono es requerido'
        ];
    }
}