<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/ie.css" media="screen" />
<![endif]-->

<?php wp_head(); ?>
</head>
<body>
	<div class="container">
		<div class="edge"></div>
		

		<div class="masthead-container">
			<div class="search-container">
				<div class="search">
					<?php include ('searchform.php'); ?>
				</div><!-- end search -->
			</div>

			<h1 class="logo"><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1><!-- end logo -->
			<div class="tools">
				<ul>
				<li class="email"><a href="#">Email</a></li>
				<li class="rss"><a href="<?php bloginfo('rss2_url'); ?>">RSS</a></li>
				</ul>
				
			</div>

		</div><!-- end masthead container -->








