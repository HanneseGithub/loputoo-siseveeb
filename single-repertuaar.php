<?php
acf_form_head();
acf_enqueue_uploader();

function retrieve_file_url( $number){
    $url = wp_get_attachment_url( $number );
    if(!$number){
        return "#";
    }else{
        return  $url;
    }
}
function retrieve_file_name($post_id){
    $url = retrieve_file_url($post_id);
    return wp_basename($url);
}

function editThisPostUrl($post_id){
    return get_edit_post_link($post_id);
}

$administrator = current_user_can( 'administrator' );
$note_handler = current_user_can('note-handler');

$canChangeRepertoire = $administrator || $note_handler;

$context         = Timber::context();
$timber_post     = Timber::get_post();
$context['canChangeRepertoire'] = $canChangeRepertoire;
$context['post'] = $timber_post;
Timber::render( 'views/single-repertoire.twig', $context );