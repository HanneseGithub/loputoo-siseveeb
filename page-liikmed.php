<?php
acf_form_head();

// Display all the possible roles you want to see in the table.
$users = get_users(['role__in' => [
    'administrator',
    'singer',
    'bookie',
    'conductor',
    'president',
    'secretary',
    'note-handler',
]]);

// Send email to selected people and yourself.
if (isset($_POST['submit'])) {
    $websiteEmail = 'naiskoorintra@gmail.com';
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

// Set rules for who can interact with the page
$administrator = current_user_can( 'administrator' );
$bookie = current_user_can( 'bookie' );
$president = current_user_can( 'president' );
$conductor = current_user_can('conductor');

$canSeePersonalId = $administrator || $president || $conductor;
$canEditUserChoirRoles = $administrator;
$canSendGroupEmails = $administrator || $bookie || $president || $conductor;

$context = Timber::context();

$context['users'] = $users;
$context['isABookie'] = $bookie;
$context['isAdministrator'] = $administrator;
$context['canSeePersonalId'] = $canSeePersonalId;
$context['canEditUserChoirRoles'] = $canEditUserChoirRoles;
$context['canSendGroupEmails'] = $canSendGroupEmails;

$timber_post     = new Timber\Post();
$context['post'] = $timber_post;
Timber::render( array( 'views/users-page.twig', 'page.twig' ), $context );
