<?php namespace App\Modules\Reservations\Controllers;



use App\Modules\Clients\Resources\ClientViewResource;


use App\Modules\Clients\Services\ClientsService;
use App\Modules\Reservations\Services\ReservationsService;
use App\Modules\Venues\Services\VenuesService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;
use App\Modules\Clients\Resources\ClientListResource;

class ReservationsController extends Controller
{
    private $venuesService;
    private $reservationsService;
    private $clientsService;

    public function __construct(
        VenuesService $venuesService,
        ReservationsService $reservationsService,
        ClientsService $clientsService
    )
    {
        $this->venuesService = $venuesService;
        $this->reservationsService = $reservationsService;
        $this->clientsService = $clientsService;
    }

    public function index(Request $request)
    {
        $reservations = $this->reservationsService->getAll($request);

        return view('pages/reservations/index',[
            'reservations'=>$reservations
        ]);

    }

    public function create()
    {
        return view('pages/reservations/create', [
            'venues' => $this->venuesService->getVenues()
        ]);
    }





    public function view($id)
    {
        $reservation = $this->reservationsService->getByID($id);
        if(is_null($reservation)) {
            return abort(404);
        }
        $payments = $reservation->payments()->get();
        return view('pages/reservations/show',[
            'reservation'=>$reservation,
            'payments' => $payments,
        ]);
    }



    public function store(Request $request) {
        $clientData = [
            'name' => $request->input('client_name'),
            'email' => $request->input('client_email'),
            'phone_number' => $request->input('client_phone_number'),
            'additional_phone_number' => $request->input('client_additional_phone_number')
        ];

        $client = $this->clientsService->store($clientData);

        $reservation = $this->reservationsService->store($request,$client->id);
        return redirect()->to('reservations');
    }

    public function update(Request $request) {
        $client = $this->venuesService->getByID($request->input('id'));

        if(is_null($client)) {
            return response()->json([
                'message' => 'Client Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        try {

            $client = $this->clientsService->update($request,$client);

            if($client) {
                return response()->json([
                    'message' => 'Client was updated successfully',
                    'data' => ClientViewResource::make($client)
                ], JsonResponse::HTTP_OK);
            }

            return response()->json([
                "message" => "Failed to update existing client."
            ], JsonResponse::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function delete($id){
        $reservation = $this->reservationsService->getByID($id);
        if(is_null($reservation)) {
            return response()->json([
                'message' => 'Reservation Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        try {

            $reservationDeleted = $this->reservationsService->delete($reservation);

            if($reservationDeleted) {
                return redirect()->to('reservations');
            }

            return response()->json([
                "message" => "Failed to delete existing client."
            ], JsonResponse::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }



    }


}
