<?php get_header(); ?>

	<div id="content" class="narrowcolumn">

	<?php if (have_posts()) : ?>

		<h2 class="pagetitle">Search Results</h2>

	


		<?php while (have_posts()) : the_post(); ?>

			<div class="post">
	<div class="comments"><?php comments_popup_link('NO COMMENTS', '<span> 1 </span> COMMENTS', '<span> % </span> COMMENTS'); ?></div>
				<div class="PostHead">

<div class="PostTime"><?php the_time('<b>j</b> <a>M Y</a>') ?> </div>
<h2><a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
<small class="PostDet"><?php edit_post_link('Edit', '', ' | '); ?> Author: <?php the_author() ?> | Filed under: <?php the_category(', ') ?></small>

</div>

				<p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?>   </p>
			</div>

		<?php endwhile; ?>


	<?php else : ?>

		<h2 class="pagetitle">No posts found. Try a different search?</h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>

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