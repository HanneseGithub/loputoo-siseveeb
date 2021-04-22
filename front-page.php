<?php
require __DIR__ . '/vendor/autoload.php';

require('lib/google-calendar.php');

$currentdate = date_i18n("Y-m-d H:i:s");

$event_args = array(
    'post_type' => 'Uritused',
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
$args = array(
    'post_type' => 'teated',
    'posts_per_page' =>  4,
    'orderby' => array(
        'dateTime' => 'DESC',
    )
);

$context = Timber::context();
$context['events'] = Timber::get_posts($event_args);
$context['teated'] = Timber::get_posts($args);
$context['calendar_events'] = $events;
Timber::render(array('views/front-page.twig', 'views/page.twig'), $context);
