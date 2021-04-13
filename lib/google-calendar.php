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

$myCalendarID = "markkopoljakov@gmail.com";
$events = $calendarService->events
    ->listEvents(
        $myCalendarID,
        array(
            'singleEvents' => true,
            'timeZone' => 'UTC+3',
            'orderBy' => 'startTime',
            'timeMin' => date(DATE_RFC3339),
            'maxResults' => 5,
        ),
    )->getItems();