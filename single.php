<?php  get_header(); ?>

<div id="container">
	<div id="postpage">
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<div id="pp_post">
			<div id="pp_title"><?php the_title(); ?></div>
			<div id="pp_content"><?php the_content(); ?></div>
			<div id="pp_data"></div>
			<div id="pp_comments"></div>
			
		</div>
		<?php endwhile; /* end loop */ ?>
	</div>
</div>

</body>
</html>
