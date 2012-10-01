<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

<title>
<?php if ( is_home() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php bloginfo('description'); ?><?php } ?>
<?php if ( is_search() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Search Results<?php } ?>
<?php if ( is_author() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Author Archives<?php } ?>
<?php if ( is_single() ) { ?><?php wp_title(''); ?>&nbsp;|&nbsp;<?php bloginfo('name'); ?><?php } ?>
<?php if ( is_page() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php wp_title(''); ?><?php } ?>
<?php if ( is_category() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Archive&nbsp;|&nbsp;<?php single_cat_title(); ?><?php } ?>
<?php if ( is_month() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Archive&nbsp;|&nbsp;<?php the_time('F'); ?><?php } ?>
<?php if (function_exists('is_tag')) { if ( is_tag() ) { ?><?php bloginfo('name'); ?>&nbsp;|&nbsp;Tag Archive&nbsp;|&nbsp;<?php  single_tag_title("", true); } } ?>
</title>

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/reset.css" type="text/css" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/960.css" type="text/css" />	
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />

<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php if ( get_option('woo_feedburner_url') <> "" ) { echo get_option('woo_feedburner_url'); } else { echo get_bloginfo_rss('rss2_url'); } ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!-- Show custom logo -->
<?php if ( get_option('woo_logo') <> "" ) { ?><style type="text/css">#logo h1 {background: url(<?php echo get_option('woo_logo'); ?>) no-repeat !important; }</style><?php } ?> 
    
<!--[if lt IE 7]>
<script src="<?php bloginfo('template_directory'); ?>/includes/js/pngfix.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/ie6.css" type="text/css" />	
<![endif]-->

<?php wp_enqueue_script('jquery'); ?>     
<?php wp_head(); ?>

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/includes/js/jquery.ui.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("#sidebar_accordian").accordion({ header: "h4", autoHeight: false });
	});
</script>
   
</head>

<body>

	<div id="header">
		
		<div class="container_12">
		
			<div id="logo" class="grid_4 alpha">
		
				<h1><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>		
			
			</div>	<!--grid_6-->

			<div id="twitter" class="grid_8 omega">
				<ul id="twitter_update_list"><li></li></ul>
			</div>	<!--grid_6-->
			
			<div class="clearfix"></div>			
		
		</div><!--container_12-->								
	
	</div><!--header-->
	
	<div id="nav">
		
		<div class="container_12">

				<ul id="navigation">

					<li><a <?php if ( is_home() ) { ?>class="current_page_item"<?php } ?> href="<?php bloginfo('url'); ?>">
							Home
							<span>Where it all began</span>
						</a>
					</li>
					<?php woothemes_get_pages(); ?>

				</ul><!--navigation -->	
	
		</div><!--container_12-->
	
	</div><!--nav-->	
	
	<div id="outerwrap" class="container_12">
	
		<div id="contentwrap">
		
		<div id="wrap">
