		<div id="right">
				
				<?php if ( is_single() ) { ?>
				
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<div id="post-meta" class="box">
				
						<p>You are currently reading <strong>"<?php the_title(); ?>"</strong></p>

						<p><strong>Published:</strong> <?php the_time('d.m.Y'); ?> / <?php the_time('h:i A'); ?></p>

						<p><strong>Category:</strong> <?php the_category(','); ?></p>
						
						<?php the_tags('<p><strong>Tags:</strong> ', ', ', '</p>'); ?>
				
					</div><!-- /post-meta -->
					
					<?php if ( get_option( 'woo_related' ) ) { ?>
			
					<div id="related" class="box">
			
						<span class="heading">Related Articles</span>
				
						<ul>
							<?php rp_related_posts(''); ?>
						</ul>
			
					</div><!-- /related -->
					
					<?php } ?>
					
					<?php endwhile; endif; ?>				
				
				<?php } ?>

				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(2) ) : ?>
			
				<?php endif; ?>	
			
			<div class="clear"></div>
					
		</div><!-- /right -->
