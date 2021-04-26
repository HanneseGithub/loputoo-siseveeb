<?php

/*
 * Scripts that will be enqueued
 */

/** Use jQuery */
wp_enqueue_script('jquery');

/** Use bootstrap-table css and js */
wp_enqueue_style( 'bootstrap-table-style', get_template_directory_uri() . '/src/includes/bootstrap-table.min.css');
wp_enqueue_script( 'bootstrap-table-js', get_template_directory_uri() . '/src/includes/bootstrap-table.min.js');

/** Use jquery.modal */
wp_enqueue_style( 'jquery-modal-style', get_template_directory_uri() . '/src/includes/jquery.modal.min.css');
wp_enqueue_script( 'jquery-modal-js', get_template_directory_uri() . '/src/includes/jquery.modal.min.js');

/** Enqueue our stylesheet and JS file with a jQuery dependency. */
wp_enqueue_style('my-styles', get_template_directory_uri() . '/static/css/main.css', 1.0);
wp_enqueue_script('my-js', get_template_directory_uri() . '/static/js/main.js', array('jquery'), '1.0.0', true);
