<?php

$context         = Timber::context();
$timber_post     = Timber::get_post();
$context['post'] = $timber_post;
$context['single_post'] = new Timber\Post(); 

Timber::render( 'views/single-event.twig', $context );
