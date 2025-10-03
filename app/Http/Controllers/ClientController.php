<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Services\ClientService;

class ClientController extends Controller
{
    public function __construct(private ClientService $clientService)
    {}

    public function index()
    {
        try {
            $status = request()->get('status');
            $perPage = request()->get('per_page', 15);
            $filters = request()->only([
                'cli_document_number', 'cli_email', 'cli_status',
                'sort_by', 'sort_direction'
            ]);

            if (request()->has('paginate') && request()->get('paginate') === 'false') {

                if (isset($filters['cli_status']) && $filters['cli_status'] == 1) {
                    $clients = $this->clientService->getActiveClients();
                } else {
                    $clients = $this->clientService->getAll();
                }
                
                return response()->json([
                    'data' => $clients,
                    'message' => 'Clients retrieved successfully'
                ], 200);
            }

            $clients = $this->clientService->getPaginated($filters, $perPage);
            return response()->json([
                'data' => $clients->items(),
                'pagination' => [
                    'current_page' => $clients->currentPage(),
                    'last_page' => $clients->lastPage(),
                    'per_page' => $clients->perPage(),
                    'total' => $clients->total(),
                    'from' => $clients->firstItem(),
                    'to' => $clients->lastItem()
                ],
                'filters' => $filters,
                'message' => 'Clients retrieved successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Clients could not be retrieved',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function store(StoreClientRequest $request)
    {
        try {
            $client = $this->clientService->store($request->validated());
            return response()->json([
                'data' => $client,
                'message' => 'Client created successfully'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Client could not be created',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $client = $this->clientService->findById($id);
            return response()->json([
                'data' => $client,
                'message' => 'Client retrieved successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Client not found',
                'message' => $th->getMessage()
            ], 404);
        }
    }

    public function update(UpdateClientRequest $request, string $id)
    {
        try {
            $client = $this->clientService->update($id, $request->validated());
            return response()->json([
                'data' => $client,
                'message' => 'Client updated successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Client could not be updated',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->clientService->delete($id);
            return response()->json([
                'message' => 'Client deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            if ($e->getCode() == 422) {
                return response()->json([
                    'error' => 'Client deactivated instead of deleted',
                    'message' => $e->getMessage()
                ], 422);
            }
            return response()->json([
                'error' => 'Client could not be deleted',
                'message' => $e->getMessage()
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Client could not be deleted',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function activate(string $id)
    {
        try {
            $client = $this->clientService->activate($id);
            return response()->json([
                'data' => $client,
                'message' => 'Client activated successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Client could not be activated',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function deactivate(string $id)
    {
        try {
            $client = $this->clientService->deactivate($id);
            return response()->json([
                'data' => $client,
                'message' => 'Client deactivated successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Client could not be deactivated',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}