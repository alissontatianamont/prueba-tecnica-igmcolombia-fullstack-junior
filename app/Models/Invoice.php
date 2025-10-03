<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\InvoiceNumberGeneratorService;
use App\Services\InvoiceBusinessRuleService;

class Invoice extends Model
{
    protected $fillable = [
        'inv_client_id', 'inv_user_id', 'inv_description', 'inv_notes',
        'inv_issue_date', 'inv_due_date', 'inv_total_amount', 'inv_iva_percentage', 'inv_status'
    ];

    protected $casts = [
        'inv_issue_date' => 'date',
        'inv_due_date' => 'date',
        'inv_total_amount' => 'decimal:2',
        'inv_iva_percentage' => 'decimal:2'
    ];

    protected $appends = ['is_editable'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($invoice) {
            if (empty($invoice->inv_number)) {
                $generator = app(InvoiceNumberGeneratorService::class);
                $invoice->inv_number = $generator->generate();
            }
        });
    }

    public function getIsEditableAttribute(): bool
    {
        $businessRuleService = app(InvoiceBusinessRuleService::class);
        return $businessRuleService->isEditable($this);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'inv_client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'inv_user_id');
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'ii_invoice_id');
    }

    public function invoiceFiles()
    {
        return $this->hasMany(InvoiceFile::class, 'if_invoice_id');
    }
}
