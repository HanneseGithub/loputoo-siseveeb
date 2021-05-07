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

$nonce = wp_create_nonce("event_participation");

$context = Timber::context();
$context['post'] = $timber_post;
$context['nonce'] = $nonce;
$context['singleEventEditUrl'] = get_edit_post_link();
$context['participationFunctionLink'] = admin_url('admin-ajax.php?action=event_participation&event_id='.$post->ID.'&nonce='.$nonce);
$context['event_participation'] = $single_event_event_participation;

Timber::render( 'views/single-event.twig', $context );
