<?php get_header(); ?>

<!-- BEGIN content -->
<div id="content">

	<?php
	if (have_posts()) : the_post(); 
	?>

	<div class="single">
		<h2><?php the_title(); ?></h2>
		<div class="content"><?php the_content(); ?></div>
	</div>
	
	<div id="comments">
		<?php comments_template(); ?>
	</div>
	
	<?php else : ?>
	<div class="single">
	<h2>Not Found</h2>
	<p>Sorry, but you are looking for something that is not here.</p>
	</div>
	<?php endif; ?>
	
</div>
<!-- END content -->

<?php get_sidebar(); get_footer(); ?>
