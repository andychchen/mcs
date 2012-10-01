<div class="asides">
	<div class="navigation">
		<div class="edge-nav"></div>
		
		<?php include ('navigation.php'); ?>

	</div><!-- end navigation -->
	<div class="column one">
			<h2 class="featured">Featured</h2>
			
						<?php $my_query = new WP_Query('category_name=featured&showposts=3'); ?>
						<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
							<div class="featured-post">
							
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			<p class="meta">Posted by <a href="<?php the_author(); ?>"><?php the_author(); ?></a> on <?php the_time('l, F jS, Y') ?></p>
			            <?php the_excerpt(); ?>
			<p class="more"><a href="<?php the_permalink() ?>">continue reading</a></p>
					</div>

					<?php endwhile; ?>

	</div><!-- end column -->
</div><!-- end asides -->