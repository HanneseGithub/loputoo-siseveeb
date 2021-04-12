<?php

/*
 * Scripts that will be enqueued
 */

/** Use jQuery */
wp_enqueue_script('jquery');

/** Enqueue our stylesheet and JS file with a jQuery dependency. */
wp_enqueue_style('my-styles', get_template_directory_uri() . '/static/css/main.css', 1.0);
wp_enqueue_script('my-js', get_template_directory_uri() . '/static/js/main.js', array('jquery'), '1.0.0', true);
