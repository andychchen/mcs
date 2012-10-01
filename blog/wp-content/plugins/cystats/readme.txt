=== CyStats ===
Contributors: Michael Weingaertner
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=weingaertner%2emichael%40gmx%2ede&item_name=CyStats%20WordPress%20Statistik%20Plugin&no_shipping=1&no_note=1&tax=0&currency_code=EUR&lc=DE&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: statistics,stats,hits,visits,agent,counter,bouncerate,referer,searchword,browser,robots,feedreader
Requires at least: 2.3
Tested up to: 2.7
Stable tag: 0.9.8

WordPress blog statistics plugin
 
== Description ==

CyStats is a feature-rich statistics plugin integrated in the WordPress admin area. Features are currently:

1. Bounce rate, ignore-by-cookie, ignore-by-ip/post-id/user_agent lists
1. hits, visits for day/week/month/year -  human or robots
1. Top refering pages
1. Most read categories and tags
1. Most read, most commented posts
1. Most read feeds, number of feed visits today
1. Internal/external search words
1. Operating systems
1. Daily, weekly, monthly and yearly statistics
1. 404 error requests
1. Browsers/clients/tools/..., w/o. version numbers
1. Template tags for most read posts, user count,...
1. Optional IP-anonymizing
1. Multi language support (currently english, german supported).
1. Optional tracking of admin area visits

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload directory `cystats` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Edit cystats settings according to your needs 

== Changelog ==

0.9.8

Reimplemented subpages for further statistics
Added Chrome browser USER_AGENT
Added Iron browser USER_AGENT
Added Ixquick.com to searchword parser
Bugfix: Percentage calculation
Bugfix: cystats_firstPost() now has $showmode=TRUE default
Bugfix: cystats_firstPostDays() now has $showmode=TRUE default
Bugfix: User online count showing false values
Bugfix: Fixed error messages while using CyStats with Coppermine Integrating Plugin
Removed cystats_countPages() from template tags
Removed cystats_countUsers() from template tags
Removed cystats_countLinks() from template tags
Removed cystats_getUsersOnline() from template tags
Removed cystats_getClientTypesOnline() from template tags
Removed cystats_getMostCommented() from template tags

0.9.6

Bugfix: Occasional display of '0' hits/visitor
Bugfix: translations never loading
Implemented most read tags statistics
Removed recent comments table
Completed translation files
Optimized TABLE_STATISTICS
Optimized remote_addr handling in TABLE_STATISTICS_RAW
* thanks to the guys at http://www.mysqlperformanceblog.com/ *

Complete CHANGELOG file included in plugin package.

== Completely uninstalling and deactivating CyStats ==

To delete all CyStats database data including options use the remove-all-data option in CyStats-Options panel. Select the remove-all-data option, select the 'Are you sure' option and press the 'Delete'-Button. After CyStats has cleaned the database including the WordPress options table be sure to deactivate CyStats.

== Screenshots ==

1. CyStats index page, day/week/month/year hits and visits, with and without robots, list of the most recent page requests

