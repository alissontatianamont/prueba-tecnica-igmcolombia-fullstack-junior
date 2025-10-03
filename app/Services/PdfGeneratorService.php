<?php

namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfGeneratorService
{
    public function generateInvoicePdf(Invoice $invoice): string
    {
        $invoice->load(['client', 'user', 'invoiceItems.product']);
        
        $subtotal = $invoice->invoiceItems->sum('ii_total_price');
        $ivaAmount = $subtotal * ($invoice->inv_iva_percentage / 100);
        $total = $subtotal + $ivaAmount;

        $data = [
            'invoice' => $invoice,
            'subtotal' => $subtotal,
            'ivaAmount' => $ivaAmount,
            'total' => $total
        ];

        $pdf = Pdf::loadView('invoices.pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->output();
    }
}