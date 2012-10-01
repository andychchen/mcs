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
                    
                </div>
                <div class="blog-main">
                	<div class="blog-content">
                    	<?php the_content('(Read the rest of this entry...)'); ?>
                        <p class="date"><?php the_time('M dS') ?> by <?php the_author(); ?></p>
                        
                    </div>
                </div>                
               <?php endwhile; ?>
               <br /> <br />
                
                <div class="clear"></div>
<div id="comment">
<?php comments_template(); ?>
</div> <!-- Closes Comment -->

<?php else : ?>
<div class="blog-main">
<h2 class="not-found">Not Found</h2>
<p>Sorry, but you are looking for something that isn't here.</p>
</div>
<?php endif; ?>
               
               
              

 
                
            </div>
            <div class="column-two">
            	<?php get_sidebar(); ?>
            </div>
            </div>
        </div>
    </div>
    
    
    
    



</div>



<?php get_footer(); ?>
