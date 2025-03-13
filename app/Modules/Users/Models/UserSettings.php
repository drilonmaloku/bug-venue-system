<?php

declare(strict_types=1);

namespace App\Modules\Users\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSettings extends Model
{
    use HasFactory;

    protected $table = "user_settings";

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}