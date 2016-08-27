<?php

function manageuploadsplugin() {
    if (isset($_POST['post-title'])) {
        $result = "NotUploaded";
        if (isset($_POST['my_image_upload_nonce']) && wp_verify_nonce($_POST['my_image_upload_nonce'], 'my_image_upload')) {
            $result = tryUpload(true, -1);
        }
        if (isset($_POST['my_image_upload_url_nonce'], $_POST['post-url']) && wp_verify_nonce($_POST['my_image_upload_url_nonce'], 'my_image_upload_url')) {
            $result = tryUpload(false, -1);
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

}

function ajaxPost() {
    if (isset($_GET['key'], $_GET['user'],$_GET['title'], $_GET['url'])) {
        echo "yolo";
        if ($_GET['key'] != "22") {
            return;
        }
        $_POST['post-url'] = $_GET['url'];
        $_POST['post-title'] = $_GET['title'];
        tryUpload(false, $_GET['user']);
    }
}

function tryUpload ($uploadFromDisk, $myUserid) {

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
            return postFromSource($imageFile);

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
        if ($myUserid == -1){
            $myUserid = $user_id;
        }
        insertPost(sanitize_text_field($_POST['post-title']),  esc_url($reply->data->link), $myUserid);
    }

    return "Success";
}

function insertPost($title , $postContent, $myUserid) {
    wp_insert_post( array(
        'post_author'    => $myUserid,
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

function postFromSource($url) {

    if (substr($url,-5) == '.gifv'){
        postGifv($url);
    } elseif (strpos($url, 'imgur.com/a/') > 0) {
        postFromImgurAlbum($url);
        return "Success";
    } elseif (strpos($url, '//imgur.com/gallery/') > 0) {
        postFromImgurAlbum($url, true);
        return "Success";
    } elseif (strpos($url, '//www.reddit.com/r/') > 0) {
        postFromReddit($url);
        return "Success";
    } elseif (strpos($url, '9gag.com/gag/') > 0) {
        postFrom9gag($url);
        return "Success";
    } elseif (strpos($url, '//gfycat.com/') > 0) {
        postFromGfycat($url);
        return "Success";
    }

    echo "$url";
}

function postFromImgurAlbum($url, $postFirstOnly) {

    $returned_content = get_data($url);
    $needle = "\"//i.imgur.com/";
    $lastPos = 0;
    $positions = array();
    $urls = array();

    // Find position of all imgur image url occurances.
    while (($lastPos = strpos($returned_content, $needle, $lastPos)) !== false) {
        $positions[] = $lastPos;
        $lastPos = $lastPos + strlen($needle);
    }

    foreach ($positions as $value) {
        $pos2 = strpos($returned_content,'" ',$value);
        $peos = substr($returned_content, $value, $pos2-$value);

        // Check that strings looks like imgur image
        switch (substr($peos,-4)) {
            case '.gif':
            case '.png':
            case '.jpg':
            case 'gifv':
                if (strlen($peos) < 28) {
                    $urls[] = 'http:' . substr($peos, 1);
                    if ($postFirstOnly) {
                        $_POST['post-url'] = $urls[0];
                        tryUpload(false, -1);
                        return "S";
                    }
                }
                break;
            default:
                break;
        }
    }
    $urls = array_unique($urls);
    foreach ($urls as $individualUrl) {
        // echo $individualUrl . "<br>";
        $_POST['post-url'] = $individualUrl;
        tryUpload(false, -1);
    }
}

function postGifv($url) {
    $imgurPostId = substr($url, 0, -5);
    $postContent ="</a>
    <div id=\"video-container\" onclick='togglePlayPause(this);'>
    	<video autoplay loop id='media-video' poster=\"$imgurPostId" . "h.jpg\" oncanplay=\"onVideoReady(this)\">
    		<source src='$imgurPostId.mp4' type='video/mp4'>
    	</video>
    	<div id=\"videoOverlay\">&rtrif;</div>
        <div id=\"videoFull\" onclick=\"videoFull(this)\">⇲</div>
    </div>
    <a>";


    if (isset($_GET['user'])) {
        $myUserid = $_GET['user'];
    } else {
        $myUserid = $user_id;
    }
    $title = $_POST['post-title'];
    wp_insert_post( array(
        'post_author'    => $myUserid,
        'post_title'    => sanitize_text_field($title),
        'post_type'     => 'post',
        'post_content'    => $postContent,
        'post_status'    => 'publish'
    ));
}

function postFromReddit($url) {
    //can also post comments from reddit
    $returned_content = get_data($url . '.json');
    $decodedData = json_decode($returned_content, true);
    $_POST['post-url'] = $decodedData[0]['data']['children'][0]['data']['url'];
    $_POST['post-title'] = $decodedData[0]['data']['children'][0]['data']['title'];
    tryUpload(false, -1);
}

function postFrom9gag($url) {
    $gagPostId = substr($url, -7);
    // http://img-9gag-fun.9cache.com/photo/ae64PbQ_460s.jpg
    // http://img-9gag-fun.9cache.com/photo/ae64PbQ_460sv.mp4
    $postContent ="</a>
    <div id=\"video-container\" onclick='togglePlayPause(this);'>
        <video autoplay loop id='media-video' poster='http://img-9gag-fun.9cache.com/photo/{$gagPostId}_460s.jpg' oncanplay=\"onVideoReady(this)\">
            <source src='http://img-9gag-fun.9cache.com/photo/{$gagPostId}_460sv.mp4' type='video/mp4'>
            <source src='http://img-9gag-fun.9cache.com/photo/{$gagPostId}_460svwm.webm' type='video/webm'>
        </video>
        <div id=\"videoOverlay\">&rtrif;</div>
        <div id=\"videoFull\" onclick=\"videoFull(this)\">⇲</div>
    </div>
    <a>";

    if (isset($_GET['user'])) {
        $myUserid = $_GET['user'];
    } else {
        $myUserid = $user_id;
    }
    $title = $_POST['post-title'];
    wp_insert_post( array(
        'post_author'    => $myUserid,
        'post_title'    => sanitize_text_field($title),
        'post_type'     => 'post',
        'post_content'    => $postContent,
        'post_status'    => 'publish'
    ));
}

function postFromGfycat($url) {
    $returned_content = get_data($url);

    $pos = strpos($returned_content,'id="webmSource" src="')+29;
    $pos2 = strpos($returned_content,'.webm" type="video/webm">',$pos);
    $gfyvideo = substr($returned_content, $pos, $pos2-$pos);

    $pos = strpos($gfyvideo,'gfycat.com/') + 11;
    $postThumb = substr($gfyvideo, $pos);

    $postContent ="</a>
    <div id=\"video-container\" onclick='togglePlayPause(this);'>
        <video autoplay loop id='media-video' poster='https://thumbs.gfycat.com/{$postThumb}-poster.jpg' oncanplay=\"onVideoReady(this)\">
            <source src='https://{$gfyvideo}.mp4' type='video/mp4'>
            <source src='https://{$gfyvideo}.webm' type='video/webm'>
        </video>
        <div id=\"videoOverlay\">&rtrif;</div>
        <div id=\"videoFull\" onclick=\"videoFull(this)\">⇲</div>
    </div>
    <a>";

    if (isset($_GET['user'])) {
        $myUserid = $_GET['user'];
    } else {
        $myUserid = $user_id;
    }
    $title = $_POST['post-title'];
    wp_insert_post( array(
        'post_author'    => $myUserid,
        'post_title'    => sanitize_text_field($title),
        'post_type'     => 'post',
        'post_content'    => $postContent,
        'post_status'    => 'publish'
    ));
}

function get_data($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
?>
