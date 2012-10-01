<?php
// Installer 
// Does anybody know why WordPress' $wpdb->dbDelta() gives a 'table exists' error message and exits?

$q="CREATE TABLE IF NOT EXISTS `".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW."` (
  `remote_addr` int(10) signed NOT NULL default 0,
  `remote_host` varchar(32) default NULL,
  `http_user_agent` varchar(128) default '',
  `http_accept_language` varchar(5) default NULL,
  `colordepth` tinyint(3) unsigned default NULL,
  `screen_w` smallint(5) unsigned NOT NULL default '0',
  `screen_h` smallint(5) unsigned NOT NULL default '0',
  `page` varchar(255) default '',
  `pagetype` tinyint(1) unsigned default NULL,
  `pageid` smallint(5) unsigned NOT NULL,
  `user` smallint(5) unsigned NOT NULL,
  `username` varchar(32) default '',
  `stamp` int(10) unsigned default '0',
  `browser` smallint(5) unsigned NOT NULL default '0',
  `browserversion` varchar(8) default NULL,
  `browsertype` tinyint(4) NOT NULL default '0',
  `os` smallint(5) unsigned NOT NULL default '0',
  `referer` varchar(255) default '',
  `method` tinyint(1) NOT NULL default '0',
  `cid` varchar(32) NOT NULL default '',
  `width` smallint(5) unsigned NOT NULL default '0',
  `height` smallint(5) unsigned NOT NULL default '0',
  `searchstring` varchar(128) default '',
  `searchstringtype` tinyint(1) unsigned default '0',
  `revisit` tinyint(1) NOT NULL default '0',
  `referertype` tinyint(1) unsigned NOT NULL default '0',
  `entrypage` tinyint(1) NOT NULL default '0',
  KEY `stamp` (`stamp`),
  KEY `remote_addr` (`remote_addr`),
  KEY `browsertype` (`browsertype`),
  KEY `pagetype` (`pagetype`),
  KEY `referer` (`referer`),
  KEY `searchstringtype` (`searchstringtype`),
  KEY `referertype` (`referertype`),
  KEY `entrypage` (`entrypage`)
)";
$r1=$wpdb->query($q);
if($r1!=0){
    #$wpdb->print_error();
    #wp_die('Error during database table creation'.); # show error, unformatted
}
$q="CREATE TABLE IF NOT EXISTS `".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."` (
  `name` enum('hits','hits_day','hits_day_nobots','hits_month','hits_month_nobots','hits_nobots','hits_week','hits_week_nobots','hits_year','hits_year_nobots','post_count','raw_count','user_count','visits','visits_day','visits_day_nobots','visits_month','visits_month_nobots','visits_nobots','visits_week','visits_week_nobots','visits_year','visits_year_nobots') NOT NULL,
  `type` tinyint unsigned NOT NULL default 0,
  `val1` int(11) unsigned NOT NULL default 0,
  `val2` int(11) unsigned NOT NULL default 0,
  `val3` int(11) unsigned NOT NULL default 0,
  KEY `name` (`name`,`type`,`val1`,`val2`,`val3`),
  KEY `type` (`type`,`val1`,`val2`)
)";

$r2=$wpdb->query($q);
#if($r2!=0)wp_die(); # show error, unformatted

/*
 * Bugfixing v.0.9.0
 */
	$q= "DELETE FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."  WHERE val1='1970'";		
    $r = $wpdb->query($q);

	$q= "UPDATE ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS." SET type=99 WHERE name='hits_month_nobots'";
	$r = $wpdb->query($q);
	
	$q= "UPDATE ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS." SET type=98 WHERE name='visits_month_nobots'";
	$r = $wpdb->query($q);


/*
 * Optimizing tables, thanks to http://www.mysqlperformanceblog.com/
 * This alters old cystats tables < 0.9.6
 */

$q="ALTER TABLE `".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW."` CHANGE COLUMN `remote_addr` `remote_addr` int(10) signed NOT NULL default 0";
$r = $wpdb->query($q);


