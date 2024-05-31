<?php namespace App\Modules\Reservations\Controllers;



use App\Modules\Clients\Resources\ClientViewResource;


use App\Modules\Clients\Services\ClientsService;
use App\Modules\Menus\Services\MenuService;
use App\Modules\Payments\Services\PaymentsService;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Reservations\Services\ReservationsService;
use App\Modules\Venues\Models\Venue;
use App\Modules\Venues\Services\VenuesService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Modules\Logs\Services\LogService;
use App\Modules\Clients\Resources\ClientListResource;
use App\Modules\Reservations\Models\ReservationComment;
use App\Modules\Reservations\Requests\AddCommentRequest;
use App\Modules\Reservations\Resources\ReservationListCommentResource;
use App\Modules\Reservations\Services\ReservationCommentServices;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class ReservationsController extends Controller
{
    private $venuesService;
    private $reservationsService;
    private $clientsService;
    private $menuService;
    private $paymentsService;
    private $commentReservationService;


    public function __construct(
        VenuesService $venuesService,
        ReservationsService $reservationsService,
        ClientsService $clientsService,
        MenuService $menuService,
        PaymentsService $paymentsService,
        ReservationCommentServices $commentReservationService,

    )
    {
        $this->venuesService = $venuesService;
        $this->reservationsService = $reservationsService;
        $this->clientsService = $clientsService;
        $this->menuService = $menuService;
        $this->paymentsService = $paymentsService;
        $this->commentReservationService = $commentReservationService;

    }

    public function index(Request $request)
    {
        $reservations = $this->reservationsService->getAll($request);
        if(session('success_message')){
            Alert::success('Success!', session('success_message'));
        }
        return view('pages/reservations/index',[
            'reservations'=>$reservations,
            'is_on_search'=>count($request->all())
        ]);

    }

    public function create()
    {
        return view('pages/reservations/create', [
            'venues' => $this->venuesService->getVenues(),
            'menus' => $this->menuService->getAll(request(),false)
        ]);
    }




    public function checkVenueAvailability(Request $request)
    {
        $date = Carbon::createFromFormat('Y-m-d', $request->input('date'))->format('d-m-Y');

        $reservations = Reservation::where('date', $date)
            ->get();
        $venues = Venue::all()->map(function ($venue) {
            return [
                'id' => $venue->id,
                'name' => $venue->name,
                'availability' => [1, 2, 3] // Default availability array
            ];
        });

        if(!$reservations->isEmpty()){
            // If there are reservations, map venues with conditional availability
            $venues = Venue::all()->map(function ($venue) use ($reservations) {
                $venueReservations = $reservations->where('venue_id', $venue->id);

                // Determine availability based on reservation types
                if ($venueReservations->where('reservation_type', 1)->isNotEmpty()) {
                    $availability = [];
                } else {
                    $availability = [1, 2, 3]; // Start with the default availability

                    if ($venueReservations->where('reservation_type', 2)->isNotEmpty()) {
                        $availability = array_diff($availability, [1,2]);
                    }

                    if ($venueReservations->where('reservation_type', 3)->isNotEmpty()) {
                        $availability = array_diff($availability, [1,3]);
                    }
                }

                return [
                    'id' => $venue->id,
                    'name' => $venue->name,
                    'availability' => array_values($availability) // Reindex the array to prevent gaps
                ];
            });

            // Return the mapped venues with conditional availability
            return response()->json(['data' => $venues]);
        }

        return response()->json(['data' => $venues]);
    }
    public function view($id)
    {
        $reservation = $this->reservationsService->getByID($id);
        if(is_null($reservation)) {
            return abort(404);
        }
        return view('pages/reservations/show',[
            'reservation'=>$reservation,


        ]);
    }

    public function viewJson($id)
    {

        $reservation = $this->reservationsService->getByID($id);
        if(is_null($reservation)) {
            return abort(404);
        }

        return response()->json(['data' => [
            'reservation' => $reservation,
            'reservation_client' => $reservation->client,
            'reservation_venue' => $reservation->venue,
        ]]);
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
        if($reservation && $request->input('initial_payment_value')  && $request->input('initial_payment_value')) {
            $this->paymentsService->store($request,$reservation->id,$client->id);
        }
        return redirect()->to('reservations')->withSuccessMessage('Rezervimi u krijua me sukses');
    }


    public function storePayment(Request $request, $reservationID)
    {
        $reservation = $this->reservationsService->getByID($reservationID);
        if (!$reservation) {
            return redirect()->back()->withErrors(['error' => 'Reservation not found.']);
        }
    
        $validatedData = $request->validate([
            'payment_date' => 'required|date',
            'initial_payment_value' => 'required|numeric',
            'payment_notes' => 'nullable|string',
        ]);
    
        // Assuming $reservation has a 'client_id' property or method to get client ID
        $clientID = $reservation->client_id;
    
        // Call the payment service
        $this->paymentsService->storePayment($validatedData, $reservationID, $clientID);
    
        return redirect()->route('reservations.view', ['id' => $reservationID])
                         ->with('success', 'Payment added successfully.');
    }


    public function edit($id)
    {
        $reservation = $this->reservationsService->getByID($id);
        if(is_null($reservation)) {
            return abort(404);
        }
        return view('pages/reservations/edit',[
            'reservation'=>$reservation
        ]);
    }

    public function update(Request $request, $id) {
        $reservation = $this->reservationsService->getByID($id);
    
        if(is_null($reservation)) {
            return response()->json([
                'message' => 'Rezervimi nuk u gjet '
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    
        try {
            $this->validate($request, [
                'number_of_guests' => 'required|integer|min:1',
            ]);
    
            $reservationUpdated = $this->reservationsService->update($request, $reservation);
    
            if($reservationUpdated) {
                return redirect()->route('reservations.view', ['id' => $reservation->id]);
            }
    
            return response()->json([
                "message" => "Failed to update the reservation."
            ], JsonResponse::HTTP_BAD_REQUEST);
    
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->validator->errors()->all()
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
                return redirect()->to('reservations')->withSuccessMessage('Rezervimi u fshi me sukses');
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

    public function checkAvailability($date)
    {

        $reservations = Reservation::where('date', $date)
            ->get();

        return $reservations->isEmpty();
    }





      
    public function storeComment(Request $request,$id) {
        $reservation = $this->reservationsService->getByID($id);
        $reservationComment = $this->commentReservationService->storeComment($request,$reservation);
        if($reservationComment) {

            return redirect()->back()->withSuccessMessage('Komenti per rezervim u shtua me sukses');
        }

        return response()->json([
            "message" => "Failed to create new Comment."
        ], JsonResponse::HTTP_BAD_REQUEST);


   

        try {  
            $reservation = $this->reservationsService->getByID($id);
            $reservationComment = $this->commentReservationService->storeComment($request,$reservation);
            if($reservationComment) {

                return response()->json([
                    'message' => 'Comment was created successfully',
                    'data' => ReservationListCommentResource::make($reservationComment)
                ], JsonResponse::HTTP_OK);
            }

            return response()->json([
                "message" => "Failed to create new Comment."
            ], JsonResponse::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], 500);
        }
    }



    public function deleteComment($id)
    {
        $comment = ReservationComment::find($id);

        if (is_null($comment)) {
            return response()->json(['message' => 'Comment Not Found'], JsonResponse::HTTP_NOT_FOUND);
        }

        try {
            $this->commentReservationService->deleteComment($comment);
            return redirect()->back()->withSuccessMessage('Komenti eshte fshire me sukses');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
