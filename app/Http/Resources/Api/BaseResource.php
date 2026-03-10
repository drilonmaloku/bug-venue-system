<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource
{
    /**
     * Conditionally include relationship when loaded
     */
    protected function whenLoadedRelation(string $relation, $resource = null): mixed
    {
        if (!$this->relationLoaded($relation)) {
            return $this->when(false, null);
        }

        if ($resource === null) {
            return $this->whenLoaded($relation);
        }

        return $this->whenLoaded($relation, $resource);
    }

    /**
     * Format date consistently
     */
    protected function formatDate($date): ?string
    {
        return $date ? $date->toIso8601String() : null;
    }

    /**
     * Format price consistently
     */
    protected function formatPrice(?float $amount): ?array
    {
        if ($amount === null) {
            return null;
        }

        return [
            'amount' => $amount,
            'formatted' => number_format($amount, 2),
            'currency' => config('app.currency', 'EUR'),
        ];
    }
}
