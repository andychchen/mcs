<?php get_header(); ?>

<div class="main">
	
	<?php include ('column-one.php'); ?>

		<div class="content">

			<div class="column two">
				<div class="edge-alt"></div>
				
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="entry-extended">
				<h2 class="page"><?php the_title(); ?></h2>
				
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

		</div>
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>

	</div><!-- end column -->
</div><!-- end content -->
<?php get_footer(); ?>