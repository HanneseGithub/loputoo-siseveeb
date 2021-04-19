<<?php


$üritused_args = array(
    'post_type' => 'üritused',
    'posts_per_page' =>  4,
    'orderby' => array(
        'date' => 'DESC'
    )
);
$context = Timber::context();
$context['üritused'] = Timber::get_posts($üritused_args);
Timber::render(array('views/events.twig', 'views/page.twig'), $context);
