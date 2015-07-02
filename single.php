<?php  get_header(); ?>

<div id="container">
	<div id="postpage">
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			<div id="pp_post">
				<div id="pp_title"><?php the_title(); ?></div>
				<div id="pp_content"><?php the_content(); ?></div>
				<div id="pp_data">
						<div id="stats"><a id=<?php echo '"score ' . get_the_ID() . '"'; ?>><?php $scored=get_post_meta(get_the_ID(),'postscore',true);if($scored==""){echo 0;}else{echo $scored;} ?></a> points - <?php comments_number( 'No comments', 'One comment', '% comments' ); ?> by <?php the_author_posts_link(); ?>
								<div id="tags"> <?php the_tags( 'Tags:', ',', '.' ); ?> </div>
						</div>
						<div class="vote">
							<li><a id=<?php echo '"upvoteicon ' . get_the_ID() . '" '; ?>class="voteup" href="javascript:void(0);" onclick="vote(<?php echo get_the_ID(); ?>,1);"></a></li>
							<li><a id=<?php echo '"downvoteicon ' . get_the_ID() . '" '; ?>class="votedown" href="javascript:void(0);" onclick="vote(<?php echo get_the_ID(); ?>,2);"></a></li>
						</div>
				</div>
				<div id="pp_comments">
				<?php comments_template( '', true ); ?>
				</div>
			</div>
		<?php endwhile; /* end loop */ ?>
	</div>

</div>
<?php get_footer(); ?>
