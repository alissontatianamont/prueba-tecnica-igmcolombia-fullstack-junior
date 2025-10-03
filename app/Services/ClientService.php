<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\ClientRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ClientService
{
    public function __construct(private ClientRepositoryInterface $clientRepository)
    {}

    public function store(array $data): Client
    {
        return $this->clientRepository->create($data);
    }

    public function getAll(): Collection
    {
        return $this->clientRepository->getAll();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->clientRepository->getPaginated($filters, $perPage);
    }

    public function getActiveClients(): Collection
    {
        return Client::where('cli_status', 1)->get();
    }


    public function findById(string $id): Client
    {
        return $this->clientRepository->findById($id);
    }

    public function update(string $id, array $data): Client
    {
        return $this->clientRepository->update($id, $data);
    }

    public function delete(string $id): bool
    {
        $client = $this->clientRepository->findById($id);
        
        if ($client->hasInvoices()) {
            $client->deactivate();
            throw new \Exception('Client has associated invoices and has been deactivated instead of deleted. To fully delete this client, please archive or transfer all invoices first.', 422);
        }

        return $this->clientRepository->delete($id);
    }

    public function activate(string $id): Client
    {
        $client = $this->clientRepository->findById($id);
        $client->activate();
        return $client;
    }

    public function deactivate(string $id): Client
    {
        $client = $this->clientRepository->findById($id);
        $client->deactivate();
        return $client;
    }
}