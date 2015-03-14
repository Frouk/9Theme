<?php  get_header(); ?>

<div id="container">
	<div id="postpage">
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<div id="pp_post">
			<?php addtest(get_current_user_id(),get_the_id());?>
		
			<div id="pp_title"><?php the_title(); ?></div>
			<div id="pp_content"><?php the_content(); ?></div>
			<div id="pp_data"></div>
			<div id="pp_comments"></div>
			<div class="vote">
				<li><a class="voteup"  href="javascript:void(0);" onclick="myFunction();"></a></li>		
				<li><a class="votedown" href="javascript:void(0);" onclick="myFunction();"></a></li>
			</div>
		</div>
		<?php endwhile; /* end loop */ ?>
	</div>
</div>

</body>
</html>
