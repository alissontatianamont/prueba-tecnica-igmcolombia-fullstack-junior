<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ClientRepositoryInterface
{
    public function create(array $data): Client;
    public function getAll(): Collection;
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function findById(string $id): Client;
    public function update(string $id, array $data): Client;
    public function delete(string $id): bool;
}