<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'inv_client_id' => 'required|exists:clients,id',
            'inv_description' => 'nullable|string|max:1000',
            'inv_notes' => 'nullable|string|max:1000',
            'inv_due_date' => 'required|date|after:today',
            'inv_iva_percentage' => 'required|numeric|min:0|max:100',
            'inv_status' => 'required|string|in:pending,paid,overdue',
            'items' => 'required|array|min:1',
            'items.*.ii_product_id' => 'required|exists:products,id',
            'items.*.ii_quantity' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'inv_client_id.required' => 'El cliente es requerido',
            'inv_client_id.exists' => 'El cliente seleccionado no existe',
            'inv_due_date.required' => 'La fecha de vencimiento es requerida',
            'inv_due_date.date' => 'La fecha de vencimiento debe ser una fecha válida',
            'inv_due_date.after' => 'La fecha de vencimiento debe ser posterior a hoy',
            'inv_iva_percentage.required' => 'El porcentaje de IVA es requerido',
            'inv_iva_percentage.numeric' => 'El IVA debe ser un número válido',
            'inv_iva_percentage.min' => 'El IVA debe ser mayor o igual a 0',
            'inv_iva_percentage.max' => 'El IVA no puede ser mayor al 100%',
            'inv_status.required' => 'El estado es requerido',
            'inv_status.in' => 'El estado debe ser pendiente, pagada o vencida',
            'items.required' => 'Debe incluir al menos un item para generar una factura',
            'items.array' => 'Los items deben ser un array',
            'items.min' => 'Debe incluir al menos un item para generar una factura',
            'items.*.ii_product_id.required' => 'debe haber un producto seleccionado',
            'items.*.ii_product_id.exists' => 'El producto seleccionado no existe',
            'items.*.ii_quantity.required' => 'La cantidad por producto es requerida',
            'items.*.ii_quantity.integer' => 'La cantidad debe ser un número entero',
            'items.*.ii_quantity.min' => 'La cantidad debe ser mayor a 0'
        ];
    }
}