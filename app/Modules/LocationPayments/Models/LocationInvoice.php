<?php

namespace App\Modules\LocationPayments\Models;

use App\Modules\Location\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Barryvdh\DomPDF\Facade\Pdf;

class LocationInvoice extends Model
{
    protected $fillable = [
        'location_id',
        'credits',
        'invoice_number',
        'status',
        'description',
        'due_date',
        'paid_date'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date'
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(LocationPayment::class);
    }

    public function creditDeposit(): BelongsTo
    {
        return $this->belongsTo(LocationCreditDeposit::class, 'location_credit_deposit_id');
    }

    public function generatePDF()
    {
        $pdf = PDF::loadView('pages.location-payments.invoices.pdf', [
            'invoice' => $this,
            'location' => $this->location
        ]);

        return $pdf;
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => now()
        ]);

        // Create payment record
        $this->payments()->create([
            'amount_paid' => $this->amount,
            'payment_date' => now()
        ]);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Get the amount in euros (1 credit = 1 euro)
     *
     * @return float
     */
    public function getAmountAttribute(): float
    {
        return (float) $this->credits;
    }
} 