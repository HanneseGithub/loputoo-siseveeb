<?php

/*
 * Custom Post Types
 */

/* Notifications Post Type */
$teade_labels = array(
    'name'                  => 'Teated',
    'singular_name'         => 'Teade',
    'menu_name'             => 'Teated',
    'name_admin_bar'        => 'Teated',
    'archives'              => 'Arhiveeritud',
    'attributes'            => 'Teate info',
    'parent_item_colon'     => 'Sarnane teade:',
    'all_items'             => 'Kõik teated',
    'add_new_item'          => 'Lisa uus teade',
    'add_new'               => 'Lisa uus',
    'new_item'              => 'Lisa uus teade',
    'edit_item'             => 'Muuda teadet',
    'update_item'           => 'Uuenda teadet',
    'view_item'             => 'Vaata teadet',
    'view_items'            => 'Vaata teateid',
    'search_items'          => 'Otsi teadet',
    'not_found'             => 'Ei leitud',
    'not_found_in_trash'    => 'Ei leitud prügikastist',
    'featured_image'        => 'Pilt',
    'set_featured_image'    => 'Lisa pilt',
    'remove_featured_image' => 'Eemalda pilt',
    'use_featured_image'    => 'Kasuta pildina',
    'insert_into_item'      => 'Lisa teatesse',
    'uploaded_to_this_item' => 'Uuendatud',
    'items_list'            => 'Nimekiri',
    'items_list_navigation' => 'Teadete nimekiri',
    'filter_items_list'     => 'Filtreeri',
);

$teade_args = array(
    'label'                 => 'Teated',
    'description'           => 'Siseveebi liikmetele avalehel kuvatavad teated',
    'labels'                => $teade_labels,
    'supports'              => array('title', 'editor'),
    'taxonomies'            => array('category', 'post_tag'),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 6,
    'menu_icon'             => 'dashicons-bell',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => false,
    'can_export'            => true,
    'has_archive'           => false,
    'exclude_from_search'   => false,
    'publicly_queryable'    => false,
    'capability_type'       => 'page',

);
register_post_type('teated', $teade_args);

/* Events Post Type */

$üritused_labels = array(
    'name'                  => 'Üritused',
    'singular_name'         => 'Üritus',
    'menu_name'             => 'Üritused',
    'name_admin_bar'        => 'Üritused',
    'archives'              => 'Arhiveeritud',
    'attributes'            => 'Ürituse info',
    'parent_item_colon'     => 'Sarnane üritus:',
    'all_items'             => 'Kõik üritused',
    'add_new_item'          => 'Lisa uus üritus',
    'add_new'               => 'Lisa uus',
    'new_item'              => 'Lisa uus üritus',
    'edit_item'             => 'Muuda üritust',
    'update_item'           => 'Uuenda üritust',
    'view_item'             => 'Vaata üritust',
    'view_items'            => 'Vaata üritusi',
    'search_items'          => 'Otsi üritust',
    'not_found'             => 'Ei leitud',
    'not_found_in_trash'    => 'Ei leitud prügikastist',
    'featured_image'        => 'Pilt',
    'set_featured_image'    => 'Lisa pilt',
    'remove_featured_image' => 'Eemalda pilt',
    'use_featured_image'    => 'Kasuta pildina',
    'insert_into_item'      => 'Lisa üritusse',
    'uploaded_to_this_item' => 'Uuendatud',
    'items_list'            => 'Nimekiri',
    'items_list_navigation' => 'Ürituste nimekiri',
    'filter_items_list'     => 'Filtreeri',
);

$üritused_args = array(
    'label'                 => 'Üritused',
    'description'           => 'Siseveebi ürituste lehel kuvatavad üritused',
    'labels'                => $üritused_labels,
    'supports'              => array('title', 'editor'),
    'taxonomies'            => array('category', 'post_tag'),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 6,
    'menu_icon'             => 'dashicons-calendar-alt',
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => false,
    'can_export'            => true,
    'has_archive'           => false,
    'exclude_from_search'   => false,
    'publicly_queryable'    => true,
    'capability_type'       => 'post',
);

register_post_type('Uritused', $üritused_args);