<?php  get_header(); ?>

<div id="container">
	<div id="postpage">
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			<div id="pp_post">
				<div id="pp_title"><?php the_title(); ?></div>
				<div id="pp_content"><?php the_content(); ?></div>
				<div id="pp_data">
						<div id="stats">900 points - <?php comments_number( 'No comments', 'One comment', '% comments' ); ?>
							<div id="tags"> <?php the_tags( 'Tags:', ',', '.' ); ?> </div>
						</div>
						<div class="vote">
							<li><a class="voteup" href="javascript:void(0);" onclick="vote(<?php echo get_the_ID(); ?>,1);"></a></li>
							<li><a class="votedown" href="javascript:void(0);" onclick="vote(<?php echo get_the_ID(); ?>,2);"></a></li>
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
