<?php
/**
 * Builds admin panem menu for Plugin
 */
if(!function_exists('cystats_create_admin_menu')){
	function cystats_create_admin_menu() {
		if (function_exists('add_options_page')) {
			add_options_page('CyStats Options Page', 'CyStats ', 8, 'cystats-options', 'cystats_admin_options');
		}
		if (function_exists('add_menu_page')) {
			add_menu_page('CyStats Statistics Plugin', 'CyStats', 8, __FILE__, 'cystats_admin_index');
		}
		if (function_exists('add_submenu_page')) {
			add_submenu_page(__FILE__, htmlspecialchars(__('CyStats: Index','cystats')), htmlspecialchars(__('Index','cystats')), 8, __FILE__, 'cystats_admin_index');
			add_submenu_page(__FILE__, htmlspecialchars(__('CyStats: Blog','cystats')), htmlspecialchars(__('Blog','cystats')), 8, 'cystats-blog', 'cystats_admin_blog');
			add_submenu_page(__FILE__, htmlspecialchars(__('CyStats: Clients','cystats')), htmlspecialchars(__('Clients','cystats')), 8, 'cystats-clients', 'cystats_admin_clients');
			add_submenu_page(__FILE__, htmlspecialchars(__('CyStats: Referer','cystats')), htmlspecialchars(__('Referer','cystats')), 8, 'cystats-referer', 'cystats_admin_referer');
			add_submenu_page(__FILE__, htmlspecialchars(__('CyStats: Robots &amp; Tools','cystats')), htmlspecialchars(__('Robots/Tools','cystats')), 8, 'cystats-robots', 'cystats_admin_robots');
			add_submenu_page(__FILE__, htmlspecialchars(__('CyStats: Pages','cystats')), htmlspecialchars(__('Pages','cystats')), 8, 'cystats-pages', 'cystats_admin_pages');
			add_submenu_page(__FILE__, htmlspecialchars(__('CyStats: Time','cystats')), htmlspecialchars(__('Time','cystats')), 8, 'cystats-time', 'cystats_admin_time');
			add_submenu_page(__FILE__, htmlspecialchars(__('CyStats: Options','cystats')),htmlspecialchars(__('Options','cystats')), 8, 'cystats-options', 'cystats_admin_options');
		}
	}  
}

// Get neccesary class file      
require_once("view.class.php");

// admin page functions
function cystats_admin_index(){
    $statistics=new statisticsView();
    $statistics->init();    
    include(dirname(__FILE__).'/../admin/index.php');
}
function cystats_admin_clients(){
    $statistics=new statisticsView();
    $statistics->init();    
    include(dirname(__FILE__).'/../admin/clients.php');
}
function cystats_admin_blog(){
    $statistics=new statisticsView();
    $statistics->init();    
    include(dirname(__FILE__).'/../admin/blog.php');
}
function cystats_admin_referer(){
    $statistics=new statisticsView();
    $statistics->init();    
    include(dirname(__FILE__).'/../admin/referer.php');
}
function cystats_admin_robots(){
    $statistics=new statisticsView();
    $statistics->init();    
    include(dirname(__FILE__).'/../admin/robots.php');
}
function cystats_admin_search(){
    $statistics=new statisticsView();
    $statistics->init();    
    include(dirname(__FILE__).'/../admin/search.php');
}
function cystats_admin_pages(){
    $statistics=new statisticsView();
    $statistics->init();    
    include(dirname(__FILE__).'/../admin/pages.php');
}
function cystats_admin_time(){
    $statistics=new statisticsView();
    $statistics->init();    
    include(dirname(__FILE__).'/../admin/time.php');
}
function cystats_admin_feeds(){
    $statistics=new statisticsView();
    $statistics->init();    
    include(dirname(__FILE__).'/../admin/feeds.php');
}

function cystats_admin_about(){
    $statistics=new statisticsView();
    $statistics->init();    
    include(dirname(__FILE__).'/../admin/about.php');
}

function cystats_admin_options(){
  	global $userdata;
	get_currentuserinfo();
	if(!current_user_can('manage_options')){   
        ?>
        <div class="wrap">
            <h2><?php echo htmlspecialchars(__('Access denied','cystats'));?></h2>
            <p>
                <span style="display:block;width:100%;text-align:center;color:red;">
                    <?php echo htmlspecialchars(__('You do not have the required userlevel to access the cystats administration area.','cystats'));?>
                </span>
            </p>
        </div>
        <?php
    }else{    
        include(dirname(__FILE__).'/../admin/options.php');    
    }
}

?>
