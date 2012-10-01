
		</div><!--wrap-->
		
		</div><!--contentwrap-->
	
	</div><!--outerwrap-->
	
	<div class="clearfix"></div>
	
	<div id="footer">
		
		<div class="container_12">
		
			<div class="grid_4 alpha">
				
				<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar(2) ) :endif; ?>	
		
			</div><!--grid_4-->

			<div class="grid_4">

				<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar(3) ) :endif; ?>	
		
			</div><!--grid_4-->

			<div class="grid_4 omega">

				<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar(4) ) :endif; ?>				
		
			</div><!--grid_4-->				
		
			<div class="clearfix"></div>
			
			<div id="credit">
				
				<p>&copy; Copyright <?php bloginfo('name'); ?>. All Rights Reserved. BlogTheme by <a href="http://www.woothemes.com"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/woothemes-trans.png" alt="WooThemes" id="woo" /></a></p>
				
			</div>
		
		</div><!--container_12-->
		
	</div>

<?php if(get_option('woo_twitter') !== "") { ?>
<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo get_option('woo_twitter'); ?>.json?callback=twitterCallback2&amp;count=1"></script>
<?php } ?>

<?php wp_footer(); ?>

<?php if ( get_option('woo_google_analytics') <> "" ) { echo stripslashes(get_option('woo_google_analytics')); } ?>

</body>
</html>