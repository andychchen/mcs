<?php get_header(); ?>
<div class="content-bg">
    	<div class="main">
        	<div class="content-shadow">
        	<div class="column-one">
            <?php if (have_posts()) : ?>

<?php while (have_posts()) : the_post(); ?>
            	<div class="blog-head">
                	<div class="blog-date"><?php the_time('d M Y') ?></div>
                    <div class="blog-header"><h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2></div>
                    <div class="comment-icon"><?php comments_popup_link(0, 1, '%', '', ''); ?></div>
                </div>
                <div class="blog-main">
                	<div class="blog-content">
                    	<?php the_content('(Continue Reading)'); ?>
                        <p class="date"><?php the_time('M dS') ?> by <?php the_author(); ?></p>
                        <p class="link"><a href="<?php the_permalink() ?>">Continue Reading</a></p>
                    </div>
                </div>                
               <?php endwhile; ?>

<?php else : ?>
<div class="blog-main">
<h2 class="not-found">Not Found</h2>
<p >Sorry, but you are looking for something that isn't here.</p>
</div>
<?php endif; ?>
                <div class="paging-main">
                	<div class="page-count">Page 1 of 38</div>
                    <div class="page-list">
                    	<ul class="paging">
                        	<li><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li>.</li>
                            <li>.</li>
                            <li>.</li>
                            <li><a href="#">Last</a></li>
                        </ul>
                    </div>
                </div>
                
            </div>
            <div class="column-two">
            	<?php get_sidebar(); ?>
            </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>
