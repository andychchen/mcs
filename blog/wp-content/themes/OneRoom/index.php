<?php get_header(); ?>

<div id="content">

    <?php include (TEMPLATEPATH . '/intro.php'); ?>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

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
                <?php comments_popup_link('No Comments', '1 Comment &#187;', '% Comments &#187;', 'meta-comments', 'Comments off'); ?>
                <p class="meta-categories"><?php the_category(', ') ?></p>
                <?php the_tags('<p class="meta-tags">',', ','</p>'); ?>
			</div>

			<div class="post" id="post-<?php the_ID(); ?>">
				<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

				<div class="entry">
					<?php the_content('<span class="more">More &raquo;</span>'); ?>
				</div>
            </div>
        </div>

    <?php endwhile; ?>

		<div class="nav nav-border-bottom">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?>&nbsp;</div>
			<div class="alignright">&nbsp;<?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>

	<?php else : ?>

		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isn't here.</p>
		<?php get_search_form(); ?>

	<?php endif; ?>

</div> <!-- / content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
