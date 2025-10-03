<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'pro_name' => 'required|string|max:255',
            'pro_description' => 'nullable|string|max:1000',
            'pro_unique_price' => 'required|numeric|min:0',
            'pro_iva_percentage' => 'required|numeric|min:0|max:100',
            'pro_stock' => 'required|integer|min:0',
            'pro_status' => 'required|string|in:active,inactive'
        ];
    }

    public function messages(): array
    {
        return [
            'pro_name.required' => 'El nombre del producto es requerido',
            'pro_name.max' => 'El nombre es demasiado largo',
            'pro_unique_price.required' => 'El precio es requerido',
            'pro_unique_price.numeric' => 'El precio debe ser un número válido',
            'pro_unique_price.min' => 'El precio debe ser mayor o igual a 0',
            'pro_iva_percentage.required' => 'El porcentaje de IVA es requerido',
            'pro_iva_percentage.numeric' => 'El IVA debe ser un número válido',
            'pro_iva_percentage.min' => 'El IVA debe ser mayor o igual a 0',
            'pro_iva_percentage.max' => 'El IVA no puede ser mayor al 100%',
            'pro_stock.required' => 'El stock es requerido',
            'pro_stock.integer' => 'El stock debe ser un número entero',
            'pro_stock.min' => 'El stock debe ser mayor o igual a 0',
            'pro_status.required' => 'El estado es requerido',
            'pro_status.in' => 'El estado debe ser activo o inactivo'
        ];
    }
}