<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = 'invoice_items';
    protected $fillable = [
        'ii_invoice_id', 'ii_product_id', 'ii_quantity', 'ii_unit_price', 'ii_iva_percentage', 'ii_total_price'
    ];

    protected $casts = [
        'ii_quantity' => 'integer',
        'ii_unit_price' => 'decimal:2',
        'ii_iva_percentage' => 'decimal:2',
        'ii_total_price' => 'decimal:2'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'ii_invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ii_product_id');
    }

    public function getSubtotalAttribute()
    {
        return $this->ii_quantity * $this->ii_unit_price;
    }
}
