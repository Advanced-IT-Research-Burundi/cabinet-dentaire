<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaisseDetail extends Model
{
    use HasFactory,SoftDeletes;

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
        'operation_type',
        'sens',
        'date_operation',
        'reference',
        'banque',
        'justificatif',
        'source_caisse_id',
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
        'source_caisse_id' => 'integer',
        'date_operation' => 'date',
    ];

    public function caisse(): BelongsTo
    {
        return $this->belongsTo(Caisse::class);
    }

    public function sourceCaisse(): BelongsTo
    {
        return $this->belongsTo(Caisse::class, 'source_caisse_id');
    }

    public function getOperationLabelAttribute(): string
    {
        if ($this->operation_type === COLLECTE_CAISSE) {
            return 'Collecte caisse utilisateur';
        }

        return OPERATIONS_CAISSE_CENTRALE[$this->operation_type]['label'] ?? ($this->type ?? '--');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
