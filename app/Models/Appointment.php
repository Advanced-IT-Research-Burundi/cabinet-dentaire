<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patient_id',
        'dentist_id',
        'date',
        'start_time',
        'end_time',
        'reason',
        'status',
        'notes',
        'reminder_sent',
        'creator_id',
        'planned_treatment_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'patient_id' => 'integer',
        'dentist_id' => 'integer',
        'date' => 'date',
        'reminder_sent' => 'boolean',
        'created_at' => 'datetime',
        'creator_id' => 'integer',
        'planned_treatment_id' => 'integer',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(Dentist::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plannedTreatment(): BelongsTo
    {
        return $this->belongsTo(TreatmentType::class);
    }
    public function plannedTreatments(): BelongsToMany
    {
        return $this->belongsToMany(TreatmentType::class,'appointment_treatment_types','appointment_id','treatment_type_id');
    }
}
