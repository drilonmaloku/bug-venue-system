<?php namespace App\Modules\Reservations\Services;


use App\Modules\Users\Services\UsersService;
use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use App\Modules\Clients\Models\Client;
use App\Modules\Clients\Services\ClientsService;
use App\Modules\Reservations\Models\Discount;
use App\Modules\Reservations\Notifications;
use App\Modules\Reservations\Notifications\DiscountAddedNotification;
use App\Modules\Reservations\Notifications\DiscountDeletedNotification;
use App\Modules\Reservations\Notifications\DiscountUpdatedNotification;
use Illuminate\Support\Facades\Notification;



class DiscountReservationsServices
{
    private $logService;
private $clientService;
      private $usersService;
    public function __construct()
    {
        $this->logService = new LogService();
                $this->clientService = new ClientsService();

        $this->usersService = app()->make(UsersService::class);

    }

    public function getByID($id){
        return Discount::find($id);
    }

    public function store($data, $reservation_id)
    {
        $discount = Discount::create([
            "location_id" => auth()->user()->getCurrentLocationId(),
            "reservation_id" => $reservation_id,
            "amount" => data_get($data, "discount_amount"),
            "description" => data_get($data, "discount_description"),
            "date" => data_get($data, "discount_date"),
        ]);
        if ($discount) {
            $this->logService->log([
                'message' => 'Zbritja është krijuar me sukses',
                'context' => Log::LOG_CONTEXT_INVOICE,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
            ]);
              Notification::send(
                 $this->usersService->getUsersForNotifications(),
                 new DiscountAddedNotification(
                     $discount,
                     auth()->user()
                 )
             );
        }

        return $discount;
    }

    public function update($request, Discount $discount)
    {
        $previousData = $discount->attributesToArray();
        $discount->amount = $request->input('discount');
        $discount->date = $request->input('date');
        $discount->description = $request->input('description');
        $discountSaved = $discount->save();
        $discount->reservation->updateReservationTracking($discount->reservation);
        if ($discountSaved) {
            $this->logService->log([
                'message' => 'Zbritja u përditësua me sukses',
                'context' => Log::LOG_CONTEXT_INVOICE,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
                'previous_data' => json_encode($previousData),
                'updated_data' => json_encode($discount)
            ]);
            Notification::send(
                 $this->usersService->getUsersForNotifications(),
                 new DiscountUpdatedNotification(
                     $discount,
                     auth()->user()
                 )
             );
        }
    
        return $discountSaved;
    }

    public function delete(Discount $discount)
    {
        $previousData = $discount->attributesToArray();
        $discountDeleted = $discount->delete();


        if ($discountDeleted) {
            $this->logService->log([
                'message' => 'Zbritja është fshirë me sukses',
                'context' => Log::LOG_CONTEXT_DSCOUNT,
                'ttl' => Log::LOG_TTL_THREE_MONTHS,
                'previous_data' => json_encode($previousData),
            ]);
             Notification::send(
                 $this->usersService->getUsersForNotifications(),
                 new DiscountDeletedNotification(
                     $discount,
                     auth()->user()
                 )
             );
        }
        return $discountDeleted;
    }
}
