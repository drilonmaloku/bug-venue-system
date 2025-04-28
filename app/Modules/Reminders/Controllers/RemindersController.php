<?php

namespace App\Modules\Reminders\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Reminders\Services\RemindersService;
use App\Modules\Reservations\Services\ReservationsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use RealRashid\SweetAlert\Facades\Alert;

class RemindersController extends Controller
{
    private $remindersService;
    private $reservationsService;

    public function __construct(
        RemindersService $remindersService,
        ReservationsService $reservationsService
    ) {
        $this->remindersService = $remindersService;
        $this->reservationsService = $reservationsService;
    }

    /**
     * Display a listing of reminders for a reservation.
     *
     * @param int $reservationId
     * @return \Illuminate\View\View
     */
    public function index($reservationId)
    {
        $reservation = $this->reservationsService->getByID($reservationId);
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }

        $reminders = $this->remindersService->getByReservationId($reservationId);

        return view('pages.reminders.index', [
            'reservation' => $reservation,
            'reminders' => $reminders,
        ]);
    }

    /**
     * Show the form for creating a new reminder.
     *
     * @param int $reservationId
     * @return \Illuminate\View\View
     */
    public function create($reservationId)
    {
        $reservation = $this->reservationsService->getByID($reservationId);
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }

        return view('pages.reminders.create', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * Store a newly created reminder in storage.
     *
     * @param Request $request
     * @param int $reservationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $reservationId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'reminder_date' => 'required|date',
            'type' => 'required|in:in_app,email,both',
        ]);

        $reminder = $this->remindersService->store($request, $reservationId);

        if ($reminder) {
            Alert::success('Success!', 'Reminder created successfully.');
            return redirect()->route('reservations.view', ['id' => $reservationId]);
        }

        Alert::error('Error!', 'Failed to create reminder.');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified reminder.
     *
     * @param int $reservationId
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($reservationId, $id)
    {
        $reservation = $this->reservationsService->getByID($reservationId);
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }

        $reminder = $this->remindersService->getById($id);
        if (is_null($reminder) || $reminder->reservation_id != $reservationId) {
            return abort(404, 'Reminder Not Found');
        }

        return view('pages.reminders.edit', [
            'reservation' => $reservation,
            'reminder' => $reminder,
        ]);
    }

    /**
     * Update the specified reminder in storage.
     *
     * @param Request $request
     * @param int $reservationId
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $reservationId, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'reminder_date' => 'required|date',
            'type' => 'required|in:in_app,email,both',
        ]);

        $reminder = $this->remindersService->getById($id);
        if (is_null($reminder) || $reminder->reservation_id != $reservationId) {
            return abort(404, 'Reminder Not Found');
        }

        $updated = $this->remindersService->update($request, $reminder);

        if ($updated) {
            Alert::success('Success!', 'Reminder updated successfully.');
            return redirect()->route('reservations.view', ['id' => $reservationId]);
        }

        Alert::error('Error!', 'Failed to update reminder.');
        return redirect()->back();
    }

    /**
     * Remove the specified reminder from storage.
     *
     * @param int $reservationId
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($reservationId, $id)
    {
        $reminder = $this->remindersService->getById($id);
        if (is_null($reminder) || $reminder->reservation_id != $reservationId) {
            return abort(404, 'Reminder Not Found');
        }

        $deleted = $this->remindersService->delete($reminder);

        if ($deleted) {
            Alert::success('Success!', 'Reminder deleted successfully.');
            return redirect()->route('reservations.view', ['id' => $reservationId]);
        }

        Alert::error('Error!', 'Failed to delete reminder.');
        return redirect()->back();
    }

    /**
     * Display the specified reminder.
     *
     * @param int $reservationId
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($reservationId, $id)
    {
        $reservation = $this->reservationsService->getByID($reservationId);
        if (is_null($reservation)) {
            return abort(404, 'Reservation Not Found');
        }

        $reminder = $this->remindersService->getById($id);
        if (is_null($reminder) || $reminder->reservation_id != $reservationId) {
            return abort(404, 'Reminder Not Found');
        }

        return view('pages.reminders.show', [
            'reservation' => $reservation,
            'reminder' => $reminder,
        ]);
    }
} 