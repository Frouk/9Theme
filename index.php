<?php  get_header(); ?>

<div id="container">
	<div id="main">
		<div id="posts">
			<?php query_posts('paged=' . get_query_var('paged'));?>
       			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<div id="singlepost">
        				<div id="posttitle">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</div>
					<div id="postcontent">
						<a href="<?php the_permalink(); ?>"><?php the_content(); ?></a>
					</div>
					<div id="postdata">
						<div id="stats">900 points - <?php comments_number( 'No comments', 'One comment', '% comments' ); ?></div>
						<div class="vote">
							<li><a class="voteup" href="javascript:void(0);" onclick="UpVote();"></a></li>
							<li><a class="votedown" href="javascript:void(0);" onclick="DownVote();"></a></li>
						</div>
					</div>
				</div>


			<?php endwhile; else: ?>
				<?php _e('It seems there are no posts here.'); ?></p>
			<?php endif; ?>
		</div>
		<?php get_sidebar(); ?>
	</div>

<?php get_footer(); ?>