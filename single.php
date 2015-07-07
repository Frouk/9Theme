<?php  get_header(); ?>

<div id="container">
	<div id="main">
	<div id="postpage">
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			<div id="pp_post">
				<div id="pp_title"><?php the_title(); ?></div>
				<div id="pp_content"><?php the_content(); ?></div>
				<div id="pp_data">
						<div id="stats"><a id=<?php echo '"score ' . get_the_ID() . '"'; ?>><?php $scored=get_post_meta(get_the_ID(),'postscore',true);if($scored==""){echo 0;}else{echo $scored;} ?></a> points - <?php comments_number( 'No comments', 'One comment', '% comments' ); ?>
								<div id="tags"> <?php the_tags( 'Tags:', ',', '.' ); ?> </div>
						</div>
						<div class="vote">
							<?php if (is_user_logged_in()) {?>
								<li><a id=<?php echo '"upvoteicon ' . get_the_ID() . '" '; ?>class="voteup" href="javascript:void(0);" onclick="vote(<?php echo get_the_ID(); ?>,1);"></a></li>
								<li><a id=<?php echo '"downvoteicon ' . get_the_ID() . '" '; ?>class="votedown" href="javascript:void(0);" onclick="vote(<?php echo get_the_ID(); ?>,2);"></a></li>
						<?php }else{ ?>
								<li><a id=<?php echo '"upvoteicon ' . get_the_ID() . '" '; ?>class="voteup" href="javascript:void(0);" onclick="jQuery('#show_login').click();"></a></li>
								<li><a id=<?php echo '"downvoteicon ' . get_the_ID() . '" '; ?>class="votedown" href="javascript:void(0);" onclick="jQuery('#show_login').click();"></a></li>
						<?php } ?>
						</div>
						<div id="postedby">Posted by <?php the_author_posts_link(); echo '(<font color="green">';echo get_the_author_meta('user_score');echo '</font>)';?></div>
				</div>
				<div id="pp_comments">
				<?php comments_template( '', true ); ?>
				</div>
			</div>
		<?php endwhile; /* end loop */ ?>
	</div>
<?php get_sidebar(); ?>
</div>
</div>
<?php get_footer(); ?>
