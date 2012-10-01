	<div id="footer">
		
		<ul>
			<li class="<?php if ( is_home() ) { echo 'current_page_item'; } ?>"><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><span class="left"></span>Home<span class="right"></span></a></li>
			<?php wp_list_pages('sort_column=menu_order&depth=1&title_li=&exclude=' . $exclude . ',' . get_option( 'woo_exclude_pages_footer' ) ); ?>
			<li class="rss"><a href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" title="RSS Subscription">Subscribe RSS</a></li>
		</ul>
		
		<p>BloggingStream by <a href="http://www.woothemes.com" title="WooThemes"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/woologo.png" alt="WooThemes" /></a></p>
		
	</div><!-- /footer -->

</div><!-- /container -->

<?php wp_footer(); ?>
<?php if ( get_option('woo_google_analytics') <> "" ) { echo stripslashes(get_option('woo_google_analytics')); } ?>

</body>
</html>