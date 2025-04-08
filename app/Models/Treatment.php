<?php

/**
 * Treatment Model
 *
 * Represents a dental treatment in the system.
 *
 * @version  GIT: 1.0.0
 * @category Models
 * @package  CabinetDentaire
 * @author   Advanced IT Research Team <contact@advanced-it-research.bi>
 * @license  MIT License
 * @link     https://github.com/Advanced-IT-Research-Burundi/cabinet-dentaire
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Treatment Model Class
 *
 * @category Models
 * @package  CabinetDentaire
 * @author   Advanced IT Research Team <contact@advanced-it-research.bi>
 * @license  MIT License
 * @link     https://github.com/Advanced-IT-Research-Burundi/cabinet-dentaire
 */
class Treatment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'dentist_id',
        'treatment_type_id',
        'appointment_id',
        'date',
        'description',
        'medical_notes',
        'applied_price',
        'status',
    ];

    protected $casts = [
        'date' => 'datetime',
        'applied_price' => 'float',
    ];

    protected $with= [
        'patient',
        'dentist',
        'treatmentType',
        'appointment'
    ];

    /**
     * Get the patient that owns the treatment.
     *
     * @return BelongsTo Patient relationship
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the dentist that performed the treatment.
     *
     * @return BelongsTo User relationship
     */
    public function dentist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }

    /**
     * Get the treatment type of this treatment.
     *
     * @return BelongsTo TreatmentType relationship
     */
    public function treatmentType(): BelongsTo
    {
        return $this->belongsTo(TreatmentType::class);
    }

    /**
     * Get the appointment associated with this treatment.
     *
     * @return BelongsTo Appointment relationship
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }


}