$q="ALTER TABLE `".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."` CHANGE COLUMN `name`  `name`  
enum('hits', 'hits_day', 'hits_day_nobots', 'hits_month',  
'hits_month_nobots', 'hits_nobots', 'hits_week', 'hits_week_nobots',  
'hits_year', 'hits_year_nobots', 'post_count', 'raw_count',  
'user_count', 'visits', 'visits_day', 'visits_day_nobots',  
'visits_month', 'visits_month_nobots', 'visits_nobots', 'visits_week',  
'visits_week_nobots', 'visits_year', 'visits_year_nobots') NOT NULL";
$r = $wpdb->query($q);

$q="ALTER TABLE `".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."` CHANGE COLUMN `type` `type` tinyint  unsigned NOT NULL default 0";
$r = $wpdb->query($q);

$q="ALTER TABLE `".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."` CHANGE COLUMN `val1` `val1` int(11)  unsigned NOT NULL default 0";
$r = $wpdb->query($q);

$q="ALTER TABLE `".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."` CHANGE COLUMN `val2` `val2` int(11)  unsigned NOT NULL default 0";
$r = $wpdb->query($q);

$q="ALTER TABLE `".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."` CHANGE COLUMN `val3` `val3` int(11)  unsigned NOT NULL default 0";
$r = $wpdb->query($q);

$q="ALTER TABLE `".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."` DROP INDEX `name`";
$r = $wpdb->query($q);

$q="ALTER TABLE `".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."` ADD INDEX `name` (`name`, `type`,  `val1`, `val2`, `val3`)";
$r = $wpdb->query($q);

$q="OPTIMIZE TABLE `wp_TABLE_STATISTICS`";
$r = $wpdb->query($q);


/*
 *  BUGFIXING 0.9.6
 */

$q="SELECT name,type,val1,val2,sum(val3) AS sumup FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS." GROUP BY type,val1,val2";    
$val=$wpdb->get_results($q,ARRAY_A);
$wpdb->query("DELETE FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS);
foreach($val as $bugrow=>$bugvalues){
    $buginsert="INSERT INTO ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS ."(name,type,val1,val2,val3) 
    VALUES ('".$bugvalues['name']."',
                '".$bugvalues['type']."',
                '".$bugvalues['val1']."',
                '".$bugvalues['val2']."',
                '".$bugvalues['sumup']."')";
    $wpdb->query($buginsert);            
    }

/*
 * Add options to wordpress options database table,
 * those options can be removed later on by the cystats uninstaller plugin or
 * via phpmyadmin (or other tool) searching and deleting options with field 'option_name'
 * starting with 'cstats_'
 */
add_option('cystats_install_stamp',time());
add_option('cystats_rawtable_max','1209600');
add_option('cystats_userlevel','10');
add_option('cystats_adminpage_tracking',1);
add_option('cystats_visit_deltatime','300');
add_option('cystats_visits_displayrows','300');
add_option('cystats_javascript_tracking','0');
add_option('cystats_shorten_referer','50');
add_option('cystats_shorten_page','50');
add_option('cystats_shorten_user_agent','30');
add_option('cystats_last_cleanup','0');
add_option('cystats_cleanup_interval','3600');
add_option('cystats_localreferer_tracking','1');
add_option('cystats_hits_delta','0');
add_option('cystats_visits_delta','0');
add_option('cystats_userlevel_tracking','10');
add_option('cystats_noip','0');
add_option('cystats_time_offset','0');
add_option('cystats_hide_cookie','0'); 

delete_option('cystats_ignorelist_ip'); 
delete_option('cystats_ignorelist_ua'); 
delete_option('cystats_ignorelist_pgs'); 

add_option('cystats_ignorelist_ip',array()); 
add_option('cystats_ignorelist_ua',array()); 
add_option('cystats_ignorelist_pgs',array()); 

update_option('cystats_version',"0.9.8");

?>
