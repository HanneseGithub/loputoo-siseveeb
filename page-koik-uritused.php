<?php
global $wpdb;

$currentdate = date_i18n("Y-m-d H:i:s");

$allEventsArgs = array(
    'post_type' => 'uritused',
    'meta_key'	=> 'datestart',
    'orderby'   => 'meta_value',
    'order'     => 'ASC',
    'posts_per_page' => '-1'
);

$allEvents = Timber::get_posts($allEventsArgs);

$table_name = $wpdb->prefix . "event_participations";
$current_user_id = get_current_user_id();

foreach ($allEvents as $event) {
    $event_participation = $wpdb->get_var(
        $wpdb->prepare(
        "
            SELECT participation
            FROM $table_name
            WHERE participant_id = %d AND event_id = %d
        ",
        $current_user_id, $event->id)
    );

    $event->event_participation = $event_participation;
}

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

$allEventsUniqueMonths = createUniqueMonths($allEvents);
$allEventsUniqueMonthsWithEvents = createUniqueMonthsWithEvents($allEventsUniqueMonths, $allEvents);

function createNewPostUrl($post_type){
    $create_new_post_url_slug = 'post-new.php?post_type=' . $post_type;
    return $adminurl = admin_url($create_new_post_url_slug);
}

$administrator = current_user_can( 'administrator' );
$conductor = current_user_can('conductor');
$president = current_user_can( 'president' );

$canAddAllEvents = $administrator || $president || $conductor;

$context = Timber::context();
$context['post'] = new Timber\Post();
$context['uniqueMonthsWithEvents'] = $allEventsUniqueMonthsWithEvents;
$context['createNewPostUrl'] = createNewPostUrl('uritused');

$context['canAddAllEvents'] = $canAddAllEvents;

Timber::render(array('views/events-page-all.twig', 'views/page.twig'), $context);
