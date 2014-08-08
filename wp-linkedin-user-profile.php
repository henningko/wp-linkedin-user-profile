<?php
/*
  Plugin Name: WP LinkedIn User Profile
  Plugin URI: https://github.com/henningko/wp-linkedin-user-profile
  Description: Adds your LinkedIn profile to your user profile.
  Version: 0.1
  Author: Henning Kollenbroich
  Author URI: http://henningko.tumblr.com
  License: GPLv2 or later
 */

global $options, $user_options, $uri, $state;


function linkedin_user_profile_get_data( $user ) {
    $options = get_option( 'linkedin_settings' );
    $user_options = get_user_meta( $user->ID, 'linkedin' );
    $uri = explode("?", 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
    $uri = $uri[0];
    $state = '564e849';

    # Check if we are coming back from the redirect
    if ( !empty( $_GET['code'] ) && $state == $_GET['state'] ) {
        $auth_code = $_GET['code'];
        # get current URL for redirect
        $curl = curl_init();
        curl_setopt_array($curl,
            array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => "https://www.linkedin.com/uas/oauth2/accessToken",
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => "grant_type=authorization_code&code={$auth_code}
                    &redirect_uri={$uri}&client_id={$options['linkedin_api_key']}
                    &client_secret={$options['linkedin_app_secret']}"
            )
        );

        # Send the request & save response to $resp
        $resp = curl_exec($curl);
        print_r($resp);
        curl_close($curl);
        # Save response in object
        $resp = json_decode($resp);
        if ( !empty($resp->access_token) && !empty( $resp->expires_in ) ) {

            $user_options->access_token = $resp->access_token;
            $user_options->access_token_expires = time() + $resp->expires_in;
            print_r($user_options);
            # Save access token and expiry date
            update_user_meta( $user->ID, 'linkedin', $user_options );

            # Get user data
            $user_options->data = get_linkedin_data( $user_options->access_token, '(id,public-profile-url,first-name,last-name,headline,skills,positions,languages)' );

            # save to user meta
            update_user_meta( $user->ID, 'linkedin', $user_options);
        }
        # else: bad result
        else {
            wp_die( 'Cannot retrieve data from LinkedIn.' );
        }
    } //end if auth-code

    if ( !empty( $user_options->access_token ) ) {
        // $user_options['id'] = $linkedin_data->id;
        // $user_options['first_name'] = $linkedin_data->firstName;
        // $user_options['last_name'] = $linkedin_data->lastName;
        // $user_options['headline'] = $linkedin_data->headline;
        // $user_options['url'] = $linkedin_data->publicProfileUrl;
    } //!empty( $user_options->access_token )

    require 'inc/profile_page_wrapper.php';
} // end function

add_action('show_user_profile', 'linkedin_user_profile_get_data'); // Hook display of auth button when OWN profile is shown.

/*
Get data from LinkedIn, return as object
*/
function get_linkedin_data($access_token, $scope) {
    $curl = curl_init();
    curl_setopt_array($curl,
        array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "https://api.linkedin.com/v1/people/~:{$scope}?oauth2_access_token={$access_token}&format=json"
        )
    );
    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    echo $resp;
    // Close request to clear up some resources
    curl_close($curl);
    // JSON Decode
    if ( false != $resp ) {
        $resp = json_decode($resp);
        return $resp;
    }
    # if request fails
    else {
        wp_die( 'Cannot retrieve data from LinkedIn.' );
    }
}

// function show_linkedin_userdata( $user ) {

// }
// add_action('show_user_profile', 'linkedin_user_profile_get_data');


 /*
 * Add admin  panel
 */
function linkedin_user_profile_menu() {
    add_options_page(' LinkedIn User Profie', 'LinkedIn User Profile','manage_options', 'linkedin-user-profile', 'linkedin_user_profile_options_page');
}
add_action('admin_menu', 'linkedin_user_profile_menu' );

/*
* Show options page
*/
function linkedin_user_profile_options_page() {
    if( !current_user_can( 'manage_options' ) ) {
        wp_die( 'You have insufficient credentials to access site' );
    }


    if( true == $_POST['linkedin_submitted']  && !empty( $_POST['linkedin_api_key'] )  && !empty( $_POST['linkedin_app_secret'] ) ) {
        $options['linkedin_api_key'] = sanitize_text_field( $_POST['linkedin_api_key'] );
        $options['linkedin_app_secret'] = sanitize_text_field( $_POST['linkedin_app_secret'] );
        update_option( 'linkedin_settings', $options );
    }

    $options = get_option( 'linkedin_settings' );
    require( 'inc/options_page_wrapper.php' );
}


?>
