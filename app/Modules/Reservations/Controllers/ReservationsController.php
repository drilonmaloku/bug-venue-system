<?php

namespace App\Modules\Reservations\Controllers;

use App\Modules\Clients\Services\ClientsService;
use App\Modules\Menus\Services\MenuService;
use App\Modules\Payments\Services\PaymentsService;
use App\Modules\Reservations\Models\Reservation;
use App\Modules\Reservations\Services\InvoicesServices;
use App\Modules\Reservations\Services\ReservationsService;
use App\Modules\Venues\Models\Venue;
use App\Modules\Venues\Services\VenuesService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Modules\Reservations\Models\ReservationComment;
use App\Modules\Reservations\Resources\ReservationListCommentResource;
use App\Modules\Reservations\Services\DiscountReservationsServices;
use App\Modules\Reservations\Services\ReservationCommentServices;
use App\Modules\Users\Services\UsersService;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class ReservationsController extends Controller
{
    private $venuesService;
    private $reservationsService;
    private $clientsService;
    private $menuService;
    private $paymentsService;
    private $invoiceService;
    private $commentReservationService;
    private $userService;
    private $discountService;




    public function __construct(
        VenuesService $venuesService,
        ReservationsService $reservationsService,
        ClientsService $clientsService,
        MenuService $menuService,
        PaymentsService $paymentsService,
        ReservationCommentServices $commentReservationService,
        UsersService $userService,
        InvoicesServices $invoiceService,
        DiscountReservationsServices $discountService
    ) {
        $this->venuesService = $venuesService;
        $this->reservationsService = $reservationsService;
        $this->clientsService = $clientsService;
        $this->menuService = $menuService;
        $this->paymentsService = $paymentsService;
        $this->commentReservationService = $commentReservationService;
        $this->userService = $userService;
        $this->invoiceService = $invoiceService;
        $this->discountService = $discountService;
    }

    public function index(Request $request)
    {
        $reservations = $this->reservationsService->getAll($request);
        if (session('success_message')) {
            Alert::success('Success!', session('success_message'));
        }
        return view('pages/reservations/index', [
            'reservations' => $reservations,
            'is_on_search' => count($request->all())
        ]);
    }

    public function create()
    {
        return view('pages/reservations/create', [
            'venues' => $this->venuesService->getVenues(),
            'menus' => $this->menuService->getAll(request(), false),
            'users' => $this->userService->getAll(request(), false)
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

        if (!$reservations->isEmpty()) {
            // If there are reservations, map venues with conditional availability
            $venues = Venue::all()->map(function ($venue) use ($reservations) {
                $venueReservations = $reservations->where('venue_id', $venue->id);

                // Determine availability based on reservation types
                if ($venueReservations->where('reservation_type', 1)->isNotEmpty()) {
                    $availability = [];
                } else {
                    $availability = [1, 2, 3]; // Start with the default availability

                    if ($venueReservations->where('reservation_type', 2)->isNotEmpty()) {
                        $availability = array_diff($availability, [1, 2]);
                    }

                    if ($venueReservations->where('reservation_type', 3)->isNotEmpty()) {
                        $availability = array_diff($availability, [1, 3]);
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
        $totalDiscount = $reservation->discounts->sum('amount');
        $totalInvoiceAmount = $reservation->invoices->sum('amount');
        $totalAmount = ($reservation->menu_price * $reservation->number_of_guests) + $totalInvoiceAmount - $totalDiscount;


        if (is_null($reservation)) {
            return abort(404);
        }
        return view('pages/reservations/show', [
            'reservation' => $reservation,
            'totalDiscount'=>$totalDiscount,
            'totalInvoiceAmount'=>$totalInvoiceAmount,
            'totalAmount'=>$totalAmount
        ]);
    }

    public function viewJson($id)
    {

        $reservation = $this->reservationsService->getByID($id);
        if (is_null($reservation)) {
            return abort(404);
        }

        return response()->json(['data' => [
            'reservation' => $reservation,
            'reservation_client' => $reservation->client,
            'reservation_venue' => $reservation->venue,
        ]]);
    }

    public function store(Request $request)
    {
        $clientData = [
            'name' => $request->input('client_name'),
            'email' => $request->input('client_email'),
            'phone_number' => $request->input('client_phone_number'),
            'additional_phone_number' => $request->input('client_additional_phone_number')
        ];

        $client = $this->clientsService->store($clientData);

        $reservation = $this->reservationsService->store($request, $client->id);
        if ($reservation && $request->input('initial_payment_value')  && $request->input('initial_payment_value')) {
            $this->paymentsService->store($request, $reservation->id, $client->id);
        }
        return redirect()->to('reservations')->withSuccessMessage('Rezervimi u krijua me sukses');
    }

    public function edit($id)
    {
        $reservation = $this->reservationsService->getByID($id);
        if (is_null($reservation)) {
            return abort(404);
        }
        return view('pages/reservations/edit', [
            'reservation' => $reservation,
            'users' => $this->userService->getAll(request(), true),
            'venues' => $this->venuesService->getVenues(),
            'menus' => $this->menuService->getAll(request(), false),

        ]);
    }

    public function update(Request $request, $id)
    {
        $reservation = $this->reservationsService->getByID($id);
        if (is_null($reservation)) {
            return response()->json([
                'message' => 'Rezervimi nuk u gjet '
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        try {
//            $this->validate($request, [
//                'number_of_guests' => 'required|integer|min:1',
//                'menu_price' => 'nullable|numeric',
//                'staff_expenses' => 'nullable|numeric',
//                'date' => 'required|date',
//                'venue_id' => 'required|integer',
//                'reservation_type' => 'required|integer|in:1,2,3',
//            ]);



            $reservationUpdated = $this->reservationsService->update($request, $reservation);

            if ($reservationUpdated) {
                return redirect()->route('reservations.view', ['id' => $reservation->id]);
            }

            return redirect()->route('reservations.view', ['id' => $reservation->id])->withSuccessMessage('Rezervimi u be update me sukses');
        } catch (ValidationException $e) {
            return redirect()->route('reservations.view', ['id' => $reservation->id])->withErrorMessage('Rezervimi nuk u be update');
        } catch (\Exception $e) {
            return redirect()->route('reservations.view', ['id' => $reservation->id])->withErrorMessage('Rezervimi nuk u be update');
        }
    }

    public function delete($id)
    {
        $reservation = $this->reservationsService->getByID($id);
        if (is_null($reservation)) {
            return response()->json([
                'message' => 'Reservation Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        try {

            $reservationDeleted = $this->reservationsService->delete($reservation);

            if ($reservationDeleted) {
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



    public function storeDiscount(Request $request, $reservationID)
    {
        $reservation = $this->reservationsService->getByID($reservationID);
        if (!$reservation) {
            return redirect()->back()->withErrors(['error' => 'Reservation not found.']);
        }

        $validatedData = $request->validate([
            'discount_amount' => 'nullable|numeric',
            'discount_description' => 'nullable|string',
            'discount_date' => 'nullable|date',
        ]);

        // Call the discount service
        $discountStored = $this->discountService->store($validatedData, $reservationID);
        if($discountStored) {
            $reservation->updateTotalData();
        }

        return redirect()->route('reservations.view', ['id' => $reservationID])
            ->with('success', 'Discount added successfully.');
    }

    public function editDiscount($id, $discountId)
    {
        $reservation = $this->reservationsService->getByID($id);
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }

        $discount = $this->discountService->getByID($discountId);
        if (is_null($discount)) {
            return abort(404, 'Discount Not Found');
        }

        return view('pages/reservations/edit-discount', [
            'discount' => $discount,
            'reservation' => $reservation
        ]);
    }

    public function updateDiscount(Request $request, $id, $discountId)
    {
      

        $discount = $this->discountService->getByID($discountId);
        if (is_null($discount)) {
            return response()->json([
                'message' => 'Discount Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        $discountUpdated = $this->discountService->update($request, $discount);
        if($discountUpdated) {
            $discount->reservation->updateTotalData();
        }

        return redirect()->route('reservations.view', ['id' => $id])
            ->with('success', 'Discount updated successfully.');

    }

    public function deleteDiscount($id, $discountId)
    {
        $reservation = $this->reservationsService->getByID($id);
        if (is_null($reservation)) {
            return response()->json([
                'message' => 'Reservation Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $discount = $this->discountService->getByID($discountId);
        if (is_null($discount)) {
            return response()->json([
                'message' => 'Discount Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        try {
            $discountDeleted = $this->discountService->delete($discount);

            if ($discountDeleted) {
                $discount->reservation->updateTotalData();
                return redirect()->route('reservations.view', ['id' => $id])
                    ->with('success', 'Discount deleted successfully.');
            }

            return response()->json([
                'message' => 'Failed to delete the discount.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function storeInvoice(Request $request, $reservationID)
    {
        $reservation = $this->reservationsService->getByID($reservationID);
        if (!$reservation) {
            return redirect()->back()->withErrors(['error' => 'Reservation not found.']);
        }

        $validatedData = $request->validate([
            'invoice_amount' => 'nullable|numeric',
            'invoice_description' => 'nullable|string',
            'invoice_date' => 'nullable|date',
        ]);

        $invoiceStored = $this->invoiceService->store($validatedData, $reservationID);
        if($invoiceStored) {
            $reservation->updateTotalData();
        }
        return redirect()->route('reservations.view', ['id' => $reservationID])
            ->with('success', 'Invoice added successfully.');
    }

    public function editInvoice($id, $invoiceId)
    {
        $reservation = $this->reservationsService->getByID($id);
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }

        $invoice = $this->invoiceService->getByID($invoiceId);
        if (is_null($invoice)) {
            return abort(404, 'Discount Not Found');
        }

        return view('pages/reservations/edit-invoice', [
            'invoice' => $invoice,
            'reservation' => $reservation
        ]);
    }

    public function updateInvoice(Request $request, $id, $invoiceId)
    {
        $reservation = $this->reservationsService->getByID($id);

        if (is_null($reservation)) {
            return abort(404,'Reservation not found');
        }

        $invoice = $this->invoiceService->getByID($invoiceId);
        if (is_null($invoice)) {
            return response()->json([
                'message' => 'Invoice Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        try {
            $updatedInvoice = $this->invoiceService->update($request, $invoice);

            return redirect()->route('reservations.view', ['id' => $id])
                ->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteInvoice($id, $invoiceId)
    {

        $reservation = $this->reservationsService->getByID($id);
        if (is_null($reservation)) {
            return response()->json([
                'message' => 'Reservation Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $invoice = $this->invoiceService->getByID($invoiceId);
        if (is_null($invoice)) {
            return response()->json([
                'message' => 'Invoice Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        try {
            $invoiceDeleted = $this->invoiceService->delete($invoice);

            if ($invoiceDeleted) {
                return redirect()->route('reservations.view', ['id' => $id])
                    ->with('success', 'Discount deleted successfully.');
            }

            return response()->json([
                'message' => 'Failed to delete the discount.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    public function storeComment(Request $request, $id)
    {
        $reservation = $this->reservationsService->getByID($id);
        $reservationComment = $this->commentReservationService->storeComment($request, $reservation);
        if ($reservationComment) {

            return redirect()->back()->withSuccessMessage('Komenti per rezervim u shtua me sukses');
        }

        return response()->json([
            "message" => "Failed to create new Comment."
        ], JsonResponse::HTTP_BAD_REQUEST);




        try {
            $reservation = $this->reservationsService->getByID($id);
            $reservationComment = $this->commentReservationService->storeComment($request, $reservation);
            if ($reservationComment) {

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
