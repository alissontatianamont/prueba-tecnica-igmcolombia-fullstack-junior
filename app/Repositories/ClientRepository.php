<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ClientRepository implements ClientRepositoryInterface
{
    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function getAll(): Collection
    {
        return Client::orderBy('cli_first_name')->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Client::query();

        if (!empty($filters['cli_document_number'])) {
            $query->where('cli_document_number', 'like', '%' . $filters['cli_document_number'] . '%');
        }

        if (!empty($filters['cli_email'])) {
            $query->where('cli_email', 'like', '%' . $filters['cli_email'] . '%');
        }

        if (isset($filters['cli_status']) && $filters['cli_status'] !== '') {
            $query->where('cli_status', $filters['cli_status']);
        }

        $sortField = $filters['sort_by'] ?? 'cli_first_name';
        $sortDirection = $filters['sort_direction'] ?? 'asc';
        
        $allowedSortFields = ['cli_first_name', 'cli_last_name', 'cli_email', 'cli_document_number', 'created_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('cli_first_name', 'asc');
        }

        return $query->paginate($perPage);
    }

    public function findById(string $id): Client
    {
        return Client::findOrFail($id);
    }

    public function update(string $id, array $data): Client
    {
        $client = Client::findOrFail($id);
        $client->update($data);
        return $client->fresh();
    }

    public function delete(string $id): bool
    {
        $client = Client::findOrFail($id);
        return $client->delete();
    }
}