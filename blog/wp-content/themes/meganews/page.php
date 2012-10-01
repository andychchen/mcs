<?php get_header(); ?>
	
	<!-- BEGIN content -->
	<div id="content">
	
		<?php 
		if (have_posts()) : the_post();
		?>
		
		<!-- begin post -->
		<div class="single">
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php the_content(); ?>
		</div>
		<!-- end post -->
		
		<div id="comments"><?php comments_template(); ?></div>
		
		<?php else : ?>
		
		<div class="notfound">
			<h2>Not Found</h2>
			<p>Sorry, but you are looking for something that is not here.</p>
		</div>
		<?php endif; ?>
		
	</div>
	<!-- END content -->
	
<?php get_sidebar(); get_footer(); ?>
