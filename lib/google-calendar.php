<?php

/*
 * Display Google Calendar
 */

$client = new Google_Client();
$client->setDeveloperKey('AIzaSyBfDbBJ2etJJBZz-duVxS7kLlTjybcf8Ps');
$client->setScopes(
    "https://www.googleapis.com/auth/calendar.events.readonly"
);
$calendarService = new Google_Service_Calendar($client);

$myCalendarID = get_option('google-calendar-id');
$events = $calendarService->events
    ->listEvents(
        $myCalendarID,
        array(
            'singleEvents' => true,
            'orderBy' => 'startTime',
            'timeMin' => date(DATE_RFC3339),
            'maxResults' => get_option('google-calendar-number')
        ),
    )->getItems();