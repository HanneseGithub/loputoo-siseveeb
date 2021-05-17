<?php

$context         = Timber::context();
$context['post'] = new Timber\Post(); 

Timber::render(array('views/privacy-policy.twig', 'views/page.twig'), $context);
