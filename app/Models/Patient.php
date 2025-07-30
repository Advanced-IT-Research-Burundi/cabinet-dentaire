<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patient_type',
        'nif',
        'societe',
        'first_name',
        'middle_name',
        'last_name',
        'birth_date',
        'gender',
        'phone',
        'secondary_phone',
        'email',
        'address',
        'city',
        'postal_code',
        'country',
        'insurance_number',
        'insurance_company',
        'insurance_id',
        'medical_history',
        'allergies',
        'creator_id',
        'vat_customer_payer',

    ];

    protected $with = ['assurance'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'birth_date' => 'date',
        'creator_id' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class ,'creator_id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->patient_type == 'physique' ? $this->first_name??'' . ' ' . $this->last_name??'' : $this->societe;
        // return $this->first_name . ' ' . $this->last_name;
    }

    public function appointments(){
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function treatments(){
        return $this->hasMany(Treatment::class, 'patient_id');
    }

    public function invoices(){
        return $this->hasMany(Invoice::class, 'patient_id');
    }

    public function assurance()
    {
        return $this->belongsTo(Assurance::class, 'insurance_id');
    }

    public function treatementsNotPaids()
    {
        return $this->hasMany(Treatment::class)
                          ->whereNull('payment_status')
        ;
    }

}
