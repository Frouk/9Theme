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
        <div id="popuplogin">
      <h1>Log-in</h1><br>

';
            custom_login();
        echo '<div class="login-help">
      <a id="show_register"href="javascript:void(0);">Register</a> • <a href="#">Forgot Password</a>
    </div></div>
        <div id="popupregister">
      <h1>Register</h1><br>';
            custom_registration_function();
          echo '
      <div class="login-help">
        <a id="show_login"href="javascript:void(0);">Log-in</a>
      </div>
    </div>'; }else{?>

    <div id="popuppost">
      <form id="PostUpload" method="post" action="#" enctype="multipart/form-data">
        <div id="formtitle">Upload an Image</div>
        <?php manageuploadsplugin();?>
        <label>Title</label>
        <p><input type="text" id ="post-title" name="post-title" /></p>
        <label>File</label>
          <p>
          <input type="file" name="my_image_upload" id="my_image_upload" accept="image/gif,image/png,image/jpeg"/>
          <a id="show_upload_url" href="javascript:void(0);">Upload via Url</a>
        </p>
          <?php wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' ); ?>
          <input id="submit_my_image_upload" name="submit_my_image_upload" type="submit" value="Upload" />
      </form>
    </div>
    <div id="popupposturl">
      <form id="PostUpload" method="post" action="#" enctype="multipart/form-data">
        <div id="formtitle">Upload an Image</div>
        <label>Title</label>
        <p><input type="text" id ="post-title" name="post-title" /></p>
        <label>File Url</label>
        <p>
          <input type="text" id ="post-url" name="post-url" />
          <a id="show_upload" href="javascript:void(0);">Upload via local file</a>
        </p>
        <?php wp_nonce_field( 'my_image_upload_url', 'my_image_upload_url_nonce' ); ?>
        <input id="submit_my_image_upload_url" name="submit_my_image_upload_url" type="submit" value="Upload" />
      </form>
    </div>
    <?php }?>


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
                        <a id="Logout" href="';echo wp_logout_url(home_url());echo '">Logout</a>';
                    } ?>
                </li></div>
            </nav></div>
        </header>
