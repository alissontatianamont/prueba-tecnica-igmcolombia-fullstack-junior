<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function create(array $data): Invoice
    {
        return Invoice::create($data);
    }

    public function getAll(): Collection
    {
        return Invoice::with(['client', 'user', 'invoiceItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAllByUser(int $userId): Collection
    {
        return Invoice::with(['client', 'user', 'invoiceItems.product'])
            ->where('inv_user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Invoice::with(['client', 'user', 'invoiceItems.product']);

        if (!empty($filters['user_id'])) {
            $query->where('inv_user_id', $filters['user_id']);
        }

        if (!empty($filters['inv_number'])) {
            $query->where('inv_number', 'like', '%' . $filters['inv_number'] . '%');
        }

        if (!empty($filters['client_document'])) {
            $query->whereHas('client', function ($q) use ($filters) {
                $q->where('cli_document_number', 'like', '%' . $filters['client_document'] . '%');
            });
        }

        if (!empty($filters['date_from'])) {
            $query->where('inv_issue_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('inv_issue_date', '<=', $filters['date_to']);
        }

        if (!empty($filters['due_date_from'])) {
            $query->where('inv_due_date', '>=', $filters['due_date_from']);
        }

        if (!empty($filters['due_date_to'])) {
            $query->where('inv_due_date', '<=', $filters['due_date_to']);
        }

        if (!empty($filters['status'])) {
            $query->where('inv_status', $filters['status']);
        }

        $sortField = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        
        $allowedSortFields = ['created_at', 'inv_issue_date', 'inv_due_date', 'inv_number', 'inv_total_amount'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($perPage);
    }

    public function findById(string $id): Invoice
    {
        return Invoice::with(['client', 'user', 'invoiceItems.product'])
            ->findOrFail($id);
    }

    public function update(string $id, array $data): Invoice
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update($data);
        return $invoice->fresh(['client', 'user', 'invoiceItems.product']);
    }

    public function delete(string $id): bool
    {
        $invoice = Invoice::findOrFail($id);
        return $invoice->delete();
    }

    public function createWithItems(array $invoiceData, array $items): Invoice
    {
        return DB::transaction(function () use ($invoiceData, $items) {
          
            $subtotal = 0;
            $itemsWithPrices = [];
            
            foreach ($items as $item) {
   
                $product = Product::findOrFail($item['ii_product_id']);
                $unitPrice = $product->pro_unique_price;
                $quantity = $item['ii_quantity'];
                
                $itemsWithPrices[] = [
                    'ii_product_id' => $item['ii_product_id'],
                    'ii_quantity' => $quantity,
                    'ii_unit_price' => $unitPrice,
                    'ii_iva_percentage' => $product->pro_iva_percentage,
                    'ii_total_price' => $unitPrice * $quantity
                ];
                
                $subtotal += $unitPrice * $quantity;
            }
            
           
            $ivaPercentage = $invoiceData['inv_iva_percentage'] ?? 0;
            $ivaAmount = $subtotal * ($ivaPercentage / 100);
            $total = $subtotal + $ivaAmount;
            
            
            $invoiceData['inv_total_amount'] = $total;
            
          
            $invoice = $this->create($invoiceData);
            
           
            foreach ($itemsWithPrices as $item) {
                $item['ii_invoice_id'] = $invoice->id;
                InvoiceItem::create($item);
            }
            
            return $invoice->fresh(['client', 'user', 'invoiceItems.product']);
        });
    }

    public function updateWithItems(string $id, array $invoiceData, array $items = null): Invoice
    {
        return DB::transaction(function () use ($id, $invoiceData, $items) {
            $invoice = $this->update($id, $invoiceData);
            
            if ($items !== null) {
              
                $existingItems = $invoice->invoiceItems->keyBy('ii_product_id');
                $newItemsProductIds = collect($items)->pluck('ii_product_id')->toArray();
                
               
                foreach ($existingItems as $productId => $existingItem) {
                    if (!in_array($productId, $newItemsProductIds)) {
                        $existingItem->delete();
                    }
                }
                
         
                foreach ($items as $item) {
                    if (isset($item['_delete']) && $item['_delete']) {
                        if (isset($item['id'])) {
                            InvoiceItem::destroy($item['id']);
                        }
                        continue;
                    }
                    
                    
                    $product = Product::findOrFail($item['ii_product_id']);
                    $currentPrice = $product->pro_unique_price;
                    $quantity = $item['ii_quantity'];
                    
                    
                    $existingItem = $existingItems->get($item['ii_product_id']);
                    
                    if ($existingItem) {
                        
                        if ($existingItem->ii_quantity != $quantity) {
                            $existingItem->update([
                                'ii_quantity' => $quantity,
                                'ii_unit_price' => $currentPrice,
                                'ii_iva_percentage' => $product->pro_iva_percentage,
                                'ii_total_price' => $currentPrice * $quantity
                            ]);
                        }
                    } else {
                     
                        InvoiceItem::create([
                            'ii_invoice_id' => $invoice->id,
                            'ii_product_id' => $item['ii_product_id'],
                            'ii_quantity' => $quantity,
                            'ii_unit_price' => $currentPrice,
                            'ii_iva_percentage' => $product->pro_iva_percentage,
                            'ii_total_price' => $currentPrice * $quantity
                        ]);
                    }
                }
                
                $invoice->refresh();
                $calculationService = app(\App\Services\InvoiceCalculationService::class);
                $total = $calculationService->calculateTotal($invoice);
                $invoice->update(['inv_total_amount' => $total]);
            }
            
            return $invoice->fresh(['client', 'user', 'invoiceItems.product']);
        });
    }
}