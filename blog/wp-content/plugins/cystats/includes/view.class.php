<?php
/**
 * CyStats frontend main class
 *
 *  This is the main frontend class for the CyStats WordPress statistics plugin.
 *  This class provides functions to read stored information about visitors, bots,
 *  browsers... from the database and other functions to display those functions
 *  in the wordpress admin panel.
 *
 * LICENSE: 
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.

 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @copyright  2007 Michael Weingärtner
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 * @link       http://www.cywhale.de/
*/

/*
 * Sorting helper function
 */
function dimsort($a, $b) {
    if ($a['count'] == $b['count'])return(NULL);
    if ($a['count'] > $b['count'])return(-1);
    if ($a['count'] < $b['count'])return(1);
}


require_once("defines.php");    
require_once("cystats_dbescape.php");

class statisticsView {
    var $_db = FALSE;
    // database handler
    var $_dbLimit = 10;
    // result row limit
    var $treshold = 8000;
    var $_browser_full_info = TRUE;
    var $_referer_substr = 70;
    // cut referer string
    var $http_method = array("POST", "GET");
    // as the name says
    var $images= array( 'browser'=>array(// browser icons
        600 => "firefox.gif",
        700 => "firefox.gif",
        701 => "firefox.gif",
        702 => "firefox.gif",
        2600 => "ie.gif",
        4101 => "ie.gif",
        4102 => "ie.gif",
        300 => "opera.gif",
        400 => "netscape.gif",
        800 => "safari.gif",
        100 => "mozilla.gif",
        2200 => "epiphany.png",
        "galeon" => "galeon.png",
        900 => "konqueror.png",
        200 => "seamonkey.png",
        1800 => "kmeleon.gif",
        "amaya" => "amaya.gif",
        1700 => "icab.gif",
        "isurf" => "isurf.gif",
        13300 => "w3c.gif",
        13400 => "w3c.gif",
        9100 => "tb.png",
        5600 => "msn.png",
        5000 => "google.png",
        5100 => "google.png",
        5500 => "google.png",
        5200 => "google.png",
        5300 => "google.png",
        5400 => "google.png",
        6400 => "yahoo.png",
        6700 => "ask.png",
        9700 => 'liferea.png',
        11200=>'akregator.png',
        10700=>'news-alloy.png',
        11300=>'technorati.png',
        11400=>'technorati.png',
        11100=>'bloglines.png',
        13900=>'wordpress.png'),

        'os'=>array(
        1=>'windows.png',
        20=>'windows.png',
        40=>'windows.png',
        60=>'windows.png',
        80=>'windows.png',
        100=>'windows.png',
        120=>'windows.png',
        140=>'windows.png',
        160=>'windows.png',
        180=>'windows.png',
        200=>'windows.png',
        220=>'windows.png',
        240=>'windows.png',
        260=>'windows.png',
        280=>'windows.png',
        300=>'windows.png',
        320=>'windows.png',
        340=>'windows.png',
        360=>'windows.png',
        380=>'windows.png',
        400=>'windows.png',
        420=>'windows.png',
        440=>'windows.png',
        460=>'windows.png',
        480=>'windows.png',
        500=>'windows.png',
        520=>'windows.png',
        540=>'windows.png',
        560=>'windows.png',
        720=>'tux.png',
        760=>'ubuntu.png',
        780=>'kubuntu.png',
        800=>'xubuntu.png',
        820=>'edubuntu.png',
        858=>'ubuntu.png',
        859=>'ubuntu.png',
        860=>'ubuntu.png',
        880=>'ubuntu.png',
        900=>'ubuntu.png',
        920=>'ubuntu.png',
        940=>'ubuntu.png',
        960=>'ubuntu.png',
        980=>'ubuntu.png',
        1000=>'ubuntu.png',
        1020=>'ubuntu.png',
        1040=>'ubuntu.png',
        740=>'debian.png',
        1080=>'debian.png',
        1081=>'debian.png',
        1082=>'debian.png',
        1100=>'fedora.png',
        )
        
    );
    
    var $pagetypes=array(
        0=>'BLOG',
        1=>'BLOG',
        2=>'BLOG',
        3=>'BLOG',
        4=>'BLOG',
        5=>'BLOG',
        6=>'BLOG',
        7=>'BLOG',
        8=>'BLOG',
        9=>'BLOG',
        10=>'BLOG',
        13=>'ATT',
        14=>'TB',
        15=>'BLOG',
        11=>'404',
        12=>'FEED',
        16=>'ADM'
    );

    var $pagetypes_diff=array(
        0=>'HOME',
        1=>'SINGLE',
        2=>'PAGE',
        3=>'CATEGORY',
        4=>'AUTHOR',
        5=>'YEAR',
        6=>'MONTH',
        7=>'DAY',
        8=>'TIME',
        9=>'ARCHIVE',
        10=>'SEARCH',
        11=>'404',
        12=>'FEED',
        13=>'ATTACHMENT',
        14=>'TRACKBACK',
        15=>'UNKNOWN_HTTP_METHOD_GET',
        16=>'ADMIN',
    );

    var $pagetypes_title=array();

    var $localurl = array();
    var $weekdays = array();
 
    // initialize user_agent/clinet  array
    var $browser = array();
    var $os = array();
    
    /**
     * Initializes (loads) browser parsing definition file
     * browsers.php
     */
    function init() {
        // get browser/user_agent definition table
        include("browsers.php");
        include("os.php");
        $this->localurl[]=get_option('siteurl');
        $this->weekdays=array(
            'Sunday'=>htmlspecialchars(__('Sunday','cystats')),
            'Monday'=>htmlspecialchars(__('Monday','cystats')),
            'Tuesday'=>htmlspecialchars(__('Tuesday','cystats')),
            'Wednesday'=>htmlspecialchars(__('Wednesday','cystats')),
            'Thursday'=>htmlspecialchars(__('Thursday','cystats')),
            'Friday'=>htmlspecialchars(__('Friday','cystats')),
            'Saturday'=>htmlspecialchars(__('Saturday','cystats'))
        );
        $this->pagetypes_title=array(
        0=>htmlspecialchars(__('HOME is the webite main page','cystats')),
        1=>htmlspecialchars(__('SINGLE is a single WordPress post','cystats')),
        2=>htmlspecialchars(__('PAGE is a single WordPress page','cystats')),
        3=>htmlspecialchars(__('CATEGORY is a category overview page','cystats')),
        4=>htmlspecialchars(__('AUTHOR is a WordPress author information page','cystats')),
        5=>htmlspecialchars(__('YEAR is a WordPress yearly archive page','cystats')),
        6=>htmlspecialchars(__('MONTH is a WordPress monthly archive page','cystats')),
        7=>htmlspecialchars(__('DAY is a WordPress daily archive page','cystats')),
        8=>htmlspecialchars(__('TIME is a WordPress time based archive page','cystats')),
        9=>htmlspecialchars(__('ARCHIVE is a WordPress generic archive page','cystats')),
        10=>htmlspecialchars(__('SEARCH is the WordPress search result page','cystats')),
        11=>htmlspecialchars(__('404 is a \'HTTP 404 Not Found\' error page','cystats')),
        12=>htmlspecialchars(__('FEED is a WordPress newsfeed','cystats')),
        13=>htmlspecialchars(__('ATTACHMENT is a WordPress attachment','cystats')),
        14=>htmlspecialchars(__('TRACKBACK is a WordPress trackback','cystats')),
        15=>htmlspecialchars(__('UNKNOWN_HTTP_METHOD_GET is a unknown page requeted via method HTTP_GET','cystats')),
        16=>htmlspecialchars(__('ADMIN is a WordPress admin panel page','cystats')),
    );
        
    }

