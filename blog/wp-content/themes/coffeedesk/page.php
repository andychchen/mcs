<?php get_header(); ?>
<div id="content">
	<div class="spacer"></div>
<?php  
	if(have_posts()) : while (have_posts()) : the_post(); 
?>		
			<div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
				<div class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></div>
				<div class="entry">
					<?php  the_content('more...'); ?><div class="clear"></div>
					<?php  wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				</div>
			</div>
		<?php endwhile; ?>
	<?php endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer();?>
