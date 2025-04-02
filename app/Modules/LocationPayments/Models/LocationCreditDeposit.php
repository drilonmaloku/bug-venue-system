<?php

namespace App\Modules\LocationPayments\Models;

use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Barryvdh\DomPDF\Facade\Pdf;

class LocationCreditDeposit extends Model
{
    protected $fillable = [
        'location_id',
        'amount',
        'credits',
        'deposit_number',
        'status',
        'description',
        'due_date',
        'completed_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'completed_date' => 'date'
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(LocationCreditTransaction::class, 'location_credit_deposit_id');
    }

    public function generatePDF()
    {
        $pdf = PDF::loadView('pdf.credit-deposit', [
            'deposit' => $this,
            'location' => $this->location
        ]);

        return $pdf;
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_date' => now()
        ]);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isOverdue(): bool
    {
        return $this->isPending() && $this->due_date->isPast();
    }
} 