<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MouvementStock extends Model
{
    use HasFactory,SoftDeletes;

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


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Constantes pour les types de mouvements
    const MOUVEMENT_TYPES = [
        "" => "----",
        'EN' => 'Entrée Normales',
        'ER' => 'Entrée Retour',
        'EI' => 'Entrée Inventaire',
        'EAJ' => 'Entrées Ajustement',
        'ET' => 'Entrées Transfert',
        'EAU' => 'Entrées Autres',
        'SN' => 'Sorties Normales',
        'SP' => 'Sorties Perte',
        'SV' => 'Sorties Vol',
        'SD' => 'Sorties Désuétude',
        'SC' => 'Sorties Casse',
        'SAJ' => 'Sorties Ajustement',
        'ST' => 'Sorties Transfert',
        'SAU' => 'Sorties Autres',
    ];

    // Scopes
    public function scopeEntrees($query)
    {
        return $query->where('item_movement_type', 'LIKE', 'E%');
    }

    public function scopeSorties($query)
    {
        return $query->where('item_movement_type', 'LIKE', 'S%');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('item_movement_date', '>=', now()->subDays($days));
    }

    // Accesseurs
    public function getMovementTypeNameAttribute()
    {
        return self::MOUVEMENT_TYPES[$this->item_movement_type] ?? $this->item_movement_type;
    }

    public function getIsEntryAttribute()
    {
        return str_starts_with($this->item_movement_type, 'E');
    }

    public function getIsExitAttribute()
    {
        return str_starts_with($this->item_movement_type, 'S');
    }

    public function getMovementClassAttribute()
    {
        return $this->is_entry ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100';
    }

    public function getFormattedQuantityAttribute()
    {
        $prefix = $this->is_entry ? '+' : '-';
        return $prefix . number_format($this->item_quantity, 2);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->item_purchase_or_sale_price, 0, ',', ' ') . ' ' . ($this->item_purchase_or_sale_currency ?? 'BIF');
    }

    public function getTotalValueAttribute()
    {
        return $this->item_quantity * $this->item_purchase_or_sale_price;
    }
}
