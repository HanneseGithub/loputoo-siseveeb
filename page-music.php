<?php
$context = Timber::get_context();

function retrieve_file_url(int $number){
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

function createNewPostUrl($post_type){
    $create_new_post_url_slug = 'post-new.php?post_type=' . $post_type;
    return $adminurl = admin_url($create_new_post_url_slug);
}

$context['createNewPostUrl'] = createNewPostUrl('repertoire');
$context['repertoire'] = Timber::get_posts( ['post_type' => 'repertoire'] );


Timber::render( array( 'views/music.twig', 'views/page.twig' ), $context );
