<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'tax',
        'total_quantity',
        'total_sacs',
        'amount_tax',
        'type_paiement',
        'type_facture',
        'invoice_currency',
        'invoice_type',
        'invoice_number',
        'date_emission',
        'date_echeance',
        'amount',
        'montant_assurance',
        'montant_patient',
        'products',
        'company',
        'client',
        'canceled_or_connection',
        'addresse_client',
        'user_id',
        'client_id',
        'commissionaire_id',
        'maison_id',
        'is_cancelled',
        'status',
        'notes',
        'tax_rate',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'insurance_covered_amount',
        'patient_amount',
    ];

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }
}
