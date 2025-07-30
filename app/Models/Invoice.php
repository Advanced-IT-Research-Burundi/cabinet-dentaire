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
        'client',
        'obr_order_format'
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
            //$nextId = (static::max('id') ?? 0) + 1;


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


    public function obrPointer(){
        return $this->belongsTo(ObrPointer::class, 'id', 'invoice_id');
    }

    public function getObrOrderFormatAttribute($value)
    {

        return [
            "invoice_id" => $this->id,
            "invoice_number" => $this->invoice_number,
            "invoice_date" => $this->created_at->format('Y-m-d H:i:s'),
            "invoice_type" => $this->invoice_type,
            "tp_type" => $this->company['tp_type'],
            "tp_name" => $this->company['tp_name'],
            "tp_TIN" => $this->company['tp_TIN'],
            "tp_trade_number" => $this->company['tp_trade_number'],
            "tp_postal_number" => $this->company['tp_postal_number'],
            "tp_phone_number" => $this->company['tp_phone_number'],
            "tp_address_province" => $this->company['tp_address_privonce'] ?? "",
            "tp_address_commune" => $this->company['tp_address_commune'] ?? "",
            "tp_address_quartier" => $this->company['tp_address_quartier'] ?? "",
            "tp_address_avenue" => $this->company['tp_address_avenue'] ?? "",
            "tp_address_number" => $this->company['tp_address_number'] ?? "",
            "vat_taxpayer" => $this->company['vat_taxpayer'],
            "ct_taxpayer" => $this->company['ct_taxpayer'] ,
            "tl_taxpayer" => $this->company['tl_taxpayer'],
            "tp_fiscal_center" => $this->company['tp_fiscal_center'] ?? "",
            "tp_activity_sector" => $this->company['tp_activity_sector'] ?? "",
            "tp_legal_form" => $this->company['tp_legal_form'] ?? "",
            "payment_type" => $this->payment_type,
            "invoice_currency" => $this->invoice_currency,
            "customer_name" => $this->client['customer_name'],
            "customer_TIN" => $this->client['customer_TIN'] ?? "",
            "customer_address" => $this->client['customer_address'],
            "vat_customer_payer" => $this->client['vat_customer_payer'],
            "cancelled_invoice_ref" => $this->cancelled_invoice_ref,
            "invoice_ref"   => $this->invoice_ref,
            "cn_motif" => $this->cn_motif,
            "invoice_identifier" => $this->invoice_identifier,
            "invoice_items" => $this->description,
        ];
    }
}
