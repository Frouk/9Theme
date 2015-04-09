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
	
	function mh_load_my_script() {
		wp_enqueue_script( 'jquery' );
	}
	add_action( 'wp_enqueue_scripts', 'mh_load_my_script' );
	
	
	
	//Login shit
	
	add_action( 'wp_login_failed', 'pu_login_failed' ); // hook failed login

	function pu_login_failed( $user ) {
		// check what page the login attempt is coming from
		$referrer = $_SERVER['HTTP_REFERER'];

		// check that were not on the default login page
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
		// check what page the login attempt is coming from
		$referrer = $_SERVER['HTTP_REFERER'];

		$error = false;

		if($_POST['log'] == '' || $_POST['pwd'] == '')
		{
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
		if(isset($_GET['login']) && $_GET['login'] == 'failed')
			{
				echo'	
					<script type="text/javascript">jQuery(document).ready(function($) {jQuery("#show_login").click();});</script>
					<div class="login_error">
						<p>FAILED: Try again!</p>
					</div>
				';
			}
            if (is_user_logged_in()) {
                echo 'Hello, ', $user_login, '. You are already logged in.<a id="wp-submit" href="', wp_logout_url(), '" title="Logout">Logout</a>';
            } else {
					$referrer = $_SERVER['HTTP_REFERER'];
                    $args = array(
                                'echo'           => true,
                                'redirect'       => $referrer, 
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
	//Register user shit
	
	
	function registration_form( $username, $password, $email ) {
		
		echo '
		<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
		<div>
		<label for="username">Username <strong>*</strong></label>
		<input type="text" name="username" value="' . ( isset( $_POST['username'] ) ? $username : null ) . '">
		</div>
		 
		<div>
		<label for="password">Password <strong>*</strong></label>
		<input type="password" name="password" value="' . ( isset( $_POST['password'] ) ? $password : null ) . '">
		</div>
		 
		<div>
		<label for="email">Email <strong>*</strong></label>
		<input type="text" name="email" value="' . ( isset( $_POST['email']) ? $email : null ) . '">
		</div>
		
		
		<input type="submit" name="submit" value="Register"/>
		</form>
		';
	}
	function registration_validation( $username, $password, $email )  {
		global $reg_errors;
		$reg_errors = new WP_Error;
		
		if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
			$reg_errors->add('field', 'Required form field is missing');
		}
		if ( 4 > strlen( $username ) ) {
		$reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
		}
		if ( username_exists( $username ) ){
			$reg_errors->add('user_name', 'Sorry, that username already exists!');
		}
		if ( ! validate_username( $username ) ) {
			$reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
		}
		if ( 5 > strlen( $password ) ) {
			$reg_errors->add( 'password', 'Password length must be greater than 5' );
		}
		if ( !is_email( $email ) ) {
			$reg_errors->add( 'email_invalid', 'Email is not valid' );
		}
		if ( email_exists( $email ) ) {
			$reg_errors->add( 'email', 'Email Already in use' );
		}
		if ( is_wp_error( $reg_errors ) ) {
			echo '<script type="text/javascript">jQuery(document).ready(function($) {jQuery("#show_register").click();});</script>';
			foreach ( $reg_errors->get_error_messages() as $error ) {
			 
				echo '<div>';
				echo '<strong>ERROR</strong>:';
				echo $error . '<br/>';
				echo '</div>';
				 
			}
		}
	}
	function complete_registration() {
		global $reg_errors, $username, $password, $email;
		if ( 1 > count( $reg_errors->get_error_messages() ) ) {
			$userdata = array(
			'user_login'    =>   $username,
			'user_email'    =>   $email,
			'user_pass'     =>   $password,
			);
			$user = wp_insert_user( $userdata );
			echo 'Registration complete. Goto <a href="' . get_site_url() . '/wp-login.php">login page</a>.';   
		}
	}
	
	function custom_registration_function() {
		if ( isset($_POST['submit'] ) ) {
			registration_validation(
			$_POST['username'],
			$_POST['password'],
			$_POST['email']
			);
			 
			// sanitize user form input
			global $username, $password, $email;
			$username   =   sanitize_user( $_POST['username'] );
			$password   =   esc_attr( $_POST['password'] );
			$email      =   sanitize_email( $_POST['email'] );
	 
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
?>
