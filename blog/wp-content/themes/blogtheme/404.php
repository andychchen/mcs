<?php get_header(); ?>
	
	<div id="contentcontainer" class="grid_9 alpha">

	<div id="content">
		
				
				<?php $counter++; ?>
				
				<div class="post">
					
					<div class="meta grid_2 alpha">
					
						<ul>
							<li class="auth"><em>404</em> Error!</li>
						</ul>
		
					</div><!--grid_2-->

					<div class="postbody grid_7 omega first">
						
						<h2>Sorry! None has been found...</h2>
						
						<div class="entry">
							 <p>Unfortunately we were not able to find that page you were looking for...</p>
							 <p>We suggest that you try going back one page or visit <a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>">our homepage</a> instead.</p>
						</div><!--entry-->	
					
					</div><!--grid_7-->
					
					<div class="clearfix"></div>
					
				</div>
	
	</div><!--content-->
	
		<div class="clearfix"></div>
	
	</div><!--contentcontainer-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>