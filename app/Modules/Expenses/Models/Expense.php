<?php namespace App\Modules\Expenses\Models;

use App\Models\User;
use App\Scopes\CurrentLocationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $guarded =[];

    protected static function booted()
    {
        static::addGlobalScope(new CurrentLocationScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
