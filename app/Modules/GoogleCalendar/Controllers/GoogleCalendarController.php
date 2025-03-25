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
        // Store the sync parameters in session if they exist
        if (request()->has('start_date')) {
            session([
                'sync_params' => [
                    'start_date' => request('start_date'),
                    'end_date' => request('end_date'),
                    'venue_id' => request('venue_id')
                ]
            ]);
        }

        return redirect()->away($this->googleCalendarService->client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
        $this->googleCalendarService->authenticate($request->get('code'));
            
            // Store the refresh token if available
            $accessToken = $this->googleCalendarService->client->getAccessToken();
            if (isset($accessToken['refresh_token'])) {
                session(['google_refresh_token' => $accessToken['refresh_token']]);
            }
            
            return redirect('/dashboard')->with('success', 'Successfully authenticated with Google Calendar');
        } catch (\Exception $e) {
            return redirect('/dashboard')->with('error', 'Failed to authenticate with Google Calendar: ' . $e->getMessage());
        }
    }

    public function syncEventsToGoogle(Request $request)
    {
        try {
            if (!$this->googleCalendarService->setAccessToken()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not authenticated with Google Calendar',
                    'redirect' => route('google.auth')
                ], 401);
            }

            $query = Reservation::with(['client', 'venue']);

            // Apply date filters if provided
            if ($request->has('start_date')) {
                $query->where('date', '>=', $request->start_date);
            }
            if ($request->has('end_date')) {
                $query->where('date', '<=', $request->end_date);
            }

            // Apply venue filter if provided
            if ($request->has('venue_id') && $request->venue_id) {
                $query->where('venue_id', $request->venue_id);
            }

            $reservations = $query->get();
            $syncedCount = 0;

        foreach ($reservations as $reservation) {
                try {
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
                    $syncedCount++;
                } catch (\Exception $e) {
                    \Log::error('Error syncing reservation: ' . $reservation->id, ['error' => $e->getMessage()]);
                    continue;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully synced {$syncedCount} events",
                'data' => [
                    'total_processed' => $reservations->count(),
                    'successfully_synced' => $syncedCount
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Sync error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync events: ' . $e->getMessage()
            ], 500);
        }
    }

    public function syncAllEventsToGoogle()
    {
        try {
            if (!$this->googleCalendarService->setAccessToken()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not authenticated with Google Calendar',
                    'redirect' => route('google.auth')
                ], 401);
            }

            // Get all reservations
            $reservations = Reservation::with(['client', 'venue'])->get();
            $syncedCount = 0;

            foreach ($reservations as $reservation) {
                try {
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
                    $syncedCount++;
                } catch (\Exception $e) {
                    \Log::error('Error syncing reservation: ' . $reservation->id, ['error' => $e->getMessage()]);
                    continue;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully synced {$syncedCount} events",
                'data' => [
                    'total_processed' => $reservations->count(),
                    'successfully_synced' => $syncedCount
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Sync all error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync events: ' . $e->getMessage()
            ], 500);
        }
    }
}