<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_get_archives('type=yearly&format=link'); ?>
	<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>
	<?php if(is_singular()){ wp_enqueue_script('comment-reply');}?>
	<?php wp_enqueue_script('jquery');?>
	<?php wp_head(); ?>
	<script type='text/javascript' src='<?php bloginfo("template_directory");?>/coffeedesk.js'></script>
	<!--[if IE]>
		<style type="text/css">
		#menu ul li a{
			background:none;
			filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/images/bg_menu1.png', sizingMethod='scale');
		}
		#menu ul li a:hover, #header ul li.current_page_item a {
			background:none;
			filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php bloginfo('template_directory'); ?>/images/bg_menu2.png', sizingMethod='scale');
		}
		</style>
	<![endif]-->	
</head>
<body>
<div id="base_top"><div id="base_bottom">
		<div id="base_bg"><div id="base">
			<div id="header">
				<div id="blogtitle"><a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a></div>
				<div id="subtitle"><?php bloginfo('description');?></div>
				<div id="menu"><ul><?php
						$options = get_option('widget_pages'); 
						$exclude = empty($options['exclude'] ) ? '' : $options['exclude']; 
						wp_list_pages('sort_column=menu_order&depth=1&title_li=&exclude='.$exclude);
						//wp_page_menu('sort_column=menu_order&depth=1&title_li=&exclude='.$exclude);
					?>
				</ul></div>
				<div id="mainsearch">
					<form id="mainsearchform" action="<?php bloginfo('url'); ?>/" method="get">
					<input class="input" type="text" value="Search ..." onfocus="if (this.value == 'Search ...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search ...';}" name="s" id="s" />
					<input class="submit" value="" type="submit"/>
					</form>
				</div>
				<a href="<?php bloginfo('url'); ?>/" title="Home"><img src="<?php bloginfo('template_directory'); ?>/images/spacer.gif" alt="Home" class="home"/></a>
			</div>
			<div id="container">