<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceFile extends Model
{
    protected $table = 'invoice_files';
    protected $fillable = [
        'if_invoice_id', 'if_file_path', 'if_file_name', 'if_file_type', 'if_file_size', 'if_mime_type', 'if_encrypted_path'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'if_invoice_id');
    }
}
