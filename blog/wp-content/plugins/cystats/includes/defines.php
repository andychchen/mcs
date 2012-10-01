<?php
// define database table names
define('CYSTATS_TABLE_STATISTICS','TABLE_STATISTICS');
define('CYSTATS_TABLE_STATISTICS_RAW','TABLE_STATISTICS_RAW');

// define single-number-statistic types
define('CYSTATS_HITSYEAR',0);
define('CYSTATS_HITSMONTH',1);
define('CYSTATS_VISITSYEAR',2);
define('CYSTATS_VISITSMONTH',3);
define('CYSTATS_SEARCHWORD',4);
define('CYSTATS_SEARCHWORDSTRING',14);
define('CYSTATS_RAWCOUNT',5);
define('CYSTATS_HITS',6);
define('CYSTATS_VISITS',7);
define('CYSTATS_HITSWEEK',8);
define('CYSTATS_VISITSWEEK',9);
define('CYSTATS_HITSDAY',10);
define('CYSTATS_VISITSDAY',11);
define('CYSTATS_FIRSTDAY',12);
define('CYSTATS_MAXUSER',13);
define('CYSTATS_SEARCHWORDWP',15);
define('CYSTATS_SEARCHWORDSTRINGWP',16);
define('CYSTATS_VISITSDAY_NOBOTS',17);
define('CYSTATS_VISITSYESTERDAY_NOBOTS',18);
define('CYSTATS_HITSDAY_NOBOTS',19);
define('CYSTATS_HITSYESTERDAY_NOBOTS',20);
define('CYSTATS_HITS_NOBOTS',21);
define('CYSTATS_VISITS_NOBOTS',22);
define('CYSTATS_HITSWEEK_NOBOTS',23);
define('CYSTATS_VISITSWEEK_NOBOTS',24);
define('CYSTATS_HITSMONTH_NOBOTS',99); // fixed 23 => 99
define('CYSTATS_VISITSMONTH_NOBOTS',98); // fixed 24 => 98
define('CYSTATS_HITSYEAR_NOBOTS',25);
define('CYSTATS_VISITSYEAR_NOBOTS',26);
define('CYSTATS_POSTCOUNT',27);
define('CYSTATS_USERCOUNT',28);


// define type identifier
define('CYSTATS_BROWSER',0);        // ;)
define('CYSTATS_SEARCH',1);         // searchengine
define('CYSTATS_TOOL',2);           // toolbars, DL-manager, offlinespider, ...
define('CYSTATS_EMAIL',3);          // emailharvester
define('CYSTATS_LINK',4);           // linkharvester
define('CYSTATS_UNKNOWN',5);        //
define('CYSTATS_FEED',6);           // feedreader
define('CYSTATS_MALWARE',7);            // malware/other bad robots

// define field index for array of predefined 'user_agents'
define('CYSTATS_BLOCK',0);          // FALSE/TRUE ->block identified cliend or don´t
define('CYSTATS_TYPE',1);           // 'type identifier' above
define('CYSTATS_NAME',2);           // name shown in statistics view
define('CYSTATS_URL',3);            // url to website shown
define('CYSTATS_AGENT',4);          // original uaser_agent string
define('CYSTATS_SECONDPASS',5);     // FALSE / one or array of values to check in 2nd pass
define('CYSTATS_SECONDPASS_TYPE',6); // FALSE / 2nd pass check type: CYSTATS_IP or CYSTATS_UA (for [U]ser[A]gent)

// define block mode (used in predefined array of 'user_agents')
define('CYSTATS_DONTBLOCK',0);      // dont give access to website if identified
define('CYSTATS_BLOCKIT',1);        // block user agent, not implemented

// define second pass check method (used in predefined array of 'user_agents')
define('CYSTATS_IP',0);             //ip check
define('CYSTATS_UA',1);             //preg_match user agent check

?>
