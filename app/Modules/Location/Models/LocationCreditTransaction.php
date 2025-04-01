<?php

namespace App\Modules\Location\Models;

use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationCreditTransaction extends Model
{
    protected $fillable = [
        'location_id',
        'location_credit_deposit_id',
        'amount',
        'credits',
        'payment_method',
        'transaction_id',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function deposit(): BelongsTo
    {
        return $this->belongsTo(LocationCreditDeposit::class, 'location_credit_deposit_id');
    }

    public function processTransaction()
    {
        // Update location credits
        $this->location->increment('credits', $this->credits);

        // If transaction is associated with a deposit, mark it as completed
        if ($this->location_credit_deposit_id) {
            $this->deposit->markAsCompleted();
        }
    }
} 