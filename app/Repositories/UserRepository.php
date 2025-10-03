<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{ 
    public function __construct()
    {
        //
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function getAll(): Collection
    {
        return User::with('roles')->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = User::with('roles');

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        $sortField = $filters['sort_by'] ?? 'name';
        $sortDirection = $filters['sort_direction'] ?? 'asc';
        
        $allowedSortFields = ['name', 'email', 'created_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('name', 'asc');
        }

        return $query->paginate($perPage);
    }

    public function findById(string $id): User
    {
        return User::with('roles')->findOrFail($id);
    }

    public function update(string $id, array $data): User
    {
        $user = User::findOrFail($id);
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        $user->update($data);
        return $user->fresh('roles');
    }

    public function delete(string $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }
}
