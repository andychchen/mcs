<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/favicon.ico" />


<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<!--[if IE 6]>
<style type="text/css">
.comment-form{margin-bottom: -3px}
</style>
<![endif]-->

<?php wp_head(); ?>
</head>
<body id="top">
<div id="container">
    <div id="wrapper">

        <div id="header">
            <div id="logo">
                <!-- Your Logo image - max-width: 200px -->
                <!-- USAGE:
                	1-create your own logo and save it as logo.jpg in the folder "images" in your theme folder.
                	2-uncomment code below:
                <a class="logo" href="<?php bloginfo('home')?>/" title="Back Home"></a>
                -->
        	    <h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
            </div>

		    <!-- ADMIN NAVIGATION -->
            <ul id="navAdmin">
                <?php wp_register(); ?>
                <li><?php wp_loginout(); ?></li>
                <?php wp_meta(); ?>
			</ul>

		    <!-- MAIN NAVIGATION -->
            <?php /* http://codex.wordpress.org/Template_Tags/wp_page_menu */
                wp_page_menu('show_home=1&sort_column=menu_order');
            ?>
            
            <!-- Alternative navigation
            <ul class="menu">
            	<li class="<?php if ( is_home() || is_single() || is_search() ) { echo "current_page_item"; } ?>"><a href="<?php echo get_option('home'); ?>/">Home</a></li>
				<?php wp_list_pages('title_li='); ?>
            	<li><a href="http://yourExternalLinkHere.com">External Link</a></li>								
			</ul>  -->
			
			<!-- / END MAIN NAVIGATION -->
            

            <!-- FEED -->
            <a id="rss" href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>">SUBSCRIBE TO <b>RSS</b></a>

        </div>

        <div id="pageWrapper">
