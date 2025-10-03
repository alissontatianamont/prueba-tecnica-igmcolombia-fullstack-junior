<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'cli_first_name',
        'cli_middle_name',
        'cli_last_name',
        'cli_second_last_name',
        'cli_document_type',
        'cli_document_number',
        'cli_email',
        'cli_phone',
        'cli_address',
        'cli_status'
    ];

    public function isActive(): bool
    {
        return $this->cli_status == 1;
    }

    public function isInactive(): bool
    {
        return $this->cli_status == 0;
    }

    public function activate(): bool
    {
        return $this->update(['cli_status' => 1]);
    }

    public function deactivate(): bool
    {
        return $this->update(['cli_status' => 0]);
    }

    public function hasInvoices(): bool
    {
        return $this->invoices()->exists();
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'inv_client_id');
    }
}
