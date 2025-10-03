<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceFile;
use App\Repositories\InvoiceFileRepositoryInterface;

class InvoiceFileService
{
    public function __construct(
        private InvoiceFileRepositoryInterface $invoiceFileRepository,
        private PdfGeneratorService $pdfGeneratorService
    ) {}

    public function generateAndStoreInvoicePdf(Invoice $invoice): InvoiceFile
    {
        $pdfContent = $this->pdfGeneratorService->generateInvoicePdf($invoice);
        $s3Path = get_invoice_s3_path($invoice->inv_number);
        $fileName = $invoice->inv_number;
        
        $filePath = upload_pdf_to_s3($pdfContent, $s3Path, $fileName);
        
        $invoiceFileData = [
            'if_invoice_id' => $invoice->id,
            'if_file_name' => $fileName,
            'if_file_path' => $filePath,
            'if_file_type' => 'pdf',
            'if_file_size' => strlen($pdfContent),
            'if_mime_type' => 'application/pdf',
            'if_encrypted_path' => encrypt_s3_path($filePath)
        ];

        return $this->invoiceFileRepository->create($invoiceFileData);
    }

    public function updateInvoicePdf(Invoice $invoice): InvoiceFile
    {
        $existingFile = $this->invoiceFileRepository->findByInvoiceId($invoice->id);
        
        if ($existingFile) {
            delete_file_from_s3($existingFile->if_file_path);
            $this->invoiceFileRepository->delete($existingFile->id);
        }

        return $this->generateAndStoreInvoicePdf($invoice);
    }

    public function deleteInvoicePdf(string $invoiceId): bool
    {
        $invoiceFile = $this->invoiceFileRepository->findByInvoiceId($invoiceId);
        
        if (!$invoiceFile) {
            \Log::warning("No se encontró archivo para la factura ID: {$invoiceId}");
            return true; 
        }

        $s3Deleted = delete_file_from_s3($invoiceFile->if_file_path);
        $dbDeleted = $this->invoiceFileRepository->delete($invoiceFile->id);
        
        if ($s3Deleted && $dbDeleted) {
            \Log::info("Archivo eliminado exitosamente para factura ID: {$invoiceId}");
            return true;
        } else {
            \Log::error("Error parcial al eliminar archivo para factura ID: {$invoiceId}", [
                's3_deleted' => $s3Deleted,
                'db_deleted' => $dbDeleted
            ]);
            return false;
        }
    }

    public function getInvoiceFileByInvoiceId(string $invoiceId): ?InvoiceFile
    {
        return $this->invoiceFileRepository->findByInvoiceId($invoiceId);
    }
}