<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p>This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
	<h3 id="comments"><?php comments_number('No Responses', 'One Response', '% Responses' );?> to “<?php the_title(); ?>”</h3>

	<div></div>
	<div>
		<div><?php previous_comments_link() ?></div>
		<div><?php next_comments_link() ?></div>
	</div>
 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>
<?php endif; ?>

<?php if ( comments_open() ) : ?>

	<?php foreach($comments as $comment) : ?>

	    <?php $comment_type = get_comment_type(); ?> <!-- checks for comment type -->
		        <?php if ($comment->comment_approved == '0') : ?> <!-- if comment is awaiting approval -->
		            <p class="waiting-for-approval">
		                <em><?php _e('Your comment is awaiting approval.'); ?></em>
		            </p>
		            <?php endif; ?>
		        <div class="comment-text">
		            <p class="gravatar">
						<?php if(function_exists('get_avatar')) { echo get_avatar($comment, '36'); } ?>
					</p>
                    <p class="meta_data2"><?php comment_type(); ?> by <?php comment_author_link(); ?> on <?php comment_date(); ?> at <?php comment_time(); ?></p>
			        <?php comment_text(); ?>
		        </div><!--.commentText-->

		        <div class="comment-meta">
					<?php edit_comment_link('Edit Comment', '', ''); ?>
		        </div><!--.commentMeta-->

	    <?php endforeach; ?>

<?php endif; // if you delete this the sky will fall on your head ?>