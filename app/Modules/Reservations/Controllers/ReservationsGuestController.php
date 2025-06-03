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
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
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

class ReservationsGuestController extends Controller
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


    public function listGuests(Request $request,$uuid){
        App::setLocale('sq');
        $reservation = Reservation::where('uuid',$uuid)->get()->first();
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }

        return view('pages/reservations/show-guest', [
            'reservation' => $reservation,
            'guests' => $reservation->guests,
            'is_on_search'=>count($request->all()),
        ]);
    }

    public function addGuest(Request $request,$uuid){
        $reservation = Reservation::where('uuid',$uuid)->get()->first();
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }
        $guest = $this->reservationGuestService->store($request,$reservation->id,$reservation->location_id);

        if($guest) {
            Alert::success('Success!','U shtua me sukses');
        }
        return redirect()->back();
    }

    public function deleteGuest($uuid, $guestId)
    {
        $reservation = Reservation::where('uuid',$uuid)->get()->first();
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


    public function updateGuest(Request $request, $reservationId, $guestId)
    {
        $guest = $this->reservationGuestService->getByID($guestId);
        if (is_null($guest)) {
            return redirect()->back()->withErrors(['error' => 'Guest not found.']);
        }

        $updated = $this->reservationGuestService->update($guest, $request);

        if ($updated) {
            Alert::success('Success!', 'Guest updated successfully.');
        }

        return redirect()->back();
    }


}
