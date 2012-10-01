	<!-- BEGIN bottom -->
	<div id="bottom">
	<?php if ( !function_exists('dynamic_sidebar')
	|| !dynamic_sidebar(1) ) : ?>
		<!-- begin popular posts -->
		<div class="box">
		<h2>Popular Posts</h2>
		<ul><?php dp_popular_posts(5); ?></ul>
		</div>
		<!-- end popular posts -->
		
		<!-- begin recent posts -->
		<div class="box">
		<h2>Recent Posts</h2>
		<?php 
		query_posts('showposts=5&orderby=date&order=DESC'); 
		if (have_posts()) :
		?>
		<ul>
		<?php while (have_posts()) : the_post(); ?>
		<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
		<?php endwhile; ?>
		</ul>
		<?php endif; ?>
		</div>
		<!-- end recent posts -->
		
		<!-- begin recent comments -->
		<div class="box">
		<h2>Recent Comments</h2>
		<ul class="comments"><?php dp_recent_comments(5); ?></ul>
		</div>
		<!-- end recent comments -->
	<?php endif; ?>
	</div>
	<!-- END bottom -->
	
</div>
<!-- END content -->

<!-- BEGIN sidebar -->
<div id="sidebar">
<?php if ( !function_exists('dynamic_sidebar')
|| !dynamic_sidebar(2) ) : ?>
	<!-- begin pages -->
	<h2>Pages</h2>
	<ul><?php dp_list_pages(); ?></ul>
	<!-- end pages -->
	
	<!-- begin archives -->
	<h2>Archives</h2>
	<ul><?php wp_get_archives('type=monthly'); ?></ul>
	<!-- end archives -->
	
	<!-- begin blogroll -->
	<?php wp_list_bookmarks('category_before=&category_after=&title_before=<h2>&title_after=</h2>'); ?>
	<!-- end blogroll -->
	
	<!-- begin categories -->
	<h2>Categories</h2>
	<ul><?php wp_list_categories('title_li='); ?></ul>
	<!-- end categories -->
	
	<!-- begin meta -->
	<h2>Meta</h2>
	<ul>
	<?php wp_register(); ?>
	<li><?php wp_loginout(); ?></li>
	<li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
	<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
	<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
	<?php wp_meta(); ?>
	</ul>
	<!-- end meta -->
	
	<!-- begin tags -->
	<h2>Tags</h2>
	<div class="tags">
	<?php if (function_exists('wp_widget_tag_cloud')) wp_widget_tag_cloud(array('before_title'=>'<!--','after_title'=>'-->')); ?>
	</div>
	<!-- end tags -->
	
	<!-- begin flickr photos -->
	<h2>Flickr Photos</h2>
	<div class="flickr">
	<?php if (function_exists('get_flickrRSS')) get_flickrRSS(); ?>
	</div>
	<!-- end flickr photos -->
	
	<!-- begin featured video -->
	<h2>Featured Video</h2>
	<div class="video">
	<script type="text/javascript">showVideo('<?php echo dp_settings("youtube") ?>');</script>
	</div>
	<!-- end featured video -->
<?php endif; ?>
</div>
<!-- END sidebar -->
