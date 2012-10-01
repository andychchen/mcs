<?php get_header(); ?>

<div class="main">
	
	<?php include ('column-one.php'); ?>

		<div class="content">

			
			<div class="column two">
	
				<div class="edge-alt"></div>
	
									<?php if (have_posts()) : ?>

										<?php while (have_posts()) : the_post(); ?>
											<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
											
											                <div class="entry-thumb">
											                	<?php $key="thumb"; echo get_post_meta($post->ID, $key, true); ?>
											                </div>
															<div class="entry">
				<p class="meta"><?php the_time('l, F jS, Y') ?> - <span class="category"><?php the_category(', '); ?></span></p>


																<?php the_excerpt(); ?>
																<p class="more"><a href="<?php the_permalink() ?>">continue reading</a></p>
	

															</div>
															<?php endwhile; ?>

															<div class="navigation">
																<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
																<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
															</div>

														<?php else : ?>

															<h2 class="center">Not Found</h2>
															<p class="center">Sorry, but you are looking for something that isn't here.</p>
															<?php endif; ?>
														
			</div><!-- end column -->

			
		</div><!-- end content -->

		<?php get_footer(); ?>
