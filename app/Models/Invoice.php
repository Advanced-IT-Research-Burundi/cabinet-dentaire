<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patient_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'total_amount',
        'insurance_amount',
        'patient_amount',
        'status',
        'notes',
        'creator_id',
        'description',
        'company',
        'client'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'patient_id' => 'integer',
        'issue_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
        'insurance_amount' => 'decimal:2',
        'patient_amount' => 'decimal:2',
        'creator_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {

            $nextId = (static::max('id') ?? 0) + 1;
            $invoice->invoice_number = '#' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function getCompanyAttribute($value)
    {
        return json_decode($value, true);
    }
    public function getClientAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getDescriptionAttribute($value)
    {
        return json_decode($value, true);
    }
}
