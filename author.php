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

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['editUserInfo']))
{
    $userID = $_POST['userID'];
    $changeFirstNameTo = $_POST['firstName'];
    $changeLastNameTo = $_POST['lastName'];
    $changeEmailFrom = esc_attr( $_POST['currentEmail']);
    $changeEmailTo = esc_attr( $_POST['email']);
    $phoneNumber =  $_POST['phoneNumber'];
    $homeAddress = $_POST['homeAdress'];
    $isInSchool = $_POST['isInSchool'];
    
        if (isset( $changeEmailTo )) {
            // check if user is really updating the value
            if ( $changeEmailFrom  !=  $changeEmailTo ) {       
                // check if email is free to use
                if (email_exists( $changeEmailTo  )){
                    // Email exists, do not update value.
                    // Maybe output a warning.
                    echo '<script>alert("See e-mail on juba olemas")</script>';
                } else {
                    $changeEmailFrom == $changeEmailTo;
               }   
           }
        } 

        $userfield = 'user_' . $userID;
        // Update ACF phone number field for this user
        update_field('field_607d4cda3b47c', $phoneNumber, $userfield);
        // Update home address
        update_field('field_607d4cff3b47d', $homeAddress, $userfield);
        // Update if the user is still a student
        update_field('field_607d4c0d1af9d', $isInSchool, $userfield);


        $user_data = wp_update_user(array( 
            'ID' => $userID, 
            'first_name' => $changeFirstNameTo,
            'last_name' => $changeLastNameTo,
            'user_email' => esc_attr( $_POST['email'] )
        ));

    if ( is_wp_error( $user_data ) ) {
        // There was an error; possibly this user doesn't exist.
        echo '<script>alert("Error")</script>'; 
        echo 'Error.';
    } else {
        // Success!
        echo '<script>alert("User profile updated")</script>'; 
    }
}
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
        }else{
            echo '<script>alert("the new passwords did not match")</scipt>';
        }
    } else {
        echo '<script>alert("Wrong password")</scipt>';
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['managerEditsUserInfo'])){
    $userID = $_POST['userID'];
    $userfield = 'user_' . $userID;
    $voice =  $_POST['voice'];
    $userTitle = $_POST['userTitle'];
    $isBeginner = $_POST['is_beginner'];
    // Update ACF phone number field for this user
    update_field('field_607d4a411af9b', $voice, $userfield);
    // Update users title
    update_field('field_607d4af81af9c', $userTitle, $userfield);
    // Update if user is beginner field
    update_field('field_608fe31c8e06a', $isBeginner, $userfield);

}
// Form for editing user info in the users view.
function editUserRole($userID){
    $options = array(
        'post_id' => 'user_' . $userID,
        'field_groups' => array('group_607d4a30d0f6a'),
        'submit_value' => __("Muuda andmeid", 'acf'),
        'updated_message' => __("Andmed muudetud!", 'acf'),
        'html_submit_button'  => '<input type="submit" class="stylised-button submit-button" value="%s" />',
    );
    acf_form($options);
}

function editUserOrganisationalInfo(){
    $options = array(
    'post_id' => 'user_' . $userID,
    'field_groups' => array('group_607d4a30d0f6a'),
    'submit_value' => __("Salvesta", 'acf'),
    'updated_message' => __("Andmed muudetud!", 'acf'),
    'html_submit_button'  => '<input type="submit" class="user-info__save-button" value="%s" />',
    );
    acf_form($options);
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

function returnUserRoles($userRoles){
    $currentUserRoles= array();
    foreach ($userRoles as $role) {
        $currentUserRoles = translate_user_role($role);
    }
    return $currentUserRoles;
}


function currentUserWantsToCangePassword(){
    if(is_user_logged_in()){
        return true;
    }
}
function returnCurrentUserProfileLink(){
	$url = get_author_posts_url( get_current_user_id() );
		return $url;
    }

// Setting up who can edit user organisational info
$bookie = current_user_can( 'bookie' );
$president = current_user_can( 'president' );
$conductor = current_user_can('conductor');
$canEditUser = $president || $conductor || $bookie;


global $wp_query;

$context          = Timber::context();
$context['canEditUser'] = $canEditUser;
$context['posts'] = new Timber\PostQuery();
if ( isset( $wp_query->query_vars['author'] ) ) {
	$author            = new Timber\User( $wp_query->query_vars['author'] );
	$context['author'] = $author;
	$context['title']  = 'Author Archives: ' . $author->name();
}
Timber::render( array( 'views/author.twig', 'archive.twig' ), $context );