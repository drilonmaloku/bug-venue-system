<?php namespace App\Modules\Payments\Controllers;



use App\Modules\Clients\Resources\ClientViewResource;


use App\Modules\Clients\Services\ClientsService;
use App\Modules\Payments\Services\PaymentsService;
use App\Modules\Venues\Services\VenuesService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;
use App\Modules\Clients\Resources\ClientListResource;

class PaymentsController extends Controller
{
    private $venuesService;
    private $logService;
    private $clientsService;
    private $paymentsService;

    public function __construct(
        VenuesService $venuesService,
        LogService $logService,
        ClientsService $clientsService,
        PaymentsService $paymentsService
    )
    {
        $this->paymentsService = $paymentsService;
        $this->venuesService = $venuesService;
        $this->logService = $logService;
        $this->clientsService = $clientsService;
    }

    public function index(Request $request)
    {
        $payments = $this->paymentsService->getAll($request);

        return view('pages/payments/index',[
            'payments'=>$payments
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

        $payment = $this->paymentsService->getByID($id);
        if(is_null($payment)) {
            return abort(404);
        }

        return view('pages/payments/show', [
            'payment' => $payment
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

        $venue = $this->venuesService->store($request);

        return redirect()->to('reservations');
        try {


        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
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
        $client = $this->venuesService->getByID($id);
        if(is_null($client)) {
            return response()->json([
                'message' => 'Client Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        try {

            $clientDeleted = $this->venuesService->delete($client);

            if($clientDeleted) {
                return response()->json([
                    'message' => 'Client was deleted successfully'
                ], JsonResponse::HTTP_OK);
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