    /**
    * Set query result row limit
    * 
    * @param integer $limit
    */
    function setLimit($limit) {
        $this->_dbLimit = $limit;
    }

    function safespace($s){
        return(str_replace(' ','&#160;',$s));
    }

    /*
    * Set switch to show browser full version strings
    * TRUE->show full version strings in browser listing
    * FALSE->show only general browser family in browser listing
    *
    * @param bool $s
    */
    function set_browser_full_info($s) {
        $this->_browser_full_info = $s;
    }

    /*
    * Set char count to be used to cut down strings
    * e.g. referer urls
    *
    * @param integer $s char count
    */
    function set_cut_length($s) {
        $this->_referer_substr = $s;
    }

    /*
    * Shorten referer string to defined number of chars,
    * append "..." if shortened
    * deprecated, use cutString()
    *
    * @param string $r->string to be shortened
    */
    function cutReferer($r) {        
        $v = (strlen($r) > $this->_referer_substr)?substr($r, 0, $this->_referer_substr)."...":
        $r;
        return $v;
    }

    /*
    * Shorten string to defined number of chars,
    * append "..." if shortened
    *
    * @param string $r->string to be shortened
    */
    function cutString($r,$length=FALSE) {
        if($length==FALSE){
            $cutlength=$this->_referer_substr;
        }else{
            $cutlength=$length;
        }
        $v = (strlen($r) > $cutlength)?substr($r, 0, $cutlength).'&hellip;':
        $r;
        return $v;
    }

    /*
     * Converts bigint value to dotted ip string
     * Thanks to d_n for posting at http://php.oss.eznetsols.org/manual/de/language.types.integer.php
     * @param integer $bigint
     * @return string $ipdotted
     */
    function bigint2ip($bigint){
        $ipArr = array(0 =>floor($bigint/0x1000000));
        $ipVint = $bigint-($ipArr[0]*0x1000000);
        $ipArr[1] = ($ipVint & 0xFF0000)  >> 16;
        $ipArr[2] = ($ipVint & 0xFF00  )  >> 8;
        $ipArr[3] =  $ipVint & 0xFF;
        $ipDotted = implode('.', $ipArr);    
        return($ipDotted);
    }
    
    /*
    * Get statistics data from database, preprocess
    * and postprocess data
    *
    * @param string $field database column name to get
    * @param string $where mysql "where" clause to use
    * @param string $append string to append to fetched column value
    */
    function getStatsData($field, $where = "", $append = "", $limit=FALSE) {
        // define database handler
        global $wpdb;
        $ret = array();
        if(!$limit){
            $limit=$this->_dbLimit;
        }
        // define result array
        $field0 = $field; // backup
        $group = " GROUP BY ".$field;
        $order = " ORDER BY count DESC, stamp ASC";

        // build browser/browser+version mysql expression according to settings
        switch($field) {
            case("browser"):
            if ($this->_browser_full_info == TRUE) {
                
                $field = " CONCAT(".$field.",'&#160;',browserversion)";
                $group = " GROUP BY ".$field." ";
            }
            break;
            case("screenres"):
                $field = " CONCAT(screen_w,'&#215;',screen_h)";
                $group = " GROUP BY ".$field." ";
                #$where=" WHERE screen_w!=0";
                $field0=$field;
            break;
            case("referer"):
            $field = " IF(CHAR_LENGTH(referer)>1,(TRIM(TRAILING '/' FROM referer)),referer) ";
            $group = " GROUP BY ".$field." ";
            #$where = " WHERE referer!=''";
            break;
            case("page"):
            $field = " IF(CHAR_LENGTH(page)>1,(TRIM(TRAILING '/' FROM page)),page) ";
            $group = " GROUP BY ".$field." ";
            break;
            case("entry"):
            $group = " GROUP BY   IF(CHAR_LENGTH(page)>1,(TRIM(TRAILING '/' FROM page)),page) ";
            break;
        }

        if ($field == "entry") {
            $field = " IF(CHAR_LENGTH(page)>1,(TRIM(TRAILING '/' FROM page)),page) ";
            // build select query string
            $select = "   SELECT DISTINCT remote_addr AS item,
                MAX(stamp) AS tstamp,
                ".$field." AS itemsolo,
                ".$field." AS item,
                pagetype,
                pageid,
                COUNT(".$field.") as count
                FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW." 
                WHERE entrypage=1 AND pagetype !=12
                ";

            $q = $select."
                GROUP BY item
                ORDER BY count DESC, stamp DESC
                LIMIT ".$limit;
        } else {
            // build select query string
            $select = "   SELECT DISTINCT ".$field." AS item,
                ".$field0." AS itemsolo,
                remote_addr AS ip,
                browserversion,
                MAX(stamp) AS tstamp,
                pagetype,
                pageid,
                COUNT(".$field.") AS count
                FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW;

            $q = $select."
                ".$where."
                ".$group."
                ".$order."
                LIMIT ".$limit."
                ";
        }
        // query ;)
        $r = $wpdb->get_results($q,ARRAY_A);#mysql_query($q);

        // get max count of item
        $max=0;
        // cycle through result rows
        if($r)foreach($r AS $row){$max=$max+$row['count'];}
        $scount = 0;
        #while ($row = mysql_fetch_assoc($r)) {
        if($r){
            foreach($r AS $row){
                // strip escaped chars
                $row = dbStripArray($row);
                // get/calculate percent, count and itemsolo ( = e.g. browser item without version)
                $ret2['item'] = $row['item'];
                $ret2['pageid'] = $row['pageid'];
                $ret2['pagetype'] = $row['pagetype'];
                $ret2['percent'] = ($max==0)?0:round(($row['count']/$max*100));
                $ret2['itemsolo'] = ($row['itemsolo'] != '')?str_replace(' ', '&#160', $row['itemsolo'].$append):'';
                $ret2['count'] = $row['count'];
                $ret2['stamp'] = $row['tstamp'];
                $ret2['browserversion'] = $row['browserversion'];
                
                // perform special actions for browser, referer
                // if browser set browsername and browserlink
                if ($field0 == 'browser'){
                    $ret2['item'] = (isset($this->browser[intval($ret2['itemsolo'])]) && $this->browser[intval($ret2['itemsolo'])][CYSTATS_URL] != '')?
                        '<a href="http://'.str_replace('http://','',$this->browser[intval($ret2['itemsolo'])][CYSTATS_URL]).'">'
                        .preg_replace('/\s\s+/', '&#160;', $this->browser[intval($ret2['itemsolo'])][2]).' '.((($this->_browser_full_info == TRUE)?htmlspecialchars($ret2['browserversion']):'')).'</a>':'';#:                        preg_replace('/\s\s+/', '&#160;', htmlspecialchars($ret2['item']));
                }
                elseif ($field0 == 'os'){
                    $ret2['item'] = (isset($this->os[intval($ret2['itemsolo'])]))?preg_replace('/\s\s+/', '&#160;',$this->os[intval($ret2['itemsolo'])][0]):'';
                }
                // if referer set refererlink and shorten referer for output
                elseif($field0 == 'referer'){
                    $ret2['item'] = '<a href="'.$ret2['itemsolo'].'" title="'.__('Visit refering page','cystats').'" target="_blank">'.cystats_htmlfilter(preg_replace('/\s\s+/', '&#160;', $this->cutString($ret2['itemsolo'],get_option('cystats_shorten_referer')))).'</a>';
                }
                elseif($field0 == 'pagetype'){
                    $ret2['item'] = isset($this->pagetypes_diff[$ret2['item']])?'<a href="#" title="'.$this->pagetypes_title[$ret2['item']].'">'.$this->pagetypes_diff[$ret2['item']].'</a>':''; 
                    
                    }
                 // all other cases->build item string and append $append string or set to "?" if item empty
                
                else{
                    $ret2['item'] = preg_replace('/\s\s+/', '&#160;', $row['item']);
                }

                // increment item count
                $scount = $scount+$row['count'];

                // page->cut string
                $translateID = array(1, 2);
                if ($field0 == 'page' || $field0 == 'entry') {
                    if (in_array($ret2['pagetype'], $translateID)) {
                        $getname = $wpdb->get_var('SELECT post_title FROM '.$wpdb->posts.' WHERE ID='.$ret2['pageid']);
                        if ($getname != FALSE) {
                            $ret2['item'] = '<a href="'.''.$ret2['item'].'" title="'.__('Visit this page','cystats').'">'.cystats_htmlfilter(str_replace(' ', '&#160;', $this->cutString($getname,get_option('cystats_shorten_referer')))).'</a>';
                        } else {
                            $ret2['item'] = '<a href="'.''.$ret2['item'].'" title="'.__('Visit this page','cystats').'">'.cystats_htmlfilter(str_replace(' ', '&#160;', $this->cutString($ret2['item'],get_option('cystats_shorten_referer')))).'</a>';
                        }
                    }else{
						if($ret2['pagetype']==0){
							$title=__('Main page','cystats');
							
						}else{
							$title=str_replace(' ', '&#160;', $this->cutString($ret2['item'],get_option('cystats_shorten_referer')-20));
						}       	
                        $ret2['item'] = '<a href="'.''.$ret2['item'].'" title="'.__('Go to called page','cystats').'">'.cystats_htmlfilter($title).'</a>';
                	                if($ret2['item']=='/' || $ret2['item']==get_option('siteurl'))$ret2['item']=__('Main page','cystats');

                	}
                }
                if(!empty($ret2['item']))
                $ret[] = $ret2;
            }
        }
        return($ret);
    }

