<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) { ?>
			<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
			<?php return;
		}
	}
	/* This variable is for alternating comment background */
	$oddcomment = 'class="alt" ';
?>

<!-- Start editing here. -->

<?php if ($comments) : ?>
	<h3 class="header"><?php comments_number('No Comments', 'One Comment', '% Comments' );?> on &#8220;<?php the_title(); ?>&#8221;</h3>

	<ul class="commentlist">

	<?php foreach ($comments as $comment) : ?>

		<li class="post" id="comment-<?php comment_ID() ?>">

					<div class="meta grid_2 alpha">
					
						<ul>
							<li class="gravatar"><?php echo get_avatar( $comment, $size = '32' ); ?></li>
							<li class="auth"><em>By</em> <?php comment_author_link(); ?></li>
							<li class="date"><a href="<?php the_permalink() ?>#comment-<?php comment_ID() ?>"><?php comment_date('d/m/y'); ?> at <?php comment_time('H:i'); ?></a></li>
						</ul>
		
					</div><!--grid_2-->

					<div class="postbody grid_7 omega <?php if ( $counter == 1 ) { ?>first<?php } ?>">
						
						<div class="entry">							 
							 <?php comment_text(); ?>
						</div><!--entry-->	
					
					</div><!--grid_7-->
					
					<div class="clearfix"></div>		

		</li>

		<?php $oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : ''; ?>

	<?php endforeach; ?>

	</ul>

	<?php else : // this is displayed if there are no comments so far ?>

		<?php if ('open' == $post->comment_status) : ?>

		<?php else : ?>
		<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<h3 class="header" id="respond">Hi, Stranger! Leave Your Comment...</h3>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p class="right">You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>

<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

	<p class="right">Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Log out &raquo;</a></p>

<?php else : ?>
	
	<div class="textfield">
		
		<div class="textlabel">Name <?php if ($req) echo "(required)"; ?></div>
		<div class="thefield"><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" class="field" /></div>
	
	</div><!--textfield-->

	<div class="textfield">
		
		<div class="textlabel">Email <?php if ($req) echo "(required)"; ?></div>
		<div class="thefield"><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" class="field" /></div>
	
	</div><!--textfield-->

	<div class="textfield">
		
		<div class="textlabel">Website</div>
		<div class="thefield"><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" class="field" /></div>
	
	</div><!--textfield-->		

<?php endif; ?>

	<div class="textfield">
		
		<div class="textlabel">Message</div>
		<div class="thefield"><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></div>
	
	</div><!--textfield-->

	<div class="textfield">
		
		<div class="textlabel">&nbsp;</div>
		<div class="thefield"><input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" /><input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" /></div>
	
	</div><!--textfield-->	

<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; endif; ?>