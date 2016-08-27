<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

// Do not delete these lines
    if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
        die ('Please do not load this page directly. Thanks!');

    if ( post_password_required() ) { ?>
        <p class="nocomments">This post is password protected. Enter the password to view comments.</p>
    <?php
        return;
    }
?>

<!-- You can start editing here. -->

<?php if ( comments_open() ) : ?>

<div id="respond">
<script type="text/javascript">
function CheckLength()
{
    if (document.getElementById("comment").value.length > 0) {
        document.getElementById("commentform").submit();
    }
}
</script>
<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
    <p>You must be <a href="javascript:void(0);" onclick="jQuery('#show_login').click();">Logged in</a> to post a comment.</p>
<?php else : ?>
    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
    <?php if ( is_user_logged_in() ) : ?>
        <p>Leave a Reply</p>
    <?php else : ?>
        <p>
            Leave a Reply as :
            <input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
        </p>
    <?php endif; ?>

        <div id="comment-avatar">
            <?php echo get_avatar( $comment, '50' ); ?>
        </div>
        <div id="postcommenttextarea">
            <textarea name="comment" id="comment" cols="58" rows="10" tabindex="4" minlength="10"></textarea>
        </div>
        <input name="submitbtn" type="button" id="submitbtn" tabindex="5" value="Submit Comment" onClick="CheckLength();"/>
        <?php comment_id_fields(); ?>
    <?php do_action('comment_form', $post->ID); ?>
    </form>
<?php endif; ?>
</div>
<?php endif; ?>

<?php if ( have_comments() ) : ?>
    <div class="commentlist">
        <?php wp_list_comments('reverse_top_level=1&type=comment&callback=mytheme_comment'); ?>
    </div>
<?php endif; ?>
