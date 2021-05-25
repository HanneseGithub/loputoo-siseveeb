<?php
global $wp_query;
global $wp_roles;
acf_form_head();

// Form for editing user avatar.
function editUsersAvatar($userID){
    $options = array(
        'post_id' => 'user_' . $userID,
        'fields' => array('field_60a6b62c55846'),
        'submit_value' => __("Salvesta profiilipilt", 'acf'),
        'updated_message' => false,
        'html_submit_button'  => '<input type="submit" class="author-submit-button h-margin-top-lg" value="%s" />',
    );
    acf_form($options);
}

$context = Timber::context();

// Setting up roles and role groups for use.
$bookie = current_user_can( 'bookie' );
$admin = current_user_can( 'administrator' );
$president = current_user_can( 'president' );
$conductor = current_user_can('conductor');
$authorCanSeePersonalId = $president || $conductor || $admin;

// Author page - user information submit.
if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['editUserInfo'])) {
    // Validate nonce
    if (isset($_POST['uform_generate_nonce']) && wp_verify_nonce($_POST['uform_generate_nonce'], 'submit_user_data')) {
        $userID = $_POST['userID'];
        $userUpdatingErrors = [];

        // Wordpress fields
        $incomingFirstName = sanitize_text_field($_POST['first_name']);
        $incomingLastName = sanitize_text_field($_POST['last_name']);
        $incomingEmail = sanitize_email($_POST['email']);

        // ACF fields that every user can edit
        $incomingPersonalId = sanitize_text_field($_POST['personal_id']);
        $incomingPhoneNumber = sanitize_text_field($_POST['phone_number']);
        $incomingBirthday = sanitize_text_field($_POST['birthday']);
        $incomingIsStudent = sanitize_text_field($_POST['is_student']);
        $incomingIsStudentOrGraduate = sanitize_text_field($_POST['is_student_or_graduate']);

        // ACF fields that specific roles can edit
        if (isset($_POST['voice_and_pitch'])) {
            $incomingVoiceAndPitch = sanitize_text_field($_POST['voice_and_pitch']);
        }
        if (isset($_POST['is_new_singer'])) {
            $incomingIsNewSinger = sanitize_text_field($_POST['is_new_singer']);
        }
        if (isset($_POST['in_reserve'])) {
            $incomingInReserve = sanitize_text_field($_POST['in_reserve']);
        }
        if(isset($_POST['title'])) {
            $incomingTitle = sanitize_text_field($_POST['title']);
        }
        if(isset($_POST['singing_mentor'])) {
            $incomingSingingMentor = sanitize_text_field($_POST['singing_mentor']);
        }
        if(isset($_POST['singing_apprentices'])) {
            $incomingSingingApprentices = sanitize_text_field($_POST['singing_apprentices']);
        }
        if(isset($_POST['role'])) {
            $incomingRole = sanitize_text_field($_POST['role']);
        }

        if (isset($userID)) {
            $user = get_user_by('ID', $userID);

            $userfield = 'user_' . $userID;

            // Get user's data before the potential change.
            $currentFirstName = $user->first_name;
            $currentLastName = $user->last_name;
            $currentEmail = $user->user_email;

            $currentPersonalId = $user->personal_id;
            $currentPhoneNumber = $user->phone_number;
            $currentBirthday = $user->birthday;
            $currentIsStudent = $user->is_student;
            $currentIsStudentOrGraduate = $user->is_student_or_graduate;

            $currentVoiceAndPitch = $user->voice_and_pitch;
            $currentIsNewSinger = $user->is_new_singer;
            $currentInReserve = $user->in_reserve;
            $currentTitle = $user->title;
            $currentSingingMentor = $user->singing_mentor;
            $currentSingingApprentices = $user->singing_apprentices;
            $currentRole = implode(", ", $user->roles);

            // Try updating first name field
            if ($currentFirstName != $incomingFirstName && isset($incomingFirstName)) {
                $updatingFirstName = wp_update_user(array(
                    'ID' => $userID,
                    'first_name' => $incomingFirstName
                ));

                if (is_wp_error($updatingFirstName)) {
                    $userUpdatingErrors[] = 'Eesnime uuendamine ebaõnnestus';
                }
            }

            // Try updating last name field
            if ($currentLastName != $incomingLastName && isset($incomingLastName)) {
                $updatingLastName = wp_update_user(array(
                    'ID' => $userID,
                    'last_name' => $incomingLastName
                ));

                if (is_wp_error($updatingLastName)) {
                    $userUpdatingErrors[] = 'Perenime uuendamine ebaõnnestus';
                }
            }

            // Try updating email field
            if ($currentEmail != $incomingEmail && isset($incomingEmail)) {
                if (email_exists($incomingEmail)) {
                    $userUpdatingErrors[] = 'E-mail on juba kasutuses';
                } else {
                    $updatingEmail = wp_update_user(array(
                        'ID' => $userID,
                        'user_email' => $incomingEmail
                    ));

                    if (is_wp_error($updatingEmail)) {
                        $userUpdatingErrors[] = 'E-maili uuendamine ebaõnnestus';
                    }
                }
            }

            // Try updating personal id field
            if ($currentPersonalId != $incomingPersonalId && isset($incomingPersonalId)) {
                $updatingPersonalId = update_field('field_607d4d0b3b47e', $incomingPersonalId, $userfield);

                if (!$updatingPersonalId) {
                    $userUpdatingErrors[] = 'Isikukoodi uuendamine ebaõnnestus';
                }
            }

            // Try updating phone number field
            if ($currentPhoneNumber != $incomingPhoneNumber && isset($incomingPhoneNumber)) {
                $updatingPhoneNumber = update_field('field_6093c62793e61', $incomingPhoneNumber, $userfield);

                if (!$updatingPhoneNumber) {
                    $userUpdatingErrors[] = 'Telefoninumbri uuendamine ebaõnnestus';
                }
            }

            // Try updating birthday field
            if ($currentBirthday != $incomingBirthday && isset($incomingBirthday)) {
                $updatingBirthday = update_field('field_60916d22a7bca', $incomingBirthday, $userfield);

                if (!$updatingBirthday) {
                    $userUpdatingErrors[] = 'Sünnipäeva uuendamine ebaõnnestus';
                }
            }

            // Try updating student field
            if ($currentIsStudent != $incomingIsStudent && isset($incomingIsStudent)) {
                $updatingIsStudent = update_field('field_609a50005a409', $incomingIsStudent, $userfield);

                if (!$updatingIsStudent) {
                    $userUpdatingErrors[] = 'Tudengi välja uuendamine ebaõnnestus';
                }
            }

            // Try updating seos Tartu Ülikooliga field
            if ($currentIsStudentOrGraduate != $incomingIsStudentOrGraduate && isset($incomingIsStudentOrGraduate)) {
                $updatingIsStudentOrGraduate = update_field('field_6093c54e2b67d', $incomingIsStudentOrGraduate, $userfield);

                if (!$updatingIsStudentOrGraduate) {
                    $userUpdatingErrors[] = 'Seos Tartu Ülikooliga välja uuendamine ebaõnnestus';
                }
            }

            // Try updating voice and pitch field
            if (isset($incomingVoiceAndPitch) && $currentVoiceAndPitch != $incomingVoiceAndPitch) {
                $updatingVoiceAndPitch = update_field('field_60916c26a42a7', $incomingVoiceAndPitch, $userfield);

                if (!$updatingVoiceAndPitch) {
                    $userUpdatingErrors[] = 'Häälerühma ja kõrguse välja uuendamine ebaõnnestus';
                }
            }

            // Try updating new singer field
            if (isset($incomingIsNewSinger) && $currentIsNewSinger != $incomingIsNewSinger) {
                $updatingIsNewSinger = update_field('field_60916e943fcaa', $incomingIsNewSinger, $userfield);

                if (!$updatingIsNewSinger) {
                    $userUpdatingErrors[] = 'Noorlaulja välja uuendamine ebaõnnestus';
                }
            }

            // Try updating in reserve field
            if (isset($incomingInReserve) && $currentInReserve != $incomingInReserve) {
                $updatingInReserve = update_field('field_60917028760e7', $incomingInReserve, $userfield);

                if (!$updatingInReserve) {
                    $userUpdatingErrors[] = 'Reservlase välja uuendamine ebaõnnestus';
                }
            }

            // Try updating title field
            if (isset($incomingTitle) && $currentTitle != $incomingTitle) {
                $updatingTitle = update_field('field_606d5009b2f7f', $incomingTitle, $userfield);

                if (!$updatingTitle) {
                    $userUpdatingErrors[] = 'Amet naiskooris välja uuendamine ebaõnnestus';
                }
            }

            // Try updating singing mentor field
            if (isset($incomingSingingMentor) && $currentSingingMentor != $incomingSingingMentor) {
                $updatingSingingMentor = update_field('field_60a92297a8d02', $incomingSingingMentor, $userfield);

                if (!$updatingSingingMentor) {
                    $userUpdatingErrors[] = 'Lauluema välja uuendamine ebaõnnestus';
                }
            }

            // Try updating singing apprentices field
            if (isset($incomingSingingApprentices) && $currentSingingApprentices != $incomingSingingApprentices) {
                $updatingSingingApprentices = update_field('field_60a9231eab2e4', $incomingSingingApprentices, $userfield);

                if (!$updatingSingingApprentices) {
                    $userUpdatingErrors[] = 'Laululaste välja uuendamine ebaõnnestus';
                }
            }

            // Try updating role field
            if (isset($incomingRole) && $currentRole != $incomingRole) {
                foreach ($wp_roles->roles as $key => $role) {
                    if ($incomingRole == $key) {
                        $updatingRole = wp_update_user(array(
                            'ID' => $userID,
                            'role' => $incomingRole
                        ));

                        if (is_wp_error($updatingRole)) {
                            $userUpdatingErrors[] = 'Siseveebi rolli uuendamine ebaõnnestus';
                        }
                    }
                }
            }

            if ( $userUpdatingErrors) {
                $context['errorsOccuredWhileUpdatingPersonalInfo'] = true;
                $context['updatingErrors'] = implode(', ', $userUpdatingErrors);
            } else {
                $context['userChangedHisPersonalInfo'] = true;
            }
        }
    }
}

