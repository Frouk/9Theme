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
		<?php if (!is_user_logged_in()) {echo '
		<div id="popuplogin">';
			custom_login();
		echo '</div>
		<div id="popupregister">';
			custom_registration_function();
		echo '</div>';}?>
		<header id="top-nav">
			<div class="nav-wrap"><nav>
				<div id="logo"><li><a href=<?php echo get_bloginfo(wpurl) . ">" .get_bloginfo(); ?></a></li></div>
				<div id="menuz"><?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?></div>
				<div id="usercrap"><li>
					<?php if (!is_user_logged_in()) {echo '
						<a id="show_login" href="javascript:void(0);">Login</a>
						<a id="show_register" href="javascript:void(0);">Register</a>';
					}else{echo '
						<a id="show_settings" href="javascript:void(0);">My Profile</a>
						<a id="Logout" href="javascript:void(0);">Logout</a>';
					} ?>
				</li></div>
			</nav></div>
		</header>
		
