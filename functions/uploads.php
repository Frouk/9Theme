<?php

function manageuploads(){

    // Check that the nonce is valid, and the user can edit this post.
    if (isset( $_POST['my_image_upload_nonce'],$_POST['post-title'])    && wp_verify_nonce( $_POST['my_image_upload_nonce'], 'my_image_upload' ))
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
                    'post_author'    => $user_id,
                    'post_title'    => sanitize_text_field($_POST['post-title']),
                    'post_type'     => 'post',
                    'post_content'    => '<img src="'.wp_get_attachment_url($attachment_id).'"/>',
                    'post_status'    => 'publish'
            ) );
            echo '<script type=\'text/javascript\'>  window.location="/";</script>';
        }

    } else {

            // The security check failed, maybe show the user an error.
    }

        //URL

    if (isset( $_POST['my_image_upload_url_nonce'],$_POST['post-title'],$_POST['post-url'])    && wp_verify_nonce( $_POST['my_image_upload_url_nonce'], 'my_image_upload_url' ))
    {
            // These files need to be included as dependencies when on the front end.
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );


                wp_insert_post( array(
                        'post_author'    => $user_id,
                        'post_title'    =>sanitize_text_field($_POST['post-title']),
                        'post_type'     => 'post',
                        'post_content'    => '<img src="'. esc_url($_POST['post-url']).'"/>',
                        'post_status'    => 'publish'
                ) );

                echo '<script type=\'text/javascript\'>    window.location="/";</script>';


        } else {

                // The security check failed, maybe show the user an error.
        }


}


?>
