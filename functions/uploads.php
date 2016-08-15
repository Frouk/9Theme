<?php

function manageuploadsClassic () {

    // Check that the nonce is valid, and the user can edit this post.
    if (isset($_POST['my_image_upload_nonce'], $_POST['post-title']) &&
        wp_verify_nonce($_POST['my_image_upload_nonce'], 'my_image_upload')) {

        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        // Let WordPress handle the upload.
        // Remember, 'my_image_upload' is the name of our file input in our form above.
        $attachment_id = media_handle_upload('my_image_upload', $_POST['post_id']);

        if (is_wp_error($attachment_id)) {
            // There was an error uploading the image.
        } else {
                wp_insert_post( array(
                    'post_author'    => $user_id,
                    'post_title'    => sanitize_text_field($_POST['post-title']),
                    'post_type'     => 'post',
                    'post_content'    => '<img src="' . wp_get_attachment_url($attachment_id) . '"/>',
                    'post_status'    => 'publish'
            ) );
            echo '<script type=\'text/javascript\'>  window.location="/";</script>';
        }
    }

    if (isset($_POST['my_image_upload_url_nonce'], $_POST['post-title'], $_POST['post-url']) &&
            wp_verify_nonce( $_POST['my_image_upload_url_nonce'], 'my_image_upload_url')) {

        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        wp_insert_post( array(
            'post_author'    => $user_id,
            'post_title'    =>sanitize_text_field($_POST['post-title']),
            'post_type'     => 'post',
            'post_content'    => '<img src="'. esc_url($_POST['post-url']) . '"/>',
            'post_status'    => 'publish'
        ));

        echo '<script type=\'text/javascript\'>    window.location="/";</script>';
    }

}

    $maxfilesize=5000000;
    $watermarkpath="watermark.png";
    $postpassword='POSTING_PASSWORD';

   function manageuploadsplugin(){
     if (isset( $_POST['my_image_upload_nonce'],$_POST['post-title'])	&& wp_verify_nonce( $_POST['my_image_upload_nonce'], 'my_image_upload' ))
     {
       // These files need to be included as dependencies when on the front end.
       require_once( ABSPATH . 'wp-admin/includes/image.php' );
       require_once( ABSPATH . 'wp-admin/includes/file.php' );
       require_once( ABSPATH . 'wp-admin/includes/media.php' );

       $isthereerror=false;
       $errormessage="";
       $uploadedfile=$_FILES['my_image_upload']['tmp_name'];
       $dimensions=getimagesize($uploadedfile);

       if(filesize($uploadedfile)>$GLOBALS['maxfilesize']){
         $isthereerror=true;
         $errormessage="Too large file.";
       }

       if(!$isthereerror)
       {
          switch ($dimensions['mime']) {
            case "image/gif":
                $image = file_get_contents($uploadedfile);
                break;
            case "image/jpeg":
                    $newimagename='useruploads/'.rand(0,99999).'.jpg';
                    $imagedd = wp_get_image_editor( $uploadedfile );
                    if ( ! is_wp_error( $imagedd ) ) {
                        if($dimensions[0]>620){
                          $imagedd->resize( 620, 0, false);
                        }
                        $imagedd->save($newimagename);
                        watermarkjpeg($newimagename);
                        $image = file_get_contents($newimagename);
                        unlink($newimagename);
                    }else{
                        $isthereerror=true;
                    }
                break;
            case "image/png":
                  $newimagename='useruploads/'.rand(0,99999).'.png';
                  $imagedd = wp_get_image_editor( $uploadedfile );
                  if ( ! is_wp_error( $imagedd ) ) {
                      if($dimensions[0]>620){
                        $imagedd->resize( 620, 0, false);
                      }
                      $imagedd->save($newimagename);
                      watermarkpng($newimagename);
                      $image = file_get_contents($newimagename);
                      unlink($newimagename);
                  }else{
                      $isthereerror=true;
                  }
              break;
            default:
                $isthereerror=true;
                $errormessage="Unknown file type.";
          }
        }

        if(!$isthereerror)
        {
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
          if($reply->data->error!=""){
              $isthereerror=true;
              $errormessage=$reply->data->error;
          }else{
              wp_insert_post( array(
                  'post_author'	=> $user_id,
                  'post_title'	=>sanitize_text_field($_POST['post-title']),
                  'post_type'     => 'post',
                  'post_content'	=> '<img src="'. esc_url($reply->data->link).'"/>',
                  'post_status'	=> 'pending'
              ) );
          }
        }
        if($isthereerror){
            echo'
            <script type="text/javascript">jQuery(document).ready(function($) {jQuery("#show_upload").click();});</script>
            <div class="upload_error">
              <p>Error:'.$errormessage.'</p>
            </div>
            ';
        }else{
            echo'
            <script type="text/javascript">jQuery(document).ready(function($) {jQuery("#show_upload").click();});</script>
            <div class="upload_success">
              <p>Success:Thank you for your post.</p>
              <p>Your post might require manual approval.</p>
              <p>Feel free to make more posts in the meantime.</p>
            </div>
            ';
        }
      }


      if (isset( $_POST['my_image_upload_url_nonce'],$_POST['post-title'],$_POST['post-url'])	&& wp_verify_nonce( $_POST['my_image_upload_url_nonce'], 'my_image_upload_url' ))
   		{
   				// These files need to be included as dependencies when on the front end.
           $imageurl = $_POST['post-url'];
           // These files need to be included as dependencies when on the front end.
           require_once( ABSPATH . 'wp-admin/includes/image.php' );
           require_once( ABSPATH . 'wp-admin/includes/file.php' );
           require_once( ABSPATH . 'wp-admin/includes/media.php' );

           $isthereerror=false;
           $errormessage="";
           $dimensions=getimagesize($imageurl);

           if(geturlsize($imageurl)>$GLOBALS['maxfilesize']){
             $isthereerror=true;
             $errormessage="Too large file.";
           }

           if(!$isthereerror)
           {
              switch ($dimensions['mime']) {
                case "image/gif":
                    $image = file_get_contents($imageurl);
                    break;
                case "image/jpeg":
                        $newimagename='useruploads/'.rand(0,99999).'.jpg';
                        $imagedd = wp_get_image_editor($imageurl);
                        if ( ! is_wp_error( $imagedd ) ) {
                            if($dimensions[0]>620){
                              $imagedd->resize( 620, 0, false);
                            }
                            $imagedd->save($newimagename);
                            watermarkjpeg($newimagename);
                            $image = file_get_contents($newimagename);
                            unlink($newimagename);
                        }else{
                            $isthereerror=true;
                        }
                    break;
                case "image/png":
                        $newimagename='useruploads/'.rand(0,99999).'.png';
                        $imagedd = wp_get_image_editor($imageurl);
                        if ( ! is_wp_error( $imagedd ) ) {
                            if($dimensions[0]>620){
                              $imagedd->resize( 620, 0, false);
                            }
                            $imagedd->save($newimagename);
                            watermarkpng($newimagename);
                            $image = file_get_contents($newimagename);
                            unlink($newimagename);
                        }else{
                            $isthereerror=true;
                        }
                    break;
                default:
                    $isthereerror=true;
                    $errormessage="Unknown file type.";
              }
            }
            if(!$isthereerror)
            {
              $client_idl = get_option('imgur_api_key');
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
              curl_setopt($ch, CURLOPT_POST, TRUE);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
              curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_idl));
              curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => base64_encode($image)));
              $reply = curl_exec($ch);
              curl_close($ch);
              $reply = json_decode($reply);
              if($reply->data->error!=""){
                $isthereerror=true;
                $errormessage=$reply->data->error;
              }else{
                  wp_insert_post( array(
                      'post_author'	=> $user_id,
                      'post_title'	=>sanitize_text_field($_POST['post-title']),
                      'post_type'     => 'post',
                      'post_content'	=> '<img src="'. esc_url($reply->data->link).'"/>',
                      'post_status'	=> 'pending'
                  ) );
                }
              }
              if($isthereerror){
                  echo'
                  <script type="text/javascript">jQuery(document).ready(function($) {jQuery("#show_upload").click();});</script>
                  <div class="upload_error">
                    <p>Error:'.$errormessage.'</p>
                  </div>
                  ';
              }else{
                  echo'
                  <script type="text/javascript">jQuery(document).ready(function($) {jQuery("#show_upload").click();});</script>
                  <div class="upload_success">
                    <p>Success:Thank you for your post.</p>
                    <p>Your post might require manual approval.</p>
                    <p>Feel free to make more posts in the meantime.</p>
                  </div>
                  ';
              }
   			}
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
      $im = imagecreatefromjpeg($imagepath);
      $stamp=imagecreatefrompng($GLOBALS['watermarkpath']);
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
      $stamp=imagecreatefrompng($GLOBALS['watermarkpath']);
      $marge_right = 1;
      $marge_bottom = 1;
      $sx = imagesx($stamp);
      $sy = imagesy($stamp);
      imagecopymerge($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), 50);
      imagepng($im, $imagepath);
      imagedestroy($im);
   }



   function mod_upload(){
    if (current_user_can( 'editor' )||current_user_can( 'administrator' )){
     echo'
       <div id="mod_upload_form">
         <form id="modform" method="post" action="#" enctype="multipart/form-data">
           <div id="formtitle">Mass Upload Images</div>
           <label>Title</label>
           <p><input type="text" id ="post-title" name="post-title" /></p>
           <label>Tag</label>
           <p><input type="text" id ="post-tag" name="post-tag" /></p>
           <label>File Urls</label>
           <p><textarea id ="post-url" name="post-url" style="margin: 0px;width: 567px;height: 98px;"></textarea></p>
           <input id="mod_upload_sumbit" name="mod_upload_sumbit" type="submit" value="Upload" />
         </form>
       </div>';

       if (isset( $_POST['post-title']))
        {
            // These files need to be included as dependencies when on the front end.
            $imageurl = $_POST['post-url'];
            // These files need to be included as dependencies when on the front end.
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
            echo $_POST['post-title'];
            echo $_POST['post-url'];
            $string = $_POST['post-url'];
            $token = strtok($string, "\n\r");
            while ($token !== false)
            {
              $imageurl = $token;
              $dimensions=getimagesize($imageurl);
              if(geturlsize($imageurl)>$GLOBALS['maxfilesize']){
                $isthereerror=true;
                $errormessage="Too large file.";
              }
              if(!$isthereerror)
              {
                 switch ($dimensions['mime']) {
                   case "image/gif":
                       $image = file_get_contents($imageurl);
                       break;
                   case "image/jpeg":
                           $newimagename='useruploads/'.rand(0,99999).'.jpg';
                           $imagedd = wp_get_image_editor($imageurl);
                           if ( ! is_wp_error( $imagedd ) ) {
                               if($dimensions[0]>620){
                                 $imagedd->resize( 620, 0, false);
                               }
                               $imagedd->save($newimagename);
                               watermarkjpeg($newimagename);
                               $image = file_get_contents($newimagename);
                               unlink($newimagename);
                           }else{
                               $isthereerror=true;
                           }
                       break;
                   case "image/png":
                           $newimagename='useruploads/'.rand(0,99999).'.png';
                           $imagedd = wp_get_image_editor($imageurl);
                           if ( ! is_wp_error( $imagedd ) ) {
                               if($dimensions[0]>620){
                                 $imagedd->resize( 620, 0, false);
                               }
                               $imagedd->save($newimagename);
                               watermarkpng($newimagename);
                               $image = file_get_contents($newimagename);
                               unlink($newimagename);
                           }else{
                               $isthereerror=true;
                           }
                       break;
                   default:
                       $isthereerror=true;
                       $errormessage="Unknown file type.";
                 }
               }
               if(!$isthereerror)
               {
                 $client_idl = $GLOBALS['client_id'];
                 $ch = curl_init();
                 curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
                 curl_setopt($ch, CURLOPT_POST, TRUE);
                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_idl));
                 curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => base64_encode($image)));
                 $reply = curl_exec($ch);
                 curl_close($ch);
                 $reply = json_decode($reply);
                 if($reply->data->error!=""){
                   $isthereerror=true;
                   $errormessage=$reply->data->error;
                 }else{
                     wp_insert_post( array(
                         'post_author'	=> $user_id,
                         'post_title'	=>sanitize_text_field($_POST['post-title']),
                         'post_type'     => 'post',
                         'post_content'	=> '<img src="'. esc_url($reply->data->link).'"/>',
                         'post_status'	=> 'publish',
                         'tags_input' => sanitize_text_field($_POST['post-tag'])
                     ) );
                   }
                 }

              $token = strtok("\n\r");
            }
        }
      }else{
        echo 'Mass uploading is only allowed to high rank users';
      }

   }
   add_shortcode( 'mass-upload', 'mod_upload' );

   function php_upload(){
     if (isset( $_POST['post-title'],$_POST['post-url']))
      {
          if($_POST['hiddenpassword']!=$GLOBALS['postpassword']){
            return;
          }
          // These files need to be included as dependencies when on the front end.
          $imageurl = $_POST['post-url'];
          // These files need to be included as dependencies when on the front end.
          require_once( ABSPATH . 'wp-admin/includes/image.php' );
          require_once( ABSPATH . 'wp-admin/includes/file.php' );
          require_once( ABSPATH . 'wp-admin/includes/media.php' );

          $isthereerror=false;
          $errormessage="";
          $dimensions=getimagesize($imageurl);

          if(geturlsize($imageurl)>$GLOBALS['maxfilesize']){
            $isthereerror=true;
            $errormessage="Too large file.";
          }

          if(!$isthereerror)
          {
             switch ($dimensions['mime']) {
               case "image/gif":
                   $image = file_get_contents($imageurl);
                   break;
               case "image/jpeg":
                       $newimagename='useruploads/'.rand(0,99999).'.jpg';
                       $imagedd = wp_get_image_editor($imageurl);
                       if ( ! is_wp_error( $imagedd ) ) {
                           if($dimensions[0]>620){
                             $imagedd->resize( 620, 0, false);
                           }
                           $imagedd->save($newimagename);
                           watermarkjpeg($newimagename);
                           $image = file_get_contents($newimagename);
                           unlink($newimagename);
                       }else{
                           $isthereerror=true;
                       }
                   break;
               case "image/png":
                       $newimagename='useruploads/'.rand(0,99999).'.png';
                       $imagedd = wp_get_image_editor($imageurl);
                       if ( ! is_wp_error( $imagedd ) ) {
                           if($dimensions[0]>620){
                             $imagedd->resize( 620, 0, false);
                           }
                           $imagedd->save($newimagename);
                           watermarkpng($newimagename);
                           $image = file_get_contents($newimagename);
                           unlink($newimagename);
                       }else{
                           $isthereerror=true;
                       }
                   break;
               default:
                if(!$_POST['runned']=='yes'){
                  other_upload($imageurl);
                }
                   $isthereerror=true;
                   $errormessage="Unknown file type.";
             }
           }
           if(!$isthereerror)
           {
             $client_idl = $GLOBALS['client_id'];
             $ch = curl_init();
             curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
             curl_setopt($ch, CURLOPT_POST, TRUE);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
             curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_idl));
             curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => base64_encode($image)));
             $reply = curl_exec($ch);
             curl_close($ch);
             $reply = json_decode($reply);
             if($reply->data->error!=""){
               $isthereerror=true;
               $errormessage=$reply->data->error;
             }else{
                 wp_insert_post( array(
                     'post_author'	=> 504,
                     'post_title'	=>sanitize_text_field($_POST['post-title']),
                     'post_type'     => 'post',
                     'post_content'	=> '<img src="'. esc_url($reply->data->link).'"/>',
                     'post_status'	=> 'publish'
                 ) );
               }
             }
        }

   }
   add_shortcode( 'php-post-upload', 'php_upload' );

   function other_upload($url){
     	$posst='yolo';
     	if(strpos($url,'fycat.com')>0){
     		$returned_content = get_data($url);
       	$pos = strpos($returned_content,'id="webmSource" src="')+29;
       	$pos2=strpos($returned_content,'.webm" type="video/webm">',$pos);
       	$peos=substr($returned_content,$pos,$pos2-$pos);
       	$posst='<video width="100%" height="auto" autoplay loop>
         <source src="'.esc_url($peos).'.mp4" type="video/mp4">
         <source src="'.esc_url($peos).'.webm" type="video/webm">
         Your browser does not support the video tag.
         </video>';
     	} elseif (strpos($url,'.gifv')>0){
       	$peos=substr($url,0,-5);
       	$posst='<video width="100%" height="auto" autoplay loop>
         <source src="' . esc_url($peos) . '.mp4" type="video/mp4">
         <source src="' . esc_url($peos) . '.webm" type="video/webm">
       Your browser does not support the video tag.
       </video>';
     	} elseif (strpos($url,'//imgur.com')>0){
         $returned_content = get_data($url);
         $pos = strpos($returned_content,'href="http://i.imgur.com/')+6;
         $pos2=strpos($returned_content,'"/>',$pos);
         $peos=substr($returned_content,$pos,$pos2-$pos);
         $tail=substr($peos,-4);
         if(!(($tail=='.gif')||($tail=='.png')||($tail=='.jpg')||($tail=='gifv'))){
           return;
         }
         $_POST['post-url']=$peos;
         $_POST['hiddenpassword']=$GLOBALS['postpassword'];
         $_POST['runned']='yes';
         //$posst=$peos;
   	     php_upload();
        return;
      } elseif (strpos($url,'9gag')>0){
          $pos = strpos($url,'gag/')+4;
          $peos=substr($url,$pos,7);
          $videourl='http://img-9gag-fun.9cache.com/photo/'.$peos.'_460sv';

          $file = 'http://www.domain.com/somefile.jpg';
          $file_headers = @get_headers($videourl.'.mp4');
          if($file_headers[0] != 'HTTP/1.1 404 Not Found') {
              $posst='<video width="100%" height="auto" autoplay loop>
              <source src="'.esc_url($videourl).'.mp4" type="video/mp4">
              <source src="'.esc_url($videourl).'.webm" type="video/webm">
              Your browser does not support the video tag.
              </video>';
          }else{
            $_POST['post-url']=esc_url('http://img-9gag-fun.9cache.com/photo/'.$peos.'_460s.jpg');
            $_POST['hiddenpassword']=$GLOBALS['postpassword'];
            $_POST['runned']='yes';
            php_upload();
              return;
          }

     	}else{
         return;
       }
       wp_set_current_user(1);
     	 wp_insert_post( array(
                              'post_author'	=> 504,
                              'post_title'	=>sanitize_text_field($_POST['post-title']),
                              'post_type'     => 'post',
                              'post_content'	=> $posst,
                              'post_status'	=> 'publish'
                          ) );
       wp_set_current_user(504);
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
