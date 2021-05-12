<?php
global $wpdb;

require __DIR__ . '/vendor/autoload.php';

require('lib/google-calendar.php');

$currentdate = date_i18n("Y-m-d H:i:s");

$event_args = array(
    'post_type' => 'uritused',
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
    'posts_per_page' =>  4
);

$teated_args = array(
    'post_type' => 'teated',
    'posts_per_page' =>  4,
    'orderby' => array(
        'dateTime' => 'DESC',
    )
);

if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['deletePost']))
{
    wp_trash_post($_POST['ID']);
}

$custom_events = Timber::get_posts($event_args);

$table_name = $wpdb->prefix . "event_participations";
$current_user_id = get_current_user_id();

foreach ($custom_events as $event) {
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

function createNewPostUrl($post_type){
    $create_new_post_url_slug = 'post-new.php?post_type=' . $post_type;
    return $adminurl = admin_url($create_new_post_url_slug);
}

function returnUritusedUrl(){
    $url =  get_site_url() . '/uritused';
    return $url;
}
// User roles
$administrator = current_user_can( 'administrator' );
$conductor = current_user_can('conductor');
$secretary = current_user_can('secretary');

$canAddNotifications = $administrator || $secretary || $conductor;

$context = Timber::context();
$context['createNewPostUrl'] = createNewPostUrl('teated');
$context['events'] = $custom_events;
$context['teated'] = Timber::get_posts($teated_args);
$context['calendar_events'] = $events;

$context['canAddNotifications'] = $canAddNotifications;

Timber::render(array('views/front-page.twig', 'views/page.twig'), $context);
