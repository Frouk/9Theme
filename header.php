<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
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

		<div id="popuplogin">
		 <?php global $user_login;
			
			if(isset($_GET['login']) && $_GET['login'] == 'failed')
			{
				?>	
					<script type="text/javascript">jQuery(document).ready(function($) {jQuery("#show_login").click();});</script>
					<div class="login_error">
						<p>FAILED: Try again!</p>
					</div>
				<?php
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
			
			//custom_registration_function();
        ?> 
		</div>
		<div id="popupregister">
			<?php custom_registration_function(); ?>
		</div>
		<header id="top-nav">
			<div class="nav-wrap"><nav>
				<div id="logo"><li><a href=<?php echo get_bloginfo(wpurl) . ">" .get_bloginfo(); ?></a></li></div>
				<div id="menuz"><?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?></div>
				<div id="usercrap"><li>
					<a id="show_login" href="javascript:void(0);">Login</a>
					<a id="show_register" href="javascript:void(0);">Register</a>
				</li></div>
			</nav></div>
		</header>
		
