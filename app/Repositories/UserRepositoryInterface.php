<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function getAll(): Collection;
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function findById(string $id): User;
    public function update(string $id, array $data): User;
    public function delete(string $id): bool;
}
