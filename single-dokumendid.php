<?php
acf_form_head();
acf_enqueue_uploader();

// Form for editing song info in the single-repertuaar view.
function editPostInfo($post_id){
    function my_acf_prepare_field($field) {
        $field['label'] = 'Dokumendi pealkiri';
           return $field;
    }
    add_filter('acf/prepare_field/name=_post_title', 'my_acf_prepare_field');

    $options = array(
        'post_id' => $post_id,
        'post_title'    => true,
        'submit_value'  => __('Muuda dokumendi infot'),
        'html_submit_button'  => '<input type="submit" class="edit-post-button" value="%s" />',
        'updated_message' => __("Dokumendi andmed on muudetud.", 'acf'),
        'html_updated_message'  => '<div id="message" class="updated"><p>%s</p></div>',
    );
    
    acf_form($options);
}


function returnDocumentsUrl(){
    $url =  get_site_url() . '/dokumendid';
    return $url;
}

// Set rules for who can add and edit documents
$administrator = current_user_can( 'administrator' );
$bookie = current_user_can( 'bookie' );
$president = current_user_can( 'president' );
$conductor = current_user_can('conductor');
$secretary = current_user_can('secretary');
$noteHandler = current_user_can('note_handler');


$userCanEditDocuments = $administrator || $bookie || $president || $conductor || $secretary || $noteHandler;


$context         = Timber::context();
$timber_post     = Timber::get_post();
$context['userCanEditDocuments'] = $userCanEditDocuments;
$context['post'] = $timber_post;
Timber::render( 'views/single-documents.twig', $context );