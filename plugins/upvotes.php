<?php

add_action("wp_ajax_vote", "vote");
//All vote call will pass through here,should add security checks.
function vote(){

        $para1=$_REQUEST['para1'];
        $para2=$_REQUEST['para2'];

        if (get_post_status($para1)==false) die();

        $var = get_current_user_id();

        switch ($para2) {
        case 0:
            removevote($var,$para1);
            break;
        case 1:
                    addvote($var,$para1,1);
                    break;
        case 2:
                    addvote($var,$para1,0);
                    break;
            default:
            die();
        }
    die();
}

add_action("after_switch_theme", "createtablez");

function createtablez(){
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . "updownvotes";
    $sql = "CREATE TABLE $table_name (
                user_id bigint(20) NOT NULL,
                post_id bigint(20) NOT NULL,
                upvote tinyint(1) NOT NULL,
                PRIMARY KEY (user_id,post_id)
            ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function addvote($user,$id,$vote){
    global $wpdb;
    $table_name = $wpdb->prefix . "updownvotes";
    $previous=$wpdb->get_row( "SELECT upvote FROM $table_name WHERE user_id=$user AND post_id=$id");
    if (count($previous)==0){
        if($vote==1){
            $wpdb->replace( $table_name, array('user_id'=>$user,'post_id'=>$id,'upvote'=>$vote));
            indecrease($id,1);
        }else{
            $wpdb->replace( $table_name, array('user_id'=>$user,'post_id'=>$id,'upvote'=>$vote));
            indecrease($id,-1);
        }
    }elseif($previous->upvote==1){
        if($vote==0){
            $wpdb->replace( $table_name, array('user_id'=>$user,'post_id'=>$id,'upvote'=>$vote));
            indecrease($id,-2);
        }
    }elseif($previous->upvote==0){
        if($vote==1){
            $wpdb->replace( $table_name, array('user_id'=>$user,'post_id'=>$id,'upvote'=>$vote));
            indecrease($id,2);
        }
    }
}
function removevote($user,$id){
    global $wpdb;
    $table_name = $wpdb->prefix . "updownvotes";
    $previous=$wpdb->get_row( "SELECT upvote FROM $table_name WHERE user_id=$user AND post_id=$id");
    if (count($previous)==0){

    }elseif($previous->upvote==1){
        $wpdb->delete( $table_name, array( 'user_id'=>$user,'post_id'=>$id ));
        indecrease($id,-1);
    }elseif($previous->upvote==0){
        $wpdb->delete( $table_name, array( 'user_id'=>$user,'post_id'=>$id ));
        indecrease($id,1);
    }
}

// Might miss some updates if called simultaneously.
function indecrease($id,$num){
    $prev =  get_post_meta( $id, 'postscore' );
    update_post_meta($id, 'postscore', intval($prev[0])+$num);
    $post = get_post( $id );
    $prev =  get_user_meta( $post->post_author, 'user_score' );
    update_user_meta($post->post_author, 'user_score', (intval($prev[0])+$num));
}
//Really bad for sites with many signed up users
function checkvote($id){
    global $wpdb;
    $table_name = $wpdb->prefix . "updownvotes";
    $user = get_current_user_id();
    $previous=$wpdb->get_row( "SELECT upvote FROM $table_name WHERE user_id=$user AND post_id=$id");
    if (count($previous)==0){
        return '-1';
    }elseif($previous->upvote==1){
        return '1';
    }elseif($previous->upvote==0){
        return '0';
    }
}


 ?>
