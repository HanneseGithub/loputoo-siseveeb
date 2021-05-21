<?php
acf_form_head();
acf_enqueue_uploader();

$context = Timber::get_context();

function retrieve_file_url( $number){
    $url = wp_get_attachment_url( $number );
    if(!$number){
        return "#";
    }else{
        return  $url;
    }
}
function retrieve_file_name($number){
    $url = retrieve_file_url($number);
    return wp_basename($url);
}

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['deletePost']))
{
    $nameOfDeletedSong = get_the_title($_POST['ID']);
    $context['nameOfDeletedSong'] = $nameOfDeletedSong;
    $deleteSong = wp_trash_post($_POST['ID']);

    if ($deleteSong) {
        $context['musicItemtrashed'] = true;
    }
}

function createNewPostUrl($post_type){
    $create_new_post_url_slug = 'post-new.php?post_type=' . $post_type;
    return $adminurl = admin_url($create_new_post_url_slug);
}
function editThisPostUrl($post_id){
    return get_edit_post_link($post_id);
}
// User roles
$administrator = current_user_can( 'administrator' );
$conductor = current_user_can('conductor');
$president = current_user_can( 'president' );
$note_handler = current_user_can('note-handler');

$canAddNotifications = $administrator || $president || $conductor || $note_handler;

$context['createNewPostUrl'] = createNewPostUrl('repertuaar');
$context['repertoire'] = Timber::get_posts( ['post_type' => 'repertuaar', 'posts_per_page' => -1] );
$context['canAddNotifications'] = $canAddNotifications;


Timber::render( array( 'views/repertoire-page.twig', 'views/page.twig' ), $context );