    /**
    * Get list of ip, host, referer, pageviews,...
    * Before getting the data this function attempts to clean up the raw database
    * table trying to remove old entries if max number of entries is exeeded.
    * 
    * @param string $field not used ? Gotta look at this...
    * @param integer $limit number of rows to fetch from database
    */
    function getVisitsData($field, $limit) {
        global $db;
        global $wpdb;
    
        $select = "DELETE FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW." WHERE stamp < ".(time()-get_option('cystats_rawtable_max'));
        $wpdb->query($select);

        /* Create temporary table for all
         * remote adresses
         */
        $q="SELECT
                remote_addr AS item,
                browser AS browserimage,
                http_user_agent AS user_agent,
                browserversion,
                page,http_accept_language,os,stamp,browsertype,
                colordepth,screen_w,screen_h,remote_host,referer,method,pagetype
            FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW."                
            ORDER BY stamp DESC
            LIMIT ".$limit;
        $r = $wpdb->get_results($q,ARRAY_A);

        $where = '';
        $ret = array();
        $olditem = '';
        $this->set_cut_length(30);
        if(!empty($r)){
			foreach ($r AS $row) {
				$row = dbStripArray($row);
					$ret2['item'] = long2ip($row['item']);

					$ret2['browser'] = $row['browser'];
#$ret2['item'] = (isset($this->browser[intval($ret2['itemsolo'])]) && $this->browser[intval($ret2['itemsolo'])][CYSTATS_URL] != '')?
#                        '<a href="http://'.str_replace('http://','',$this->browser[intval($ret2['itemsolo'])][CYSTATS_URL]).'">'
#                        .preg_replace('/\s\s+/', '&#160;', $this->browser[intval($ret2['itemsolo'])][2]).' '.((($this->_browser_full_info == TRUE)?htmlspecialchars($ret2['browserversion']):'')).'</a>':'';#:                        preg_replace('/\s\s+/', '&#160;', htmlspecialchars($ret2['item']));
                
					if ($row['browserimage']!=0){
						$ret2['browser'] = (isset($this->browser[intval($row['browserimage'])]))?str_replace(' ', '&#160;', $this->browser[intval($row['browserimage'])][2].' '.$row['browserversion']):'';
					}else{
					   $ret2['browser']= str_replace(' ', '&#160;',htmlspecialchars($row['user_agent']));
					}

					$ret2['os'] = (isset($this->os[intval($row['os'])]))?str_replace(' ', '&#160;', $this->os[intval($row['os'])][0]):'';
					$ret2['osid']=$row['os'];
					$ret2['browsertype'] = $row['browsertype'];
					$ret2['browserimage'] = intval($row['browserimage']);
					$ret2['colordepth'] = $row['colordepth'];
					$ret2['screenres'] = $row['screen_w'].'&#215;'.$row['screen_h'];
					$ret2['js'] = $row['js'];
					$ret2['remote_host'] = $row['remote_host'];
					$ret2['referer'] = $row['referer'];
					$ret2['pageviews'] = $row['pageviews'];
					$ret2['stamp'] = str_replace(' ','&#160;', $row['stamp']);
					$ret2['page'] = $row['page'];
					$ret2['accept_language'] = $row['http_accept_language'];
					$ret2['method'] = $row['method'];
					$ret2['pagetype'] = $row['pagetype'];
					$ret[] = $ret2;
				$olditem = $row['item'];

			}
		}
        return($ret);
    }


