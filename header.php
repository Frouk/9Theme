<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <title><?php wp_title(); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
        <?php wp_head(); ?>
    </head>

<body <?php body_class(); ?>>
<body>

		<header id="top-nav">
			<div class="nav-wrap"><nav>
				<div id="logo"><li><a href=<?php echo get_bloginfo(wpurl) . ">" .get_bloginfo(); ?></a></li></div>
				<div id="menuz"><?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?></div>
				<div id="usercrap"><li><a href=<?php echo get_bloginfo(wpurl) . ">" .get_bloginfo(); ?></a></li></div>
			</nav></div>
		</header>
		
