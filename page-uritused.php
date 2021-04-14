<<?php

$context = Timber::context();

Timber::render(array('views/events.twig', 'views/page.twig'), $context);
