<?php

/*
Get Image from custom field
This function gets the custom field image and uses thumb.php to resize it
Parameters: 
        $key = Custom field key eg. "image"
        $width = Set width manually without using $type
        $height = Set height manually without using $type
         $class = CSS class to use on the img tag eg. "alignleft". Default is "thumbnail"
        $quality = Enter a quality between 80-100. Default is 90
*/
function woo_get_image($key, $width, $height, $class = "thumbnail", $quality = 90,$id = null) {


if(empty($id)){
global $post;
$id = $post->ID;
}

$custom_field = get_post_meta($id,$key, true);

if($custom_field) { //if the user set a custom field ?>

<?php if (get_option('woo_resize')) : ?>

    <img src="<?php echo bloginfo('template_url'); ?>/thumb.php?src=<?php echo $custom_field; ?>&amp;h=<?php echo $height; ?>&amp;w=<?php echo $width; ?>&amp;zc=1&amp;q=<?php echo $quality; ?>" alt="<?php echo get_the_title($id); ?>" class="<?php echo $class; ?>" height="<?php  echo $height; ?>" />

    <?php else : ?>

<img src="<?php echo $custom_field; ?>" alt="<?php echo get_the_title($id); ?>" class="<?php echo $class; ?>" height="<?php  echo $height; ?>" />

    <?php endif;
} else { 
return; 
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
	if ($style_path == "default")
	  echo 'images/';
	else
	  echo 'styles/'.$style_path;
}

?>