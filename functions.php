<?php
    require 'functions/user.php';
    require 'functions/uploads.php';
    require 'functions/upvotes.php';
    require 'functions/settings.php';

    //echo getIt();
    //DEBUGGING


    function mytheme_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        ?>
            <div id="comment-entry">
                <div id="comment-avatar">
                <?php echo get_avatar( $comment, '50' ); ?>
                </div>
                <div id="comment-notavatar">
                    <div id="comment-info">
                        <?php if ($comment->user_id>0){
                            $score=get_user_meta( $comment->user_id, 'user_score');
                            echo '<a href="'.get_author_posts_url($comment->user_id).'">';
                            echo comment_author();
                            echo '(';
                            if($score[0]==""){echo 0;}else{echo $score[0];}
                            echo ')</a>';
                        }else{
                            echo esc_attr(comment_author()) . '(Guest) :';
                        }
                        ?>
                    </div>
                    <div id="comment-text">
                        <?php echo esc_attr(comment_text()); ?>
                    </div>
                </div>
            </div>
        <?php
    }


    //End of DEBUGGING



    //Theme Customization

        // custom menu support
        add_theme_support( 'menus' );
        if ( function_exists( 'register_nav_menus' ) ) {
            register_nav_menus(
                array(
                  'header-menu' => 'Header Menu',
                  'sidebar-menu' => 'Sidebar Menu',
                  'footer-menu' => 'Footer Menu',
                  'logged-in-menu' => 'Logged In Menu',
                    'mobile-header-menu' => 'Mobile Header Menu'
                )
            );

        }

        register_sidebar(array('name'=>'Sidebar',
            'before_widget' => '<div class="sidebar-widget"><div class="sidebar-widget-box">',
            'after_widget' => '</div></div>',
            'before_title' => '</div><div id="sidebar-widget-title">',
            'after_title' => '</div><div class="sidebar-widget-box">',
        ));

    //Security

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


    //Initializations

        function mh_load_my_script() {
            wp_enqueue_script( 'jquery' );
        }
        add_action( 'wp_enqueue_scripts', 'mh_load_my_script' );

        function load_javascript(){
            wp_enqueue_script( 'function', get_template_directory_uri().'/majavascript.js', 'jquery', true);
            wp_localize_script( 'function', 'my_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
        }
        add_action('template_redirect', 'load_javascript');

    //Login/Register forms and php functions shit

        add_filter( 'widget_tag_cloud_args', 'my_widget_tag_cloud_args' );
        function my_widget_tag_cloud_args( $args ) {
            $args['number'] = 60;
            $args['largest'] = 22;
            $args['smallest'] = 9;
            $args['unit'] = 'px';
            return $args;
        }
?>
