<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stock_id',
        'type',
        'date',
        'quantity',
        'price',
        'description',
        'status',
        'is_syncronized',
    ];

    /**
     * The attributes
      * that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'stock_id' => 'integer',
        'date' => 'timestamp',
        'quantity' => 'float',
        'price' => 'double',
        'is_syncronized' => 'boolean',
    ];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    // Accesseurs
    public function getFormattedQuantityAttribute()
    {
        return number_format($this->quantity, 2);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', ' ') . ' BIF';
    }

    public function getTotalValueAttribute()
    {
        return $this->quantity * $this->price;
    }

    public function getFormattedTotalValueAttribute()
    {
        return number_format($this->getTotalValue(), 0, ',', ' ') . ' BIF';
    }
}
