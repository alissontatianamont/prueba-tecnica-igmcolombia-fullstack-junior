<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'pro_name' => 'sometimes|string|max:255',
            'pro_description' => 'nullable|string|max:1000',
            'pro_unique_price' => 'sometimes|numeric|min:0',
            'pro_iva_percentage' => 'sometimes|numeric|min:0|max:100',
            'pro_stock' => 'sometimes|integer|min:0',
            'pro_status' => 'sometimes|string|in:active,inactive'
        ];
    }

    public function messages(): array
    {
        return [
            'pro_name.max' => 'El nombre no puede tener más de 255 caracteres',
            'pro_unique_price.numeric' => 'El precio debe ser un número válido',
            'pro_unique_price.min' => 'El precio debe ser mayor o igual a 0',
            'pro_iva_percentage.numeric' => 'El IVA debe ser un número válido',
            'pro_iva_percentage.min' => 'El IVA debe ser mayor o igual a 0',
            'pro_iva_percentage.max' => 'El IVA no puede ser mayor a 100',
            'pro_stock.integer' => 'El stock debe ser un número entero',
            'pro_stock.min' => 'El stock debe ser mayor o igual a 0',
            'pro_status.in' => 'El estado debe ser active o inactive'
        ];
    }
}