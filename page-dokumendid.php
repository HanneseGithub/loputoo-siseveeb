<?php
acf_form_head();
acf_enqueue_uploader();

function addNewDocument(){
  $options = array(
      'post_id' => 'new_post',
      'post_title'    => true,
      'new_post'		=> array(
              'post_type'		=> 'document',
              'post_status'	=> 'publish'
          ),
          'submit_value'  => __('Lisa uus dokument'),
          'html_submit_button'  => '<input type="submit" class="edit-post-button" value="%s" />',
      'updated_message' => __("Dokument on lisatud.", 'acf'),
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

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['deletePost']))
{
    wp_trash_post($_POST['ID']);
}

// Set rules for who can add edit and delete documents
$administrator = current_user_can( 'administrator' );
$president = current_user_can( 'president' );
$conductor = current_user_can('conductor');
$secretary = current_user_can('secretary');
$bookie = current_user_can('bookie');

$userCanEditDocuments = $administrator || $president || $conductor || $secretary;
$userCanSeeHiddenDocuments = $administrator || $president || $conductor || $secretary || $bookie;
$canAddNotifications = $administrator || $president || $conductor || $secretary;
$args = array(
  'post_type' => 'dokumendid',
  // Order by post date
  'orderby' => array(
      'date' => 'DESC'
  ));
$documents = Timber::get_posts($args);



function createNewPostUrl($post_type){
  $create_new_post_url_slug = 'post-new.php?post_type=' . $post_type;
  return $adminurl = admin_url($create_new_post_url_slug);
}

$context = Timber::context();

$context['createNewPostUrl'] = createNewPostUrl('dokumendid');
$context['documents'] = $documents;
$context['userCanEditDocuments'] = $userCanEditDocuments;
$context['userCanSeeHiddenDocuments'] = $userCanSeeHiddenDocuments;
$context['canAddNotifications'] = $canAddNotifications;
$timber_post     = new Timber\Post();
$context['post'] = $timber_post;
Timber::render( array( 'views/documents.twig', 'views/page.twig' ), $context );