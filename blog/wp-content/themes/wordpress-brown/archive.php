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
                                    
                                    <span><?php the_time('M dS') ?> by <?php the_author(); ?></span>
                                    <a class="continue" href="<?php the_permalink() ?>">Continue Reading</a>
                                   
                            </div> <!--entry ends-->
                    	</div> <!--post ends-->
                        
                        <?php endwhile; ?>

<?php else : ?>
<div class="blog-main">
<h2 class="not-found">Not Found</h2>
<p >Sorry, but you are looking for something that isn't here.</p>
</div>
<?php endif; ?>
                        
                        
                        <div class="page_navigation">
                        	<p>Page 1 of 38</p>
                            	<ul>
                        			<li class="active"><a href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#">5</a></li>
                                    <li>...</li>
                                    <li><a href="#">Last</a></li> 
                        		</ul>
                        </div> <!--page navigation ends-->
                    </div> <!--left_column ends-->
                    
                     <?php get_sidebar(); ?><!--right_column ends-->
            	</div> <!--content text ends-->
                
                
            </div>




<?php get_footer(); ?>
