<?php

add_action( 'wp_login_failed', 'pu_login_failed' ); // hook failed login
function pu_login_failed($user) {
    // What page the login attempt is coming from
    $referrer = $_SERVER['HTTP_REFERER'];

    // Check that were not on the default login page
    if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $user!=null ) {
        // make sure we don't already have a failed login attempt
        if ( !strstr($referrer, '?login=failed' )) {
            // Redirect to the login page and append a querystring of login failed
            wp_redirect( $referrer . '?login=failed');
        } else {
            wp_redirect( $referrer );
        }

        exit;
    }
}

add_action( 'authenticate', 'pu_blank_login');
function pu_blank_login( $user ){
    // Check what page the login attempt is coming from
    $referrer = $_SERVER['HTTP_REFERER'];

    $error = false;
    if ($_POST['log'] == '' || $_POST['pwd'] == '') {
        $error = true;
    }

    // check that were not on the default login page
    if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') && $error ) {
        // make sure we don't already have a failed login attempt
        if ( !strstr($referrer, '?login=failed') ) {
            // Redirect to the login page and append a querystring of login failed
            wp_redirect( $referrer . '?login=failed' );
        } else {
            wp_redirect( $referrer );
        }
        exit;
    }
}

function custom_login() {
    global $user_login;
    if (isset($_GET['login']) && $_GET['login'] == 'failed') {
        echo'
            <script type="text/javascript">jQuery(document).ready(function($) {jQuery("#show_login").click();});</script>
            <div class="login_error">
                <p>Wrong Username and Password combination!</p>
            </div>
        ';
    }
    if (is_user_logged_in()) {
        echo 'Hello, ', $user_login, '. You are already logged in.<a id="wp-submit" href="', wp_logout_url(), '" title="Logout">Logout</a>';
    } else {
        $referrer = $_SERVER['HTTP_REFERER'];
        $args = array(
                    'echo'           => true,
                    'redirect'       => '/',
                    'form_id'        => 'loginform',
                    'label_username' => __( 'Username' ),
                    'label_password' => __( 'Password' ),
                    'label_remember' => __( 'Remember Me' ),
                    'label_log_in'   => __( 'Log In' ),
                    'id_username'    => 'user_login',
                    'id_password'    => 'user_pass',
                    'id_remember'    => 'rememberme',
                    'id_submit'      => 'wp-submit',
                    'remember'       => true,
                    'value_username' => NULL,
                    'value_remember' => true
                    );
        wp_login_form($args);
    }
}

function registration_form( $username, $password, $email ) {
    echo '
    <form action="' . home_url()  . '" method="post">
    <div>
    <label for="username">Username <strong>*</strong></label>
    <input type="text" name="username" value="' . ( isset( $_POST['username'] ) ? $username : null ) . '">
    </div>

    <div>
    <label for="password">Password <strong>*</strong></label>
    <input type="password" name="password" value="' . ( isset( $_POST['password'] ) ? $password : null ) . '">
    </div>

    <div>
    <label for="email">Email (Optional)</label>
    <input type="text" name="email" value="' . ( isset( $_POST['email']) ? $email : null ) . '">
    </div>


    <input type="submit" name="submit" value="Register"/>
    </form>
    ';
}

function registration_validation($username, $password, $email)  {
    global $reg_errors;
    $reg_errors = new WP_Error;

    if (empty($username) || empty($password)) {
        $reg_errors->add('field', 'Required form field is missing');
    }
    if (4 > strlen($username)) {
    $reg_errors->add('username_length', 'Username too short. At least 4 characters is required');
    }
    if (username_exists($username)) {
        $reg_errors->add('user_name', 'Sorry, that username already exists!');
    }
    if (!validate_username($username)) {
        $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
    }
    if (5 > strlen( $password)) {
        $reg_errors->add( 'password', 'Password length must be greater than 5' );
    }

    if (email_exists($email)) {
        $reg_errors->add( 'email', 'Email Already in use' );
    }
    if (is_wp_error($reg_errors)) {
        echo '<script type="text/javascript">jQuery(document).ready(function($) {jQuery("#show_register").click();});</script>';
        echo '<div class="login_error">';
        foreach ($reg_errors->get_error_messages() as $error) {
            echo '<p>'.$error . '</p>';
        }
        echo '</div>';
    }
}

function complete_registration() {
    global $reg_errors, $username, $password, $email;
    if (1 > count($reg_errors->get_error_messages())) {
        $userdata = array(
                        'user_login'    =>   $username,
                        'user_email'    =>   $email,
                        'user_pass'     =>   $password,
                    );
        $user = wp_insert_user($userdata);
    }
}

function custom_registration_function() {
    if (isset($_POST['submit'])) {
        registration_validation(
            $_POST['username'],
            $_POST['password'],
            $_POST['email']
        );

        // sanitize user form input
        global $username, $password, $email;
        $username   =   sanitize_user($_POST['username']);
        $password   =   esc_attr($_POST['password']);
        $email      =   sanitize_email($_POST['email']);

        // call @function complete_registration to create the user
        // only when no WP_error is found
        complete_registration(
            $username,
            $password,
            $email
        );
    }

    registration_form(
        $username,
        $password,
        $email
    );
}

function auto_login_new_user($user_id) {
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
    wp_redirect(home_url());
    exit;
}

add_action('user_register', 'auto_login_new_user');
add_action('init', 'do_output_buffer');
function do_output_buffer() {
    ob_start();
}

add_filter ('allow_password_reset', 'disable_password_reset');
function disable_password_reset() {
    return false;
}

?>
