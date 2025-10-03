<?php

namespace App\Repositories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface InvoiceRepositoryInterface
{
    public function create(array $data): Invoice;
    public function getAll(): Collection;
    public function getAllByUser(int $userId): Collection;
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function findById(string $id): Invoice;
    public function update(string $id, array $data): Invoice;
    public function delete(string $id): bool;
    public function createWithItems(array $invoiceData, array $items): Invoice;
    public function updateWithItems(string $id, array $invoiceData, array $items = null): Invoice;
}