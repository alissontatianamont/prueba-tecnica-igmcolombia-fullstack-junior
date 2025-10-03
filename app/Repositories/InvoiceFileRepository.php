<?php

namespace App\Repositories;

use App\Models\InvoiceFile;
use Illuminate\Database\Eloquent\Collection;

class InvoiceFileRepository implements InvoiceFileRepositoryInterface
{
    public function create(array $data): InvoiceFile
    {
        return InvoiceFile::create($data);
    }

    public function findByInvoiceId(string $invoiceId): ?InvoiceFile
    {
        return InvoiceFile::where('if_invoice_id', $invoiceId)->first();
    }

    public function update(string $id, array $data): InvoiceFile
    {
        $invoiceFile = InvoiceFile::findOrFail($id);
        $invoiceFile->update($data);
        return $invoiceFile->fresh();
    }

    public function delete(string $id): bool
    {
        $invoiceFile = InvoiceFile::findOrFail($id);
        return $invoiceFile->delete();
    }

    public function deleteByInvoiceId(string $invoiceId): bool
    {
        return InvoiceFile::where('if_invoice_id', $invoiceId)->delete() > 0;
    }
}