<?php namespace App\Modules\Logs\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    const LOG_CONTEXT_RESERVATIONS = 1;
    const LOG_CONTEXT_PAYMENTS = 2;
    const LOG_CONTEXT_CLIENTS = 3;
    const LOG_CONTEXT_VENUES = 4;
    const LOG_CONTEXT_USERS = 5;
    const LOG_CONTEXT_REPORTS = 6;


    const LOG_TTL_FOREVER = 0;
    const LOG_TTL_ONE_YEAR = 1;
    const LOG_TTL_SIX_MONTHS = 2;
    const LOG_TTL_THREE_MONTHS = 3;

    const LOG_TTL_KEEP_ALIVE = true;
    const LOG_TTL_DESTROY = false;



    protected $fillable = [
        "user_id",
        "message",
        "context",
        "previous_data",
        "updated_data",
        "ttl",
        "keep_alive",
        "deletes_at",
    ];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getContext($log){
        if($log->context == 1) {
            return 'Rezervimet';
        }
        else if($log->context == 2) {
            return 'Pagesat';
        }
        else if($log->context == 3) {
            return 'Klientat';
        }
        else if($log->context == 4) {
            return 'Sallat';
        }
        else if($log->context == 5) {
            return 'Userat';
        }
        else if($log->context == 6) {
            return 'Raportet';
        }
        else if($log->context == 7) {
            return 'Menu';
        }
    }

}
