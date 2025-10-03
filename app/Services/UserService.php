<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService 
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {}

    public function store(array $data): User
    {
        $role = $data['user_rol'];
        if(!$role) {
            throw new \Exception('User role is required', 422);
        }
        unset($data['user_rol']); 
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);
        $user->assignRole($role);

        return $user->fresh('roles');
    }

    public function getAll(): Collection
    {
        return $this->userRepository->getAll();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->userRepository->getPaginated($filters, $perPage);
    }

    public function findById(string $id): User
    {
        return $this->userRepository->findById($id);
    }

    public function update(string $id, array $data): User
    {
        $role = null;
        if (isset($data['user_rol'])) {
            $role = $data['user_rol'];
            unset($data['user_rol']);
        }

        $user = $this->userRepository->update($id, $data);
        
        if ($role) {
            $user->syncRoles([$role]);
        }

        return $user->fresh('roles');
    }

    public function delete(string $id): bool
    {
        $user = $this->userRepository->findById($id);
        
        if ($user->hasInvoices()) {
            throw new \Exception('Cannot delete user with associated invoices. Please contact system administrator to transfer invoices to another user before deletion.', 422);
        }

        return $this->userRepository->delete($id);
    }
}
