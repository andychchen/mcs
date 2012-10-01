<ul>
	<?php 	/* Widgetized sidebar, if you have the plugin installed. */
if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('extra-content-two') ) : ?>
	<li>
		<ul>
			<li><h2>Most Discussed</h2></li>
<?php get_mostcommented(); ?>
</ul>
</li>
<?php endif; ?>

</ul>