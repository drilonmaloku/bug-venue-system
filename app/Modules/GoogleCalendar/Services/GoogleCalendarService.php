<?php

namespace App\Modules\GoogleCalendar\Services;

use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Google\Service\Calendar\EventExtendedProperties;
use PhpParser\Error;

class GoogleCalendarService
{

    public $client;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect'));
        $this->client->addScope(Calendar::CALENDAR);
        $this->client->setAccessType('offline');
    }

    public function authenticate($code)
    {
        $this->client->authenticate($code);
        session(['google_access_token' => $this->client->getAccessToken()]);
    }

    public function isAccessTokenSet()
    {
        return session()->has('google_access_token');
    }

    public function setAccessToken()
    {
        if ($this->isAccessTokenSet()) {
            $this->client->setAccessToken(session('google_access_token'));
        }
    }

    public function addOrUpdateEvent($eventData)
    {
        try {
            $this->setAccessToken();
            $service = new \Google\Service\Calendar($this->client);

            // Check if the event already exists in Google Calendar using the event ID
            $existingEvent = $this->findEventByAppId($eventData['event_id']);

            // Define the timezone as Europe/Belgrade
            $timeZone = 'GMT+02:00';

            if ($existingEvent) {
                // Update the existing event
                $event = $service->events->get('primary', $existingEvent->getId());

                // Update event details
                $event->setSummary($eventData['title']);
                $event->setStart(new EventDateTime([
                    'dateTime' => $eventData['start'],
                    'timeZone' => $timeZone
                ]));
                $event->setEnd(new EventDateTime([
                    'dateTime' => $eventData['end'],
                    'timeZone' => $timeZone
                ]));
            } else {
                // Create a new event if not found
                $event = new Event([
                    'summary' => $eventData['title'],
                    'start' => [
                        'dateTime' => $eventData['start'],
                        'timeZone' => $timeZone
                    ],
                    'end' => [
                        'dateTime' => $eventData['end'],
                        'timeZone' => $timeZone
                    ],
                    'extendedProperties' => [
                        'private' => [
                            'app_event_id' => $eventData['event_id'],
                            'source' => 'vms'
                        ]
                    ]
                ]);
            }

            // Save or update the event in Google Calendar
            $calendarId = 'primary';

            if ($existingEvent) {
                return $service->events->update($calendarId, $event->getId(), $event);
            } else {
                return $service->events->insert($calendarId, $event);
            }
        } catch (GoogleException $e) {
            // Log the error or display it
            echo 'Error: ' . $e->getMessage();

            // Optionally, get more details about the error
            $errors = $e->getErrors();
            foreach ($errors as $error) {
                echo 'Error Domain: ' . $error['domain'] . "\n";
                echo 'Error Reason: ' . $error['reason'] . "\n";
                echo 'Error Message: ' . $error['message'] . "\n";
            }

            // Return or handle the error response as needed
            return null;
        }
    }

    public function findEventByAppId($appEventId)
    {
        $this->setAccessToken();
        $service = new \Google\Service\Calendar($this->client);

        // Query Google Calendar for events with the matching app_event_id
        $events = $service->events->listEvents('primary', [
            'privateExtendedProperty' => 'app_event_id=' . $appEventId,
        ]);

        // Return the event if found
        if (count($events->getItems()) > 0) {
            return $events->getItems()[0];
        }

        // Return null if no event found
        return null;
    }

}