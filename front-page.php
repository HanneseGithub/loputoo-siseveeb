<?php
require __DIR__ . '/vendor/autoload.php';


$client = new Google_Client();
//$client->setAuthConfig(__DIR__ . '/client_secret.json');
$client->setDeveloperKey('AIzaSyBfDbBJ2etJJBZz-duVxS7kLlTjybcf8Ps');
$client->setScopes(
    "https://www.googleapis.com/auth/calendar.events.readonly"
);
 $calendarService = new Google_Service_Calendar($client);

 $myCalendarID = "markkopoljakov@gmail.com";
 $events = $calendarService->events
                          ->listEvents($myCalendarID, array(
                                'singleEvents' => true,
                                'timeZone' => 'UTC+3',
                                'orderBy' => 'startTime',
                                'timeMin' => date(DATE_RFC3339), 
                                'maxResults' => 5,
                                ),
                          )->getItems();
$context = Timber::context();

$timber_post     = new Timber\Post();
$context['post'] = $timber_post;
$context['calendar_events'] = $events;
Timber::render( array( 'views/front-page.twig', 'views/page.twig' ), $context );


