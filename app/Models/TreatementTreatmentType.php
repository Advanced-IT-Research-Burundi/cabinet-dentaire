<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatementTreatmentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'treatment_id',
        'treatment_type_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    public function treatmentType()
    {
        return $this->belongsTo(TreatmentType::class);
    }
}
