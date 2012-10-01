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
   <div id="wrapper">
    <div id="container">
    	<div id="main">
        	<div id="navi">
            	<ul>
                	<li><a href="<?php echo get_option('home'); ?>/" title="Home">Home</a></li>
                	<?php wp_list_pages('depth=1&title_li=0&sort_column=menu_order'); ?>
        		</ul>                
                <ul class="subscribe">
                	<li class="nobg"><strong>Subscribe:</strong>&nbsp;&nbsp; <a href="<?php bloginfo('rss2_url'); ?>">Posts</a></li>
                    <li><a href="<?php bloginfo('comments_rss2_url'); ?>">Comments </a></li>
                </ul>
        	</div> <!--navi ends-->
            
            <div id="header">
            	<a id="logo" class="replace" href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a>
					<input type="text" class="keywords" value="Enter Keyword(s)"/>
                    	<div id="adds">
                        	<ul>
                        		<li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/add.jpg" alt="" /></li>
                        		<li class="nomargin"><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/add.jpg" alt="" /></li>
                                <li><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/add.jpg" alt="" /></li>
                              <li class="nomargin"><img src="<?php echo bloginfo('stylesheet_directory'); ?>/images/add.jpg" alt="" /></li>
                        	</ul>
                        </div> <!--adds ends-->
                        
              <div id="welcome">
   				<h2 class="welcome replace">Welcome</h2>
                            	<p>Welcome to our new blog! This is what we call a change!
								Check out below our lates work!</p>
   			  </div> <!--welcome ends-->
                        
                        <div id="carousel">
                        	<a href="#" class="left">Left</a>
                        
                        	<a href="#" class="right">Right</a>
                        </div> <!--carousel ends-->
          </div> <!--header end-->
    