    function getPostsByDate($mode=FALSE,$d=FALSE,$m=FALSE,$y=FALSE){
        global $wpdb;
        $datewhere='';
        if($d){
            $datewhere.= ' AND DAY('.$wpdb->posts.'.post_date)='.$wpdb->escape($d);
        }        
        if($m){
            $datewhere.= ' AND MONTH('.$wpdb->posts.'.post_date)='.$wpdb->escape($m);
        }        
        if($y){
            $datewhere.= ' AND YEAR('.$wpdb->posts.'.post_date)='.$wpdb->escape($y);
        }        
       $r=$wpdb->get_results(
            "SELECT 
                post_name, 
                post_author,post_date,
                guid,
                COUNT(".$wpdb->comments.".comment_post_ID) AS 'commentcount',
                $wpdb->prefix".CYSTATS_TABLE_STATISTICS.".val3 AS hits
            FROM 
                $wpdb->posts, 
                $wpdb->post2cat
                
            LEFT JOIN $wpdb->comments
            ON  
                $wpdb->comments.comment_post_ID=post_ID
            LEFT JOIN $wpdb->prefix".CYSTATS_TABLE_STATISTICS."
            ON
                $wpdb->prefix".CYSTATS_TABLE_STATISTICS.".type=27 
                AND $wpdb->prefix".CYSTATS_TABLE_STATISTICS.".val1=post_ID  
            WHERE 
                $wpdb->posts.ID = $wpdb->post2cat.post_id  
                AND $wpdb->posts.post_type = 'post' 
                AND $wpdb->posts.post_status = 'publish'
                ".$datewhere." 
            GROUP BY ".$wpdb->comments.".comment_post_ID  
            ORDER BY post_date DESC
            ",ARRAY_A);
            echo mysql_error();
        if($r){
            $max=0;
            foreach($r AS $row){
                $max+=$row['hits'];
            }    
            echo '<table class="cystats" style="width:100%;font-size:11px;" cellspacing="1" cellpadding="1">';
            echo '<tr><th colspan="4" class="cystats" style="text-align:left;">'.__('Posts/Date','cystats').'</th></tr>';
            echo '<tr><td><div class="table_container_stats"><table>';        
            foreach($r AS $row){
                $percent=($max==0)?0:round(($row['hits']/$max * 100));
                if ($percent < 1)$bar = 1;
                    elseif($percent > 99)$bar = 100;
                    else $bar = $percent;
                $ret2='<tr>
                    <td style="width:100%"><a href="'.htmlspecialchars($row['guid']).'">'.htmlspecialchars($row['post_name']).'</a>&#160;('.$row['commentcount'].')</td>
                    <td><span class="count_result">'.$row['hits'].'</span></td>
                    <td><div style="height:1em;width:'.$bar.'px;background-image:url(../wp-content/plugins/cystats/gfx/bar.gif)">&nbsp;</div></td>
                    <td>'.$percent.'%</td>
                    </tr>';
                echo $ret2;
            }            
            echo '</table></div></td></tr>';
            echo '</table>';
        }
    }



	function getDaily($mode=0){
	global $wpdb;
	if($mode==0){
		$t=	CYSTATS_VISITSDAY_NOBOTS;
	}else{
		$t=	CYSTATS_HITSDAY_NOBOTS;
	}
	
	$q='SELECT 	val1,val2,
				DATE_FORMAT( FROM_UNIXTIME(val2), " ") AS tstamp,
				val3 AS value 
			FROM '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS.' 
			WHERE type='.$t.' ORDER BY val2 DESC LIMIT 30';		

        $r = $wpdb->get_results($q,ARRAY_A);

        $maxa = $wpdb->get_results("SELECT val3 AS value FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS." WHERE type=".$t." ORDER BY val2 DESC LIMIT 30");
        #echo mysql_error();
        $max=0;
        if(is_array($maxa))foreach($maxa AS $ma)$max=$max+$ma->value;
        foreach ($r AS $row) {
        	if($row['val1']!='1970'){ // bug hiding !? Sorry, could not find this one yet :(
            $row['percent'] = round($row['value']/$max * 100);
            $row['item'] = str_replace(" ", '&#160;', date('d. F', (intval($row['val2']) ) )) ;
            $ret[] = $row;
		}
        }
        return($ret);
}

    /**
    * Print information for brower or screenresolution or operating system,...
    * 
    * @param array $data information array
    * @param string $header table header text
    * @param filterarray default FALSE
    * @param string $height css style string for scroll div height
    */
    function outputStatsData($data, $header, $height=FALSE, $icontype='',$convertIP=FALSE) {
    	global $wpdb;
    	#$gmt=get_option('cystats_time_offset');
        $css_height=($height==FALSE)?'':'style="height:'.$height.';"';
        if(!empty($icontype)){
            $colspan=6;
        }else{
            $colspan=5;
        }

        if (is_array($data)) {
            echo '<table class="cystats">';
            echo '<tr><th colspan="'.$colspan.'" class="cystats" style="text-align:left;">'.$header.'</th></tr>';
            echo '<td><div class="container_visits_raw_data" '.$css_height.'><table class="sortable">
            	<tr>';
            	
            if($colspan==6) echo '<th class="sorttable_nosort">&#160;</th>';
            echo	'<th>Item</th>
            		<th>Count</th>
            		<th>Percent</th>
            		<th class="sorttable_nosort">&#160;</th>
            		<th>Date</th>
            	</tr>';
            foreach($data AS $k => $v) {
                $data = dbStripArray($data);
                $v['itemsolo'] = strtolower(str_replace(" ", "", $v['itemsolo']));
                $im = (isset($this->images[$icontype][$v['itemsolo']]) && is_file('../wp-content/plugins/cystats/gfx/'.$icontype.'/'.$this->images[$icontype][$v['itemsolo']]))? '../wp-content/plugins/cystats/gfx/'.$icontype.'/'.$this->images[$icontype][$v['itemsolo']]:
                '../wp-content/plugins/cystats/gfx/'.$icontype.'/mini.gif';
                if ($v['percent'] < 1)$bar = 1;
                elseif($v['percent'] > 99)$bar = 100;
                else $bar = $v['percent'];
                

                if(!empty($v['item']))
                if($convertIP){
                    $v['item']=$this->bigint2ip($v['item']);
                    $v['item'] = '<a href="http://who.is/whois-ip/ip-address/'.htmlspecialchars($v['item']).'/" title="WhoIs...">'.htmlspecialchars($v['item']).'</a>';
                
                }
                echo '<tr>';
                    if($colspan==6){
                        echo '<td class="cystats'.($k%2).' firstcol"><img src="'.$im.'" width="14px" /></td>';
                    }
                    echo '<td class="cystats'.($k%2).' firstcol" style="width:100%;">'.$v['item'].'</td>
                    <td class="cystats'.($k%2).'" style="text-align:right;"><span class="count_result">'.$v['count'].'</span></td>
                    <td class="cystats'.($k%2).'" style="text-align:right;">'.$v['percent'].'%</td>
                    <td class="cystats'.($k%2).'"><div style="height:1em;width:'.$bar.'px;background-image:url(../wp-content/plugins/cystats/gfx/bar.gif)">&nbsp;</div></td>
                    <td class="cystats'.($k%2).' firstcol">'.gmdate(str_replace(' ', '&#160;', get_option('date_format')." ".get_option('time_format')), ($v['stamp'])).'</td></tr>';
            }
            echo '</table></div>';
            echo '</tr></table>';
        }

    }

    /**
    * Output list of ip, pageviews, host, referer, ...
    * 
    * @param array $data information array
    * @param string $header table header string
    */

    function outputVisitsData($data, $header,$height=200) {
    	global $wpdb;
        $methods=array('POST','GET');
        $pagetypes=&$this->pagetypes;
        #$gmt=get_option('cystats_time_offset');
        if (is_array($data)) {
            echo '<table class="cystats" style="width:100%;height:'.$height.'px;"><tr>';
            echo '<th colspan="8" class="cystats" style="text-align:left;">'.$header.'</th>';
            echo '</tr>';
            
            if(!empty($data)){
				echo '<tr><td><div class="container_visits_raw_data" style="height:'.$height.'px;"><table class="sortable">';
				//cycle data rows
				$osim_cache=array();
				$im_cache=array();
				echo '<tr>
					<th>IP</th>
					<th class="sorttable_nosort">&nbsp;</th>
					<th>Browser</th>
					<th class="sorttable_nosort">&nbsp;</th>
					<th>OS</th>
					<th>Date</th>
					<th>Method</th>
					<th>Type</th>
					<th>URL</th>
					</tr>';
				foreach($data AS $k => $v) {

						//get browserimage
						#$im = (in_array($v['browserimage'],$im_cache) || (isset($this->images['browser'][$v['browserimage']]) && is_file('../wp-content/plugins/cystats/gfx/browser/'.$this->images['browser'][$v['browserimage']])))?'../wp-content/plugins/cystats/gfx/browser/'.$this->images['browser'][$v['browserimage']]:'../wp-content/plugins/cystats/gfx/browser/mini.gif';
						# if($im!='../wp-content/plugins/cystats/gfx/browser/mini.gif'){
						#    $im_cache[]=$v['browserimage'];
						#}                   
							
						if(!in_array($v['browserimage'],$im_cache)){
							if(!(isset($this->images['browser'][$v['browserimage']]) && is_file('../wp-content/plugins/cystats/gfx/browser/'.$this->images['browser'][$v['browserimage']]))){
								$im='../wp-content/plugins/cystats/gfx/browser/mini.gif';
							}else{
								$im_cache[]=$v['browserimage'];                      
								$im='../wp-content/plugins/cystats/gfx/browser/'.$this->images['browser'][$v['browserimage']];
							}
						}else{
							$im='../wp-content/plugins/cystats/gfx/browser/'.$this->images['browser'][$v['browserimage']];
						}    

						if(!in_array($v['osid'],$osim_cache)){
							if(!(isset($this->images['os'][$v['osid']]) && is_file('../wp-content/plugins/cystats/gfx/os/'.$this->images['os'][$v['osid']]))){
								$osim='../wp-content/plugins/cystats/gfx/os/mini.gif';
							}else{
								$osim_cache[]=$v['osid'];                      
								$osim='../wp-content/plugins/cystats/gfx/os/'.$this->images['os'][$v['osid']];
							}
						}else{
							$osim='../wp-content/plugins/cystats/gfx/os/'.$this->images['os'][$v['osid']];
						}    
										//get hostimage
						$host = explode(".", $v['remote_host']);
						$items = count($host);
						$v['remote_host'] = (isset($host[$items-2]))?$host[$items-2].".":"";
						$v['remote_host'] .= (isset($host[$items-1]))?$host[$items-1]:"";
						$v['remote_host'] = str_replace("-", "&#45;", $v['remote_host']);
						$remote_addr = $v['item'];
						#$hostflag = (is_file("../wp-content/plugins/cystats/gfx/flags/".$host[$items-1].".gif"))?'<img src="../wp-content/plugins/cystats/gfx/flags/'.$host[$items-1].'.gif" height:10px;>':"&nbsp;";
						if(isset($this->browser['browser'][intval($v[browserimage])])){
							if(!empty($this->browser[intval($v[browserimage])][3])){
								$v['browser']='<a href="http://'.$this->browser[intval($v[browserimage])][3].'" title="">'.$v['browser'].'</a>';
							}
						}else{
							$v['browser']=$this->cutString($v['browser'],get_option('cystats_shorten_user_agent'));
						}
						
						//output data
						$td_css=(empty($v['item'])?'raw-pages':($k%2));
						if($v['stamp']>=(time()-300)){
							$td_css='raw-last-visits';
						}
						echo '<tr>
							<td class="cystats'.$td_css.'  firstcol"><a href="http://who.is/whois-ip/ip-address/'.htmlspecialchars($remote_addr).'/" title="WhoIs...">'.htmlspecialchars($remote_addr).'</a></td>
							<td class="cystats'.$td_css.'" style="padding-right:0px;"><img src="'.htmlspecialchars($im).'" width="12"/></td>
							<td class="cystats'.$td_css.'" style="border-left:none;">'.$v['browser'].'&#160;'.((!empty($v['accept_language']))?('('.htmlspecialchars($v['accept_language']).')'):'').'</td>
							<td class="cystats'.$td_css.'" style="padding-right:0px;"><img src="'.htmlspecialchars($osim).'" width="12"/></td>
							<td class="cystats'.$td_css.'" style="border-left:none;" >'.cystats_htmlfilter($v['os']).'</td>
							<td class="cystats'.$td_css.'">'.gmdate(str_replace(" ", "&#160;", get_option('date_format')." ".get_option('time_format')), ($v['stamp'])).'</td>
							<td class="cystats'.$td_css.'">'.$methods[intval($v['method'])].'</td>
							<td class="cystats'.$td_css.'"><span class="pagetype'.intval($v['pagetype']).'">'.(isset($pagetypes[intval($v['pagetype'])])?$pagetypes[intval($v['pagetype'])]:'&#160;').' </span></td>
							<td class="cystats'.$td_css.'" style="width:100%;"><a href="'.$v['page'].'" title="">'.$this->cutString($v['page'],get_option('cystats_shorten_page'))
							.'</a>'
							.(empty($v['referer'])?'':'<span class="count_sub_text" style="background:transparent;display:block;padding-top:4px;">&nbsp;&nbsp;VIA: <a class="cystats_sub_referer_link" href="'.cystats_htmlfilter($v['referer']).'" title="'.htmlspecialchars(__('Visit refering page','cystats')).'" target="blank">'.cystats_htmlfilter($this->cutString($v['referer'],get_option('cystats_shorten_referer'))).'</a></span>')
							.'</td>
							</tr>';
					#}
				}
				echo '</table></div>';
			}else{
				echo '<tr><td class="cystats0" style="width:100%;text-align:center;">'.__('No database entries','cystats').'</td></tr>';
			}
            echo '</td></tr></table>';
        }
        else echo $data;
    }

    /**
     * Read numeric stats data for e.g. Hits/Year, Hits/Month,...
     * from database.$wpdb->prefix.CYSTATS_TABLE_STATISTICS
     * 
     * @param integer $type CYSTATS_SEARCHWORD/CYSTATS_HITSYEAR/CYSTATS_HITSMONTH/CYSTATS_VISITSYEAR/CYSTATS_VISITSMONTH/...
     * @param string $where MySQL additional WHERE clause
     * 
     * @return array
     */
    function getNumberStats($type, $where = FALSE) {
        global $db;
        global $wpdb;

        $where = ($where == FALSE)?"":
        " AND ".$where;

        if ($type == CYSTATS_SEARCHWORD) {
            $select = "name AS item, val3 AS value";
            $order = "val3 DESC";
        } elseif($type == CYSTATS_HITSYEAR) {
            $select = "val1 AS item, val3 AS value";
            $order = "val1 DESC";
        } elseif($type == CYSTATS_HITSMONTH) {
            $select = "CONCAT(val2,'/',val1) AS item, val3 AS value";
            $order = "val1 DESC, val2 DESC";
        } elseif($type == CYSTATS_VISITSYEAR) {
            $select = "val1 AS item, val3 AS value";
            $order = "val1 DESC";
        } elseif($type == CYSTATS_VISITSMONTH) {
            $select = "CONCAT(val2,'/',val1) AS item, val3 AS value";
            $order = "val1 DESC, val2 DESC";
        } elseif($type == CYSTATS_VISITSWEEK) {
            $select = "CONCAT(val2,'/',val1) AS item, val3 AS value";
            $order = "val1 DESC, val2 DESC";
        } elseif($type == CYSTATS_HITSWEEK) {
            $select = "CONCAT(val2,'/',val1) AS item, val3 AS value";
            $order = "val1 DESC, val2 DESC";
        } elseif($type == CYSTATS_USERCOUNT) {
            $select = "val1 AS item, val3 AS value";
            $order = "val1 DESC, val2 DESC";
        } elseif($type == CYSTATS_POSTCOUNT) {
            $select = "val1 AS item, val3 AS value";
            $order = "val1 DESC, val2 DESC";
        } else {
            $order = "val1 DESC,val2 DESC";
            $select = "name AS item, val3 AS value";
        }

        $ret = array();

		

        $q = "  SELECT  val1,val2,".$select."
            FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."
            WHERE
            type='".$type."'".$where."
            ORDER BY ".$order." 
            ";
        $r = $wpdb->get_results($q,ARRAY_A);
        $max = $wpdb->get_var("SELECT SUM(val3) FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS." WHERE type='".$type."' ".$where);

        if(!empty($r)){
                foreach ($r AS $row ) {
                if($row['val1']!='1970'){
                #echo "<br>";var_dump($row);
                if($type == CYSTATS_VISITSMONTH) {
                    $row['item']='<a href="'.'admin.php?page=cystats-time&amp;month='.intval($row['val2']).'&amp;year='.intval($row['val1']).'">'.htmlspecialchars($row['item']).'</a>';
                }elseif($type == CYSTATS_VISITSYEAR) {
                    $row['item']='<a href="'.'admin.php?page=cystats-time&amp;&amp;year='.intval($row['val1']).'">'.htmlspecialchars($row['item']).'</a>';
                }else $row['item']=htmlspecialchars($row['item']);
                $row['percent'] = round(($row['value']/$max * 100));
                $ret[] = $row;
               }
            }
        }
        return((empty($ret)?array(array('item' => '', 'value' => '0')):$ret));
    }

    /*
    * OutputNumberStatsData like browser, os, screenres lists
    * 
    * @param array $data
    * @param string $header
    */
    function outputNumberStatsData($data,$data2, $header,$noSecondColumn=FALSE) {
        $descr[0]=str_replace(' ','&#160;',htmlspecialchars(__('Hits','cystats')));
        $descr[1]=str_replace(' ','&#160;',htmlspecialchars(__('Unique visits','cystats')));
        #var_dump($data);
        #var_dump($data2);
        if($noSecondColumn){
            $colspan=3;
        }else{
            $colspan=4;
        }
        if (is_array($data)) {
            
            echo '<table class="cystats" >';
            echo '<tr><th class="cystats" colspan="'.$colspan.'" style="text-align:left;">'.$header.'</th></tr><tr><td><table class="sortable">';
            foreach($data AS $kr=>$datarow){
                foreach($datarow AS $k => $v) {
                    #var_dump($v);
                    if ($v['percent'] < 1)$bar = 1;
                    elseif($v['percent'] > 99)$bar = 100;
                    else $bar = $v['percent'];
                    
                    echo '
                        <tr>
                        <td class="cystats'.($kr%2).' firstcol">'.$descr[$kr%2].'</td>';
                        
                    if($noSecondColumn==FALSE){
                        echo '<td class="cystats'.($kr%2).'">';
                        if($data2!=FALSE){
                            echo '<span class="count_sub';
                            if($v['value']-$data2[$kr][$k]['value']<0){
                                echo '0';
                            }else{
                                echo '1';
                            }
                            echo '">'
                            .' ('.((($v['value']-$data2[$kr][$k]['value'])>0)?'+':'').(($v['value']-$data2[$kr][$k]['value'])).')'
                            .'</font>';
                        
                        }
                            
                            echo '</td>';
                    }    
                    echo '<td class="cystats'.($kr%2).'" style="width:100%;">';
                    if($data2!=FALSE){
                        echo '<span class="count_sub_text">'.htmlspecialchars(__('Last value','cystats')).': '
                        .$data2[$kr][$k]['value'].'</span>';
                    }
                    echo '</td>';
                    echo '<td class="cystats'.($kr%2).'" style="text-align:right;">';
                    echo '<span class="count_result">'.cystats_htmlfilter($v['value']).'</span>';
                    echo '</td>
                        </tr>';
                }
            }
            echo '</table></td></tr></table>';
        }
        else echo $data;
    }

    /*
    * OutputTimeStatsData like days/weeks overview
    *
    * @param array $data
    * @param string $header
    */
    function outputTimeStatsData($data, $header) {
        if (is_array($data)) {
            echo '<table class="cystats" cellspacing="1" cellpadding="1" >';
            echo '<tr><th class="cystats" colspan="3" style="text-align:left;">'.$header.'</th></tr>';
            echo '<tr><td><div class="container_visits_raw_data"><table class="sortable"><tr><th style="text-align:left;">Date</th><th>Count</th><th class="sorttable_nosort">&#160;</th></tr>';
            $scale=1;
            $max=0;
            foreach($data AS $k=>$v)if($v['percent']>$max)$max=$v['percent'];
            $scale=round(100/$max)-1;
            $scale=($scale<=1)?1:$scale;
            foreach($data AS $k=>$v){
            
                if ($v['percent'] < 1)$bar = 1;
                elseif($v['percent'] > 99)$bar = 100;
                else $bar = $v['percent'];
                $bar=$bar*2;
                
                echo '
                    <tr>
                    <td class="cystats'.($kr%2).' firstcol" style="width:100%;">'.$v['item'].'</td>
                    <td class="cystats'.($kr%2).'"><span class="count_result">'.cystats_htmlfilter($v['value']).'</span></td>
                    <td class="cystats'.($kr%2).'"><div style="height:1em;width:'.($bar*$scale).'px;background-image:url(../wp-content/plugins/cystats/gfx/bar_long.gif)">&nbsp;</div></td>
                    </tr>';
            }
        
            echo '</table></div>';
            echo '</td></tr></table>';
        }
        else echo $data;
    }

    /**
    * Get hourly stats from raw table
    * 
    * @return array
    */
    function getTimeHours() {
        global $wpdb;
        $select = "DATE_FORMAT( FROM_UNIXTIME( stamp ) , '%H' )";
        $q = "
            SELECT
            DISTINCT ".$select." AS item,
            COUNT( ".$select.") AS value
            FROM
            ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW."
            GROUP BY item
            ORDER by ".$select." ASC
            ";
        $r = $wpdb->get_results($q,ARRAY_A);

        $max = 0;#mysql_result(mysql_query("SELECT COUNT(stamp) FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW), 0, 0);
        if(is_array($r))foreach($r AS $maxa)$max=$max+$maxa['value'];
        foreach ($r AS $row) {
            $row['percent'] = round($row['value']/$max * 100);
            $row['item'] = $row['item'].'&#160;Uhr';
            $ret[] = $row;
        }
        return($ret);
    }


    /**
    * Get week day stats from raw table
    * 
    * @return array
    */
    function getWeekDays($all=FALSE) {
        global $wpdb;
        $select = "DATE_FORMAT( FROM_UNIXTIME( stamp ) , '%W' )";
        $distinct=($all==FALSE)?' DISTINCT':'';
        $q = "
            SELECT
            DISTINCT ".$select." AS item,
            COUNT( ".$select.") AS value,
            DATE_FORMAT( FROM_UNIXTIME( stamp ) , '%w' ) AS sortday
            FROM
            ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW."
            GROUP BY item
            ORDER by sortday ASC
            ";
        $r = $wpdb->get_results($q,ARRAY_A);

        $max = 0;
        if(is_array($r))foreach($r AS $maxa)$max=$max+$maxa['value'];#mysql_result(mysql_query("SELECT COUNT(stamp) FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW), 0, 0);
        foreach ($r AS $row) {
            $row['percent'] = round($row['value']/$max * 100);
            $row['item']=$this->weekdays[$row['item']];
            $ret[] = $row;
        }
        return($ret);
    }

    function getCategoriesData(){    
		global $wpdb;
		$wpv = (float)get_bloginfo('version');
    	if($wpv<2.3){
		$querystr = "
			SELECT 
				SUM(val3) AS value, 
				$wpdb->categories.cat_name AS item
			FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS." 
			LEFT JOIN $wpdb->posts 
				ON ($wpdb->posts.ID = ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS.".val1)
			LEFT JOIN $wpdb->post2cat 
				ON ($wpdb->posts.ID = $wpdb->post2cat.post_id)
			LEFT JOIN $wpdb->categories 
				ON ($wpdb->categories.cat_ID=$wpdb->post2cat.category_id)
			WHERE $wpdb->posts.post_status = 'publish'
			AND ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS.".type=27
			AND $wpdb->posts.post_type = 'post'
			AND $wpdb->posts.post_status = 'publish'
			GROUP BY $wpdb->categories.cat_ID
			ORDER BY value DESC
		";
	}else{
		$querystr = "
			SELECT SUM( val3 ) AS value, $wpdb->terms.name AS item
			FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."
			LEFT JOIN $wpdb->posts ON ( $wpdb->posts.ID = ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS.".val1 )
			LEFT JOIN $wpdb->term_relationships ON ( $wpdb->posts.ID = $wpdb->term_relationships.object_id )
			LEFT JOIN $wpdb->term_taxonomy ON ( $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id )
			LEFT JOIN $wpdb->terms ON ( $wpdb->term_taxonomy.term_ID = $wpdb->terms.term_ID )
			WHERE $wpdb->posts.post_status = 'publish'
			AND $wpdb->posts.post_type = 'post'
			AND ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS.".type =27
			AND $wpdb->term_taxonomy.taxonomy = 'category'
			GROUP BY $wpdb->term_taxonomy.term_taxonomy_id
			ORDER BY value DESC
			";
	}

		$r=$wpdb->get_results($querystr);
		
		$querystr="
			SELECT SUM( val3 ) AS value
			FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."
			LEFT JOIN $wpdb->posts ON ( $wpdb->posts.ID = ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS.".val1 )
			LEFT JOIN $wpdb->term_relationships ON ( $wpdb->posts.ID = $wpdb->term_relationships.object_id )
			LEFT JOIN $wpdb->term_taxonomy ON ( $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id )
			LEFT JOIN $wpdb->terms ON ( $wpdb->term_taxonomy.term_ID = $wpdb->terms.term_ID )
			WHERE $wpdb->posts.post_status = 'publish'
			AND $wpdb->posts.post_type = 'post'
			AND ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS.".type =27
			AND $wpdb->term_taxonomy.taxonomy = 'category'
			GROUP BY $wpdb->term_taxonomy.term_taxonomy_id
			";

		$maxa=$wpdb->get_results($querystr);
		if(is_array($maxa))foreach($maxa AS $ma)$max=$max+$ma->value;
		
		$ret2=array();
		if(is_array($r)){
			foreach($r AS $v){
				$ret=array();
				$percentage=0;
				$percentage=($max==0)?0:round(($v->value/$max * 100));
				$ret['item']=$v->item;
				$ret['value']=$v->value;
				$ret['percent']=$percentage;
				$ret2[]=$ret;
			}	
		}
		
		return($ret2);
	}

    function getTagsData(){
        global $wpdb;
		#$tagList=get_terms('post_tag',array('orderby'=>'count','order'=>'desc'));
		#var_dump($tagList);
        $querystr=    "
			SELECT SUM( val3 ) AS value, $wpdb->terms.name AS item
			FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."
			LEFT JOIN $wpdb->posts ON ( $wpdb->posts.ID = ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS.".val1 )
			LEFT JOIN $wpdb->term_relationships ON ( $wpdb->posts.ID = $wpdb->term_relationships.object_id )
			LEFT JOIN $wpdb->term_taxonomy ON ( $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id )
			LEFT JOIN $wpdb->terms ON ( $wpdb->term_taxonomy.term_ID = $wpdb->terms.term_ID )
			WHERE $wpdb->posts.post_status = 'publish'
			AND $wpdb->posts.post_type = 'post'
			AND ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS.".type =27
			AND $wpdb->term_taxonomy.taxonomy = 'post_tag'
			GROUP BY $wpdb->term_taxonomy.term_taxonomy_id
			ORDER BY value DESC
			";
			#var_dump($querystr);
		$ret = array();
        $max=0;
		$r=$wpdb->get_results($querystr);
		#var_dump($r);
        $querystr=    "
			SELECT SUM( val3 ) AS value
			FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."
			LEFT JOIN $wpdb->posts ON ( $wpdb->posts.ID = ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS.".val1 )
			LEFT JOIN $wpdb->term_relationships ON ( $wpdb->posts.ID = $wpdb->term_relationships.object_id )
			LEFT JOIN $wpdb->term_taxonomy ON ( $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id )
			LEFT JOIN $wpdb->terms ON ( $wpdb->term_taxonomy.term_ID = $wpdb->terms.term_ID )
			WHERE $wpdb->posts.post_status = 'publish'
			AND $wpdb->posts.post_type = 'post'
			AND ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS.".type =27
			AND $wpdb->term_taxonomy.taxonomy = 'post_tag'
			GROUP BY $wpdb->term_taxonomy.term_taxonomy_id
			";
		$maxa=$wpdb->get_results($querystr);
		if(is_array($maxa))foreach($maxa AS $ma)$max=$max+$ma->value;
		
		if(is_array($r)){
			foreach($r AS $v){
				$ret=array();
				$percentage=0;
				$percentage=($max==0)?0:round(($v->value/($max/100)));
				$ret['item']=$v->item;
				$ret['value']=$v->value;
				$ret['percent']=$percentage;
				$ret2[]=$ret;
			}	
		}
            return($ret2);
    }

    /*
    * OutputTimeStatsData like days/weeks overview
    *
    * @param array $data
    * @param string $header
    */
    function outputSimpleStatsData($data, $type, $header) {
        global $wpdb;
        if (is_array($data)) {
            echo '<table class="cystats" >';
            echo '<tr><th class="cystats" colspan="4" style="text-align:left;">'.$header.'</th></tr>';
            echo '<tr><td><div class="container_visits_raw_data"><table>';
            foreach($data AS $k=>$v){
            if($type==CYSTATS_USERCOUNT){
                if(intval($v['item'])==0){
                    $username=htmlspecialchars(__('item_unknown_guests','cystats'));
                }else{
                    $username=$wpdb->get_var("SELECT user_login FROM ".$wpdb->users." WHERE ID=".(intval($v['item'])));
                }
                $v['item']=$username;
            }
            if($type==CYSTATS_POSTCOUNT){
                $posttitle=$wpdb->get_var("SELECT post_title,guid FROM ".$wpdb->posts." WHERE ID=".(intval($v['item'])));
                $v['item']='<a href="'.$v['guid'].'">'.str_replace(" ","&nbsp;",$posttitle).'</a>';
            }
                if ($v['percent'] < 1)$bar = 1;
                elseif($v['percent'] > 99)$bar = 100;
                else $bar = $v['percent'];
                
                echo '
                    <tr>
                    <td class="cystats'.($kr%2).' firstcol" style="width:100%;">'.$v['item'].'</td>
                    <td class="cystats'.($kr%2).'"><span class="count_result">'.cystats_htmlfilter($v['value']).'</span></td>
                    <td class="cystats'.($kr%2).'"><div style="height:1em;width:'.$bar.'px;background-image:url(../wp-content/plugins/cystats/gfx/bar.gif)">&nbsp;</div></td>
                    <td class="cystats'.($kr%2).'">'.cystats_htmlfilter($v['percent']).'%</td>
                    </tr>';
            }
        
            echo '</table></div></td></tr>';
            echo '</table>';
        }
        else echo $data;
    }

    function get_database_stats(){
        global $wpdb;
        $result = $wpdb->get_results("SHOW TABLE STATUS");
        $c=0;
        foreach($result AS $row )
        { 
           $sumup['free']=$sumup['free']+$row->Data_free;
           $sumup['avg']=$sumup['avg']+$row->Avg_row_length;
           $sumup['data']=$sumup['data']+$row->Data_length;
           $sumup['index']=$sumup['index']+$row->Index_length;
           $cr=$c%2;    
           $warning=($row->Data_free>0)?array('<span style="color:red;">','</span>'):array('','');
           echo '<tbody><tr>
                       <td class="cystats'.($cr).'" style="width:100%">'.htmlspecialchars($row->Name).'</td>
                       <td class="cystats'.($cr).'">'.htmlspecialchars($row->Rows).'</td>
                       <td class="cystats'.($cr).'">'.$this->convert_bytes(htmlspecialchars($row->Avg_row_length)).'</td>
                       <td class="cystats'.($cr).'">'.$this->convert_bytes(htmlspecialchars($row->Data_length)).'</td>
                       <td class="cystats'.($cr).'">'.$this->convert_bytes(htmlspecialchars($row->Index_length)).'</td>
                       <td class="cystats'.($cr).'">'.$warning[0].$this->convert_bytes(htmlspecialchars($row->Data_free)).$warning[1].'</td>
           </tr>';
            $c+=1;
        }
           echo '</tbody><tfoot><tr>
                       <td class="cystats'.($cr).'" style="width:100%;">&#160;</td>
                       <td class="cystats'.($cr).'" style="border-top:2px solid #a4a4a4;">&#160;</td>
                       <td class="cystats'.($cr).'" style="border-top:2px solid #a4a4a4;"><span class="count_result">'.$this->convert_bytes(htmlspecialchars($sumup['avg'])).'</span></td>
                       <td class="cystats'.($cr).'" style="border-top:2px solid #a4a4a4;"><span class="count_result">'.$this->convert_bytes(htmlspecialchars($sumup['data'])).'</span></td>
                       <td class="cystats'.($cr).'" style="border-top:2px solid #a4a4a4;"><span class="count_result">'.$this->convert_bytes(htmlspecialchars($sumup['index'])).'</span></td>
                       <td class="cystats'.($cr).'" style="border-top:2px solid #a4a4a4;"><span class="count_result '.($sumup['free']>0?'warning-color':'').'">'.$this->convert_bytes(htmlspecialchars($sumup['free'])).'</span></td>
           </tr></tfoot>';
    }
    
    /*
     * Converts bytes input to formatted size output
     * @param integer $bytes
     * @return string formatted size
     */
    function convert_bytes($bytes){
        $norm = array('Bytes', 'kB', 'MB', 'GB', 'TB', 'PB');
        $factor = 1000;
        $count = count($norm) -1;
        $x = 0;
        while ($bytes >= $factor && $x < $count) 
        { 
            $bytes /= $factor; 
            $x++;
        } 
        if($norm[$x]=='Bytes'){
            $format="%01.0f";
        }else{
            $format="%01.2f";
        }
        $bytes = sprintf($format, $bytes) . ' ' . $norm[$x];      
        return $bytes; 
    }    


}

?>
