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
        if ($_GET['key'] != get_option('ajax_post_key')) {
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
            return postFromSource($imageFile, $myUserid);

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
        insertPost($_POST['post-title'], '<img src="' .  esc_url($reply->data->link) . '"/>', $myUserid);
    }

    return "Success";
}

function insertPost($title , $postContent, $myUserid) {
    if (isset($_POST['album-post'])) {
        $_POST['album-post'] = $_POST['album-post'] . $postContent;
        return;
    }
    if ($myUserid == -1) {
        $myUserid = $user_id;
    }

    // When posting as a regular user have to remove filters
    // or else can't post videos and such
    kses_remove_filters();

    wp_insert_post( array(
        'post_author'    => sanitize_text_field($myUserid),
        'post_title'     => sanitize_text_field($title),
        'post_type'      => 'post',
        'post_content'   => $postContent,
        'post_status'    => 'publish',
        'filter' => true
    ));

    // Enable filters again
    kses_init_filters();
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
    // Coming soon...
}

function watermarkjpeg($imagepath){
    $im = imagecreatefromjpeg($imagepath);
    $stamp = imagecreatefrompng(get_option('9theme_watermark_url'));
    $marge_right = 1;
    $marge_bottom = 1;
    $sx = imagesx($stamp);
    $sy = imagesy($stamp);
    imagecopymerge($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), 50);
    imagejpeg($im, $imagepath);
    imagedestroy($im);
}

function watermarkpng($imagepath){
    $im = imagecreatefrompng($imagepath);
    $stamp = imagecreatefrompng(get_option('9theme_watermark_url'));
    $marge_right = 1;
    $marge_bottom = 1;
    $sx = imagesx($stamp);
    $sy = imagesy($stamp);
    imagecopymerge($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), 50);
    imagepng($im, $imagepath);
    imagedestroy($im);
}

function postFromSource($url, $myUserid) {
    // Can test $myUserid here if don't want to
    // allow users post from source.

    if (substr($url,-5) == '.gifv'){
        postGifv($url, $myUserid);
        return "Success";
    } elseif (strpos($url, 'imgur.com/a/') > 0) {
        postFromImgurAlbum($url, false, $myUserid);
        return "Success";
    } elseif (strpos($url, '//imgur.com/gallery/') > 0) {
        postFromImgurAlbum($url, true, $myUserid);
        return "Success";
    } elseif (strpos($url, '//imgur.com/') > 0) {
        postFromImgurAlbum($url, true, $myUserid);
        return "Success";
    } elseif (strpos($url, '//www.reddit.com/r/') > 0) {
        postFromReddit($url, $myUserid);
        return "Success";
    } elseif (strpos($url, '9gag.com/gag/') > 0) {
        postFrom9gag($url, $myUserid);
        return "Success";
    } elseif (strpos($url, '//gfycat.com/') > 0) {
        postFromGfycat($url, $myUserid);
        return "Success";
    }
}

function postFromImgurAlbum($url, $postFirstOnly, $myUserid) {

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
                        tryUpload(false, $myUserid);
                        return "S";
                    }
                }
                break;
            default:
                break;
        }
    }
    $urls = array_unique($urls);

    $_POST['album-post'] = " ";

    foreach ($urls as $individualUrl) {
        $_POST['post-url'] = $individualUrl;
        tryUpload(false, $myUserid);
    }

    $numberOfPosts = substr_count($_POST['album-post'], "<img ");
    $postContent = "<div id='image-slide' data-currentimage='1' data-lastimage='{$numberOfPosts}' onclick='nextImage(event, this,1)'>
                        {$_POST['album-post']}
                        <div id='next-image-button' style='left:0;' onclick='nextImage(event, this.parentElement,-1)'><p>❮<p></div>
                        <div id='next-image-button' style='right:0;' onclick='nextImage(event, this.parentElement,1)'><p>❯</p></div>
                        <span id='image-counter'>1/{$numberOfPosts}</span>
                    </div>" ;
    unset($_POST['album-post']);
    insertPost($_POST['post-title'] , $postContent, $myUserid);
}

function postGifv($url, $myUserid) {
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

    insertPost($_POST['post-title'] , $postContent, $myUserid);
}

function postFromReddit($url, $myUserid) {
    $returned_content = get_data($url . '.json');
    $decodedData = json_decode($returned_content, true);
    $_POST['post-url'] = $decodedData[0]['data']['children'][0]['data']['url'];
    $_POST['post-title'] = $decodedData[0]['data']['children'][0]['data']['title'];
    tryUpload(false, $myUserid);
}

function postFrom9gag($url, $myUserid) {
    $gagPostId = substr($url, -7);
    $postContent = "</a>
    <div id=\"video-container\" onclick='togglePlayPause(this);'>
        <video autoplay loop id='media-video' poster='http://img-9gag-fun.9cache.com/photo/{$gagPostId}_460s.jpg' oncanplay=\"onVideoReady(this)\">
            <source src='http://img-9gag-fun.9cache.com/photo/{$gagPostId}_460sv.mp4' type='video/mp4'>
            <source src='http://img-9gag-fun.9cache.com/photo/{$gagPostId}_460svwm.webm' type='video/webm'>
        </video>
        <div id=\"videoOverlay\">&rtrif;</div>
        <div id=\"videoFull\" onclick=\"videoFull(this)\">⇲</div>
    </div>
    <a>";

    insertPost($_POST['post-title'] , $postContent, $myUserid);
}

function postFromGfycat($url, $myUserid) {
    $returned_content = get_data($url);

    $pos = strpos($returned_content, 'id="webmSource" src="') + 29;
    $pos2 = strpos($returned_content, '.webm" type="video/webm">', $pos);
    $gfyvideoWebm = substr($returned_content, $pos, $pos2-$pos);

    $pos = strpos($returned_content, 'id="mp4Source" src="') + 28;
    $pos2 = strpos($returned_content, '.mp4" type="video/mp4">', $pos);
    $gfyvideoMp4 = substr($returned_content, $pos, $pos2-$pos);

    $pos = strpos($gfyvideoWebm, 'gfycat.com/') + 11;
    $postThumb = substr($gfyvideoWebm, $pos);

    $postContent ="</a>
    <div id=\"video-container\" onclick='togglePlayPause(this);'>
        <video autoplay loop id='media-video' poster='https://thumbs.gfycat.com/{$postThumb}-poster.jpg' oncanplay=\"onVideoReady(this)\">
            <source src='https://{$gfyvideoMp4}.mp4' type='video/mp4'>
            <source src='https://{$gfyvideoWebm}.webm' type='video/webm'>
        </video>
        <div id=\"videoOverlay\">&rtrif;</div>
        <div id=\"videoFull\" onclick=\"videoFull(this)\">⇲</div>
    </div>
    <a>";

    insertPost($_POST['post-title'] , $postContent, $myUserid);
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
