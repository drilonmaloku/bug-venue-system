<?php

namespace App\Modules\Reservations\Imports;

use App\Modules\Clients\Services\ClientsService;

use App\Modules\Menus\Models\Menu;
use App\Modules\Payments\Services\PaymentsService;
use App\Modules\Reservations\Services\ReservationCommentServices;
use App\Modules\Reservations\Services\ReservationsService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class ReservationsImport implements ToModel,WithHeadingRow
{
    use Importable, SkipsErrors;

    public function model(array $row)
    {
        $reservationService = app()->make(ReservationsService::class);
        $reservationCommentServices = app()->make(ReservationCommentServices::class);
        $clientsService = app()->make(ClientsService::class);
        $paymentsService = app()->make(PaymentsService::class);


        $paymentMethod = $row['metoda_e_pageses'] ?? 1;

        if($paymentMethod == 'Kesh' || $paymentMethod == 'Cash') {
            $paymentMethod =1;
        }
        elseif($paymentMethod == 'Bank') {
            $paymentMethod =2;
        }
        else {
            $paymentMethod =1;
        }

        $menu = Menu::where('name', $row['menu'])->first();

        $requestData = new Request([
            'client_name'  => $row['emri'] . ' '. $row['mbiemri'] ?? null,
            'client_email' => null,
            'client_address' => $row['adresa'] ?? null,
            'client_phone_number' => $row['nr_i_telefonit'] ?? null,
            'client_personal_number' => $row['nr_personal'] ?? null,
            'client_additional_phone_number' => null,
            "initial_payment_value" => $row['pagesa_te_kryera'] ?? null,
            "payment_notes" => null,
            "payment_method" => $paymentMethod,
            "payment_date" => $this->convertExcelDate($row['data_e_pageses']) ?? null,
            "number_of_guests" => $row['numri_i_te_ftuarve'] ?? null,
            "reservation" => "1,3",
            "menu_contents" => $menu->description,
            "comment" => $row['koment'] ?? null,
            "menu_price" => $row['qmimi_per_person'] ?? null,
            "menu_id" => $menu->id,
            "date" => $this->convertExcelDate($row['data_e_dasmes']) ?? null,
            "contract_date" => $this->convertExcelDate($row['data_e_kontrates']) ?? null,
        ]);


        $clientData = [
            'name' => $requestData->input('client_name'),
            'email' => $requestData->input('client_email'),
            'address' => $requestData->input('client_address'),
            'phone_number' => $requestData->input('client_phone_number'),
            'additional_phone_number' => $requestData->input('client_additional_phone_number'),
            'personal_number' => $requestData->input('client_personal_number')
        ];


        $client = $clientsService->store($clientData);


        $reservation = $reservationService->store($requestData, $client->id);
        if ($reservation && $requestData->input('initial_payment_value')  && $requestData->input('initial_payment_value')) {
           $paymentsService->store($requestData, $reservation->id, $client->id);
        }
        if($row['koment']){
            $reservationCommentServices->storeComment($requestData,$reservation);
        }


        return $reservation;

    }


    private function convertExcelDate($date)
    {
        if (is_numeric($date)) {
            return Date::excelToDateTimeObject($date)->format('Y-m-d');
        }
        return $date;
    }
}