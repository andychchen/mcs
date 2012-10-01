<?php get_header(); ?>

<div class="main">
	
	<?php include ('column-one.php'); ?>

		<div class="content">
					<div class="column two">
						<div class="edge-alt"></div>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>


				<div class="entry-extended">
					<h2><?php the_title(); ?></h2>
									<p class="meta">Posted by <a href="<?php the_author(); ?>"><?php the_author(); ?></a> on <?php the_time('l, F jS, Y') ?></p>
					<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
					<p class="meta">Posted in: <?php the_category(', ') ?>.</p>
					

					<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>



		<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>
</div>

		</div><!-- end column -->

	</div><!-- end content -->

	<?php get_footer(); ?>