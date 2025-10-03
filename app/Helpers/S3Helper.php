<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

if (!function_exists('get_invoice_s3_path')) {
    function get_invoice_s3_path($invoiceNumber) {
        return '/invoices/' . $invoiceNumber . '/';
    }
}

if (!function_exists('upload_pdf_to_s3')) {
    function upload_pdf_to_s3($pdfContent, $path, $fileName, $disk = 's3') {
        $filePath = $path . $fileName . '.pdf';
        Storage::disk($disk)->put($filePath, $pdfContent);
        return $filePath;
    }
}

if (!function_exists('delete_file_from_s3')) {
    function delete_file_from_s3($filePath, $disk = 's3') {
        try {
            if (Storage::disk($disk)->exists($filePath)) {
                $deleted = Storage::disk($disk)->delete($filePath);
                if ($deleted) {
                    \Log::info("Archivo eliminado de S3: {$filePath}");
                    return true;
                } else {
                    \Log::error("Error al eliminar archivo de S3: {$filePath}");
                    return false;
                }
            } else {
                \Log::warning("Archivo no encontrado en S3 para eliminar: {$filePath}");
                return true; 
            }
        } catch (\Exception $e) {
            \Log::error("Excepción al eliminar archivo de S3: {$filePath}", ['error' => $e->getMessage()]);
            return false;
        }
    }
}

if (!function_exists('encrypt_s3_path')) {
    function encrypt_s3_path($path) {
        return Crypt::encrypt($path);
    }
}

if (!function_exists('decrypt_and_serve_file')) {
    function decrypt_and_serve_file($encryptedPath) {
        try {
            $path = Crypt::decrypt($encryptedPath);
            $storage = Storage::disk('s3');
            
            if (!$storage->exists($path)) {
                abort(404, 'Archivo no encontrado');
            }
    
            $file = $storage->get($path);
            $mime = $storage->mimeType($path);
    
            return response($file, 200)->header('Content-Type', $mime);
    
        } catch (\Exception $e) {
            abort(403, 'No autorizado o enlace inválido');
        }
    }
}