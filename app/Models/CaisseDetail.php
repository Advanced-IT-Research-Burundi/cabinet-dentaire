<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaisseDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'caisse_id',
        'type',
        'price',
        'total',
        'status',
        'user_id',
        'description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'caisse_id' => 'integer',
        'price' => 'double',
        'total' => 'double',
        'user_id' => 'integer',
    ];

    public function caisse(): BelongsTo
    {
        return $this->belongsTo(Caisses,id::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users,id::class);
    }
}
