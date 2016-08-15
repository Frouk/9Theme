<?php

// Calls try Upload returns message according to status.
function manageuploadsplugin() {
    if (isset($_POST['post-title'])) {
        $result = "NotUploaded";
        if (isset($_POST['my_image_upload_nonce']) && wp_verify_nonce($_POST['my_image_upload_nonce'], 'my_image_upload')) {
            $result = tryUpload(true);
        }
        if (isset($_POST['my_image_upload_url_nonce'], $_POST['post-url']) && wp_verify_nonce($_POST['my_image_upload_url_nonce'], 'my_image_upload_url')) {
            $result = tryUpload(false);
        }

        if ($result == "NotUploaded"){
            return;
        } elseif ($result == "Success") {
            echo'
            <script type="text/javascript">jQuery(document).ready(function($) {jQuery("#show_upload").click();});</script>
            <div class="upload_success">
              <p>Success:Thank you for your post.</p>
              <p>Your post might require manual approval.</p>
              <p>Feel free to make more posts in the meantime.</p>
            </div>
            ';
        } else {
            echo'
            <script type="text/javascript">jQuery(document).ready(function($) {jQuery("#show_upload").click();});</script>
            <div class="upload_error">
              <p>Error:' . $result . '</p>
            </div>
            ';
        }
    }


    if (isset($_GET['yolo'])) {
        echo "yolo";
    }
}

function tryUpload ($uploadFromDisk) {

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    if ($uploadFromDisk) {
        $imageFile = $_FILES['my_image_upload']['tmp_name'];
        $fileSize = filesize($imageFile);
    } else {
        $imageFile = $_POST['post-url'];
        $fileSize = geturlsize($imageurl);
    }

    if ($fileSize > get_option('9theme_max_file_size')) {
        return "Too large file.";
    }

    $dimensions = getimagesize($imageFile);

    $imageWidth = get_option('9theme_image_width');

    switch ($dimensions['mime']) {

        case "image/gif":
            $image = file_get_contents($imageurl);
            break;

        case "image/jpeg":
            $tempImageName = 'useruploads/' . rand(0,99999) . '.jpg';
            $imageEditor = wp_get_image_editor($imageFile);

            if (!is_wp_error($imageEditor)) {
                if ($dimensions[0] > $imageWidth) {
                    $imageEditor->resize($imageWidth, 0, false);
                }
                $imageEditor->save($tempImageName);
                watermarkjpeg($tempImageName);
                $image = file_get_contents($tempImageName);
                unlink($tempImageName);
            } else {
                return "Can't open image.";
            }
            break;

        case "image/png":
            $tempImageName = 'useruploads/' . rand(0,99999) . '.png';
            $imageEditor = wp_get_image_editor($imageFile);

            if (!is_wp_error($imageEditor)) {
                if ($dimensions[0] > $imageWidth) {
                    $imageEditor->resize($imageWidth, 0, false);
                }
                $imageEditor->save($tempImageName);
                watermarkpng($tempImageName);
                $image = file_get_contents($tempImageName);
                unlink($tempImageName);
            } else {
                return "Can't open image.";
            }
            break;

        default:
            return "Unknown file type.";

    }

    $client_idl =  get_option('imgur_api_key');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_idl));
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => base64_encode($image)));
    $reply = curl_exec($ch);
    curl_close($ch);
    $reply = json_decode($reply);

    if ($reply->data->error != "") {
        return $reply->data->error;
    } else {
        insertPost(sanitize_text_field($_POST['post-title']),  esc_url($reply->data->link) );
    }

    return "Success";
}

function insertPost($title , $postContent) {
    wp_insert_post( array(
        'post_author'    => $user_id,
        'post_title'    => sanitize_text_field($title),
        'post_type'     => 'post',
        'post_content'    => '<img src="' . $postContent . '"/>',
        'post_status'    => 'publish'
    ));
}

function geturlsize($url){
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
   curl_setopt($ch, CURLOPT_HEADER, TRUE);
   curl_setopt($ch, CURLOPT_NOBODY, TRUE);
   $data = curl_exec($ch);
   $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
   curl_close($ch);
   return $size;
}

function watermarkgif($imagepath){
}
function watermarkjpeg($imagepath){
}
function watermarkpng($imagepath){
}
?>
