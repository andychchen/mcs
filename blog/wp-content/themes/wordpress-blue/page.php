<?php get_header(); ?>
<div class="content-bg">
    	<div class="main">
        	<div class="content-shadow">
        	<div class="column-one">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<div class="blog-head">
                	<div class="blog-date">&nbsp;</div>
                    <div class="blog-header"><h2><?php the_title(); ?></h2></div>
                    <div class="comment-icon-remove">&nbsp;</div>
                </div>
                <div class="blog-main">
                	<div class="blog-content">
                    	<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
						<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
                    </div>
                </div> 
                <?php endwhile; endif; ?>
				<div class="page-main">
				<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>              
                </div>
            </div>
            <div class="column-two">
            	<?php get_sidebar(); ?>
            </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>
