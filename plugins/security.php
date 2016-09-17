<?php

// Removes detailed login error information
add_filter('login_errors',create_function('$a', "return null;"));

// Removes WordPress Version from header
function wb_remove_version() {
    return '<!--built with Drupal-->';
}
add_filter('the_generator', 'wb_remove_version');

// Removes admin bar
add_filter('show_admin_bar', '__return_false');

// Restricts access to admin area
function restrict_admin() {
    if ( ! current_user_can( 'manage_options' ) && '/wp-admin/admin-ajax.php' != $_SERVER['PHP_SELF']) {
        wp_redirect( site_url() );
    }
}
add_action( 'admin_init', 'restrict_admin', 1 );
?>
