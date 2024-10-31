<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    protected $fillable = ['user_id', 'preferences'];

    protected $casts = [
        'preferences' => 'array', 
    ];

    public function getPreference($key)
    {
        return $this->preferences[$key] ?? false;  
    }

    public function setPreference($key, $value)
    {
        $preferences = $this->preferences;
        $preferences[$key] = $value;
        $this->preferences = $preferences;
        $this->save();
    }
    
    use HasFactory;
    
}
