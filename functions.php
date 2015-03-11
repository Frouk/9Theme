<?php
	
	add_action("after_switch_theme", "createtablez");
	
	function createtablez(){
		
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		
		$table_name = $wpdb->prefix . "updownvotes"; 
		
		$sql = "CREATE TABLE $table_name (
		  user_id bigint(20) NOT NULL,
		  post_id bigint(20) NOT NULL,
		  upvote tinyint(1) NOT NULL,
		  UNIQUE KEY keyid (user_id,post_id),  
		  KEY user_id (user_id),
		  KEY post_id (post_id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
		
	function peos(){
		?><p>yolo</p> <?php      	
	}


	// custom menu support

	add_theme_support( 'menus' );

	if ( function_exists( 'register_nav_menus' ) ) {

	  	register_nav_menus(

	  		array(

	  		  'header-menu' => 'Header Menu',

	  		  'sidebar-menu' => 'Sidebar Menu',

	  		  'footer-menu' => 'Footer Menu',

	  		  'logged-in-menu' => 'Logged In Menu'

	  		)

	  	);

	}


	// removes detailed login error information for security
	add_filter('login_errors',create_function('$a', "return null;"));
	// removes the WordPress version from your header for security
	function wb_remove_version() {
		return '<!--built on the Whiteboard Framework-->';
	}
	add_filter('the_generator', 'wb_remove_version');


?>
