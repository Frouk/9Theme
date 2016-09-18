<?php

add_action('admin_menu', 'create_9theme_settings');

function create_9theme_settings() {
	// Add menu at wordpress dashboard
	add_menu_page('9Theme Settings Page', '9Theme Settings', 'administrator', __FILE__, 'my_9theme_settings'  );

	// Create database entries for settings
	add_action( 'admin_init', 'register_9theme_settings' );
}

function register_9theme_settings() {
	register_setting('9theme_settings-group', 'imgur_api_key');
	register_setting('9theme_settings-group', 'ajax_post_key');
	register_setting('9theme_settings-group', '9theme_hidden_category');
	register_setting('9theme_settings-group', '9theme_image_width');
	register_setting('9theme_settings-group', '9theme_watermark_url');
	register_setting('9theme_settings-group', '9theme_hidden_category');
}

function my_9theme_settings() {
	?>
	<div class="wrap">
	<h1>9Theme Settings</h1>

	<form method="post" action="options.php">
	    <?php settings_fields('9theme_settings-group'); ?>
	    <?php do_settings_sections('9theme_settings-group'); ?>
	    <table class="form-table">
	        <tr valign="top">
	        <th scope="row">Imgur Api Key</th>
	        <td><input type="text" name="imgur_api_key" value="<?php echo esc_attr(get_option('imgur_api_key')); ?>" /></td>
	        </tr>

			<tr valign="top">
	        <th scope="row">Ajax Post Key</th>
	        <td><input type="text" name="ajax_post_key" value="<?php echo esc_attr(get_option('ajax_post_key')); ?>" /></td>
	        </tr>

			<tr valign="top">
	        <th scope="row">Image Width</th>
	        <td><input type="text" name="9theme_image_width" value="<?php echo esc_attr(get_option('9theme_image_width')); ?>" /></td>
	        </tr>

	        <tr valign="top">
	        <th scope="row">Max file size</th>
	        <td><input type="text" name="9theme_max_file_size" value="<?php echo esc_attr(get_option('9theme_max_file_size')); ?>" /></td>
	        </tr>

			<tr valign="top">
	        <th scope="row">Watermark Url.</th>
	        <td><input type="text" name="9theme_watermark_url" value="<?php echo esc_attr(get_option('9theme_watermark_url')); ?>" /></td>
	        </tr>

	        <tr valign="top">
	        <th scope="row">Hidden Categories.</th>
	        <td><input type="text" name="9theme_hidden_category" value="<?php echo esc_attr(get_option('9theme_hidden_category')); ?>" /></td>
	        </tr>
	    </table>

	    <?php submit_button(); ?>

	</form>
	</div>
	<?php
}

?>
