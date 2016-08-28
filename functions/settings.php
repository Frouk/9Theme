<?php

// create custom plugin settings menu
add_action('admin_menu', 'my_cool_plugin_create_menu');

function my_cool_plugin_create_menu() {

	//create new top-level menu
	add_menu_page('9Theme Settings Page', '9Theme Settings', 'administrator', __FILE__, 'my_9theme_settings'  );

	//call register settings function
	add_action( 'admin_init', 'register_my_cool_plugin_settings' );
}


function register_my_cool_plugin_settings() {
	//register our settings
	register_setting( '9theme_settings-group', 'imgur_api_key' );
	register_setting( '9theme_settings-group', '9theme_max_file_size' );
	register_setting( '9theme_settings-group', '9theme_hidden_category' );
}

function my_9theme_settings() {
?>
<div class="wrap">
<h1>Your Plugin Name</h1>

<form method="post" action="options.php">
    <?php settings_fields( '9theme_settings-group' ); ?>
    <?php do_settings_sections( '9theme_settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Imgur Api Key</th>
        <td><input type="text" name="imgur_api_key" value="<?php echo esc_attr( get_option('imgur_api_key') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Max file size</th>
        <td><input type="text" name="9theme_max_file_size" value="<?php echo esc_attr( get_option('9theme_max_file_size') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Hidden Categories.</th>
        <td><input type="text" name="9theme_hidden_category" value="<?php echo esc_attr( get_option('9theme_hidden_category') ); ?>" /></td>
        </tr>
    </table>

    <?php submit_button(); ?>

</form>
</div>
<?php
}

?>
