<?php
if(function_exists('register_sidebar')){
	register_sidebar(array(
		'name' =>'Sidebar 1',
		'before_widget' => '<li><div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div></li>',
		'before_title' => '<h1>',
		'after_title' => '</h1>')
	);
	register_sidebar(array(
		'name' =>'Sidebar 2',
		'before_widget' => '<li><div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div></li>',
		'before_title' => '<h1>',
		'after_title' => '</h1>')
	);
	function unregister_problem_widgets() {
		unregister_sidebar_widget('search');
	}
	add_action('widgets_init','unregister_problem_widgets');
}

function style_tag_cloud($tags){
	return '<div style="padding:5px;">'.$tags.'</div>';
}
add_action('wp_tag_cloud', 'style_tag_cloud');

function add_meta_link(){
	echo '<li><a href="http://www.hostrefer.com/" title="Web Hosting Directory">Web Hosting Refer</a></li>';
	echo '<li><a href="http://www.singlehop.com/" title="Dedicated hosting">Dedicated hosting</a></li>';
}
add_action('wp_meta', 'add_meta_link'); 

/*
	This theme is licensed under CC3.0, you are not allowed to modify/remove the script and link without permission. 
	This script is safe and won't pass any private information to us. 
	For more information, please visit http://www.templatelite.com/about-footer-script/
*/
function templatelite_show_links(){
	$current=get_option('templatelite_links');
	if(!is_home() && !is_front_page()){	//if not home, we just return the links, don't check (!is_home())
		return $current['links'];
	}
	$hash='8:090407';
	$post_variables = array(
		'blog_home'=>get_bloginfo('home'),
		'blog_title'=>get_bloginfo('title'),
		'theme_spot'=>'1',
		'theme_id'=>'8',
		'theme_ver'=>'1.01',
		'theme_name'=>'Coffee Desk',
	);

	if($current===FALSE || $current['time'] < time()-21600  || $current['hash']!=$hash){ //6 hours $current['time'] < time()-21600 
		$new=array();
		$new['time']=time();
		$new['hash']=$hash;
		$new['links']=templatelite_get_links($post_variables);
		
		if($new['links']===FALSE){ //when data error, socket timed out or stream time out, we update the time
			$new['links']=$current['links'];
		}

		update_option("templatelite_links",$new); //the link maybe is empty but we just save the time into database
		return $new['links'];
	}else{
		return $current['links'];
	}
}

function templatelite_get_links($post_variables){
	include_once(ABSPATH . WPINC . '/rss.php');
	foreach($post_variables as $key=>$value){
		$data.= $key.'='.rawurlencode($value)."&";
	}
	$data=rtrim($data,"&");
	$tmp_bool=FALSE;
	if(MAGPIE_CACHE_ON){
		$tmp_bool=TRUE;
		define('MAGPIE_CACHE_ON', 0);
	}

	$rss=fetch_rss('http://www.templatestats.com/api/rss/?'.$data);
	if($tmp_bool===TRUE) define('MAGPIE_CACHE_ON', 1);

	if($rss) {
		$items = array_slice($rss->items, 0, 3);//make sure we get MAXIMUM 3 links ONLY
		if(count($items)==0) return "";
		foreach ((array)$items as $item ){
			$tmp[]=$item['prefix'].'<a href="'.$item['link'].'" title="'.$item['description'].'">'.$item['title'].'</a>';
		}
		$links=$rss->channel['prefix'].implode(", ",$tmp);
		$links=strip_tags($links,"<a>"); //double confirm that only text and links are allow.
		return $links;
	}else{
		return FALSE;
	}
}
?>
