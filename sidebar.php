<div id="sidebar">



	<ul>

		<?php if ( ! dynamic_sidebar( 'Sidebar' )) : ?>

	

			

			<li id="sidebar-nav" class="widget menu">

                            <h3><?php _e('Navigation'); ?></h3>

                           <div class="sidebar_spacer">

				<ul>

					<?php wp_nav_menu( array( 'theme_location' => 'sidebar-menu' ) ); ?>

				</ul>

                            </div>

			</li>


		<?php endif; ?>

	</ul>

</div><!--sidebar-->