<?php 

// Custom functions and plugins
require_once ('admin-functions.php');

// Options panel variables and functions
require_once ('admin-setup.php');


// Custom fields 
require_once ('custom.php');

// More WooThemes Page
require_once ('admin-theme-page.php');

// Admin Interface!
require_once ('admin-interface.php');

// Uploader
require_once ('admin-uploader.php');

add_action('wp_head', 'woothemes_wp_head');
add_action('admin_menu', 'woothemes_add_admin');
add_action('admin_head', 'woothemes_admin_head'); 

?>