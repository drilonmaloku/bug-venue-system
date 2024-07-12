<?php

namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CurrentLocationScope implements Scope{
    public function apply(Builder $builder, Model $model)
    {
        if (auth()->check() && !auth()->user()->hasRole('system-admin')) {
            $locationId = auth()->user()->getCurrentLocationId();
            if($locationId) {
                $builder->where('location_id', $locationId);
            }

        }
    }
}