<?php

namespace App\Repositories;

use App\Models\InvoiceFile;
use Illuminate\Database\Eloquent\Collection;

interface InvoiceFileRepositoryInterface
{
    public function create(array $data): InvoiceFile;
    public function findByInvoiceId(string $invoiceId): ?InvoiceFile;
    public function update(string $id, array $data): InvoiceFile;
    public function delete(string $id): bool;
    public function deleteByInvoiceId(string $invoiceId): bool;
}