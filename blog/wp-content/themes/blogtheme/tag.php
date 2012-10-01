<?php get_header(); ?>
	
	<div id="contentcontainer" class="grid_9 alpha">

	<div id="content">
		
		<?php $counter = 0; ?>
	
		<?php if (have_posts()) : ?>
		
			<?php while (have_posts()) : the_post(); ?>
				
				<?php $counter++; ?>
				
				<div class="post">
					
					<div class="meta grid_2 alpha">
					
						<ul>
							<li class="auth"><em>By</em> <?php the_author_posts_link(); ?></li>
							<li class="date"><?php the_time('d/m/y'); ?> at <?php the_time('H:i'); ?></li>
							<li class="comms"><a href="<?php comments_link(); ?>"><?php comments_number('0','1','%'); ?> Comments</a></li>
						</ul>
		
					</div><!--grid_2-->

					<div class="postbody grid_7 omega <?php if ( $counter == 1 ) { ?>first<?php } ?>">
						
						<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
						
						<div class="entry">
							 <?php if ( get_option('woo_the_content') ) { the_content(); } else { the_excerpt(); ?><p><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">Continue Reading...</a></p><?php } ?>				
						</div><!--entry-->	
					
					</div><!--grid_7-->
					
					<div class="clearfix"></div>
					
				</div>
			
			<?php endwhile; ?>		
		
		<?php endif; ?>
	
	</div><!--content-->

			<div class="clearfix"></div>
			
			<div id="postnav">

				<div class="fl"><?php next_posts_link('&laquo; Older Entries') ?></div>
				<div class="fr"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
				
				<div class="clearfix"></div>
			
			</div><!--postnav-->			
	
	</div><!--contentcontainer-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>