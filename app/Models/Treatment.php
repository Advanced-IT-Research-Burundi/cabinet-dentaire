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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

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
    use HasFactory,SoftDeletes;

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
        'payment_status',
        'invoice_id',
        'user_id'
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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($treatment) {
            // user_id is not in the fillable array
            if (!Schema::hasColumn('treatments', 'user_id')) {
                Schema::table('treatments', function ($table) {
                    $table->foreignId('user_id')->constrained();
                });
            }

            $treatment->user_id = auth()->user()->id;
        });
    }

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
        return $this->belongsTo(Dentist::class, 'dentist_id');
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

    public function treatmentTypes():BelongsToMany
    {
        return $this->belongsToMany(TreatmentType::class,'treatement_treatment_types','treatment_id','treatment_type_id');
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

    // add user column if it doesn't exist
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
