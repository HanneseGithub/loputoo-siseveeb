<?php
$currentdate = date_i18n("Y-m-d H:i:s");

$args = array(
    'post_type' => 'üritused',
    'meta_query'=> array(
        array(
          'key' => 'datestart',
          'compare' => '>=',
          'value' => $currentdate,
          'type' => 'DATETIME',
        )),
    'meta_key'	=> 'datestart',
    'orderby'   => 'meta_value',
    'order'     => 'ASC',
);

$events = Timber::get_posts($args);

function createUniqueMonths($events) {
    $uniqueMonths = [];

    foreach ($events as $event) {
        $eventDatestartValue = $event->datestart;
        $eventDateInUnix = strtotime($eventDatestartValue);

        $eventMonthAndYearAsNumber = date_i18n('Y-m', $eventDateInUnix);

        if (!in_array($eventMonthAndYearAsNumber, $uniqueMonths)) {
            $uniqueMonths[] = $eventMonthAndYearAsNumber;
        }
    }

    return $uniqueMonths;
}

function createUniqueMonthsWithEvents($uniqueMonths, $events) {
    $sortedEvents = [];

    foreach ($uniqueMonths as $uniqueMonth) {
        foreach ($events as $event) {
            if (compareMonthAndYear($event->datestart, $uniqueMonth)) {
                $sortedEvents[$uniqueMonth][] = $event;
            }
        }
    }

    return $sortedEvents;
}

function compareMonthAndYear($eventDatestart, $eventMonthAndYear) {
    $eventDateUnix = strtotime($eventDatestart);
    $eventDateFormatted = date_i18n('Y-m', $eventDateUnix);

    return $eventDateFormatted == $eventMonthAndYear;
}

$eventUniqueMonths = createUniqueMonths($events);
$eventUniqueMonthsWithEvents = createUniqueMonthsWithEvents($eventUniqueMonths, $events);

$context = Timber::context();
$context['post'] = new Timber\Post();
$context['uniqueMonthsWithEvents'] = $eventUniqueMonthsWithEvents;

Timber::render(array('views/events.twig', 'views/page.twig'), $context);
