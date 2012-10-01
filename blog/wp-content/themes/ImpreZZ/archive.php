<?php get_header(); ?>

	<div id="content" class="narrowcolumn">

		<?php if (have_posts()) : ?>

 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle">Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h2>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h2>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F jS, Y'); ?></h2>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F, Y'); ?></h2>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle">Author Archive</h2>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Blog Archives</h2>
 	  <?php } ?>


	

		<?php while (have_posts()) : the_post(); ?>
		
		<div class="post">
		
						<div class="entry"><?php the_content('Read the rest of this entry &raquo;'); ?></div>
	<div class="comments"><?php comments_popup_link('NO COMMENTS', '<i> 1 </i> COMMENTS', '<span> % </span> COMMENTS'); ?></div>
				<div class="PostHead">

<div class="PostTime"><?php the_time('<b>j</b> <a>M Y</a>') ?> </div>
<h2><a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
<small class="PostDet"><?php edit_post_link('Edit', '', ' | '); ?> Author: <?php the_author() ?> | Filed under: <?php the_category(', ') ?></small>

</div>

			

			</div>
	<p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?></p>
		<?php endwhile; ?>

	
	<?php else : ?>

		<h2 class="center">Not Found</h2>
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
