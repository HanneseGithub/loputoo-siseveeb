<?php
global $wp_query;

acf_form_head();
acf_enqueue_uploader();

$context = Timber::context();

// Setting up roles and role groups for use.
$bookie = current_user_can( 'bookie' );
$admin = current_user_can( 'administrator' );
$president = current_user_can( 'president' );
$conductor = current_user_can('conductor');
$authorCanEditUser = $president || $conductor || $bookie || $admin;
$authorCanSeePersonalId = $bookie || $admin;

// Author page - user information submit.
if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['editUserInfo'])) {
    $userID = $_POST['userID'];

    // Wordpress fields
    $incomingFirstName = $_POST['firstName'];
    $incomingLastName = $_POST['lastName'];
    $incomingEmailFrom = esc_attr($_POST['email']);

    // ACF fields
    $incomingPersonalId = $_POST['personal_id'];
    $incomingPhoneNumber = $_POST['phone_number'];
    $incomingVoice = $_POST['voice'];
    $incomingVoicePitch = $_POST['voice_pitch'];
    $incomingIsStudent = $_POST['is_student'];
    $incomingIsStudentOrGraduate = $_POST['is_student_or_graduate'];
    $incomingIsNewSinger = $_POST['is_new_singer'];
    $incomingTitle = $_POST['title'];
    $incomingRole = $_POST['role'];
    $incomingBirthday = $_POST['birthday'];

    if (isset($userID)) {
        $user = get_user_by('ID', $userID);

        $userfield = 'user_' . $userID;

        // Get user's data before the potential change.
        $currentFirstName = $user->first_name;
        $currentLastName = $user->last_name;
        $currentEmail = $user->user_email;

        $currentPersonalId = $user->personal_id;
        $currentPhoneNumber = $user->phone_number;
        $currentVoice = $user->voice;
        $currentVoicePitch = $user->voice_pitch;
        $currentIsStudent = $user->is_student;
        $currentIsStudentOrGraduate = $user->is_student_or_graduate;
        $currentIsNewSinger = $user->is_new_singer;
        $currentTitle = $user->title;
        $currentRole = implode(", ", $user->roles);
        $currentBirthday = $user->birthday;

        // Try updating field. IDEA: If it fails push it to errors array and show it in toast.

        // NÄIDE:
        $isInSchoolUpdate = update_field('field_6093c54e2b67d', $isInSchool, $userfield);

        if ($isInSchoolUpdate) {
            var_dump('Korras');
            die();
        }
    }

    // TEE ÜMBER user_email põhjal üleval funktsioonis. JUST IN CASE.
    if (isset($changeEmailTo)) {
        // Check if user wants to change account's email.
        if ($changeEmailFrom != $changeEmailTo) {
            // Check if new already exists on the site.
            if (email_exists($changeEmailTo)) {
                $context['emailAlreadyExists'] = true;
            } else {
                $changeEmailFrom == $changeEmailTo;
            }
        }
    }

    // Update user's phone number field.
    update_field('field_6093c62793e61', $phoneNumber, $userfield);
    // Update user's tudeng field.
    update_field('field_6093c54e2b67d', $isInSchool, $userfield);

    // Update user's WordPress related fields.
    $user_data = wp_update_user(array(
        'ID' => $userID,
        'first_name' => $changeFirstNameTo,
        'last_name' => $changeLastNameTo,
        'user_email' => esc_attr( $_POST['email'] )
    ));

    if ( is_wp_error( $user_data ) ) {
        $context['anErrorOccuredOnPersonalInfoUpdating'] = true;
    } else {
        $context['userChangedHisPersonalInfo'] = true;
    }
}

// Author page - user new password submit.
if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['editUserPassword'])) {
    $oldPassword = $_POST['oldPassword'];
    $newPassword1 = $_POST['newPassword1'];
    $newPassword2 = $_POST['newPassword2'];

    $userID = $_POST['userID'];
    $user = get_user_by( 'ID', $userID );
    $pass = $oldPassword;
    $hash =  $user->data->user_pass;
    $oldPasswordIsCorrect = wp_check_password( $pass, $hash, $userID );

    if ( $user && $oldPasswordIsCorrect ) {
        if($newPassword1 == $newPassword2){
            // Set new password and immediatly log in again.
            wp_set_password($newPassword1, $userID);
            wp_set_auth_cookie($user->ID);
            wp_set_current_user($user->ID);
            do_action('wp_login', $user->user_login, $user);
            $context['successfulInfoUpdate'] = true;
        }else{
            $context['passwordsDidNotMatch'] = true;
        }
    } else {
        $context['badPassword'] = true;
    }
}

$context['authorCanEditUser'] = $authorCanEditUser;
$context['isAdministrator'] = $admin;
$context['authorCanSeePersonalId'] = $authorCanSeePersonalId;
$context['posts'] = new Timber\PostQuery();
if ( isset( $wp_query->query_vars['author'] ) ) {
	$author            = new Timber\User( $wp_query->query_vars['author'] );
	$context['author'] = $author;
	$context['title']  = 'Author Archives: ' . $author->name();
}
Timber::render( array( 'views/author-page.twig', 'archive.twig' ), $context );
