<!-- BEGIN sidebar -->
<div id="sidebar">

	<?php if ( !function_exists('dynamic_sidebar')
	|| !dynamic_sidebar(1) ) : ?>

	<!-- begin search -->
	<div class="search box">
	<form action="<?php echo get_option('home'); ?>">
	<input type="text" name="s" id="s" value="<?php the_search_query(); ?>" />
	<button type="submit">Search</button>
	</form>
	</div>
	<!-- end search -->
	
	<!-- begin advertisement -->
	<div class="ad box">
	<a href="#"><img src="<?php bloginfo('template_url'); ?>/images/_ad2.jpg" alt="Advertisement" /></a>
	<a href="#"><img src="<?php bloginfo('template_url'); ?>/images/_ad2.jpg" alt="Advertisement" /></a>
	<a href="#"><img src="<?php bloginfo('template_url'); ?>/images/_ad2.jpg" alt="Advertisement" /></a>
	<a href="#"><img src="<?php bloginfo('template_url'); ?>/images/_ad2.jpg" alt="Advertisement" /></a>
	</div>
	<!-- end advertisement -->
	
	<!-- begin popular posts -->
	<div class="box">
	<h2>Popular Posts</h2>
	<ul><?php dp_popular_posts(6); ?></ul>
	</div>
	<!-- end popular posts -->
	
	<!-- begin flickr photos -->
	<div class="box">
	<h2>Flickr Photos</h2>
	<p class="flickr">
	<?php if (function_exists('get_flickrRSS')) get_flickrRSS(); ?>
	</p>
	</div>
	<!-- end flickr photos -->
	
	<!-- begin featured video -->
	<div class="box">
	<h2>Featured Video</h2>
	<div class="video">
	<script type="text/javascript">showVideo('<?php echo dp_settings("youtube") ?>');</script>
	</div>
	</div>
	<!-- end featured video -->
	
	<?php endif; ?>
	
	<!-- BEGIN left -->
	<div class="l">
	
		<?php if ( !function_exists('dynamic_sidebar')
		|| !dynamic_sidebar(2) ) : ?>
	
		<!-- begin categories -->
		<div class="box">
		<h2>Categories</h2>
		<ul><?php wp_list_categories('title_li='); ?></ul>
		</div>
		<!-- end categories -->
		
		<!-- begin blogroll -->
		<div class="box">
		<?php wp_list_bookmarks('category_before=&category_after=&title_before=<h2>&title_after=</h2>'); ?>
		</div>
		<!-- end blogroll -->
		
		<?php endif; ?>
	
	</div>
	<!-- END left -->
	
	<!-- BEGIN right -->
	<div class="r">
	
		<?php if ( !function_exists('dynamic_sidebar')
		|| !dynamic_sidebar(3) ) : ?>
	
		<!-- begin pages -->
		<div class="box">
		<h2>Pages</h2>
		<ul>
		<?php dp_list_pages(); ?>
		</ul>
		</div>
		<!-- end pages -->
		
		<!-- begin archives -->
		<div class="box">
		<h2>Archives</h2>
		<ul><?php wp_get_archives('type=monthly'); ?></ul>
		</div>
		<!-- end archives -->
		
		<!-- begin meta -->
		<div class="box">
		<h2>Meta</h2>
		<ul>
		<?php wp_register(); ?>
		<li><?php wp_loginout(); ?></li>
		<li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
		<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
		<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
		<?php wp_meta(); ?>
		</ul>
		</div>
		<!-- end meta -->
		
		<?php endif; ?>
	
	</div>
	<!-- END right -->

</div>
<!-- END sidebar -->
