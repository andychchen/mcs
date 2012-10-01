<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php if (is_home () ) { bloginfo(�name�); }
elseif ( is_category() ) { single_cat_title(); echo ' - ' ; bloginfo(�name�); }
elseif (is_single() ) { single_post_title();}
elseif (is_page() ) { single_post_title();}
else { wp_title(��,true); } ?></title>
<meta name="robots" content="index,follow" />
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_head(); ?>
</head>

<body>
<div class="content">
	<div class="header">
    	<div class="container">
			<div class="menu">
            	<ul class="main-menu">
					<li><a href="<?php echo get_option('home'); ?>/" title="Home">Home</a></li>
                	<?php wp_list_pages('depth=1&title_li=0&sort_column=menu_order'); ?>
                </ul>
            </div>
            <div class="subcribe">
            	<h2>Subscribe:</h2>
                <ul class="subcribe-menu">
                	<li><a href="<?php bloginfo('rss2_url'); ?>">Posts</a></li>
                    <li class="last"><a href="<?php bloginfo('comments_rss2_url'); ?>">Comments </a></li>
                </ul>
            </div>
            <div class="clear"></div>
            <div class="logo">
            	<h1><a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a></h1>
                <h2><?php bloginfo('description'); ?></h2>
            </div>
            <div class="search">
            	<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
                <div class="search-input"><input type="text" value="<?php the_search_query(); ?>" name="s" id="searchbox" class="input"  /></div>
                <div class="search-button"><input type="image" src="<?php echo bloginfo('stylesheet_directory'); ?>/images/search-button.jpg" id="searchbutton" class="button"  /></div>
            	</form>
            </div>            
        </div>
	</div>
    <div class="banner-bg">
    	<div class="container">
        	<div class="banner-main">
            	<div class="banner-prev"><a href="#">Prev</a></div>
				<div class="banner-center">                
                <img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/banner.jpg" alt="" />
                </div>
                <div class="banner-next"><a href="#">Next</a></div>                
            </div>
            <div class="ad-bg">
            	<ul class="advertisement">
                	<li><a href="#"><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/pic2.jpg" alt="" /></a></li>
                    <li><a href="#"><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/pic2.jpg" alt="" /></a></li>
                    <li><a href="#"><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/pic2.jpg" alt="" /></a></li>
                    <li><a href="#"><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/pic2.jpg" alt="" /></a></li>
                </ul>
            </div>
        </div>
    </div>
    
    