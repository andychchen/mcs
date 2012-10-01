<?php get_header(); ?>

<!-- BEGIN content -->
<div id="content">
<?php
if (have_posts()) : the_post(); 
$arc_year = get_the_time('Y');
$arc_month = get_the_time('m');
$arc_day = get_the_time('d');
?>
<!-- begin post -->
<div class="single post">
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<p class="details1"><?php the_time('l, F j, Y') ?></p>
	<?php the_content(); ?>
	<div class="details2">
	<p class="l"><?php the_tags('Tags: ', ', ', ''); ?></p>
	<p class="r">Category: <?php the_category(', '); ?></p>
	</div>
</div>
<!-- end post -->
<!-- begin comments -->
<div id="comments"><?php comments_template(); ?></div>
<!-- end comments -->
<?php else : ?>
<div class="notfound">
	<h2>Not Found</h2>
	<p>Sorry, but you are looking for something that is not here.</p>
</div>
<?php endif; ?>
</div>
<!-- END content -->
<?php get_sidebar(); get_footer(); ?>
