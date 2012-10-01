<div id="sidebar">
	<div id="sidebar1">
		<ul>
	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : ?>
		<?php wp_list_pages('title_li=<h1>Pages</h1>'); ?>
		
		<?php wp_list_categories('show_count=1&title_li=<h1>Categories</h1>'); ?>

		<li>
			<h1>Archives</h1>
			<ul><?php wp_get_archives('type=monthly'); ?></ul>
		</li>

		<li><h1>Calendar</h1><?php get_calendar(); ?></li>



	<?php	endif;?>		
		</ul>
	</div>
	
	<div id="sidebar2">
		<ul>
	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar(2) ) : ?>
		<li><h1>Recent Posts</h1><ul><?php wp_get_archives('type=postbypost&limit=5')?></ul></li>
			
		<li><h1>Tag Cloud</h1><?php wp_tag_cloud('smallest=10&largest=20&number=30&unit=px&format=flat&orderby=name'); ?></li>

		<?php if ( is_home() || is_page() ) { 	/* If this is the frontpage */ 
				wp_list_bookmarks('orderby=rand&title_before=<h1>&title_after=</h1>&between=<br/>&show_description=1&limit=20');
		?>

<?php } ?>
		<li><h1>Meta</h1>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<li><a href="<?php bloginfo('rss2_url'); ?>" title="Syndicate this site using RSS 2.0">Entries <abbr title="Really Simple Syndication">RSS</abbr></a></li>
				<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="The latest comments to all posts in RSS">Comments <abbr title="Really Simple Syndication">RSS</abbr></a></li>
				<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress.org</a></li>
				<?php wp_meta(); ?>
			</ul>
		</li>		
	<?php	endif;?>		
		</ul>
	</div>
</div>
