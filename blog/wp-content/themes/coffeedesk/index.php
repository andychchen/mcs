<?php get_header(); ?>
<div id="content">
<?php 
	if ( is_home() ) echo '<div class="spacer">&nbsp;</div>';

	if (have_posts()) :
		$post = $posts[0]; // Hack. Set $post so that the_date() works.
		if(is_category()){
			echo '<h1 class="archivetitle">Archive for the Category &raquo;'.single_cat_title('',FALSE).' &laquo;</h1>';
		}elseif(is_day()){
			echo '<h1 class="archivetitle">Archive for &raquo; '.get_the_time('F jS, Y').'&laquo;</h1>';
		}elseif(is_month()){
			echo '<h1 class="archivetitle">Archive for &raquo; '.get_the_time('F, Y').' &laquo;</h1>';
		}elseif(is_year()){
			echo '<h1 class="archivetitle">Archive for &raquo; '.get_the_time('Y').' &laquo;</h1>';
		} elseif(is_search()){
			echo '<h1 class="archivetitle">Search Results</h1>';
		}elseif(is_author()){
			echo '<h1 class="archivetitle">Author Archive</h1>';
		}elseif(isset($_GET['paged']) && !empty($_GET['paged'])){ // If this is a paged archive
			echo '<h1 class="archivetitle">Blog Archives</h1>';
		}elseif(is_tag()){ //
			echo '<h1 class="archivetitle">Tag-Archive for &raquo; '.single_tag_title('',FALSE).' &laquo; </h1>';
		}
		
		while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
				<div class="date">
					<div class="date_d"><?php the_time('d');?></div>
					<div class="date_m"><?php the_time('M');?></div>
					<div class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></div>
					<div class="author"><?php the_author_posts_link('nickname'); ?> <?php edit_post_link(' &raquo; Edit &laquo;','|',''); ?></div>
				</div>
				<div class="entry">
					<?php if (is_search()){
							the_excerpt();
						}else{
							the_content('more...'); 
						}
					?>
				</div>
				<div class="clear"></div>
				<div class="info">
					<span class="category">Category: <?php the_category(', ') ?></span>
					<?php the_tags('&nbsp;<span class="tags">Tags: ', ', ', '</span>'); ?>
					&nbsp;<span class="bubble"><?php comments_popup_link('Leave a Comment','One Comment', '% Comments', '','Comments off'); ?></span>
				</div>
			</div>
		<?php endwhile; ?>
		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Previous Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Next Entries &raquo;') ?></div>
		</div>

	<?php else : ?>
		<h1>Not found</h1>
		<p class="sorry">"Sorry, but you are looking for something that isn't here. Try something else.</p>
	<?php endif; ?>


</div>
<?php get_sidebar(); ?>
<?php get_footer();?>
