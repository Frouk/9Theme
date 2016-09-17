<?php

add_theme_support( 'menus' );
if (function_exists( 'register_nav_menus' )) {
    register_nav_menus(
        array(
          'header-menu' => 'Header Menu',
          'header-secondary-menu' => 'Header Second Menu',
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

function mh_load_my_script() {
    wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'mh_load_my_script' );

function load_javascript(){
    wp_enqueue_script( 'main', get_template_directory_uri().'/scripts/javascript.js', 'jquery', true);

    wp_enqueue_script( 'upvotes', get_template_directory_uri().'/scripts/upvotes.js', 'jquery', true);
    wp_localize_script( 'upvotes', 'my_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
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
?>
