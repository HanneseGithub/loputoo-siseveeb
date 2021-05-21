<?php
acf_form_head();
acf_enqueue_uploader();
$context = Timber::context();

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
    $nameOfDeletedDocument = get_the_title($_POST['ID']);
    $context['nameOfDeletedDocument'] = $nameOfDeletedDocument;
    wp_trash_post($_POST['ID']);
    $context['documentTrashed'] = true;
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
 'posts_per_page' => -1,
    'orderby' => array(
      'date' => 'DESC'
  ));
$documents = Timber::get_posts($args);



function createNewPostUrl($post_type){
  $create_new_post_url_slug = 'post-new.php?post_type=' . $post_type;
  return $adminurl = admin_url($create_new_post_url_slug);
}

function editThisPostUrl($post_id){
  return get_edit_post_link($post_id);
}

$context = Timber::context();

$context['createNewPostUrl'] = createNewPostUrl('dokumendid');
$context['documents'] = $documents;
$context['userCanEditDocuments'] = $userCanEditDocuments;
$context['userCanSeeHiddenDocuments'] = $userCanSeeHiddenDocuments;
$context['canAddNotifications'] = $canAddNotifications;
$timber_post     = new Timber\Post();
$context['post'] = $timber_post;
Timber::render( array( 'views/documents-page.twig', 'views/page.twig' ), $context );