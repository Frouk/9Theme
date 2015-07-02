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
		echo '</div>'; }else{?>

    <div id="popuppost">
      <a id="show_upload_url" href="javascript:void(0);">Upload via Url</a>
      <form id="PostUpload" method="post" action="#" enctype="multipart/form-data">
        <p><label>Title :</label><input type="text" id ="post-title" name="post-title" /></p>
	      <p><input type="file" name="my_image_upload" id="my_image_upload"  multiple="false" /></p>
	      <?php wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' ); ?>
	      <input id="submit_my_image_upload" name="submit_my_image_upload" type="submit" value="Upload" />
      </form>
    </div>
    <div id="popupposturl">
      <a id="show_upload" href="javascript:void(0);">Upload via file</a>
      <form id="PostUploadUrl" method="post" action="#" enctype="multipart/form-data">
        <p><label>Title :</label><input type="text" id ="post-title" name="post-title" /></p>
        <p><label>URL :</label><input type="text" id ="post-url" name="post-url" /></p>
        <?php wp_nonce_field( 'my_image_upload_url', 'my_image_upload_url_nonce' ); ?>
        <input id="submit_my_image_upload_url" name="submit_my_image_upload_url" type="submit" value="Upload" />
      </form>
    </div>

      <?php

      // Check that the nonce is valid, and the user can edit this post.
      if (isset( $_POST['my_image_upload_nonce'],$_POST['post-title'])	&& wp_verify_nonce( $_POST['my_image_upload_nonce'], 'my_image_upload' ))
      {
      	// These files need to be included as dependencies when on the front end.
      	require_once( ABSPATH . 'wp-admin/includes/image.php' );
      	require_once( ABSPATH . 'wp-admin/includes/file.php' );
      	require_once( ABSPATH . 'wp-admin/includes/media.php' );

      	// Let WordPress handle the upload.
      	// Remember, 'my_image_upload' is the name of our file input in our form above.
      	$attachment_id = media_handle_upload( 'my_image_upload', $_POST['post_id'] );

        if ( is_wp_error( $attachment_id ) ) {
      		// There was an error uploading the image.
      	} else {
            wp_insert_post( array(
      				'post_author'	=> $user_id,
      				'post_title'	=> $_POST['post-title'],
      				'post_type'     => 'post',
      				'post_content'	=> '<img src="'.wp_get_attachment_url($attachment_id).'"/>',
      				'post_status'	=> 'publish'
  				) );
          ?>
          <script type='text/javascript'>  window.location="/";</script>
        	<?php
        }

        } else {

        	// The security check failed, maybe show the user an error.
        }

        //URL

      if (isset( $_POST['my_image_upload_url_nonce'],$_POST['post-title'],$_POST['post-url'])	&& wp_verify_nonce( $_POST['my_image_upload_url_nonce'], 'my_image_upload_url' ))
      {
        	// These files need to be included as dependencies when on the front end.
        	require_once( ABSPATH . 'wp-admin/includes/image.php' );
        	require_once( ABSPATH . 'wp-admin/includes/file.php' );
        	require_once( ABSPATH . 'wp-admin/includes/media.php' );


            wp_insert_post( array(
        				'post_author'	=> $user_id,
        				'post_title'	=> $_POST['post-title'],
        				'post_type'     => 'post',
        				'post_content'	=> '<img src="'.$_POST['post-url'].'"/>',
        				'post_status'	=> 'publish'
    				) );
            ?>
            <script type='text/javascript'>
              window.location="/";
            </script>'
          	<?php


        } else {

          	// The security check failed, maybe show the user an error.
        }


    }?>




		<header id="top-nav">
			<div class="nav-wrap"><nav>
				<div id="logo"><li><a href=<?php echo get_bloginfo(wpurl) . ">" .get_bloginfo(); ?></a></li></div>
				<div id="menuz"><?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?></div>
				<div id="usercrap"><li>
					<?php if (!is_user_logged_in()) {echo '
						<a id="show_login" href="javascript:void(0);">Login</a>
						<a id="show_register" href="javascript:void(0);">Register</a>';
					}else{echo '
						<a id="show_upload" href="javascript:void(0);">Upload</a>
						<a id="Logout" href="';echo wp_logout_url();echo '">Logout</a>';
					} ?>
				</li></div>
			</nav></div>
		</header>
