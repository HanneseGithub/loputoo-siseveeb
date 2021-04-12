<?php
require __DIR__ . '/vendor/autoload.php';

require('lib/google-calendar.php');

$args = array(
    'post_type' => 'teated',
    'posts_per_page' =>  4,
    'orderby' => array(
        'date' => 'DESC'
    )
);

$context = Timber::context();

$timber_post     = new Timber\Post();
$context['teated'] = Timber::get_posts($args);
$context['post'] = $timber_post;
$context['calendar_events'] = $events;
Timber::render(array('views/front-page.twig', 'views/page.twig'), $context);
