<?php

namespace App\Modules\LocationPayments\Models;

use App\Modules\Location\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationPayment extends Model
{
    protected $fillable = [
        'location_invoice_id',
        'amount_paid',
        'payment_date'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'date'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(LocationInvoice::class, 'location_invoice_id');
    }

    public function processPayment()
    {
        // If payment is associated with an invoice, mark it as paid
        if ($this->location_invoice_id) {
            $this->invoice->markAsPaid();
        }
    }
} 