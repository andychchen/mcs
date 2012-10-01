<?php get_header(); ?>

<!-- BEGIN content -->
<div id="content">

	<div class="buffer">
	
	<h2 class="title">Search Results for <strong><?php the_search_query(); ?></strong></h2>
	
	<?php 
	if (have_posts()) :
	while (have_posts()) : the_post();
	?>

	<!-- begin post -->
	<div class="post">
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="date"><?php the_author_posts_link(); ?> on <?php the_time('m j, Y') ?> | <?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></p>
		<div class="thumb"><a href="<?php the_permalink(); ?>"><?php dp_attachment_image($post->ID, 'medium', 'alt="' . $post->post_title . '"'); ?></a></div>
		<p><?php echo dp_clean($post->post_content, 200); ?></p>
		<a class="readmore" href="<?php the_permalink(); ?>">Read More</a>
	</div>
	<!-- end post -->
	
	<?php endwhile; ?> 
	<p class="postnav">
		<?php next_posts_link('&laquo; Older Entries'); ?> &nbsp; 
		<?php previous_posts_link('Newer Entries &raquo;'); ?>
	</p>
	<?php endif; ?>
	
	</div>

<?php get_sidebar(); get_footer(); ?>
