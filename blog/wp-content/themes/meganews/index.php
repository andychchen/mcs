<?php get_header(); ?>
	
	<!-- BEGIN content -->
	<div id="content">
		
		<?php 
		$categories = get_categories('hide_empty=1'); 
		foreach ($categories as $category) :
		query_posts('showposts=1&cat='.$category->cat_ID);
		if (have_posts()) : the_post();
		?>
		
		<!-- begin post -->
		<div class="post">
		<h2><a href="<?php echo get_category_link($category->cat_ID); ?>"><?php echo $category->name ?></a></h2>
		<div class="thumb"><a href="<?php the_permalink(); ?>"><?php dp_attachment_image($post->ID, 'thumbnail', 'alt="' . $post->post_title . '"'); ?></a></div>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php echo dp_clean($post->post_content, 300); ?></p>
		<a href="<?php the_permalink(); ?>" class="readmore">Read More</a>
		</div>
		<!-- end post -->
		
		<?php endif; endforeach; ?>
		
	</div>
	<!-- END content -->
	
<?php get_sidebar(); get_footer(); ?>
