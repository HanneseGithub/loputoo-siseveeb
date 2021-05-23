<?php
global $wpdb;

$timber_post = Timber::get_post();

$table_name = $wpdb->prefix . "event_participations";
$current_user_id = get_current_user_id();

$single_event_event_participation = $wpdb->get_var(
    $wpdb->prepare(
    "
        SELECT participation
        FROM $table_name
        WHERE participant_id = %d AND event_id = %d
    ",
    $current_user_id, $timber_post->id)
);

$singleEventAllUsers = get_users();
$usersGoing = [];
$usersNotGoing = [];
$usersNotAnswered = [];

foreach ($singleEventAllUsers as $user) {
    $userParticipation = $wpdb->get_var(
        $wpdb->prepare(
        "
            SELECT participation
            FROM $table_name
            WHERE participant_id = %d AND event_id = %d
        ",
        $user->ID, $timber_post->id)
    );

    if (is_null($userParticipation)) {
        $usersNotAnswered[] = $user;
    } else if (isset($userParticipation) && $userParticipation == '1') {
        $usersGoing[] = $user;
    } else if (isset($userParticipation) && $userParticipation == '0') {
        $usersNotGoing[] = $user;
    }
}

// User roles
$administrator = current_user_can( 'administrator' );
$conductor = current_user_can('conductor');
$president = current_user_can( 'president' );

$canEditEvents = $administrator || $president || $conductor;

$nonce = wp_create_nonce("event_participation");

$context = Timber::context();
$context['post'] = $timber_post;
$context['nonce'] = $nonce;
$context['singleEventEditUrl'] = get_edit_post_link();
$context['participationFunctionLink'] = admin_url('admin-ajax.php?action=event_participation&event_id='.$post->ID.'&nonce='.$nonce);
$context['event_participation'] = $single_event_event_participation;
$context['canEditEvents'] = $canEditEvents;
$context['usersGoing'] = $usersGoing;
$context['usersNotGoing'] = $usersNotGoing;
$context['usersNotAnswered'] = $usersNotAnswered;

Timber::render( 'views/single-event.twig', $context );
