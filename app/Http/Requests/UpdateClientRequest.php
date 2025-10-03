<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
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
            'cli_first_name' => 'sometimes|string|max:255',
            'cli_middle_name' => 'nullable|string|max:255',
            'cli_last_name' => 'sometimes|string|max:255',
            'cli_second_last_name' => 'nullable|string|max:255',
            'cli_document_type' => 'sometimes|string|in:CC,CE,TI,PP,NIT',
            'cli_document_number' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('clients')->where(function ($query) {
                    return $query->where('cli_document_type', $this->cli_document_type);
                })->ignore($this->route('id'))
            ],
            'cli_email' => 'sometimes|email|unique:clients,cli_email,' . $this->route('id'),
            'cli_phone' => 'sometimes|string|max:20',
            'cli_address' => 'nullable|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'cli_first_name.string' => 'El primer nombre no es valido',
            'cli_last_name.string' => 'El primer apellido no es valido',
            'cli_document_type.in' => 'El tipo de documento debe ser CC, CE, TI, PP o NIT',
            'cli_document_number.unique' => 'Ya existe un cliente con este tipo y número de documento',
            'cli_email.email' => 'El email debe ser una dirección válida',
            'cli_email.unique' => 'Este email ya está registrado'
        ];
    }
}