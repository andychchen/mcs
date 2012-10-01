<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">

<!-- BEGIN html head -->
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
<!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/ie.css" /><![endif]-->
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/scripts.js"></script>
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
	<div id="header"><div class="buffer"><div class="buffer">
		<ul><?php dp_list_categories(null, 6); ?></ul>
		<h1><a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a></h1>
		<div class="ad">
		<a href="#"><img src="<?php bloginfo('template_url'); ?>/images/ad468x60.jpg" alt="Advertisement" /></a>
		</div>
		<ol>
		<li class="f"><a href="<?php echo get_option('home'); ?>">Home</a></li>
		<?php dp_list_pages(); ?>
		</ol>
		<form action="<?php echo get_option('home'); ?>">
		<input type="text" name="s" id="s" value="<?php the_search_query(); ?>" />
		<button type="submit">Search</button>
		</form>
	</div></div></div>
	<!-- END header -->
	
	<!-- BEGIN body -->
	<div id="body">
