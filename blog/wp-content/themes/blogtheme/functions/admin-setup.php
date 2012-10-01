<?php

// Options panel stylesheet
function woothemes_admin_head() { 
	echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/functions/admin-style.css" media="screen" />';
}

$options = array();
global $options;

$GLOBALS['template_path'] = get_bloginfo('template_directory');

$layout_path = TEMPLATEPATH . '/layouts/'; 
$layouts = array();

$alt_stylesheet_path = TEMPLATEPATH . '/styles/';
$alt_stylesheets = array();

$ads_path = TEMPLATEPATH . '/images/ads/';
$ads = array();

$woo_categories_obj = get_categories('hide_empty=0');
$woo_categories = array();

$woo_pages_obj = get_pages('sort_column=post_parent,menu_order');
$woo_pages = array();

if ( is_dir($layout_path) ) {
	if ($layout_dir = opendir($layout_path) ) { 
		while ( ($layout_file = readdir($layout_dir)) !== false ) {
			if(stristr($layout_file, ".php") !== false) {
				$layouts[] = $layout_file;
			}
		}	
	}
}	

if ( is_dir($alt_stylesheet_path) ) {
	if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
		while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
			if(stristr($alt_stylesheet_file, ".css") !== false) {
				$alt_stylesheets[] = $alt_stylesheet_file;
			}
		}	
	}
}	

if ( is_dir($ads_path) ) {
	if ($ads_dir = opendir($ads_path) ) { 
		while ( ($ads_file = readdir($ads_dir)) !== false ) {
			if((stristr($ads_file, ".jpg") !== false) || (stristr($ads_file, ".png") !== false) || (stristr($ads_file, ".gif") !== false)) {
				$ads[] = $ads_file;
			}
		}	
	}
}

foreach ($woo_categories_obj as $woo_cat) {
	$woo_categories[$woo_cat->cat_ID] = $woo_cat->cat_name;
}

foreach ($woo_pages_obj as $woo_page) {
	$woo_pages[$woo_page->ID] = $woo_page->post_name;
}

$other_entries = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
$categories_tmp = array_unshift($woo_categories, "Select a category:");
$woo_pages_tmp = array_unshift($woo_pages, "Select a page:");

// OTHER FUNCTIONS

if ( function_exists('register_sidebar') ) {
    register_sidebars(1,array('name' => 'Sidebar','before_widget' => '<div class="widget">','after_widget' => '</div></div>','before_title' => '<h4><a href="#">','after_title' => '</a></h4><div class="content">'));
    register_sidebars(3,array('name' => 'Footer %d','before_widget' => '','after_widget' => '','before_title' => '<h4>','after_title' => '</h4>'));    
}

// Check for widgets in widget-ready areas http://wordpress.org/support/topic/190184?replies=7#post-808787
// Thanks to Chaos Kaizer http://blog.kaizeku.com/
function is_sidebar_active( $index = 1){
	$sidebars	= wp_get_sidebars_widgets();
	$key		= (string) 'sidebar-'.$index;
 
	return (isset($sidebars[$key]));
}

$bm_trackbacks = array();
$bm_comments = array();

function split_comments( $source ) {

    if ( $source ) foreach ( $source as $comment ) {

        global $bm_trackbacks;
        global $bm_comments;

        if ( $comment->comment_type == 'trackback' || $comment->comment_type == 'pingback' ) {
            $bm_trackbacks[] = $comment;
        } else {
            $bm_comments[] = $comment;
        }
    }
} 

// Show menu in header.php
// Exlude the pages from the slider
function woo_show_pagemenu( $exclude="" ) {
	// Split the featured pages from the options, and put in an array
	if ( get_option('woo_ex_featpages') ) {
		$menupages = get_option('woo_featpages');
		$exclude = $menupages . ',' . $exclude;
	}
	
	$pages = wp_list_pages('sort_column=menu_order&title_li=&echo=0&depth=1&exclude='.$exclude);
	$pages = preg_replace('%<a ([^>]+)>%U','<a $1><span>', $pages);
	$pages = str_replace('</a>','</span></a>', $pages);
	echo $pages;
}

// Get the style path currently selected
function woo_style_path() {

	$style = $_REQUEST[style];
	
	if ($style != '') {

		$style_path = $style;

	} else {
		
		$stylesheet = get_option('woo_alt_stylesheet');
		$style_path = str_replace(".css","",$stylesheet);
	
	}
	
	echo 'styles/'.$style_path;
	
}


?>