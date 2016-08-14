<?php  get_header(); ?><div id="container">    <div id="main">        <div id="pagecontent">            <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>                            <?php the_content(); ?>            <?php endwhile; ?>        </div>
        <?php get_sidebar(); ?>    </div>
<?php get_footer(); ?>