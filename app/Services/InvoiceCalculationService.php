<?php

namespace App\Services;

use App\Models\Invoice;

class InvoiceCalculationService
{
    public function calculateTotal(Invoice $invoice): float
    {
        $subtotal = $invoice->invoiceItems->sum(function ($item) {
            return $item->ii_quantity * $item->ii_unit_price;
        });
        
        $ivaAmount = $subtotal * ($invoice->inv_iva_percentage / 100);
        
        return $subtotal + $ivaAmount;
    }
}