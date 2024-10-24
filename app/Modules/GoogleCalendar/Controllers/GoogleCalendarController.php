<?php

namespace App\Modules\GoogleCalendar\Controllers;


use App\Modules\GoogleCalendar\Services\GoogleCalendarService;
use App\Modules\Reservations\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class GoogleCalendarController extends Controller
{
    protected $googleCalendarService;

    public function __construct(GoogleCalendarService $googleCalendarService)
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
//        if (!session()->has('google_calendar_token')) {
//            return redirect()->route('google.auth');  // Redirect to login if no token
//        }

        $events = [
            [
                'start_time' => '2024-10-21T14:00:00+02:00',
                'end_time' => '2024-10-21T15:00:00+02:00',
            ]
        ];

        foreach ($events as $event) {
            $eventData = [
                'title' => "test",
                'start' => $event['start_time'],
                'end' => $event['end_time'],
                'event_id' => '22'
            ];
            $this->googleCalendarService->addOrUpdateEvent($eventData);
        }

        return response()->json(['message' => 'Events synced successfully!']);
    }
}