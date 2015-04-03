<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <title><?php wp_title(); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
        <?php wp_enqueue_script("jquery"); ?>
        <?php wp_head(); ?>
    </head>

<body <?php body_class(); ?>>

		<div id="popup">
		 <?php global $user_login;

			if(isset($_GET['login']) && $_GET['login'] == 'failed')
			{
				?>
					<div class="aa_error">
						<p>FAILED: Try again!</p>
					</div>
				<?php
			}
            if (is_user_logged_in()) {
                echo 'Hello, ', $user_login, '. You are already logged in.<a id="wp-submit" href="', wp_logout_url(), '" title="Logout">Logout</a>';
            } else {
                    $args = array(
                                'echo'           => true,
                                'redirect'       => home_url('/wp-admin/'), 
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

        ?> 
		</div>
		
		<header id="top-nav">
			<div class="nav-wrap"><nav>
				<div id="logo"><li><a href=<?php echo get_bloginfo(wpurl) . ">" .get_bloginfo(); ?></a></li></div>
				<div id="menuz"><?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?></div>
				<div id="usercrap"><li><a href=<?php echo get_bloginfo(wpurl) . ">" .get_bloginfo(); ?></a></li></div>
			</nav></div>
		</header>
		
