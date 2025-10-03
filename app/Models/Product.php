<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'pro_name', 'pro_description', 'pro_unique_price', 'pro_iva_percentage',
        'pro_stock', 'pro_status'
    ];

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'ii_product_id');
    }

    
}
