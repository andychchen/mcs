<?php get_header(); ?>
<div id="blog-page-container">
	
	<? unset($pages); ?> 
    <br clear="all" />
    
    <div class="column01">
    <div id="post-one">
    
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<span class="top"></span>
  		<div class="main-post" id="post-<?php the_ID(); ?>">
    	<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
        <span class="meta">This page created <?php the_time('F') ?> <?php the_time('jS') ?> <?php the_time('Y') ?></span>
        <?php the_content('Read the rest of this entry &raquo;'); ?>
        <br clear="all" />
        </div>
        <span class="btm"></span>
        
<?php endwhile; ?>
<?php else: ?>
 <!-- Error message when no post published -->
<?php endif; ?>        
    </div>
</div>

    <br clear="all" />
    </div>
    <div class="spacer">
</div>
</div>
<div class="lower-outer">
	
<?php get_footer(); ?>