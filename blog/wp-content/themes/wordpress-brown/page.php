<?php get_header(); ?>
<div id="content">
            	<div id="content_text">
                	<div id="left_column">
                    <?php if (have_posts()) : ?>

<?php while (have_posts()) : the_post(); ?>
                    	<div class="post">
                    		<div class="post_heading">
                            	<span class="date"><?php the_time('dS') ?></span>
                                	<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                                    	<span class="comments"><?php comments_popup_link(0, 1, '%', '', ''); ?></span>
                            </div> <!--post heading ends-->
                            
                            <div class="entry">
                            	
                                	<?php the_content('(Continue Reading)'); ?>
                                    
                                    
                                   
                            </div> <!--entry ends-->
                    	</div> <!--post ends-->
                        
                        <?php endwhile; ?>

<?php else : ?>
<div class="blog-main">
<h2 class="not-found">Not Found</h2>
<p >Sorry, but you are looking for something that isn't here.</p>
</div>
<?php endif; ?>
                        
                        
                         <!--page navigation ends-->
                    </div> <!--left_column ends-->
                    
                     <?php get_sidebar(); ?><!--right_column ends-->
            	</div> <!--content text ends-->
                
                
            </div>




<?php get_footer(); ?>
