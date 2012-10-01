<ul>
	<?php 	/* Widgetized sidebar, if you have the plugin installed. */
			if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('extra-content-one') ) : ?>
	
	<li>
		<ul>
			<li><h2>Categories</h2></li>
			<?php wp_list_categories('hierarchical=0&title_li='); ?>
			
		</ul>
	</li>
<?php endif; ?>

</ul>