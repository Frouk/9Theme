<?php
function remove_hidden_category($query) {
    if (!is_user_logged_in()) {
        $query->set('category__not_in', get_option('9theme_hidden_category') );
    }
    return $query;
}
add_filter( 'pre_get_posts', 'remove_hidden_category' );

function my_the_post_action( $post_object ) {
	// modify post object here
    $post_object->post_title =  "Hidden post";
}
//add_action( 'the_post', 'my_the_post_action' );

function yourprefix_add_to_content( $content ) {
    if(is_single()) {
        $content = "</a><p>You must be <a href='javascript:void(0);'
                        onclick=\"jQuery('#show_login').click();\">Logged in</a>
                        to view this post.</p><a>";
    }
    return $content;
}
//add_filter( 'the_content', 'yourprefix_add_to_content' );
?>
