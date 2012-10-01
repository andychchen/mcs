<!-- BEGIN sidebar -->
<div id="sidebar">
	<?php if ( !function_exists('dynamic_sidebar')
	|| !dynamic_sidebar(1) ) : ?>
	<!-- begin popular articles -->
	<h2>Popular Articles</h2>
	<ul><?php dp_popular_posts(6); ?></ul>
	<!-- end popular articles -->
	<!-- begin recent comments -->
	<h2>Recent Comments</h2>
	<ul class="rc"><?php dp_recent_comments(6); ?></ul>
	<!-- end recent comments -->
	<!-- begin flickr photos -->
	<h2>Flickr Photos</h2>
	<div class="flckr">
	<?php if (function_exists('get_flickrRSS')) get_flickrRSS(); ?>
	</div>
	<!-- end flickr photos -->
	<!-- begin tags -->
	<h2>Tags</h2>
	<div class="tags">
	<?php if (function_exists('wp_widget_tag_cloud')) wp_widget_tag_cloud(array('before_title'=>'<!--','after_title'=>'-->')); ?>
	</div>
	<!-- end tags -->
	<?php endif; ?>
	<!-- BEGIN left -->
	<div class="l">
	<?php if ( !function_exists('dynamic_sidebar')
	|| !dynamic_sidebar(2) ) : ?>
		<!-- begin pages -->
		<h2>Pages</h2>
		<ul><?php dp_list_pages(); ?></ul>
		<!-- end pages -->
		<!-- begin categories -->
		<h2>Categories</h2>
		<ul>
		<?php wp_list_categories('title_li='); ?>
		</ul>
		<!-- end categories -->
	<?php endif; ?>
	</div>
	<!-- END left -->
	
	<!-- BEGIN right -->
	<div class="r">
	<?php if ( !function_exists('dynamic_sidebar')
	|| !dynamic_sidebar(3) ) : ?>
		<!-- begin archives -->
		<h2>Archives</h2>
		<ul><?php wp_get_archives('type=monthly'); ?></ul>
		<!-- end archives -->
		<!-- begin blogroll -->
		<?php wp_list_bookmarks('category_before=&category_after=&title_before=<h2>&title_after=</h2>'); ?>
		<!-- end blogroll -->
		<!-- begin meta -->
		<h2>Meta</h2>
		<ul>
		<?php wp_register(); ?>
		<li><?php wp_loginout(); ?></li>
		</ul>
		<!-- end meta -->
	<?php endif; ?>
	</div>
	<!-- END right -->

</div>
<!-- END sidebar -->
