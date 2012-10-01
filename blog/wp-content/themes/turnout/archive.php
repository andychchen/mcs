<?php get_header(); ?>

<!-- BEGIN content -->
<div id="content">

	<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
	<?php /* If this is a category archive */ if (is_category()) { ?>
	<h2 class="title">Archive for the <strong><?php single_cat_title(); ?></strong> Category</h2>
	<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
	<h2 class="title">Posts Tagged <strong><?php single_tag_title(); ?></strong></h2>
	<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
	<h2 class="title">Archive for <?php the_time('F jS, Y'); ?></h2>
	<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
	<h2 class="title">Archive for <?php the_time('F, Y'); ?></h2>
	<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
	<h2 class="title">Archive for <?php the_time('Y'); ?></h2>
	<?php /* If this is an author archive */ } elseif (is_author()) { ?>
	<h2 class="title">Author Archive</h2>
	<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
	<h2 class="title">Blog Archives</h2>
	<?php } ?>

	<?php
	if (have_posts()) :
	while (have_posts()) : the_post(); 
	?>

	<!-- begin post -->
	<div class="post">
		<p class="details1"><?php the_time('F j, Y'); ?> / <?php the_category(', '); ?></p>
		<div class="buffer">
		<div class="content">
		<a href="<?php the_permalink(); ?>"><?php dp_attachment_image($post->ID, 'thumbnail', 'alt="' . $post->post_title . '"'); ?></a>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p><?php echo dp_clean($post->post_content, 200); ?></p>
		</div>
		<p class="details2"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?> /  <a href="<?php the_permalink(); ?>">Read More</a></p>
		</div>
	</div>
	<!-- end post -->
	
	<?php endwhile; ?>
	<p class="postnav">
	<?php next_posts_link('&laquo; Older Entries'); ?> &nbsp; 
	<?php previous_posts_link('Newer Entries &raquo;'); ?>
	</p>
	<?php else : ?>
	<div class="notfound">
	<h2>Not Found</h2>
	<p>Sorry, but you are looking for something that is not here.</p>
	</div>
	<?php endif; ?>
	
</div>
<!-- END content -->

<?php get_sidebar(); get_footer(); ?>
