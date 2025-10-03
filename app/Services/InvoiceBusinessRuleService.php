<?php

namespace App\Services;

use App\Models\Invoice;
use Carbon\Carbon;

class InvoiceBusinessRuleService
{
    public function isEditable(Invoice $invoice): bool
    {
        return Carbon::now()->lessThan($invoice->inv_due_date);
    }

    public function canBeDeleted(Invoice $invoice): bool
    {
        return $invoice->inv_status !== 'paid' && $this->isEditable($invoice);
    }
}