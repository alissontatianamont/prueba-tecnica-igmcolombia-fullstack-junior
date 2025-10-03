<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Services\InvoiceService;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceService $invoiceService)
    {}

    public function index()
    {
        try {
            $user = auth('sanctum')->user();
            
            
            $isAdmin = \DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->where('model_has_roles.model_type', get_class($user))
                ->where('roles.name', 'admin')
                ->exists();
            
            $perPage = request()->get('per_page', 15);
            $filters = request()->only([
                'inv_number', 'client_name', 'client_document', 'status',
                'date_from', 'date_to', 'due_date_from', 'due_date_to',
                'sort_by', 'sort_direction'
            ]);

            if (request()->has('paginate') && request()->get('paginate') === 'false') {
                $invoices = $this->invoiceService->getAllForUser($user->id, $isAdmin);
                return response()->json([
                    'data' => $invoices,
                    'message' => 'Invoices retrieved successfully'
                ], 200);
            }

            $invoices = $this->invoiceService->getPaginatedForUser($user->id, $filters, $perPage, $isAdmin);
            return response()->json([
                'data' => $invoices->items(),
                'pagination' => [
                    'current_page' => $invoices->currentPage(),
                    'last_page' => $invoices->lastPage(),
                    'per_page' => $invoices->perPage(),
                    'total' => $invoices->total(),
                    'from' => $invoices->firstItem(),
                    'to' => $invoices->lastItem()
                ],
                'filters' => $filters,
                'message' => 'Invoices retrieved successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Invoices could not be retrieved',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function store(StoreInvoiceRequest $request)
    {
        try {
            $userId = auth('sanctum')->id();
            $invoice = $this->invoiceService->store($request->validated(), $userId);
            return response()->json([
                'data' => $invoice,
                'message' => 'Invoice created successfully'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Invoice could not be created',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $user = auth('sanctum')->user();
            
            
            $isAdmin = \DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->where('model_has_roles.model_type', get_class($user))
                ->where('roles.name', 'admin')
                ->exists();
            
            $invoice = $this->invoiceService->findById($id);
            
            if (!$isAdmin && $invoice->inv_user_id !== $user->id) {
                return response()->json([
                    'error' => 'No tienes permisos para ver esta factura'
                ], 403);
            }
            
            return response()->json([
                'data' => $invoice,
                'message' => 'Invoice retrieved successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Invoice not found',
                'message' => $th->getMessage()
            ], 404);
        }
    }

    public function update(UpdateInvoiceRequest $request, string $id)
    {
        try {
            $user = auth('sanctum')->user();
            
            
            $isAdmin = \DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->where('model_has_roles.model_type', get_class($user))
                ->where('roles.name', 'admin')
                ->exists();
            
            $invoice = $this->invoiceService->findById($id);
            if (!$isAdmin && $invoice->inv_user_id !== $user->id) {
                return response()->json([
                    'error' => 'No tienes permisos para editar esta factura'
                ], 403);
            }
            
            $invoice = $this->invoiceService->update($id, $request->validated());
            return response()->json([
                'data' => $invoice,
                'message' => 'Invoice updated successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Invoice could not be updated',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = auth('sanctum')->user();
            
            
            $isAdmin = \DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->where('model_has_roles.model_type', get_class($user))
                ->where('roles.name', 'admin')
                ->exists();
            
            $invoice = $this->invoiceService->findById($id);
            if (!$isAdmin && $invoice->inv_user_id !== $user->id) {
                return response()->json([
                    'error' => 'No tienes permisos para eliminar esta factura'
                ], 403);
            }
            
            $this->invoiceService->delete($id);
            return response()->json([
                'message' => 'Invoice deleted successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Invoice could not be deleted',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}