<!-- BEGIN sidebar -->
<div id="sidebar">

	<?php if ( !function_exists('dynamic_sidebar')
	|| !dynamic_sidebar(1) ) : ?>
	
	<!-- begin search -->
	<form action="<?php echo get_option('home'); ?>">
		<input type="text" name="s" id="s" value="<?php the_search_query(); ?>" />
		<button type="submit">Search</button>
	</form>
	<!-- end search -->
	
	<!-- begin popular articles -->
	<div class="box">
		<h2>Popular Articles</h2>
		<ul><?php dp_popular_posts(5); ?></ul>
	</div>
	<!-- end popular articles -->
	
	<!-- begin flickr rss -->
	<div class="box">
		<h2>Flickr RSS</h2>
		<p class="flickr">
		<?php if (function_exists('get_flickrRSS')) get_flickrRSS(); ?>
		</p>
	</div>
	<!-- end flickr rss -->
	
	<!-- begin featured video -->
	<div class="box">
		<h2>Featured Video</h2>
		<div class="video"><?php 
	if (function_exists('wp_cumulus_insert')) : wp_cumulus_insert();
	elseif (function_exists('wp_widget_tag_cloud')) : wp_widget_tag_cloud(array('before_title'=>'<!--','after_title'=>'-->'));
	endif;
	?></div>
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
		<!-- end categories-->
		
		<!-- begin archives -->
		<div class="box">
			<h2>Archives</h2>
			<ul><?php wp_get_archives('type=monthly'); ?></ul>
		</div>
		<!-- end archives -->
		
	<?php endif; ?>
		
	</div>
	<!-- END left -->
	
	<!-- BEGIN right -->
	<div class="r">
	
	<?php if ( !function_exists('dynamic_sidebar')
	|| !dynamic_sidebar(3) ) : ?>
	
		<!-- begin blogroll -->
		<div class="box">
			<?php wp_list_bookmarks('category_before=&category_after=&title_before=<h2>&title_after=</h2>'); ?>
		</div>
		<!-- end blogroll -->
		
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
	
	<div class="break"></div>
	
</div>
<!-- END sidebar -->
