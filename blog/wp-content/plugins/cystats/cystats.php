<?php
/*
Plugin Name: CyStat
Plugin URI: http://www.cywhale.de/cystats-wordpress-statistik-plugin/
Description: Statistik-Plugin
Version: 0.9.8
Author: Michael Weingärtner
Author URI: http://www.cywhale.de/
Author URI: http://www.cywhale.de/
Min WP Version: 2.3
Max WP Version: 2.7
*/

/*  Copyright 2007  Michael Weingärtner  (email : admin@cywhale.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/* Security note quoted from http://codex.wordpress.org/Function_Reference/wpdb_Class
 * -
 * If you're making a SQL query, make sure any untrusted data is escaped properly 
 * first. This can be conveniently done with the escape method.
 * Note that values taken from $_GET, $_POST, $_REQUEST, $_COOKIE 
 * and $_SERVER will already be escaped, regardless of the server's 
 * magic_quotes setting. 
 * -
 * Therefore cystats will save cpu cycles and not escape this data again. If anybody
 * knows about another security issue please report to the plugin author.
 */ 
 #error_reporting(E_ALL);
$cystats_tm_start = array_sum(explode(' ', microtime()));
// Load language file

if(is_admin()){
    load_plugin_textdomain('cystats','wp-content/plugins/cystats/languages');
}
/*
* init functions, will be executed if plugin is
* enabled, initializing database tables,...
*/
if (!function_exists('cystats_trigger_installer')) {
    function cystats_trigger_installer(){
        global $wpdb;
        include(dirname(__FILE__).'/includes/installer.php');
    }
}
#register_activation_hook(__FILE__,'cystats_trigger_installer');
add_action('activate_' . dirname(plugin_basename(__FILE__)).'/cystats.php', 'cystats_trigger_installer');
// Creates admin panel and subpanel entries
if(is_admin()){
    include(dirname(__FILE__).'/includes/admin.php');
}

// include neccessary files and create new class instance
include(dirname(__FILE__).'/includes/cystats.class.php');
$GLOBALS['statistics'] = new statistics();

/*
* Gets statistics object handler,
* fetches and parses client data 
*/
function cystats_logger() {
    $statistics = &$GLOBALS['statistics'];
    $statistics->init();
    $statistics->addEntry();
}

/**
 * gets page and user information via wordpress functions
 * and initiates statistics database writing
 */
function cystats_update_data(){
    global $wp_query;

    // get site type
    $pagetype = -1;
    #$pageid = -1;
    $pageid = (isset($wp_query->post->ID))?$wp_query->post->ID:0;
    
    $pagequery = (isset($wp_query->query_string))?$wp_query->query_string:'';

    if (is_404()) {
        $pagetype = 11;
    } elseif(is_home()) {
        $pagetype = 0;
    } elseif(is_feed()) {
        $pagetype = 12;
        $pageid = $wp_query->post->ID;
    } elseif(is_single()) {
        $pagetype = 1;
        $pageid = $wp_query->post->ID;
    } elseif(is_page()) {
        $pagetype = 2;
        $pageid = $wp_query->post->ID;
    } elseif(is_category()) {
        $pagetype = 3;
        $pageid = $wp_query->post->ID;
    } elseif(is_author()) {
        $pagetype = 4;
        $pageid = $wp_query->post->ID;
    } elseif(is_year()) {
        $pagetype = 5;
    } elseif(is_month()) {
        $pagetype = 6;
    } elseif(is_day()) {
        $pagetype = 7;
    } elseif(is_time()) {
        $pagetype = 8;
    } elseif(is_archive()) {
        $pagetype = 9;
    } elseif(is_search()) {
        $pagetype = 10;
    } elseif($_SERVER['REQUEST_METHOD'] != 'GET') {
        $pagetype = 15;
    } elseif(is_admin()) {
        $pagetype = 16;
    } elseif(is_attachment()) {
        $pagetype = 13;
        $pageid = $wp_query->post->ID;
    } elseif(is_trackback()) {
        $pagetype = 14;
        $pageid = $wp_query->post->ID;
    }

    $user = wp_get_current_user();
    
    // get statistics class handler
    $statistics = &$GLOBALS['statistics'];
    
    // set vars and eventually write all gathered data to database
    $statistics->_pagetype=$pagetype;
    $statistics->_pageid=(empty($pageid))?"0":$pageid;
    #var_dump($pageid);
    #var_dump($statistics->_pageid);
    $statistics->_pagequery=$pagequery;
    $statistics->_userid=$user->ID;
    $statistics->update();
    // prepare id hash and pagetype for js data writing
    $GLOBALS['cystats_cid']=$statistics->get_cid();
    $GLOBALS['cystats_pagetype']=$pagetype;
    
}





// Load template functions
include(dirname(__FILE__).'/includes/template-functions.php');

/*
* Loads admin page css file and
*/
function cystats_css() {
    $url = get_settings('siteurl');
    $url_css = $url . '/wp-content/plugins/cystats/admin/style.css';
    echo '<link rel="stylesheet" type="text/css" href="' . $url_css . '" />';
}
  
  
      
// check for admin page tracking
if( (get_option('cystats_adminpage_tracking')==1) || (get_option('cystats_adminpage_tracking')==0 && (!is_admin()))){
    #add_action('init' , 'cystats_queries');
    add_action('init' , 'cystats_logger');
    #add_action('add_cacheaction' , 'cystats_logger',98);
    
    if (is_admin()){
        add_action('shutdown' , 'cystats_update_data');
    }else{
        add_action('template_redirect' , 'cystats_update_data');
        #add_action('add_cacheaction' , 'cystats_update_data',99);
    }
}

/**
 * Add admin navigation and css and optionally blocker cookie
 */
if (is_admin()) {
    add_action('admin_menu' , 'cystats_create_admin_menu');
    add_action('admin_head' , 'cystats_css');
    
	if(get_option('cystats_hide_cookie') == 1){
		setcookie("CyStatsHide", '1', time()+60*60*24*30,'/');
	}
	else{
		setcookie("CyStatsHide", '0', time()-1,'/');
	}
}
$cystats_secs_total = array_sum(explode(' ', microtime())) - $cystats_tm_start;
function cystats_show_microtime(){
    global $cystats_secs_total;
    echo "<span style='font-size:9px;color:white;background-color:transparent;'>CyStats: ".sprintf("%.3f",$cystats_secs_total)."</span>";
}
    #add_action('shutdown' , 'cystats_show_microtime');
?>
