	<div id="sidebar">
		<ul>
			<li class="nobackground">
				<?php get_search_form(); ?>
			</li>

			<!-- Author information is disabled per default. Uncomment and fill in your details if you want to use it.  -->
			<li><h2>Author</h2>
            <p><img class="alignleft" src="<?php bloginfo('template_url'); ?>/images/about.jpg" alt="About Me" />This is an example of a WordPress page, you could edit this to put information about yourself or your site so readers know where you are coming from.</p>
			</li>

			<!-- Banners  / OPTIONAL  -->
			<!-- Banners' size must be 125x125 -->
<!--
			<li class="ads clearfix"><h2>Our Sponsors</h2>
				<!-- First Row -->
				<a class="ad-left" href="#"><img src="<?php bloginfo('template_url'); ?>/images/ad.jpg" alt="" /></a>
				<a class="ad-right" href="#"><img src="<?php bloginfo('template_url'); ?>/images/ad.jpg" alt="" /></a>
			</li>
-->

			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

			<?php wp_list_pages('title_li=<h2>Pages</h2>' ); ?>   

            <!-- Optional - Latest Posts
            <li><h2>Latest Posts</h2>
                <ul>
                    <?php get_archives('postbypost', 10); ?>
                </ul>
            </li>-->

			<li><h2>Archives</h2>
				<ul>
				    <?php wp_get_archives('type=monthly'); ?>
				</ul>
			</li>

			<?php wp_list_categories('show_count=1&title_li=<h2>Categories</h2>'); ?>

			<?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>
				<?php wp_list_bookmarks(); ?>

				<li><h2>Meta</h2>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
					<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
					<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
					<?php wp_meta(); ?>
				</ul>
				</li>
			<?php } ?>

			<?php endif; ?>
		</ul>
	</div>

