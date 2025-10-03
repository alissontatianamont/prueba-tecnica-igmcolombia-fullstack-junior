<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Invoice;

class UpdateInvoiceRequest extends FormRequest
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
        $invoice = Invoice::find($this->route('id'));
        
        if ($invoice && !$invoice->isEditable()) {
            return [
                'non_editable' => 'required'
            ];
        }

        return [
            'inv_description' => 'sometimes|string|max:1000',
            'inv_notes' => 'sometimes|string|max:1000',
            'inv_status' => 'sometimes|string|in:pending,paid,overdue',
            'items' => 'sometimes|array|min:1',
            'items.*.id' => 'sometimes|exists:invoice_items,id',
            'items.*.ii_product_id' => 'required_with:items|exists:products,id',
            'items.*.ii_quantity' => 'required_with:items|integer|min:1',
            'items.*._delete' => 'sometimes|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'non_editable.required' => 'No se puede editar una factura cuya fecha de vencimiento ya pasó',
            'inv_status.in' => 'El estado debe ser pending, paid o overdue',
            'items.array' => 'Los items deben ser un array',
            'items.min' => 'Debe incluir al menos un item',
            'items.*.id.exists' => 'El item seleccionado no existe',
            'items.*.ii_product_id.required_with' => 'El producto es requerido en cada item',
            'items.*.ii_product_id.exists' => 'El producto seleccionado no existe',
            'items.*.ii_quantity.required_with' => 'La cantidad es requerida en cada item',
            'items.*.ii_quantity.integer' => 'La cantidad debe ser un número entero',
            'items.*.ii_quantity.min' => 'La cantidad debe ser mayor a 0'
        ];
    }
}