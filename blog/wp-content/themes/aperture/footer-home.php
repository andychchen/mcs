<div id="footerWrap" class="container_16">
    
    <div class="grid_4 alpha">                             
        <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('footer-1') )  ?>		           
    </div>
    
    <div class="grid_4">
        <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('footer-2') )  ?>		           
    </div>

    <div class="grid_8 omega">

        <div id="blog" class="widget">

            <h3 id="news">Latest blog posts</h3>
        
            <?php 
            $cat_id = get_cat_id(get_option('woo_blog_cat'));
            $post_count = get_option('woo_featured_posts');
            query_posts("cat=$cat_id&showposts=$post_count");
            
            if (have_posts()) :

              $counter = 0; $counter2 = 0;
              while (have_posts()) : the_post(); 
      
              $counter++; 
            ?>
                    
            <div class="grid_4 <?php if ($counter == 1) { echo 'alpha'; } else { echo 'omega'; $counter = 0; } ?>">
            
                <div class="box">

                    <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php woo_get_image('image','198','100'); ?></a>
                    <h4><a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h4>
                    <p class="post_meta"><span class="date"><?php the_time('F jS, Y') ?></span></p>
                    <p><?php echo strip_tags(get_the_excerpt(), '<a><strong>'); ?></p>
                 
                </div><!-- / #box -->
        
            </div><!-- / #grid_4 -->
        
            <?php if ($counter == 0) { ?><div class="fix" style="margin-bottom:20px;"></div><?php } ?>
      
            <?php endwhile; ?>
     
            <?php else: ?>	
                <p>No posts yet.</p>
            <?php endif; ?>

        </div><!-- / #blog -->

    </div><!-- / #grid_8 -->
    
</div><!-- / #footerWrap -->