<?php get_header(); ?>

<!-- BEGIN content -->
<div id="content">
<?php
if (have_posts()) :
while (have_posts()) : the_post(); 
$arc_year = get_the_time('Y');
$arc_month = get_the_time('m');
$arc_day = get_the_time('d');
?>
<!-- begin post -->
<div class="post">
	<div class="thumb"><a href="<?php the_permalink(); ?>"><?php dp_attachment_image($post->ID, 'full', 'alt="' . $post->post_title . '"'); ?></a></div>
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<p class="details1"><?php the_time('l, F j, Y') ?> <?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></p>
	<?php the_excerpt(); ?>
	<div class="details2">
	<p class="l"><?php the_tags('Tags: ', ', ', ''); ?></p>
	<p class="r">Category: <?php the_category(', '); ?></p>
	</div>
</div>
<!-- end post -->
<?php endwhile; ?>
<p class="postnav">
	<?php next_posts_link('Older Entries'); ?> &nbsp; 
	<?php previous_posts_link('Newer Entries'); ?>
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
