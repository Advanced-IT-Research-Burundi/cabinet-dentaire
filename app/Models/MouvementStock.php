<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouvementStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_or_device_id',
        'item_code',
        'item_designation',
        'item_quantity',
        'item_measurement_unit',
        'item_purchase_or_sale_price',
        'item_purchase_or_sale_currency',
        'item_movement_type',
        'item_movement_invoice_ref',
        'item_movement_description',
        'item_movement_date',
        'item_product_detail_id',
        'is_send_to_obr',
        'is_sent_at',
        'user_id',
        'stock_id',
    ];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}
