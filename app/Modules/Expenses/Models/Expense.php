<?php namespace App\Modules\Expenses\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $guarded =[];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
