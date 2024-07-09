<?php namespace App\Modules\Location\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $guarded =[];


    public function user()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
