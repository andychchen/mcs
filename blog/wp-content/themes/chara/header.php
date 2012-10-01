<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">

<!-- BEGIN html head -->
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
<!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/ie.css" /><![endif]-->
<?php if (function_exists('wp_enqueue_script') && function_exists('is_singular')) : ?>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php endif; ?>
<?php wp_head(); ?>
</head>
<!-- END html head -->

<body>

<!-- BEGIN wrapper -->
<div id="wrapper">
	
	<!-- BEGIN header -->
	<div id="header">
		<ul>
		<li><a href="<?php echo get_option('home'); ?>">Home</a></li>
		<?php dp_list_pages(); ?>
		</ul>
		<p class="links">
		Subscribe:
		<a href="<?php bloginfo('rss2_url'); ?>">Posts</a> |
		<a href="<?php bloginfo('comments_rss2_url'); ?>">Comments</a> |
		<a href="#">Email</a>
		</p>
		<div class="break"></div>
		<div class="logo">
		<h1><a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a></h1>
		<p><?php bloginfo('description'); ?></p>
		</div>
		<div class="ad468x60">
		<a href="#"><img src="<?php bloginfo('template_url'); ?>/images/ad468x60.jpg" alt="Advertisement" /></a>
		</div>
	</div>
	<!-- END header -->
	
	<!-- BEGIN body -->
	<div id="body">
