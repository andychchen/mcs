<?php get_header(); ?>

	<?php $GLOBALS['homepage'] = 'true';?>    
    <!-- Featured Slider -->
    <?php include(TEMPLATEPATH . '/includes/featured.php'); ?>
	
    <div id="albums">
    <div class="container_16">
    		
            <?php 
                $exclude = get_exclude_categories("woo_cat_box_");
                
                $getcats = get_categories('hierarchical=0&hide_empty=0&exclude=' . $exclude); 

                $track = array();
                foreach ( $getcats as $cat ) { ?>		
                    
                        <?php 
                        $count = 0; 
                        $cat_id = $cat->cat_ID;
                        query_posts("cat=$cat_id&showposts=100"); 
                        if (have_posts()) : while (have_posts()) : the_post(); 
                        if (!in_array($post->ID,$track) ) {
                        ?>
                        <div class="grid_4">
                            
						 	<div class="category-image-block"><a href="<?php echo get_category_link($cat_id);?>"><?php woo_get_image('image','220','150'); ?></a></div>
                            <p class="category"><a href="<?php echo get_category_link($cat_id); ?>"><?php single_cat_title() ?></a></p>
                         
                         </div>
                         <?php
                        
                         $count++;
                         }
                         $track[] = $post->ID;
                         if($count >= 1) break;
                     
                      endwhile; endif; 
                      ?>

                    <?php } ?>	

    </div><!-- / #container_16 -->
    </div><!-- / #albums -->
			
<?php get_footer(); ?>