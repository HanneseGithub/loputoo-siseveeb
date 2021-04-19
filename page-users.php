<?php

 // Display all the possible roles you want to see in the table
$users = get_users( [ 'role__in' => [ 
    'singer',
    'president',
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
        $currentUserRoles = translate_user_role($role);
    }
    return $currentUserRoles;
}

// Set rules for who can interact with the page
$bookie = current_user_can( 'bookie' );
$president = current_user_can( 'president' );
$conductor = current_user_can('conductor');
$canSendGroupEmails = $bookie || $president || $conductor;


$context = Timber::context();

$context['users'] = $users;
$context['isABookie'] = $bookie;
$context['president'] = $president;
$context['conductor'] = $conductor;
$context['canSendGroupEmails'] = $canSendGroupEmails;

$timber_post     = new Timber\Post();
$context['post'] = $timber_post;
Timber::render( array( 'views/' . $timber_post->post_name . '.twig', 'page.twig' ), $context );
