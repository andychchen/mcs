<?php get_header(); ?>

<!-- BEGIN content -->
<div id="content">

	<div class="buffer">
	
	<?php 
	$categories = get_categories('hide_empty=1'); 
	foreach ($categories as $category) :
	query_posts('showposts=1&cat='.$category->cat_ID);
	if (have_posts()) : the_post();
	?>

	<!-- begin post -->
	<div class="post">
		<h2><a href="<?php echo get_category_link($category->cat_ID); ?>"><?php echo $category->name ?></a></h2>
		<p class="date"><?php the_author_posts_link(); ?> on <?php the_time('m j, Y') ?> | <?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></p>
		<div class="thumb"><a href="<?php the_permalink(); ?>"><?php dp_attachment_image($post->ID, 'medium', 'alt="' . $post->post_title . '"'); ?></a></div>
		<p><?php echo dp_clean($post->post_content, 200); ?></p>
		<a class="readmore" href="<?php the_permalink(); ?>">Read More</a>
	</div>
	<!-- end post -->
	
	<?php endif; endforeach; ?>
	
	</div>

<?php get_sidebar(); get_footer(); ?>
