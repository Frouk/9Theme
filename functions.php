<?php
	
	
	function load_shit(){
		wp_enqueue_script( 'function', get_template_directory_uri().'/majavascript.js', 'jquery', true);
		wp_localize_script( 'function', 'my_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}
	add_action('template_redirect', 'load_shit');

	add_action("wp_ajax_nopriv_domyshit", "domyshit");
	add_action("wp_ajax_domyshit", "domyshit");
	
	
	function domyshit(){
		
		$var = get_current_user_id();
		addtest($var,666);
		
		die();
	}
	
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
	function addtest($user,$id){
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		
		$table_name = $wpdb->prefix . "updownvotes"; 
		
		$sql = "INSERT INTO wp_updownvotes ()
				VALUES ($user,$id,1);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
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

	add_filter('show_admin_bar', '__return_false');
	

	//restricts access to admin area
	function restrict_admin()
	{
		if ( ! current_user_can( 'manage_options' ) && '/wp-admin/admin-ajax.php' != $_SERVER['PHP_SELF'] ) {
			wp_redirect( site_url() );
		}
	}
	add_action( 'admin_init', 'restrict_admin', 1 );
?>
