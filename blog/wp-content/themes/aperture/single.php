<?php get_header(); ?>

 <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
            <div class="grid_16 alpha"> <h2 class="single"><?php the_title(); ?></h2></div>
            <div class="grid_4 alpha">
            <p class="post_meta"><span class="date">Posted on <?php the_time('F jS, Y') ?></span></p>
            <p class="post_meta"><span class="details">Archived in <?php the_category(', ') ?></span></p>
            <p class="post_meta"><span class="comments"><a href="#respond">Leave a comment</a></span></p>
            <div style="clear:both;height:20px;border-bottom:1px solid #eee;margin-bottom: 20px;"></div>
            
            <h4 class="tags">Tags</h4>
             <?php the_tags('', ', ', '<br />'); ?> 
             
             <div style="clear:both;height:10px;border-bottom:1px solid #eee;margin-bottom: 20px;"></div>
             
             <div class="navigation">
					<div class="previous">
                    <h4>Previous Post</h4>
					<p><?php previous_post_link('%link') ?></p>
                    </div>
					<div class="next">
                    <h4>Next Post</h4>
					<p><?php next_post_link('%link') ?></p>
                    </div>
				</div>
                
                 <div style="clear:both;height:10px;border-bottom:1px solid #eee;margin-bottom: 20px;"></div>

            </div>
			<div id="main" class="grid_12 omega">
			
            <div class="entry">
                
             <!--   <?php woo_get_image('image','700','467'); ?> -->
				
				<?php the_content(); ?>
                
                 <?php edit_post_link(__('Edit this post.'), ' &#45; ', ''); ?>
                
            </div>
            
<?php comments_template(); ?>

<?php endwhile; endif; ?>
			
			</div><!-- / #main -->
            
<?php get_footer(); ?>