<?php

// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->
<div class="clear"></div>

<?php if ( have_comments() ) : ?>
<?php if ( ! empty($comments_by_type['comment']) ) : ?>
    <div class="left-sidebar">
	    <h3 id="comments"><?php comments_number('No Comments','1 Comment', '% Comments' );?></h3>
    </div>

	<div class="nav nav-padding">
	    <div class="alignleft"><?php previous_post('&laquo; %', 'Previous Post', 'no'); ?>&nbsp;</div>
		<div class="alignright">&nbsp;<?php next_post('% &raquo; ', 'Next Post', 'no'); ?></div>
	</div>

    <ul class="commentlist">
		<!-- To theme comments, open function.php -->  
        <?php wp_list_comments('type=comment&callback=mytheme_comment'); ?>
    </ul>    

	<div class="nav">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
<?php endif; ?>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->
        <div class="left-sidebar">
    	    <h3 id="comments">No Comments</h3>
        </div>

    	<div class="nav nav-padding">
    	    <div class="alignleft"><?php previous_post('&laquo; %', 'Previous Post', 'no'); ?>&nbsp;</div>
    		<div class="alignright">&nbsp;<?php next_post('% &raquo; ', 'Next Post', 'no'); ?></div>
    	</div>

	 <?php else : // this is displayed if there comments are closed ?>
		<!-- If comments are closed -->
    	<div class="nav nav-padding nav-border-bottom">
    	    <div class="alignleft"><?php previous_post('&laquo; %', 'Previous Post', 'no'); ?>&nbsp;</div>
    		<div class="alignright">&nbsp;<?php next_post('% &raquo; ', 'Next Post', 'no'); ?></div>
    	</div>
    	

     <?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<div id="respond">
    <?php if ( get_option('comment_registration') && !$user_ID ) : ?>
    <div class="left-sidebar">
        <h3><?php comment_form_title( 'Leave a Reply'); ?></h3>
    </div>

    <div class="comment-content">
        <p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>
    </div>

    <?php else : ?>
    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

        <div class="left-sidebar">
            <h3><?php comment_form_title( 'Leave a Reply','Leave a Reply<br /> to %s'); ?></h3>

            <div class="cancel-comment-reply">
            	<small><?php cancel_comment_reply_link(); ?></small>
            </div>

            <?php if ( $user_ID ) : ?>

            <p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>.<br /> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>
            <p>&nbsp;</p>

            <?php else : ?>

            <p><input class="field" type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
            <label for="author"><small>Name <?php if ($req) echo "(required)"; ?></small></label></p>

            <p><input class="field" type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
            <label for="email"><small>Mail <?php if ($req) echo "(required)"; ?></small></label></p>

            <p><input class="field" type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
            <label for="url"><small>Website</small></label></p>

            <?php endif; ?>

            <p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit" />
            <?php comment_id_fields(); ?>
            </p>
            <?php do_action('comment_form', $post->ID); ?>
        </div>

        <div class="form-content comment-form">
            <textarea name="comment" id="comment" cols="56" rows="5" tabindex="4"></textarea>
            <!--<p><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></p>-->
            <p>You can follow any responses to this entry through the <?php post_comments_feed_link('RSS 2.0'); ?> feed.</p>
        </div>
    <div class="clear"></div>
    </form>

    <?php endif; // If registration required and not logged in ?>

    <div class="clear"></div>
</div>
<div class="clear"></div>

<!-- Trackbacks/Pingbacks -->	
<?php if ( have_comments() ) : ?>
<?php if ( ! empty($comments_by_type['pings']) ) : ?>

	<div class="post">
		<h3 style="margin:0px;">Trackbacks / Pingbacks</h3>	
			<ul>
				<!-- To theme Trackbacks/Pingbacks, open function.php -->  
				<?php wp_list_comments('type=pings&callback=mytheme_trackbacks'); ?>
			</ul>
	</div>
<?php endif; ?>	

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>

	 <?php else : // this is displayed if there comments are closed ?>
		<!-- If comments are closed -->
     <?php endif; ?>
<?php endif; ?>	
	

<?php endif; // if you delete this the sky will fall on your head ?>
