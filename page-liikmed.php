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

// Calculate birthday.
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

// Form for editing user info in the users view.
function editUserRole($userID){
    $options = array(
        'post_id' => 'user_' . $userID,
        'field_groups' => array('group_607d4a30d0f6a'),
        'submit_value' => __("Muuda andmeid", 'acf'),
        'updated_message' => __("Andmed muudetud!", 'acf'),
        'html_submit_button'  => '<input type="submit" class="save-button" value="%s" />',
    );
    acf_form($options);
}

// Set rules for who can interact with the page
$administrator = current_user_can( 'administrator' );
$bookie = current_user_can( 'bookie' );
$president = current_user_can( 'president' );
$conductor = current_user_can('conductor');
$canEditUserChoirRoles = $administrator || $president || $conductor || $bookie;
$canSendGroupEmails = $administrator || $bookie || $president || $conductor;

$context = Timber::context();

$context['users'] = $users;
$context['isABookie'] = $bookie;
$context['canEditUserChoirRoles'] = $canEditUserChoirRoles;
$context['canSendGroupEmails'] = $canSendGroupEmails;

$timber_post     = new Timber\Post();
$context['post'] = $timber_post;
Timber::render( array( 'views/users-page.twig', 'page.twig' ), $context );
