<?php

namespace App\Modules\Reservations\Controllers;

use App\Modules\Clients\Services\ClientsService;
use App\Modules\Menus\Services\MenuService;
use App\Modules\Reservations\Services\ReservationGuestService;
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
use App\Modules\Collaborators\Services\CollaboratorsService;
use App\Modules\Decors\Services\DecorService;
use App\Modules\Reservations\Exports\ReservationsExport;
use App\Modules\Reservations\Models\ReservationCollaborator;
use App\Modules\Reservations\Models\ReservationComment;
use App\Modules\Reservations\Models\ReservationStaff;
use App\Modules\Reservations\Resources\ReservationListCommentResource;
use App\Modules\Reservations\Services\DiscountReservationsServices;
use App\Modules\Reservations\Services\ReservationCollaboratorServices;
use App\Modules\Reservations\Services\ReservationCommentServices;
use App\Modules\Reservations\Services\ReservationStaffServices;
use App\Modules\Users\Services\UsersService;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\Rule;
use App\Modules\Reservations\Imports\ReservationsImport;
use Illuminate\Support\Facades\DB;

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
    private $staffServices;
    private $decorService;
    private $collaboratorService;
    private $reservationcollaboratorService;
    private $reservationGuestService;

    public function __construct(
        VenuesService $venuesService,
        ReservationsService $reservationsService,
        ClientsService $clientsService,
        MenuService $menuService,
        PaymentsService $paymentsService,
        ReservationCommentServices $commentReservationService,
        ReservationStaffServices $staffServices,
        UsersService $userService,
        InvoicesServices $invoiceService,
        DiscountReservationsServices $discountService,
        DecorService $decorService,
        CollaboratorsService $collaboratorService,
        ReservationCollaboratorServices $reservationcollaboratorService,
        ReservationGuestService $reservationGuestService
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
        $this->staffServices = $staffServices;
        $this->decorService = $decorService;
        $this->collaboratorService = $collaboratorService;
        $this->reservationcollaboratorService = $reservationcollaboratorService;
        $this->reservationGuestService = $reservationGuestService;

    }

    public function index(Request $request)
    {

        
        $reservations = $this->reservationsService->getAll($request);
        if (session('success_message')) {
            Alert::success('Success!', session('success_message'));
        }
        return view('pages/reservations/index', [
            'reservations' => $reservations,
            'is_on_search' => count($request->all()),
            'venues' => $this->venuesService->getVenues(),
            'menus' => $this->menuService->getAll(new Request(), false),
            'decors' => $this->decorService->getAll(new Request(), false),
            'collaborators' => $this->collaboratorService->getAll(new Request(), false),
        ]);
    }

    public function create()
    {
        return view('pages/reservations/create', [
            'venues' => $this->venuesService->getVenues(),
            'menus' => $this->menuService->getAll(request(), false),
            'users' => $this->userService->getAll(request(), false),
            'clients' => $this->clientsService->getAll(request(), false),
            'decors' => $this->decorService->getAll(request(), false),
            'collaborators' => $this->collaboratorService->getAll(request(), false),

        ]);
    }

    public function checkVenueAvailability(Request $request)
    {
        $reservationId = $request->input('reservation_id');
        $currentReservation = Reservation::find($reservationId);
        $isEdit = $currentReservation ? true : false;

        $date = Carbon::createFromFormat('Y-m-d', $request->input('date'))->format('Y-m-d');
        $reservations = Reservation::where('date', $date)->get();
        if ($isEdit && $currentReservation) {
            $reservations = $reservations->filter(function ($reservation) use ($currentReservation) {
                return $reservation->id !== $currentReservation->id;
            });
        }
        $venues = Venue::all()->map(function ($venue) {
            return [
                'id' => $venue->id,
                'name' => $venue->name,
                'availability' => [1 => true, 2 => true, 3 => true]
            ];
        });

        if (!$reservations->isEmpty()) {
            $venues = Venue::all()->map(function ($venue) use ($reservations,$currentReservation) {
                $venueReservations = $reservations->where('venue_id', $venue->id);
                if ($venueReservations->where('reservation_type', 1)->isNotEmpty()) {
                    $availability = [1 => false,2=> false,3=>false];
                } else {
                    $availability = [1 =>true, 2 =>true, 3 =>true];

                    if ($venueReservations->where('reservation_type', 2)->isNotEmpty()) {
                        $availability[1] = false;
                        $availability[2] = false;
                    }

                    if ($venueReservations->where('reservation_type', 3)->isNotEmpty()) {
                        $availability[1] = false;
                        $availability[3] = false;
                    }
                }

                return [
                    'id' => $venue->id,
                    'name' => $venue->name,
                    'availability' => $availability // Reindex the array to prevent gaps
                ];
            });


            return response()->json(['data' => $venues->toArray()]);
        }

        return response()->json(['data' =>$venues->toArray()]);
    }

    public function view($id)
    {
        $reservation = $this->reservationsService->getByID($id);
        $totalDiscount = $reservation->discounts->sum('amount');
        $totalInvoiceAmount = $reservation->invoices->sum('amount');
        $totalAmount = ($reservation->menu_price * $reservation->number_of_guests) + $totalInvoiceAmount - $totalDiscount;

        $location = auth()->user()->getCurrentLocation();
        $locationSettings = $location->locationSettings;

        $contractContent = json_decode($locationSettings->settings, true);
        if (is_null($reservation)) {
            return abort(404);
        }
     
//        Inertia::setRootView('pages.reservations.show-inertia');
//        return Inertia::render('Reservation', [
//            'reservation' => $reservation,
//            'totalDiscount'=>$totalDiscount,
//            'totalInvoiceAmount'=>$totalInvoiceAmount,
//            'totalAmount'=>$totalAmount,
//            'users' => $this->userService->getStaffUsers()
//        ]);

        return view('pages/reservations/show', [
            'reservation' => $reservation,
            'totalDiscount'=>$totalDiscount,
            'totalInvoiceAmount'=>$totalInvoiceAmount,
            'totalAmount'=>$totalAmount,
            'users' => $this->userService->getStaffUsers(),
            'contract' => $this->reservationsService->generateReservationContract($reservation,$contractContent['contract']),
            'collaborators' => $this->collaboratorService->getAll(),
            'planning' => json_decode($reservation->planning, true), 

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
            'planning'=>$reservation->planning,
        ]]);
    }

    public function store(Request $request)
{
    $client = null;
    if ($request->has('client_name') && $request->input('client_name')) {
        $clientData = [
            'name' => $request->input('client_name'),
            'email' => $request->input('client_email'),
            'address' => $request->input('client_address'),
            'phone_number' => $request->input('client_phone_number'),
            'additional_phone_number' => $request->input('client_additional_phone_number'),
            'personal_number' => $request->input('client_personal_number'),
        ];

        $client = $this->clientsService->store($clientData);
    }

    // Proceed with reservation creation regardless of client being created or not
    $reservation = $this->reservationsService->store($request, $client ? $client->id : null);

    if ($reservation && $request->input('initial_payment_value')) {
        $this->paymentsService->store($request, $reservation->id, $client ? $client->id : null);
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
            'decors' => $this->decorService->getAll(request(), false),
            'collaborators' =>  $this->collaboratorService->getAll(request(), false),
            'planning' => json_decode($reservation->planning, true), 

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
            // Check if a client exists, if not create a new one
            if (!$reservation->client_id && $request->has('name') && $request->input('name')) {
                $clientData = [
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'address' => $request->input('address'),
                    'phone_number' => $request->input('phone_number'),
                    'additional_phone_number' => $request->input('additional_phone_number'),
                    'personal_number' => $request->input('personal_number'),
                ];

                $client = $this->clientsService->store($clientData);
                $reservation->client_id = $client->id; // Associate the new client with the reservation
            } elseif ($reservation->client_id) {
                // Update existing client
                $client = $this->clientsService->getByID($reservation->client_id);
                $this->clientsService->update($request, $client);
            }

            $reservationUpdated = $this->reservationsService->update($request, $reservation);

            if ($reservationUpdated) {
                return redirect()->route('reservations.view', ['id' => $reservation->id]);
            }

            return redirect()->route('reservations.view', ['id' => $reservation->id])->withSuccessMessage('Rezervimi u be update me sukses');
        } catch (ValidationException $e) {
            return redirect()->route('reservations.view', ['id' => $reservation->id])->withErrorMessage('Rezervimi nuk u be update');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $reservation = $this->reservationsService->getByID($id);
        if (is_null($reservation)) {
            return response()->json([
                'message' => 'Rezervimi nuk u gjet '
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        try {

            $reservationUpdated = $this->reservationsService->updateStatus($request, $reservation);

            if ($reservationUpdated) {
                return redirect()->route('reservations.view', ['id' => $reservation->id])->withSuccessMessage('Rezervimi u be update me sukses');
            }
            return redirect()->route('reservations.view', ['id' => $reservation->id])->withErrorMessage('Rezervimi nuk u be update');

        } catch (ValidationException $e) {
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
  $reservationDeleted = $this->reservationsService->delete($reservation);

            if ($reservationDeleted) {
                return redirect()->to('reservations')->withSuccessMessage('Rezervimi u fshi me sukses');
            }

            return response()->json([
                "message" => "Failed to delete existing client."
            ], JsonResponse::HTTP_BAD_REQUEST);
        try {

          
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

    public function editpayment($id, $paymentId)
    {
        $reservation = $this->reservationsService->getByID($id);
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }

        $payment = $this->paymentsService->getByID($paymentId);
        if (is_null($payment)) {
            return abort(404, 'Payment Not Found');
        }

        session(['payment_return_url' => route('reservations.view', ['id' => $id])]);

        return redirect()->route('payments.edit', ['id' => $paymentId]);
    }

    public function updatePayment(Request $request, $id, $paymentId)
    {
        $reservation = $this->reservationsService->getByID($id);

        if (is_null($reservation)) {
            return response()->json([
                'message' => 'Reservation Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $payment = $this->paymentsService->getByID($paymentId);
        if (is_null($payment)) {
            return response()->json([
                'message' => 'Payment Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        try {
         
       $this->paymentsService->update($request, $payment);
        $allPayments = $this->paymentsService->getByReservationID($id);

        $totalPayment = $allPayments->sum('value');

        $reservation->current_payment = $totalPayment;
        $reservation->save();

            return redirect()->route('reservations.view', ['id' => $id])
                ->with('success', 'Payment updated successfully.');
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

        $invoiceDeleted = $this->invoiceService->delete($invoice);
        try {

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

         $this->commentReservationService->deleteComment($comment);
            return redirect()->back()->withSuccessMessage('Komenti eshte fshire me sukses');
        try {
           
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function printContract($id)
    {
        $reservation = $this->reservationsService->getByID($id);
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }

        // Create a new PHPWord instance
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Add a section to the document
        $section = $phpWord->addSection();

        // Fetch the contract content
        $locationSettings = auth()->user()->getCurrentLocation()->locationSettings;
        $contractContent = json_decode($locationSettings->settings,true)['contract'];

        // Replace all placeholders in the contract content
        $contractContent = $this->reservationsService->generateReservationContract($reservation,$contractContent);

        // Check if contractContent is not empty
        if (!empty($contractContent)) {
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $contractContent);
        }

        // Save the document to a temporary file
        $tempFilePath = tempnam(sys_get_temp_dir(), 'contract');
        $phpWord->save($tempFilePath, 'Word2007');

        // Set headers to force download
        return response()->download($tempFilePath, 'reservation_contract_' . $reservation->id . '.docx')->deleteFileAfterSend(true);
    }

    public function addMember($reservation, Request $request)
    {
        try {
            $member = $this->staffServices->addMember($reservation,$request);
            return redirect()->back()->with('success', 'Staffi eshte shtuar me sukses');

        } catch (\Exception $e) {
            \Log::error('Error adding staff member: ' . $e->getMessage());

            return redirect()->back()->withErrors(['message' => 'Internal Server Error']);
        }
    }

    public function addCollaborator($reservation, Request $request)
    {
   
    try {
        $collaborator = $this->reservationcollaboratorService->addCollaborator($reservation,$request);
            $request->validate([
            'collaborator_id' => [
                'required',
                Rule::unique('reservation_collaborator', 'collaborator_id')
                    ->where('reservation_id', $reservationId)
            ]
        ]);
      
        return redirect()->back()->with('success', 'Bashkpuntori eshte shtuar me sukses');

    } catch (\Exception $e) {
        \Log::error('Error adding collaborator: ' . $e->getMessage());

        return redirect()->back()->withErrors(['message' => 'Internal Server Error']);
    }
}

    public function deleteCollaborator($reservationId, $collaboratorId)
    {
        try {
            $collaborator = ReservationCollaborator::where('reservation_id', $reservationId)
                ->where('collaborator_id', $collaboratorId)
                ->first();

            if (is_null($collaborator)) {
                return redirect()->back()->withErrorMessage('Bashkpuntori nuk u gjet');
            }

            $deleted = $this->reservationcollaboratorService->deleteCollaborator($collaborator);

            if ($deleted) {
                return redirect()->back()->withSuccessMessage('Bashkpuntori eshte fshire me sukses');
            }

            return redirect()->back()->withErrorMessage('Bashkpuntori nuk mund te fshihet');

            } catch (\Exception $e) {
                \Log::error('Error deleting collaborator: ' . $e->getMessage());
                return redirect()->back()->withErrorMessage('Ndodhi nje problem gjate fshirjes se bashkpuntorit');
            }
    }

    public function deleteStaff($id)
        {
            // TODO Improve Code
            $staff = ReservationStaff::find($id);

            if (is_null($staff)) {
                return response()->json(['message' => 'Staff Not Found'], JsonResponse::HTTP_NOT_FOUND);
            }

            $this->staffServices->deleteStaff($staff);
            return redirect()->back()->withSuccessMessage('Stafi eshte fshire me sukses');
            try {
            } catch (\Exception $e) {
                return response()->json(['message' => 'Internal Server Error'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

    public function export(Request $request)
    {
        $reservations = null;

        if($request->has('ids')) {
            $reservations = explode(',', $request->input('ids'));
        }
        // $this->logService->log([
        //     'message' => 'Payments are being exported to Excel',
        //     'context' => Log::LOG_CONTEXT_MENU,
        //     'ttl'=> Log::LOG_TTL_THREE_MONTHS,
        // ]);
        return Excel::download(new ReservationsExport($reservations), "reservations-export.xlsx");
    }

    public function updatePlanning(Request $request,$reservationId){
        $reservation = $this->reservationsService->getByID($reservationId);
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }

        $this->reservationsService->updatePlanning($request,$reservation);

        return redirect()->route('reservations.view', ['id' => $reservation->id])
            ->withSuccessMessage('Rezervimi u be update me sukses');


    }

    public function editNotes($reservationId){
        $reservation = $this->reservationsService->getByID($reservationId);
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }

        return view('pages/reservations/edit-notes', [
            'reservation' => $reservation
        ]);


    }

    public function updateNotes(Request $request,$reservationId){
        $reservation = $this->reservationsService->getByID($reservationId);
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }

        $this->reservationsService->updateNotes($request,$reservation);

        Alert::success('Success!', 'U përditsuan shënimet.');

        return redirect()->route('reservations.view', ['id' => $reservation->id])
            ->withSuccessMessage('Rezervimi u be update me sukses');


    }

    public function listGuests(Request $request,$id){
        $reservation = $this->reservationsService->getByID($id);
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }

        return view('pages/reservations/manage-guests', [
            'reservation' => $reservation,
            'guests' => $reservation->guests,
            'is_on_search' => count($request->all()),
        ]);
    }


    public function addGuest(Request $request,$id){
        $reservation = $this->reservationsService->getByID($id);
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }

        $guest = $this->reservationGuestService->store($request,$reservation->id);

        if($guest) {
            Alert::success('Success!','U shtua me sukses');
        }
        return redirect()->back();
    }


    public function deleteGuest($id, $guestId)
    {

        $reservation = $this->reservationsService->getByID($id);
        if (is_null($reservation)) {
            return response()->json([
                'message' => 'Reservation Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $guest = $this->reservationGuestService->getByID($guestId);
        if (is_null($guest)) {
            return response()->json([
                'message' => 'Guest Not Found'
            ], JsonResponse::HTTP_NOT_FOUND);
        }


        try {
            $guestDeleted = $this->reservationGuestService->delete($guest);
            if ($guestDeleted) {
                Alert::success('Success!','U fshi me sukses');

            }
            return redirect()->back();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateGuestStatus(Request $request,$reservationId, $guestId)
    {
        $reservation = $this->reservationsService->getByID($reservationId);
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }
        try {
            $guest = $this->reservationGuestService->getByID($guestId);
            $guestUpdated = $this->reservationGuestService->updateStatus($request,$guest);
            if ($guestUpdated) {
                Alert::success('Success!','U be update me sukses');
            }
            return redirect()->back();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }


    }

    public function updateGuestCheckin(Request $request,$reservationId, $guestId)
    {
        $reservation = $this->reservationsService->getByID($reservationId);
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }
        try {
            $guest = $this->reservationGuestService->getByID($guestId);
            $guestUpdated = $this->reservationGuestService->updateCheckInStatus($request,$guest);
            if ($guestUpdated) {
                Alert::success('Success!','U be update me sukses');
            }
            return redirect()->back();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal Server Error'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }


    }

    public function importPage()
    {
        return view('pages.reservations.import', [
            'venues' => $this->venuesService->getVenues(),
            'menus' => $this->menuService->getAll(request(), false),
            'users' => $this->userService->getAll(request(), false),
            'clients' => $this->clientsService->getAll(request(), false),
        ]);
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new ReservationsImport, $request->file('file'));

        return redirect()->route('reservations.index')->withSuccessMessage('Rezervimet u bene import me sukses');
    }
}
