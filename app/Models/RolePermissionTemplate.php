<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermissionTemplate extends Model
{
    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class);
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(\Spatie\Permission\Models\Permission::class);
    }
}
