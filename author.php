<?php  get_header(); ?>
<?php
		//can call curauth->get description and shit
		if(isset($_GET['author_name'])) :
			$curauth = get_userdatabylogin($author_name);
	    else :
			$curauth = get_userdata(intval($author));
		endif;
	?>
<div id="container">
	<div id="main">
		<div id="posts">
			<?php $yo=$curauth->ID;  query_posts('author='.$yo.'&paged=' . get_query_var('paged'));?>
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
							<li><a class="voteup" href="http://google.com"></a></li>
							<li><a class="votedown" href="http://google.com"></a></li>
						</div>
					</div>
				</div>


			<?php endwhile; else: ?>
				<?php _e('It seems there are no posts here.'); ?></p>
			<?php endif; ?>
		</div>
	</div>
<?php get_footer(); ?>