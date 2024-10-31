<?php

namespace App\Modules\GoogleCalendar\Controllers;


use App\Modules\GoogleCalendar\Services\GoogleCalendarService;
use App\Modules\Reservations\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;


class GoogleCalendarController extends Controller
{
    protected $googleCalendarService;

    public function __construct(
        GoogleCalendarService $googleCalendarService
    )
    {
        $this->googleCalendarService = $googleCalendarService;
    }

    public function redirectToGoogle()
    {
        return redirect()->away($this->googleCalendarService->client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        $this->googleCalendarService->authenticate($request->get('code'));
        return redirect('/events/sync');
    }

    public function syncEventsToGoogle()
    {
        if (!session()->has('google_access_token')) {
            return redirect()->route('google.auth');  // Redirect to login if no token
        }
        $reservations = Reservation::with(['client', 'venue'])->get();


        foreach ($reservations as $reservation) {

            $title = "{$reservation->client->name}, Salla: {$reservation->venue->name}, Te Ftuar: {$reservation->number_of_guests}";

            $timeSlots = [
                1 => [
                    'start' => '09:00',
                    'end' => '23:45'
                ],
                2 => [
                    'start' => '09:00',
                    'end' => '14:00'
                ],
                3 => [
                    'start' => '19:00',
                    'end' => '23:45'
                ]
            ];


            $selectedSlot = $timeSlots[$reservation->reservation_type];

            $startDateTime = "{$reservation->date}T{$selectedSlot['start']}:00+01:00";
            $endDateTime = "{$reservation->date}T{$selectedSlot['end']}:00+01:00";


            $eventData = [
                'title' => $title,
                'start' => $startDateTime,
                'end' => $endDateTime,
                'event_id' => $reservation->id,
                'date' => $reservation->date,
                'is_full_day' => $reservation->reservation_type == 1
            ];



            $this->googleCalendarService->addOrUpdateEvent($eventData);
        }


        Alert::success('Success!', 'Eventet u bene sync me sukses');
        return redirect()->to('/dashboard')->withSuccessMessage('Eventet u bene sync me sukses');
    }
}