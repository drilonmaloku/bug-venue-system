<?php

declare(strict_types=1);

namespace App\Modules\Users\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserMeta extends Model
{
    use HasFactory;

    protected $table = "user_meta";

    protected $fillable = [
        "user_id",
        "meta_key",
        "meta_value"
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }
}