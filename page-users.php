<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/templates/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

 // Display all the possible roles you want to see in the table
$users = get_users( [ 'role__in' => [ 
    'singer',
    'choirBoss',
    'bookie',
    'conductor',
    'secretary',
    'noteHandler',
    'subscriber', 
    'author' 
    ] ] );

if(isset($_POST['submit'])){
    // First send a control, e-mail to your own account;
    $websiteEmail = 'naiskoorintra@gmail.com'; // this is your website Email address
    $subject = $_POST['subject'];
    $senderName = $_POST['sender-name'];
    $senderRoles = $_POST['sender-roles'];
    $recievers = $_POST['recievers'];
    $message = $_POST['message'];
    $messageToUs = $senderName . "with a ." . $senderRoles. ". e-mailed the following:" . "\n\n" . $message . "\n\n" . "to the following recievers: " . $recievers;
    $messageToFrontend = $senderName . " with the role of " . $senderRoles[0] ." successfully sent e-mails";
    mail($websiteEmail,$subject,$messageToUs);
    mail($recievers,$subject,$message);
    }


// Calculate birthday
function calculateBirthday($Personal_ID){
    $century = 0;
    $centuryIdentificator = (int) substr($Personal_ID, 0, 1);
    if ($centuryIdentificator < 3) {
        $century = 18;
    } elseif ($centuryIdentificator > 2 && $centuryIdentificator < 5) {
        $century = 19;
    } elseif ($centuryIdentificator > 4 && $centuryIdentificator < 7) {
        $century = 20;
    }
    $PersonalIdBirthInfo =  substr($Personal_ID, 1, 6);
    $birthYear = substr($PersonalIdBirthInfo, 0, 2);
    $birthMonth = substr($PersonalIdBirthInfo, 2, 2);
    $birthDay = substr($PersonalIdBirthInfo, 4, 2);


    $birthDate = $birthDay .'. '. $birthMonth . '. '  . $century . $birthYear;

    return $birthDate;
}

function returnUserRoles($userRoles){
    $currentUserRoles= array();
    foreach ($userRoles as $role) {
        $currentUserRoles = $role;
    }
    return $currentUserRoles;
}

// Set rules for who can interact with the page
$bookie = current_user_can( 'bookie' );
$choirBoss = current_user_can( 'choirBoss' );
$conductor = current_user_can('conductor');
$canSendGroupEmails = $bookie || $choirBoss || $conductor;


$context = Timber::context();

$context['users'] = $users;
$context['isABookie'] = $bookie;
$context['choirBoss'] = $choirBoss;
$context['conductor'] = $conductor;
$context['canSendGroupEmails'] = $canSendGroupEmails;

$timber_post     = new Timber\Post();
$context['post'] = $timber_post;
Timber::render( array( 'views/' . $timber_post->post_name . '.twig', 'page.twig' ), $context );
