<?php get_header(); ?>

<div id="content">

        <div id="intro">
            <h2 class="pagetitle">Err... You got me! Error 404 - <span>Page Not Found or Does Not Exist!</span></h2>
            <p>But don't panic! All is not lost. Search for what you are looking for in the search bar on your right or browse one of the link below.</p>
        </div>
        <div class="postWrapper">
            <div class="post">
    			<div class="entry">
                    <?php include (TEMPLATEPATH . '/links.php'); ?>
    			</div>
    		</div>
        </div>

</div> <!-- / content -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>