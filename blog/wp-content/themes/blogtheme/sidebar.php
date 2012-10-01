<div id="sidebar" class="grid_3 omega">
	
	<?php if ( ( is_archive() or is_search() or is_tag() or is_category() ) && !$GLOBALS['sitemap'] ) { ?>
		
		<div class="pageinfo">
		
			<?php if (is_category()) { ?>You are currently browsing the archives for <strong>"<?php echo single_cat_title(); ?>"</strong>.
			<?php } elseif (is_day()) { ?>You are currently browsing the archives for <strong><?php the_time('F jS, Y'); ?></strong>.
			<?php } elseif (is_month()) { ?>You are currently browsing the archives for <strong><?php the_time('F, Y'); ?></strong>.
			<?php } elseif (is_year()) { ?>You are currently browsing the archives for <strong><?php the_time('Y'); ?></strong>.
			<?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>You are currently browsing the archives.
			<?php } elseif (is_tag()) { ?>You are currently browsing the tag archives for <strong>"<?php echo single_tag_title('', true); ?>"</strong>.
			<?php } elseif (is_search()) { ?>You are currently browsing the search results for <strong>"<?php the_search_query() ?>"</strong>.
			<?php } ?>
		
		</div><!--pageinfo-->
	
	<?php } ?>

	<div id="sidebar_accordian" class="ui-accordion-container">
	
		<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) :endif; ?>	
	
	</div><!--uo-accordion-container-->
	
	<div class="clearfix"></div>
	
</div><!--sidebar-->