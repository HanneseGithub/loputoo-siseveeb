<?php
/**
 * The template for displaying Author Archive pages
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */
acf_form_head();
acf_enqueue_uploader();

$context          = Timber::context();

// author-profile-info.twig
if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['editUserInfo']))
{
    $userID = $_POST['userID'];
    $changeFirstNameTo = $_POST['firstName'];
    $changeLastNameTo = $_POST['lastName'];
    $changeEmailFrom = esc_attr( $_POST['currentEmail']);
    $changeEmailTo = esc_attr( $_POST['email']);
    $phoneNumber =  $_POST['phoneNumber'];
    $isInSchool = $_POST['isInSchool'];
    
        if (isset( $changeEmailTo )) {
            // check if user is really updating the value
            if ( $changeEmailFrom  !=  $changeEmailTo ) {       
                // check if email is free to use
                if (email_exists( $changeEmailTo  )){
                    // Email exists, do not update value.
                    $context['emailAlreadyExists'] = true;
                } else {
                    $changeEmailFrom == $changeEmailTo;
               }   
           }
        } 

        $userfield = 'user_' . $userID;
        // Update ACF phone number field for this user
        update_field('field_6093c62793e61', $phoneNumber, $userfield);
        // Update if the user is is_student_or_graduate
        update_field('field_6093c54e2b67d', $isInSchool, $userfield);

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


// author-organization-info-edit.twig
function editUserRole($userID){
    $options = array(
        'post_id' => 'user_' . $userID,
        'field_groups' => array('group_607d4cd06f6b2'),
        'submit_value' => __("Muuda andmeid", 'acf'),
        'updated_message' => __("Andmed muudetud!", 'acf'),
        'html_submit_button'  => '<input type="submit" class="stylised-button submit-button" value="%s" />',
    );
    acf_form($options);
}
// On successful update of author info run this code
if( isset($_GET['updated']) && $_GET['updated'] == 'true' ) {
    $context['userOrganisationalInfoWasUpdated'] = true;    
}


// Setting up who can edit user organisational info
$bookie = current_user_can( 'bookie' );
$admin = current_user_can( 'administrator' );
$president = current_user_can( 'president' );
$conductor = current_user_can('conductor');
$authorCanEditUser = $president || $conductor || $bookie || $admin;
$authorCanSeePersonalId = $bookie || $admin;


global $wp_query;

// author-profile-edit-password.twig
if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['editUserPassword'])){
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