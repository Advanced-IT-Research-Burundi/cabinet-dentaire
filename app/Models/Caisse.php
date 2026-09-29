<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caisse extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'date',
        'montant',
        'description',
        'status',
        'user_id',
        'name',
        'is_centrale',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'date' => 'date',
        'montant' => 'double',
        'user_id' => 'integer',
        'is_centrale' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function caisseDetails(){
        return $this->hasMany(CaisseDetail::class , 'caisse_id')->latest();
    }

    /**
     * Caisses des utilisateurs (hors caisse centrale).
     */
    public function scopeUtilisateurs($query)
    {
        return $query->where('is_centrale', false);
    }

    /**
     * Retourne la caisse centrale, en la créant si elle n'existe pas encore.
     */
    public static function centrale(): self
    {
        return static::firstOrCreate(
            ['is_centrale' => true],
            [
                'name' => 'Caisse Centrale',
                'type' => 'transfer',
                'date' => now(),
                'montant' => 0,
                'status' => 'active',
                'description' => 'Caisse centrale : opérations bancaires et collecte des caisses utilisateurs',
                'user_id' => auth()->id(),
            ]
        );
    }
}
