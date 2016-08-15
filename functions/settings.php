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
	register_setting( '9theme_settings-group', 'some_other_option' );
	register_setting( '9theme_settings-group', 'option_etc' );
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
        <th scope="row">Some Other Option</th>
        <td><input type="text" name="some_other_option" value="<?php echo esc_attr( get_option('some_other_option') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Options, Etc.</th>
        <td><input type="text" name="option_etc" value="<?php echo esc_attr( get_option('option_etc') ); ?>" /></td>
        </tr>
    </table>

    <?php submit_button(); ?>

</form>
</div>
<?php
}

// add the admin options page
/*add_action('admin_menu', 'plugin_admin_add_page');
function plugin_admin_add_page() {
    add_options_page('Custom Plugin Page', 'Custom Plugin Menu', 'manage_options', 'plugin', 'plugin_options_page');
}
function plugin_options_page() {
    ?>
    <div>
    <h2>My custom plugin</h2>
    Options relating to the Custom Plugin.
    <form action="options.php" method="post">
    <?php settings_fields('plugin_options'); ?>
    <?php do_settings_sections('plugin'); ?>

    <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
    </form></div>

    <?php
    echo get_option('plugin_options')['text_string'];
}

add_action('admin_init', 'plugin_admin_init');
function plugin_admin_init(){
    register_setting( 'plugin_options', 'plugin_options', 'plugin_options_validate' );
    add_settings_section('plugin_main', 'Main Settings', 'plugin_section_text', 'plugin');
    add_settings_field('plugin_text_string', 'Plugin Text Input', 'plugin_setting_string', 'plugin', 'plugin_main');
}
function plugin_setting_string() {
    $options = get_option('plugin_options');
    echo "<input id='plugin_text_string' name='plugin_options[text_string]' size='40' type='text' value='{$options['text_string']}' />";
}

function plugin_options_validate2($input) {
    $newinput['text_string'] = trim($input['text_string']);
    if(!preg_match('/^[a-z0-9]{32}$/i', $newinput['text_string'])) {
        $newinput['text_string'] = '';
    }
    return $newinput;
}

function plugin_options_validate($input) {
    $options = get_option('plugin_options');
    $options['text_string'] = trim($input['text_string']);
    if(!preg_match('/^[a-z0-9]{32}$/i', $options['text_string'])) {
        $options['text_string'] = '';
    }
return $options;
}*/
?>
