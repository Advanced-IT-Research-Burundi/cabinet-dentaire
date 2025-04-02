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
        return $this->belongsTo(Category::class);
    }
}
