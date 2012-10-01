<?php get_header(); ?>

<div id="content">

	<?php if (have_posts()) : ?>

        <div id="intro">
            <h2 class="pagetitle">Search Results for <span>'<?php the_search_query(); ?>'</span></h2>
        </div>

		<?php while (have_posts()) : the_post(); ?>

        <div class="postWrapper">

            <!-- META -->
		    <div class="postmetadata">
                <p class="meta-date">
				    <span class="date-day"><?php the_time('j') ?></span>
                    <span class="date-month"><?php the_time('M') ?></span>
					<span class="date-year"><?php the_time('Y') ?></span>
                </p>
                <p class="meta-author">by <?php the_author() ?></p>
                <?php edit_post_link('<p class="meta-edit">Edit</p>', '', ''); ?>
                <?php comments_popup_link('<p class="meta-comments">No Comments</p>', '<p class="meta-comments">1 Comment &#187;</p>', '<p class="meta-comments">% Comments &#187;</p>'); ?>
                <p class="meta-categories"><?php the_category(', ') ?></p>
                <?php the_tags('<p class="meta-tags">',', ','</p>'); ?>
			</div>

			<div class="post" id="post-<?php the_ID(); ?>">
				<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

				<div class="entry">
					<?php the_excerpt(); ?>
                    <a class="more" href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">More &raquo;</a>
				</div>
            </div>
        </div>

		<?php endwhile; ?>

		<div class="nav nav-border-bottom">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?>&nbsp;</div>
			<div class="alignright">&nbsp;<?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>

	<?php else : ?>
        <div id="intro">
            <h2>No posts found for <span>'<?php the_search_query(); ?>'</span>.</h2>
            <p>Try a different search or browse one of the link below.</p>
        </div>
        <div class="postWrapper">
            <div class="post">
    			<div class="entry">
                    <?php include (TEMPLATEPATH . '/links.php'); ?>
    			</div>
    		</div>
        </div>



	<?php endif; ?>

</div> <!-- / content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>