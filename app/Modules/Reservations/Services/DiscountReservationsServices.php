<?php namespace App\Modules\Reservations\Services;


use App\Modules\Logs\Models\Log;
use App\Modules\Logs\Services\LogService;
use App\Modules\Reservations\Models\Discount;

class DiscountReservationsServices
{
    private $logService;

    public function __construct()
    {
        $this->logService = new LogService();
    }

    public function getByID($id){
        return Discount::find($id);
    }

    public function store($data, $reservation_id)
    {
        $discount = Discount::create([
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
        }
        return $discountDeleted;
    }
}
