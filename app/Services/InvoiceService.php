<?php

namespace App\Services;

use App\Models\Invoice;
use App\Repositories\InvoiceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class InvoiceService
{
    public function __construct(
        private InvoiceRepositoryInterface $invoiceRepository,
        private InvoiceFileService $invoiceFileService,
        private InvoiceBusinessRuleService $businessRuleService,
        private InvoiceCalculationService $calculationService
    ) {}

    public function store(array $data, int $userId): Invoice
    {
        $items = $data['items'];
        unset($data['items']);
        
        $data['inv_user_id'] = $userId;
        $data['inv_issue_date'] = Carbon::now()->toDateString();
        
        $invoice = $this->invoiceRepository->createWithItems($data, $items);
        $this->invoiceFileService->generateAndStoreInvoicePdf($invoice);
        
        return $invoice;
    }

    public function getAll(): Collection
    {
        return $this->invoiceRepository->getAll();
    }

    public function getAllForUser(int $userId, bool $isAdmin = false): Collection
    {
        if ($isAdmin) {
            return $this->getAll();
        }
        
        return $this->invoiceRepository->getAllByUser($userId);
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->invoiceRepository->getPaginated($filters, $perPage);
    }

    public function getPaginatedForUser(int $userId, array $filters = [], int $perPage = 15, bool $isAdmin = false): LengthAwarePaginator
    {
        if ($isAdmin) {
            return $this->getPaginated($filters, $perPage);
        }
        
        // Agregar filtro de usuario a los filtros existentes
        $filters['user_id'] = $userId;
        return $this->invoiceRepository->getPaginated($filters, $perPage);
    }

    public function findById(string $id): Invoice
    {
        return $this->invoiceRepository->findById($id);
    }

    public function update(string $id, array $data): Invoice
    {
        $invoice = $this->findById($id);
        
        if (!$this->businessRuleService->isEditable($invoice)) {
            throw new \Exception('No se puede editar una factura cuya fecha de vencimiento ya pasó');
        }
        
        $items = $data['items'] ?? null;
        if (isset($data['items'])) {
            unset($data['items']);
        }
        
        $updatedInvoice = $this->invoiceRepository->updateWithItems($id, $data, $items);
        $this->invoiceFileService->updateInvoicePdf($updatedInvoice);
        
        return $updatedInvoice;
    }

    public function delete(string $id): bool
    {
        $invoice = $this->findById($id);
        
        if (!$this->businessRuleService->canBeDeleted($invoice)) {
            throw new \Exception('No se puede eliminar esta factura');
        }
        
        $this->invoiceFileService->deleteInvoicePdf($id);
        return $this->invoiceRepository->delete($id);
    }
}