// Author page - user new password submit.
if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['editUserPassword'])) {
    if (isset($_POST['oldPassword']) && isset($_POST['newPassword1']) && isset($_POST['newPassword2'])) {
        $oldPassword = $_POST['oldPassword'];
        $newPassword1 = $_POST['newPassword1'];
        $newPassword2 = $_POST['newPassword2'];

        if ($newPassword1 == $newPassword2) {
            $userID = $_POST['userID'];
            $user = get_user_by( 'ID', $userID );

            $oldPasswordHash =  $user->data->user_pass;
            $oldPasswordIsCorrect = wp_check_password($oldPassword, $oldPasswordHash, $userID);

            if ($user && $oldPasswordIsCorrect) {
                // Set new password and immediatly log in again.
                wp_set_password($newPassword1, $userID);
                wp_set_auth_cookie($user->ID);
                wp_set_current_user($user->ID);
                do_action('wp_login', $user->user_login, $user);
                $context['successfulPasswordUpdate'] = true;
            } else {
                $context['badPassword'] = true;
            }
        } else {
            $context['passwordsDidNotMatch'] = true;
        }
    }
}

$context['isAdministrator'] = $admin;
$context['authorCanSeePersonalId'] = $authorCanSeePersonalId;
$context['listOfRoles'] = $wp_roles->get_names();
$context['posts'] = new Timber\PostQuery();
if ( isset( $wp_query->query_vars['author'] ) ) {
	$author            = new Timber\User( $wp_query->query_vars['author'] );
	$context['author'] = $author;
	$context['title']  = 'Author Archives: ' . $author->name();
}
Timber::render( array( 'views/author-page.twig', 'archive.twig' ), $context );
