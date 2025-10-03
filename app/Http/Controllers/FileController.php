<?php

namespace App\Http\Controllers;

use App\Services\InvoiceFileService;

class FileController extends Controller
{
    public function __construct(private InvoiceFileService $invoiceFileService)
    {}

    public function serveInvoiceFile(string $encryptedPath)
    {
        return decrypt_and_serve_file($encryptedPath);
    }

    public function getInvoiceFileUrl(string $invoiceId)
    {
        try {
            $user = auth('sanctum')->user();
            
           
            $isAdmin = \DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->where('model_has_roles.model_type', get_class($user))
                ->where('roles.name', 'admin')
                ->exists();
            
            if (!$isAdmin) {
                $invoice = \App\Models\Invoice::find($invoiceId);
                if (!$invoice || $invoice->inv_user_id !== $user->id) {
                    return response()->json([
                        'error' => 'No tienes permisos para acceder a este archivo'
                    ], 403);
                }
            }
            
            $invoiceFile = $this->invoiceFileService->getInvoiceFileByInvoiceId($invoiceId);
            
            if (!$invoiceFile) {
                return response()->json([
                    'error' => 'Archivo no encontrado'
                ], 404);
            }

            return response()->json([
                'data' => [
                    'file_url' => route('files.serve.invoice', ['encryptedPath' => $invoiceFile->if_encrypted_path]),
                    'file_name' => $invoiceFile->if_file_name . '.pdf',
                    'file_size' => $invoiceFile->if_file_size
                ],
                'message' => 'URL del archivo obtenida exitosamente'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Error al obtener el archivo',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}