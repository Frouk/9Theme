<?php  get_header(); ?>
<?php define( "DONOTCACHEPAGE", true ); ?>
<div id="container">
    <div id="main">
        <div id="posts">
            <div id="PageTitleBox"><div id="PageTitle"><?php single_cat_title(); ?></div></div>
            <div id="postslist">
                <?php $yo = get_cat_ID(single_cat_title('', false)); ?>
                <?php query_posts('cat='.$yo.'&paged=' . get_query_var('paged'));?>
           <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div id="singlepost">
                    <div id="posttitle">
                        <a href="<?php the_permalink(); ?>"><?php esc_html(the_title()); ?></a>
                    </div>
                    <div id="postcontent">
                        <a href="<?php the_permalink(); ?>"><?php the_content(); ?></a>
                    </div>
                    <div id="postdata">
                        <div id="stats"><a id=<?php echo '"score ' . get_the_ID() . '"'; ?>><?php $scored=get_post_meta(get_the_ID(),'postscore',true);if($scored==""){echo 0;}else{echo $scored;} ?></a> points - <?php comments_number( 'No comments', 'One comment', '% comments' ); ?>
                            <div id="tags"> <?php the_tags( 'Tags: ', ', ', '' ); ?> </div>
                        </div>
                        <div class="vote">
                            <?php if (is_user_logged_in()) {
                                $theId = get_the_ID();
                                $hasivote = checkvote($theId);
                                if ($hasivote == 1) {
                                    echo "<li><a id='upvoteicon {$theId}' class='voteupactive' href='javascript:void(0);' onclick='vote({$theId}, 0);'></a></li>";
                                } else {
                                    echo "<li><a id='upvoteicon {$theId}' class='voteup' href='javascript:void(0);'onclick='vote({$theId}, 1);'></a></li>";
                                }
                                if ($hasivote  == 0) {
                                    echo "<li><a id='downvoteicon {$theId}' class='votedownactive' href='javascript:void(0);' onclick='vote({$theId}, 0);'></a></li>";
                                } else {
                                    echo "<li><a id='downvoteicon {$theId}' class='votedown' href='javascript:void(0);' onclick='vote({$theId}, 2);'></a></li>";
                                }
                            }else{ ?>
                                <li><a id=<?php echo '"upvoteicon ' . get_the_ID() . '" '; ?>class="voteup" href="javascript:void(0);" onclick="jQuery('#show_login').click();"></a></li>
                                <li><a id=<?php echo '"downvoteicon ' . get_the_ID() . '" '; ?>class="votedown" href="javascript:void(0);" onclick="jQuery('#show_login').click();"></a></li>
                        <?php } ?>
                        </div>
                        <?php if (get_current_user_id()==1) {
                             echo '<a href="'. get_edit_post_link() . '" target="_blank">Edit Post</a>';
                        } ?>
                    </div>
                </div>

                <?php endwhile; else: ?>
                    <?php _e('It seems there are no posts here.'); ?></p>
                <?php endif; ?>
            </div>
            <div id="Paging">
                <div id="NewerPages"><?php previous_posts_link('&laquo; Newer Posts') ?></div>
                <div id="OlderPages"><?php next_posts_link('Older Posts &raquo;') ?></div>
            </div>
        </div>

        <div id="sidebar-container">
            <div id="sidebar-dummy"></div>
            <?php get_sidebar(); ?>
        </div>
    </div>

<?php get_footer(); ?>
