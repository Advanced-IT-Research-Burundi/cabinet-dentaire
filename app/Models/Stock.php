<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code_product',
        'product_name',
        'marque',
        'unite_mesure',
        'quantite',
        'quantite_alert',
        'price',
        'price_ttc',
        'price_max',
        'price_tvac',
        'taux_tva',
        'item_ott_tax',
        'item_tsce_tax',
        'price_min',
        'date_expiration',
        'description',
        'location',
        'supplier',
        'user_id',
        'category_id',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'quantite' => 'double',
        'quantite_alert' => 'double',
        'price' => 'double',
        'price_ttc' => 'double',
        'price_max' => 'double',
        'price_tvac' => 'double',
        'taux_tva' => 'double',
        'item_ott_tax' => 'double',
        'item_tsce_tax' => 'double',
        'price_min' => 'double',
        'date_expiration' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class , 'category_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class , 'supplier_id');
    }

    public function mouvements()
    {
        return $this->hasMany(MouvementStock::class);
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Scopes pour faciliter les requêtes
    public function scopeActive($query)
    {
        return $query->where('status', 'Disponible');
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantite <= quantite_alert');
    }

    public function scopeExpirationSoon($query, $days = 30)
    {
        return $query->where('date_expiration', '<=', now()->addDays($days))
                    ->where('date_expiration', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('date_expiration', '<=', now());
    }

    // Accesseurs pour améliorer l'affichage
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Disponible'     => '<span class="badge badge-success">Disponible</span>',
            'Faible_stock'   => '<span class="badge badge-warning">Faible stock</span>',
            'En_rupture'     => '<span class="badge badge-danger">En rupture</span>',
            'Expire'         => '<span class="badge badge-dark">Expiré</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge badge-secondary">Non défini</span>';
    }


    public function getStockStatusAttribute()
    {
        if ($this->quantite <= 0) {
            return ['status' => 'out_of_stock', 'label' => 'Rupture', 'class' => 'text-red-600 bg-red-100'];
        } elseif ($this->quantite <= $this->quantite_alert) {
            return ['status' => 'low_stock', 'label' => 'Stock Faible', 'class' => 'text-yellow-600 bg-yellow-100'];
        } else {
            return ['status' => 'in_stock', 'label' => 'En Stock', 'class' => 'text-green-600 bg-green-100'];
        }
    }

    public function getExpirationStatusAttribute()
    {
        if (!$this->date_expiration) {
            return ['status' => 'no_expiration', 'label' => 'Sans expiration', 'class' => 'text-gray-600 bg-gray-100'];
        }

        $daysUntilExpiration = now()->diffInDays($this->date_expiration, false);

        if ($daysUntilExpiration < 0) {
            return ['status' => 'expired', 'label' => 'Expiré', 'class' => 'text-red-600 bg-red-100'];
        } elseif ($daysUntilExpiration <= 30) {
            return ['status' => 'expires_soon', 'label' => 'Expire bientôt', 'class' => 'text-orange-600 bg-orange-100'];
        } else {
            return ['status' => 'valid', 'label' => 'Valide', 'class' => 'text-green-600 bg-green-100'];
        }
    }

    // Méthodes utilitaires
    public function getTotalValue()
    {
        return $this->quantite * $this->price;
    }

    public function getFormattedPrice()
    {
        return number_format($this->price, 0, ',', ' ') . ' BIF';
    }

    public function getFormattedTotalValue()
    {
        return number_format($this->getTotalValue(), 0, ',', ' ') . ' BIF';
    }
}
