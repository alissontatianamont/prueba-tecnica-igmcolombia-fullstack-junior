<?php

namespace App\Services;

use App\Models\Invoice;

class InvoiceNumberGeneratorService
{
    public function generate(): string
    {
        $prefix = 'FACT-' . strtoupper(substr(md5(uniqid()), 0, 3));
        $lastInvoice = Invoice::orderBy('id', 'desc')->first();
        $sequence = $lastInvoice ? ($lastInvoice->id + 1) : 1;
        $paddedSequence = str_pad($sequence, 15, '0', STR_PAD_LEFT);
        
        return $prefix . $paddedSequence;
    }
}