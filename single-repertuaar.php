<?php
acf_form_head();
acf_enqueue_uploader();

// Form for editing song info in the single-repertuaar view.
function editPostInfo($post_id){
    $options = array(
        'post_id' => $post_id,
        'post_title'    => true,
        'post_content'  => true,
        'submit_value'  => __('Muuda laulu infot'),
        'html_submit_button'  => '<input type="submit" class="edit-post-button" value="%s" />',
        'updated_message' => __("Laulu andmed on muudetud.", 'acf'),
        'html_updated_message'  => '<div id="message" class="updated"><p>%s</p></div>',
    );
    acf_form($options);
}


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

$context         = Timber::context();
$timber_post     = Timber::get_post();
$context['post'] = $timber_post;
Timber::render( 'views/single-repertuaar.twig', $context );