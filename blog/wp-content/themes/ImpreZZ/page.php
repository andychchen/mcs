<?php get_header(); ?>

	<div id="content" class="narrowcolumn innerpage">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		<h2 class="page_title"><?php the_title(); ?></h2>
			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div>
		</div>
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	</div>

<?php get_sidebar(); ?>
<div class="sidebar-right"> 
<div class="twitter">
<div class="twitter-content">
        <ul id="twitter_update_list"><li></li></ul></div>
		<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
        <script type="text/javascript" src="http://twitter.com/statuses/user_timeline/gopalraju.json?callback=twitterCallback2&amp;count=1"></script>
<a class="followme" href="http://twitter.com/gopalraju">Follow us on twitter</a>
</div>

<img src="<?php bloginfo('template_url'); ?>/images/pdlogo.gif" alt="productivedreams" class="pdlogo"/>

</div>



<div class="google-ads">

</div>


</div>

<?php get_footer(); ?>