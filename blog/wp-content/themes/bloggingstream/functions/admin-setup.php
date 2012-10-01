<?php

// Register widgetized areas
if ( function_exists('register_sidebar') )
    register_sidebars(1,array('name' => 'Homepage','before_widget' => '<div id="%1$s" class="box widget %2$s">','after_widget' => '</div>','before_title' => '<span class="heading">','after_title' => '</span>'));
    register_sidebars(1,array('name' => 'Sidebar','before_widget' => '<div id="%1$s" class="box widget %2$s">','after_widget' => '</div>','before_title' => '<span class="heading">','after_title' => '</span>'));    
	

// Options panel stylesheet
function woothemes_admin_head() { 
	echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/functions/admin-style.css" media="screen" />';
}

$options = array();
global $options;

$GLOBALS['template_path'] = get_bloginfo('template_directory');

$layout_path = TEMPLATEPATH . '/layouts/'; 
$layouts = array();

$feat_layout_path = TEMPLATEPATH . '/featured-layouts/'; 
$feat_layouts = array();

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

if ( is_dir($feat_layout_path) ) {
	if ($feat_layout_dir = opendir($feat_layout_path) ) { 
		while ( ($feat_layout_file = readdir($feat_layout_dir)) !== false ) {
			if(stristr($feat_layout_file, ".php") !== false) {
				$feat_layouts[] = $feat_layout_file;
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
$header_layout = array("none","about.php","ad468x60.php");
$categories_tmp = array_unshift($woo_categories, "Select a category:");
$woo_pages_tmp = array_unshift($woo_pages, "Select a page:");

// OTHER FUNCTIONS

function gravatar($rating = false, $size = false, $default = false, $border = false) {
	global $comment;
	$out = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($comment->comment_author_email);
	if($rating && $rating != '')
		$out .= "&amp;rating=".$rating;
	if($size && $size != '')
		$out .="&amp;size=".$size;
	if($default && $default != '')
		$out .= "&amp;default=".urlencode($default);
	if($border && $border != '')
		$out .= "&amp;border=".$border;
	echo $out;
}

add_action('widgets_init', 'remove_default_widgets', 0);
function remove_default_widgets() {
if (function_exists('unregister_sidebar_widget')) {
unregister_sidebar_widget('Search');
	}
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
function woo_exclude_pages() {

	$exclude = '';
	$exclude = $exclude . get_option( 'woo_tabber_pages' ) . ',' . get_option( 'woo_intro_page' ) . ',' . get_option( 'woo_intro_page_left' ) . ',' . get_option( 'woo_intro_page_right' );
	return $exclude;

}

// Determine the widgetized sidebar
// Used in sidebar.php
function woo_sidebars() {

	$home = get_option( 'woo_home_sidebar' );
	$page = get_option( 'woo_page_sidebar' );	
	$blog = get_option( 'woo_blog_sidebar' );
	
	if ( is_home() ) { return $home;
	} elseif ( is_page() ) { return $page;
	} elseif ( is_single() ) { return $blog;
	} elseif ( is_archive() ) { return $blog;
	} else { return $blog; }

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
	if ($style_path == "default")
	  echo 'images/';
	else
	  echo 'styles/'.$style_path;
}

/*
Plugin Name: WP-PageNavi 
Plugin URI: http://www.lesterchan.net/portfolio/programming.php 
*/ 

function wp_pagenavi($before = '', $after = '') {
	global $wpdb, $wp_query;
	if (!is_single()) {
		$request = $wp_query->request;
		$posts_per_page = intval(get_query_var('posts_per_page'));
		$paged = intval(get_query_var('paged'));
		$pagenavi_options = get_option('pagenavi_options');
		$numposts = $wp_query->found_posts;
		$max_page = $wp_query->max_num_pages;
		/*
		$numposts = 0;
		if(strpos(get_query_var('tag'), " ")) {
		    preg_match('#^(.*)\sLIMIT#siU', $request, $matches);
		    $fromwhere = $matches[1];			
		    $results = $wpdb->get_results($fromwhere);
		    $numposts = count($results);
		} else {
			preg_match('#FROM\s*+(.+?)\s+(GROUP BY|ORDER BY)#si', $request, $matches);
			$fromwhere = $matches[1];
			$numposts = $wpdb->get_var("SELECT COUNT(DISTINCT ID) FROM $fromwhere");
		}
		$max_page = ceil($numposts/$posts_per_page);
		*/
		if(empty($paged) || $paged == 0) {
			$paged = 1;
		}
		$pages_to_show = intval($pagenavi_options['num_pages']);
		$pages_to_show_minus_1 = $pages_to_show-1;
		$half_page_start = floor($pages_to_show_minus_1/2);
		$half_page_end = ceil($pages_to_show_minus_1/2);
		$start_page = $paged - $half_page_start;
		if($start_page <= 0) {
			$start_page = 1;
		}
		$end_page = $paged + $half_page_end;
		if(($end_page - $start_page) != $pages_to_show_minus_1) {
			$end_page = $start_page + $pages_to_show_minus_1;
		}
		if($end_page > $max_page) {
			$start_page = $max_page - $pages_to_show_minus_1;
			$end_page = $max_page;
		}
		if($start_page <= 0) {
			$start_page = 1;
		}
		if($max_page > 1 || intval($pagenavi_options['always_show']) == 1) {
			$pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($paged), $pagenavi_options['pages_text']);
			$pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
			echo $before.'<div class="wp-pagenavi">'."\n";
			switch(intval($pagenavi_options['style'])) {
				case 1:
					if(!empty($pages_text)) {
						echo '<span class="pages">&#8201;'.$pages_text.'&#8201;</span>';
					}					
					if ($start_page >= 2 && $pages_to_show < $max_page) {
						$first_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['first_text']);
						echo '<a href="'.clean_url(get_pagenum_link()).'" title="'.$first_page_text.'">&#8201;'.$first_page_text.'&#8201;</a>';
						if(!empty($pagenavi_options['dotleft_text'])) {
							echo '<span class="extend">&#8201;'.$pagenavi_options['dotleft_text'].'&#8201;</span>';
						}
					}
					previous_posts_link($pagenavi_options['prev_text']);
					for($i = $start_page; $i  <= $end_page; $i++) {						
						if($i == $paged) {
							$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
							echo '<span class="current">&#8201;'.$current_page_text.'&#8201;</span>';
						} else {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
							echo '<a href="'.clean_url(get_pagenum_link($i)).'" title="'.$page_text.'">&#8201;'.$page_text.'&#8201;</a>';
						}
					}
					next_posts_link($pagenavi_options['next_text'], $max_page);
					if ($end_page < $max_page) {
						if(!empty($pagenavi_options['dotright_text'])) {
							echo '<span class="extend">&#8201;'.$pagenavi_options['dotright_text'].'&#8201;</span>';
						}
						$last_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['last_text']);
						echo '<a href="'.clean_url(get_pagenum_link($max_page)).'" title="'.$last_page_text.'">&#8201;'.$last_page_text.'&#8201;</a>';
					}
					break;
				case 2;
					echo '<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="get">'."\n";
					echo '<select size="1" onchange="document.location.href = this.options[this.selectedIndex].value;">'."\n";
					for($i = 1; $i  <= $max_page; $i++) {
						$page_num = $i;
						if($page_num == 1) {
							$page_num = 0;
						}
						if($i == $paged) {
							$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
							echo '<option value="'.clean_url(get_pagenum_link($page_num)).'" selected="selected" class="current">'.$current_page_text."</option>\n";
						} else {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
							echo '<option value="'.clean_url(get_pagenum_link($page_num)).'">'.$page_text."</option>\n";
						}
					}
					echo "</select>\n";
					echo "</form>\n";
					break;
			}
			echo '</div>'.$after."\n";
		}
	}
}

add_action('init', 'pagenavi_init');
function pagenavi_init() {
	// Add Options
	$pagenavi_options = array();
	$pagenavi_options['current_text'] = '%PAGE_NUMBER%';
	$pagenavi_options['page_text'] = '%PAGE_NUMBER%';
	$pagenavi_options['first_text'] = __('&laquo; First','wp-pagenavi');
	$pagenavi_options['last_text'] = __('Last &raquo;','wp-pagenavi');
	$pagenavi_options['next_text'] = __('&raquo;','wp-pagenavi');
	$pagenavi_options['prev_text'] = __('&laquo;','wp-pagenavi');
	$pagenavi_options['dotright_text'] = __('...','wp-pagenavi');
	$pagenavi_options['dotleft_text'] = __('...','wp-pagenavi');
	$pagenavi_options['style'] = 1;
	$pagenavi_options['num_pages'] = 5;
	$pagenavi_options['always_show'] = 0;
	add_option('pagenavi_options', $pagenavi_options, 'PageNavi Options');
}

// This function gets the custom field image and uses thumb.php to resize it
// Parameters: 
// 		$key = Custom field key eg. "image"
// 		$type = Predefined type eg. "featured"
//		$width = Set width manually without using $type
//		$height = Set height manually without using $type
// 		$class = CSS class to use on the img tag eg. "alignleft". Default is "thumbnail"
//		$quality = Enter a quality between 80-100. Default is 95
function woo_get_image($key, $type, $width = 0, $height = 0, $class = "thumbnail", $quality = 90) {

	
// Set defaul sizes if width and height not set
if (!$width && !$height) {
	if ($type == "featured") {
		$width = "430"; $height = "170";
		// Get custom sizes from options panel
		if ( get_option('woo_image_width') && get_option('woo_image_height') ) {
			$width = get_option('woo_image_width');
			$height = get_option('woo_image_height');
		} 		
	} elseif ($type == "featured_alt") {
		$width = "130"; $height = "85";
		// Get custom sizes from options panel
		if ( get_option('woo_feat_alt_width') && get_option('woo_feat_alt_height') ) {
			$width = get_option('woo_feat_alt_width');
			$height = get_option('woo_feat_alt_height');
		} 		
	} elseif ($type == "thumbnail") {
		$width = "64"; $height = "64";
		// Get custom sizes from options panel
		if ( get_option('woo_thumb_width') && get_option('woo_thumb_height') ) {
			$width = get_option('woo_thumb_width');
			$height = get_option('woo_thumb_height');
		} 		
	} elseif ($type == "single") {
		$width = "180"; $height = "120";	
		// Get custom sizes from options panel
		if ( get_option('woo_single_width') && get_option('woo_single_height') ) {
			$width = get_option('woo_single_width');
			$height = get_option('woo_single_height');
		} 		
	}
}

global $post;
$custom_field = get_post_meta($post->ID, $key, true);

if($custom_field) { //if the user set a custom field ?>

<a title="Permanent Link to <?php the_title(); ?>" href="<?php if (is_single()) { echo $custom_field; } else { the_permalink(); } ?>"><img src="<?php echo bloginfo('template_url'); ?>/thumb.php?src=<?php echo $custom_field; ?>&amp;h=<?php echo $height; ?>&amp;w=<?php echo $width; ?>&amp;zc=1&amp;q=<?php echo $quality; ?>" alt="<?php the_title(); ?>" class="<?php echo $class; ?>" /></a>

<?php 
}
else { //else, return
	return;
}
}

/*
Get Video
This function gets the embed code from the custom field
Parameters: 
		$key = Custom field key eg. "embed"
		$width = Set width manually without using $type
		$height = Set height manually without using $type
*/

function woo_get_embed($key, $width, $height) {

global $post;
$custom_field = get_post_meta($post->ID, $key, true);

if ($custom_field) : 
	
	// Get custom width and height
	$custom_width = get_post_meta($post->ID, 'width', true);
	$custom_height = get_post_meta($post->ID, 'height', true);	
	
	// Set values
	if ( !$custom_width ) $width = 'width="'.$width.'"'; else $width = 'width="'.$custom_width.'"';
	if ( !$custom_height ) $height = 'height="'.$height.'"'; else $height = 'height="'.$custom_height.'"';
	
	$custom_field = preg_replace( '/width="[^"]+"/' , $width , $custom_field );
	$custom_field = preg_replace( '/height="[^"]+"/' , $height , $custom_field );	
?>
<div class="video">
	<?php echo $custom_field; ?>
</div>
<?php 
endif;

}

?>