<?php
acf_form_head();
$context = Timber::context();

// Display all the possible roles you want to see in the table.
$users = get_users( array( 'search' => '*' ) );

// Send an email when sending button is pressed.
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submitGroupEmail'])) {
    $websiteEmail = 'naiskoorintra@gmail.com';
    $subject = $_POST['subject'];
    $senderName = $_POST['sender-name'];
    $senderRole = $_POST['sender-role'];
    $recievers = $_POST['recievers'];

    $message = $_POST['message'];
    $messageToUs = $senderName . " saatis liikmetele järgneva e-maili:" . "\n\n" . $message . "\n\n" . ". E-mail saadeti järgmistele isikutele: " . $recievers;
    // Send email to website's inbox.
    wp_mail($websiteEmail,$subject,$messageToUs);
    // Send email to selected people's inbox.
    $sentGroupEmail = wp_mail($recievers, $subject, $message);

    $arrayOfRecievers = explode(',', $recievers);

    if (sizeof($arrayOfRecievers) == 1 && $sentGroupEmail) {
        $context['successfulEmail'] = true;
    } elseif (sizeof($arrayOfRecievers) > 1 && $sentGroupEmail) {
        $context['successfulMultipleEmail'] = true;
    }
}

// Form for editing user info in the users view.
function editUserRole($userID){
    $options = array(
        'post_id' => 'user_' . $userID,
        'field_groups' => array('group_606c616a0f5af'),
        'submit_value' => __("Salvesta", 'acf'),
        'updated_message' => __("Andmed muudetud!", 'acf'),
        'html_submit_button'  => '<input type="submit" class="user-info__save-button" value="%s" />',
    );
    acf_form($options);
}
function get_author_url($userID){
    $url  = get_author_posts_url($userID);
    return $url;
}

// Set rules for who can interact with the page
$administrator = current_user_can( 'administrator' );
$bookie = current_user_can( 'bookie' );
$president = current_user_can( 'president' );
$conductor = current_user_can('conductor');
$secretary = current_user_can('secretary');

$canSeePersonalId = $administrator || $president || $conductor || $bookie;
$canEditUserChoirRoles = $administrator || $conductor || $president;
$canSendGroupEmails = $administrator || $bookie || $president || $conductor;
$canAddEvents = $administrator || $secretary || $conductor;


if( isset($_GET['updated']) && $_GET['updated'] == 'true' ) {
    $context['successfulInfoUpdate'] = true;
}

$context['users'] = $users;
$context['isABookie'] = $bookie;
$context['canSeePersonalId'] = $canSeePersonalId;
$context['canEditUserChoirRoles'] = $canEditUserChoirRoles;
$context['canSendGroupEmails'] = $canSendGroupEmails;
$context['isAdministrator'] = $administrator;


$timber_post     = new Timber\Post();
$context['post'] = $timber_post;
Timber::render( array( 'views/users-page.twig', 'page.twig' ), $context );
