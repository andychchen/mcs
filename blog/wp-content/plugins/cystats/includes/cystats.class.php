<?php
/**
 * CyStats backend main class
 *
 *  This is the main backend class for the CyStats WordPress statistics plugin.
 *  This class provides functions to extract information for a page visit like
 *  global $_SERVER[] contents or javascript information.
 *  Other functions parse the found data or store the data to the database.
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
if (!defined('CYSTATS_HITSYEAR')){require_once('defines.php');}
/**
  * write debugging output to file debug.txt on the webserver
  * not needed on working stat system,
  * this function will be deleted sometimes
  */
function debuglog($text){
    $f=fopen('error_log.txt','a');  
    fwrite($f,$text."\n");
    fclose($f);
}


if (!class_exists('statistics')) {

    class statistics{
        var $_all               = 0;
        var $_thisyear          = 0;
        var $_thismonth         = 0;
        var $_today             = 0;
        var $_now               = 0;
        var $_pagetype          =FALSE;
        var $_pageid            =0;
        var $_pagequery         =FALSE;
        var $_userid            =FALSE;
        var $_now_deltatime     = 300;  // sec.
        var $_max_raw_count     = 4000; // approx. <1Mb raw data, 4000 Hits
        var $info               = array();  // client information array
        var $cystats_data        = array();
        var $cystats_entry        = array();
        var $cid                = '';
        var $sec_min=60;
        var $yesterday=0;
        var $lastmonday=0;
        var $today=0;
        var $lastlastmonday=0;
        var $sec_hour=0;
        var $sec_day=0;
        var $sec_week=0;
        var $browser=array();
        var $os=array();
        var $ignore_ip=array();
        var $ignore_ua=array();
        var $ignore_pages=array();
        var $realIP=0;
        var $realUA='';
        var $debugstring="";

        // define searchword detection phrases
        var $_se_query= array(
                'q',
                'p',
                'query',
                's',
                'search',
                'searchfor'
            );



        /**
          * Initializes basic parameters like pagetype, pageid,...
          * and loads user_agent and operating system parsing file.
          */
        function init(){
            include('browsers.php');
            include('os.php');
            if(function_exists('get_option')){                
                $this->_now_deltatime = get_option('cystats_visit_deltatime');
                $this->_max_raw_count = get_option('cystats_rawtable_max');
                
           		$blocklists=$this->get_ignorelists();

                $this->ignore_ip=$blocklists['IP'];
                $this->ignore_ua=$blocklists['UA'];
                $this->ignore_pages=$blocklists['ID'];
                
            /*
            $q="DELETE FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS." WHERE type=".CYSTATS_RAWCOUNT;    
            $r=$wpdb->query($q);

            $q="INSERT INTO ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS." (name,type,val1,val2,val3) VALUES ("")";    
            $r=$wpdb->query($q);
*/
    
            #echo $wpdb->error();
        }
            
            
           /*
            
 		$testbrowsers = array(
        'microsoft internet explorer',
        'msie'                       ,
        'netscape6'                  ,
        'netscape'                  ,
        'galeon'                     ,
        'phoenix'                    ,
        'mozilla firebird'           ,
        'firebird'                   ,
        'firefox'                   ,
        'chimera'                   ,
        'camino'                     ,
        'epiphany'                   ,
        'safari'                    ,
        'k-meleon'                  ,
        'mozilla'                  ,
        'opera'                     ,
        'konqueror'                  ,
        'icab'                  ,
        'lynx'                    ,
        'links'                     ,                    
        'ncsa mosaic'               ,
        'amaya'                     ,
        'omniweb'                    ,
        'hotjava'                ,
        'browsex'                    ,
        'amigavoyager'        ,
        'amiga-aweb'               ,
        'ibrowse'                
        ); 
        srand();
		$anon=array();
		for($i=10000;$i<20000;$i++){
			$r=rand(0,26);
			$ipa=array(rand(1,500),rand(1,500),rand(1,500),rand(1,500));
			$_SERVER['REMOTE_ADDR']=implode('.',$ipa);
			#echo '<br>'.$_SERVER['REMOTE_ADDR'].' '.$_SERVER['HTTP_USER_AGENT']=$testbrowsers[$r];            
			
			$aip=$this->anonymousID();
			if(in_array($aip,$anon))die('DUPLICATE: '.$aip.' '.$i);
			$anon[]=$aip;
		}
		echo '<br>Done '.$i;
		#var_dump($anon);
			*/
		}
		
		function get_ignorelists(){
                $ignore=array();
                $ignore['IP']=get_option('cystats_ignorelist_ip');
                $ignore['UA']=get_option('cystats_ignorelist_ua');
                $ignore['ID']=get_option('cystats_ignorelist_pgs');
                #var_dump($ignore);
                foreach($ignore AS $ignore_key=>$ignore_value){
                    if(is_serialized($ignore_value)){
                        $ignore[$ignore_key]=array_map('stripslashes',unserialize($ignore_value));
                    }elseif(is_array($ignore_value)){
                        $ignore[$ignore_key]=array_map('stripslashes',$ignore_value);
                    } else $ignore[$ignore_key]=FALSE;   
                }
                #var_dump($ignore);
                return($ignore);
        }
        /**
          * Initializes time variables needed for time calculations
          */
        function init_timefunc(){
            $this->yesterday=strtotime('-1 day 00:00 GMT');
            $this->lastmonday=strtotime('last monday 00:00 GMT');
            $this->today=strtotime('today 0:00 GMT');
            $this->lastlastmonday=$this->lastmonday-$this->sec_week;
            $this->yesterday=$this->today-$this->sec_day;
            $this->sec_hour=$this->sec_min*60;
            $this->sec_day=$this->sec_hour*24;
            $this->sec_week=$this->sec_day*7;
        }

        /**
          * Returns stored MD5 cid
          */
        function get_cid(){
            return($this->cid);
            }

		/**
		 * Build anonymous id for requesting client or robot
		 * @param string $ip optional IPv4 
		 * @return string $ip
		 */
		function anonymousID($ip=FALSE){
				$id='';
				// get some http data
				$id.=isset($_SERVER['HTTP_CONNECTION'])?$_SERVER['HTTP_CONNECTION']:'';
				$id.=isset($_SERVER['HTTP_COOKIE'])?$_SERVER['HTTP_COOKIE']:'';
				$id.=isset($_SERVER['HTTP_ACCEPT'])?$_SERVER['HTTP_ACCEPT']:'';
				$id.=isset($_SERVER['HTTP_CONNECTION'])?$_SERVER['HTTP_CONNECTION']:'';
				$id.=isset($_SERVER['HTTP_ACCEPT_CHARSET'])?$_SERVER['HTTP_ACCEPT_CHARSET']:'';
				$id.=isset($_SERVER['HTTP_ACCEPT_ENCODING'])?$_SERVER['HTTP_ACCEPT_ENCODING']:'';
				$id.=isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])?$_SERVER['HTTP_ACCEPT_LANGUAGE']:'';
				$id.=isset($_SERVER['HTTP_CONNECTION'])?$_SERVER['HTTP_CONNECTION']:'';
				$id.=isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']			:'';
				$id.=isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
				$id.=isset($_SERVER['REMOTE_HOST'])?$_SERVER['REMOTE_HOST']:'';
				
				// get parameter if set
				if($ip!==FALSE)$_SERVER['REMOTE_ADDR']=$ip;
				
				// get and divide ip
				if(isset($_SERVER['REMOTE_ADDR'])){
					$ip=explode('.',$_SERVER['REMOTE_ADDR']);
				}else{
					$ip=array(127,0,0,1);
				}
				
				// append ip integer to id string
				$id.=$_SERVER['REMOTE_ADDR'];
				
				// hash to 32 chars
				$id=md5($id);
				
				// split hashed id to 8 char chunks
				$id_int_parts=c_str_split($id,8);
				
				// sum up all 8 chars of a chunk
				for($i=0;$i<4;$i++){
			    	for($j=0;$j<8;$j++){
						$id_part[$j]=ord($id_int_parts[$i][$j]);
					$id_int[$i]=$id_int[$i]+ord($id_int_parts[$i][$j]);
					}		
				}

				// implode to dotted ip				
				return(implode('.',$id_int));
		}

        /**
          * Performs REGEXP check for remote adress.
          * Wildcard '*' for parts of remote adress to check are allowed, e.g.
          * '127.0.0.*'.
          *
          * @param $needle string remote adress to check
          * @param $haystack array array of reference remote adresses
          */
        function IPCheck($needle,$haystack){
            $search=array(
                '=\*=',
                '=\.=');
            $repl=array(
                '[0-9]{1,3}',
                '\.');
            $needle=preg_replace($search,$repl,$needle);

            #echo "    =".$needle."="."  => ".$haystack;
            return(preg_match('='.$needle.'=',$haystack));
        }

        /**
          * Second pass verification for remote adresses or user agents,
          * used e.g. for google checking first for user agent googlebot and
          * then in the second pass checking for valid google remote adress.
          *
          * @param $type string CYSTATS_IP or CYSTATS_UA
          * @param $needle string ip/ua to search for
          * @param $haystack string array of ip/ua source
          *
          * @return bool
          */
        function secondPass($type,$needle,$haystack){
            if($type==CYSTATS_IP){
                if(is_array($needle)){
                    foreach($needle AS $v){
                        if($this->IPCheck($v,$haystack)==TRUE){
                            return TRUE;
                        }
                    }
                }else
                {
                    if($this->IPCheck($needle,$haystack)==TRUE){
                        return TRUE;
                    }
                }
            }
            elseif($type==CYSTATS_UA){
                if(is_array($needle)){
                    foreach($needle AS $v){
                        if(preg_match($v,$haystack)==TRUE){
                            return TRUE;
                        }
                    }
                }else
                {
                    if(preg_match($needle,$haystack)==TRUE){
                        return TRUE;
                    }
                }

            }
            return FALSE;
        }
        
        /**
         * Generate IPv4 string from BIGINT
         * @param float $bigint
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
    	 * Check for number of visits within last 60 sec.
    	 * 
    	 */
    	function checkBot($ip){

            global $wpdb;

			if(get_option('cystats_noip')==1){
			    $ip=$this->anonymousID($ip);
			}
           	$ipa=explode('.',$ip);
           	$nip    = $ipa[0]*0x1000000+$ipa[1]*0x10000+$ipa[2]*0x100+$ipa[3];

                       $q='SELECT COUNT(remote_addr)
                FROM '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW.'                 
                WHERE remote_addr=\''.$nip.'\'
                AND stamp >'.(time()-60);
                echo $q;
            $r=$wpdb->get_var($q);
            var_dump($nip);var_dump($r);
        }

        /**
          * Extract visitor information from array $data, parses
          * user agent, os, removes session ids from referer string,
          * creates MD5 cid for later use in javascript data tracking.
          *
          * @param string $ref $_SERVER['USER_AGENT'] string or similar
          * @param optional int $vlength nr of version chars to check, default 7
          * @return array $info
          */
        function getUserData($vlength=10){

            $daten = $this->cystats_data;
            $ref = !empty($daten[4])?$daten[4]:FALSE;
            $referer = !empty($daten[3])?$daten[3]:FALSE;

            $info['type']           = CYSTATS_UNKNOWN;
            $info['name']           = 0;
            $info['version']        = FALSE;
            $info['link']           = FALSE;
            $info['agent']          = $ref;
            $info['os']             = FALSE;
            $info['block']          = FALSE;
            $info['searchwords']    = FALSE;
            $info['cid']            = FALSE;

            #$this->checkBot($daten[0]);
            // convert user_agent to uppercase for comparison
            $ref=strtoupper(str_replace(' ','',$ref));

            $info['agent'] = $this->realUA= $ref;
            foreach ($this->browser as $rowname=>$row) {

                $s = strpos($ref, $row[CYSTATS_AGENT]);
                if ($s!==FALSE) {
                    if($row[CYSTATS_SECONDPASS]){
                        $secondpassstring=($row[CYSTATS_SECONDPASS_TYPE]==CYSTATS_IP)?$daten[0]:$info['agent'];
                        if($this->secondPass($row[CYSTATS_SECONDPASS_TYPE],$row[CYSTATS_SECONDPASS],$secondpassstring)){
                            $f = $s + strlen($row[CYSTATS_AGENT]);
                            $version = substr($ref, $f, $vlength);
                            $version = preg_replace('/[^0-9\,\.]/','',$version);
                            $info['type']   = $row[CYSTATS_TYPE];
                            $info['name']   = $rowname;#$row[CYSTATS_NAME];
                            $info['version']= $version;
                            $info['agent']   = FALSE;
                        }
                    }else{
                        $f = $s + strlen($row[CYSTATS_AGENT]);
                        $version = substr($ref, $f, $vlength);
                        $version = preg_replace('/[^0-9\,\.]/','',$version);
                        $info['type']   = $row[CYSTATS_TYPE];
                        $info['name']   = $rowname;#$row[CYSTATS_NAME];
                        $info['version']= $version;
                        $info['agent']   = FALSE;
                    }
                }
            }
            if($info['type']!=CYSTATS_SEARCH && $info['type']!=CYSTATS_UNKNOWN){
            foreach ($this->os as $oskey=>$row) {
                $s = strpos($ref, $row[1]);
                if ($s!==FALSE) {
                    #$f = $s + strlen($row[1]);
                    $info['os']   = $oskey;#$row[0];
                }
            }}
            
			// generate bigint IP
			if(get_option('cystats_noip')==1){
			    $anonymousIP=$this->anonymousID($daten[0]);
				$daten[0]=$anonymousIP;
			}

           	$info['remote_addr']    = ip2long($daten[0]);
			
            $info['remote_host']    = !empty($daten[2])?$daten[2]:FALSE;
            $info['timestamp']      = !empty($daten[1])?$daten[1]:current_time('timestamp');

            if($info['timestamp']!==FALSE){
                $info['year']           = gmdate('Y',$daten[1]);
                $info['month']          = gmdate('m',$daten[1]);
                $info['day']            = gmdate('d',$daten[1]);
            }
            else{
                $info['year']           = FALSE;
                $info['month']          = FALSE;
                $info['day']            = FALSE;
            }
           
 
           if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
                 preg_match( '=([^;,]*)=', $_SERVER['HTTP_ACCEPT_LANGUAGE'],$lang);
                $info['accept_language']=$lang[0];         
            }else{
                $info['accept_language']='';            
            }

            $info['page']           = !empty($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'/';
            $info['method']         = ($_SERVER['REQUEST_METHOD']=='GET')?1:0;

            // remove session ids
            $info['page']=preg_replace('=[&|\?][\w\_]+\=[\w]{32}=','',$info['page']);
                        
            // dont write referer string if deactivated local referer tracking
			// get HTTP_HOST if set
            if(isset($_SERVER['HTTP_HOST'])){
            	$serverhost = $_SERVER['HTTP_HOST'];
            }else{
				$serverhost = get_option('siteurl');
			}
            // strip 'www.' from HTTP_HOST
            if(substr($serverhost,0,4)=='www.'){
            	$serverhost=substr($serverhost,4);
            }
            
            $referer_host_array=parse_url($referer);
            $referer_host=$referer_host_array['host'];
            $pos=strpos($referer_host,$serverhost);

            if($pos!==false){
                $info['referer_type']=2;            
                #if(get_option('cystats_localreferer_tracking')!=1){
                    $referer='';                
                #}
            }else{
                $info['referer_type']=0;                        
            }
            $r=preg_replace('=[&|\?][\w\_]+\=[\w]{32}=','',$referer);
            $info['referer']=$r;                    
            
            $this->info=$info;
            $this->cystats_info=$info;
        }



        /**
          * block acces for used user_agent,
          * not used in this version
          */
        function block(){
           if($this->info['block']){
                die();
            }
        }

        /**
          * Extract wordpress searchwords from $_GET global
          *
          * @return string searchwords
          */
        function extractBlogSearchwords(){
            if (is_search() && !is_paged() && !is_admin() ) {
                $query = urldecode(stripslashes($_GET['s']));
                $query=preg_replace('=[\"\'\\n\\r]+=','',$query);
                // array of searchwords - from wp-query.php
                preg_match_all('/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $query, $matches);
                $searchwords=$matches[0];
                // lowercase all words
                $searchwords = array_map('strtolower', $searchwords);
                // return the resulting string
                return(implode(' ',$searchwords));
            }else{
                return FALSE;
            }
        }
        
        /**
          * Extract searchwords in referer string
          *
          * Parses http_referer, extracts host and query string,
          * determines word delimiter from array of searchengine hosts and
          * finally extracts the searchwords, puts them into an array and appends a string
          * containing all the searchwords to the searchword array
          *
          * @return array $searchwords , array of searchwords, $searchwords[count()-1]=string of all searchwords
          */
       function extractSearchwords(){

            $searchhosts=array(
				'google.com'=>'q',
				'google.ae'=>'q',
				'google.com.af'=>'q',
				'google.com.ag'=>'q',
				'google.com.ai'=>'q',
				'google.am'=>'q',
				'google.com.ar'=>'q',
				'google.as'=>'q',
				'google.at'=>'q',
				'google.com.au'=>'q',
				'google.az'=>'q',
				'google.ba'=>'q',
				'google.com.bd'=>'q',
				'google.be'=>'q',
				'google.bg'=>'q',
				'google.com.bh'=>'q',
				'google.bi'=>'q',
				'google.com.bn'=>'q',
				'google.com.bo'=>'q',
				'google.com.br'=>'q',
				'google.bs'=>'q',
				'google.co.bw'=>'q',
				'google.com.by'=>'q',
				'google.com.bz'=>'q',
				'google.ca'=>'q',
				'google.cd'=>'q',
				'google.cg'=>'q',
				'google.ch'=>'q',
				'google.ci'=>'q',
				'google.co.ck'=>'q',
				'google.cl'=>'q',
				'google.cn'=>'q',
				'google.com.co'=>'q',
				'google.co.cr'=>'q',
				'google.com.cu'=>'q',
				'google.cz'=>'q',
				'google.de'=>'q',
				'google.dj'=>'q',
				'google.dk'=>'q',
				'google.dm'=>'q',
				'google.com.do'=>'q',
				'google.com.ec'=>'q',
				'google.ee'=>'q',
				'google.com.eg'=>'q',
				'google.es'=>'q',
				'google.com.et'=>'q',
				'google.fi'=>'q',
				'google.com.fj'=>'q',
				'google.fm'=>'q',
				'google.fr'=>'q',
				'google.ge'=>'q',
				'google.gg'=>'q',
				'google.com.gi'=>'q',
				'google.gl'=>'q',
				'google.gm'=>'q',
				'google.gp'=>'q',
				'google.gr'=>'q',
				'google.com.gt'=>'q',
				'google.gy'=>'q',
				'google.com.hk'=>'q',
				'google.hn'=>'q',
				'google.hr'=>'q',
				'google.ht'=>'q',
				'google.hu'=>'q',
				'google.co.id'=>'q',
				'google.ie'=>'q',
				'google.co.il'=>'q',
				'google.im'=>'q',
				'google.co.in'=>'q',
				'google.is'=>'q',
				'google.it'=>'q',
				'google.je'=>'q',
				'google.com.jm'=>'q',
				'google.jo'=>'q',
				'google.co.jp'=>'q',
				'google.co.ke'=>'q',
				'google.com.kh'=>'q',
				'google.ki'=>'q',
				'google.kg'=>'q',
				'google.co.kr'=>'q',
				'google.kz'=>'q',
				'google.la'=>'q',
				'google.li'=>'q',
				'google.lk'=>'q',
				'google.co.ls'=>'q',
				'google.lt'=>'q',
				'google.lu'=>'q',
				'google.lv'=>'q',
				'google.com.ly'=>'q',
				'google.co.ma'=>'q',
				'google.md'=>'q',
				'google.mn'=>'q',
				'google.ms'=>'q',
				'google.com.mt'=>'q',
				'google.mu'=>'q',
				'google.mv'=>'q',
				'google.mw'=>'q',
				'google.com.mx'=>'q',
				'google.com.my'=>'q',
				'google.com.na'=>'q',
				'google.com.nf'=>'q',
				'google.com.ng'=>'q',
				'google.com.ni'=>'q',
				'google.nl'=>'q',
				'google.no'=>'q',
				'google.com.np'=>'q',
				'google.nr'=>'q',
				'google.nu'=>'q',
				'google.co.nz'=>'q',
				'google.com.om'=>'q',
				'google.com.pa'=>'q',
				'google.com.pe'=>'q',
				'google.com.ph'=>'q',
				'google.com.pk'=>'q',
				'google.pl'=>'q',
				'google.pn'=>'q',
				'google.com.pr'=>'q',
				'google.pt'=>'q',
				'google.com.py'=>'q',
				'google.com.qa'=>'q',
				'google.ro'=>'q',
				'google.ru'=>'q',
				'google.rw'=>'q',
				'google.com.sa'=>'q',
				'google.com.sb'=>'q',
				'google.sc'=>'q',
				'google.se'=>'q',
				'google.com.sg'=>'q',
				'google.sh'=>'q',
				'google.si'=>'q',
				'google.sk'=>'q',
				'google.sn'=>'q',
				'google.sm'=>'q',
				'google.st'=>'q',
				'google.com.sv'=>'q',
				'google.co.th'=>'q',
				'google.com.tj'=>'q',
				'google.tk'=>'q',
				'google.tm'=>'q',
				'google.to'=>'q',
				'google.tp'=>'q',
				'google.com.tr'=>'q',
				'google.tt'=>'q',
				'google.com.tw'=>'q',
				'google.com.ua'=>'q',
				'google.co.ug'=>'q',
				'google.co.uk'=>'q',
				'google.com.uy'=>'q',
				'google.co.uz'=>'q',
				'google.com.vc'=>'q',
				'google.co.ve'=>'q',
				'google.vg'=>'q',
				'google.co.vi'=>'q',
				'google.com.vn'=>'q',
				'google.vu'=>'q',
				'google.ws'=>'q',
				'google.co.yu'=>'q',
				'google.co.za'=>'q',
				'google.co.zm'=>'q',
				'google.co.zw'=>'q',
				'blogsearch.google.com'=>'q',
				'blogsearch.google.ae'=>'q',
				'blogsearch.google.com.af'=>'q',
				'blogsearch.google.com.ag'=>'q',
				'blogsearch.google.com.ai'=>'q',
				'blogsearch.google.am'=>'q',
				'blogsearch.google.com.ar'=>'q',
				'blogsearch.google.as'=>'q',
				'blogsearch.google.at'=>'q',
				'blogsearch.google.com.au'=>'q',
				'blogsearch.google.az'=>'q',
				'blogsearch.google.ba'=>'q',
				'blogsearch.google.com.bd'=>'q',
				'blogsearch.google.be'=>'q',
				'blogsearch.google.bg'=>'q',
				'blogsearch.google.com.bh'=>'q',
				'blogsearch.google.bi'=>'q',
				'blogsearch.google.com.bn'=>'q',
				'blogsearch.google.com.bo'=>'q',
				'blogsearch.google.com.br'=>'q',
				'blogsearch.google.bs'=>'q',
				'blogsearch.google.co.bw'=>'q',
				'blogsearch.google.com.by'=>'q',
				'blogsearch.google.com.bz'=>'q',
				'blogsearch.google.ca'=>'q',
				'blogsearch.google.cd'=>'q',
				'blogsearch.google.cg'=>'q',
				'blogsearch.google.ch'=>'q',
				'blogsearch.google.ci'=>'q',
				'blogsearch.google.co.ck'=>'q',
				'blogsearch.google.cl'=>'q',
				'blogsearch.google.cn'=>'q',
				'blogsearch.google.com.co'=>'q',
				'blogsearch.google.co.cr'=>'q',
				'blogsearch.google.com.cu'=>'q',
				'blogsearch.google.cz'=>'q',
				'blogsearch.google.de'=>'q',
				'blogsearch.google.dj'=>'q',
				'blogsearch.google.dk'=>'q',
				'blogsearch.google.dm'=>'q',
				'blogsearch.google.com.do'=>'q',
				'blogsearch.google.com.ec'=>'q',
				'blogsearch.google.ee'=>'q',
				'blogsearch.google.com.eg'=>'q',
				'blogsearch.google.es'=>'q',
				'blogsearch.google.com.et'=>'q',
				'blogsearch.google.fi'=>'q',
				'blogsearch.google.com.fj'=>'q',
				'blogsearch.google.fm'=>'q',
				'blogsearch.google.fr'=>'q',
				'blogsearch.google.ge'=>'q',
				'blogsearch.google.gg'=>'q',
				'blogsearch.google.com.gi'=>'q',
				'blogsearch.google.gl'=>'q',
				'blogsearch.google.gm'=>'q',
				'blogsearch.google.gp'=>'q',
				'blogsearch.google.gr'=>'q',
				'blogsearch.google.com.gt'=>'q',
				'blogsearch.google.gy'=>'q',
				'blogsearch.google.com.hk'=>'q',
				'blogsearch.google.hn'=>'q',
				'blogsearch.google.hr'=>'q',
				'blogsearch.google.ht'=>'q',
				'blogsearch.google.hu'=>'q',
				'blogsearch.google.co.id'=>'q',
				'blogsearch.google.ie'=>'q',
				'blogsearch.google.co.il'=>'q',
				'blogsearch.google.im'=>'q',
				'blogsearch.google.co.in'=>'q',
				'blogsearch.google.is'=>'q',
				'blogsearch.google.it'=>'q',
				'blogsearch.google.je'=>'q',
				'blogsearch.google.com.jm'=>'q',
				'blogsearch.google.jo'=>'q',
				'blogsearch.google.co.jp'=>'q',
				'blogsearch.google.co.ke'=>'q',
				'blogsearch.google.com.kh'=>'q',
				'blogsearch.google.ki'=>'q',
				'blogsearch.google.kg'=>'q',
				'blogsearch.google.co.kr'=>'q',
				'blogsearch.google.kz'=>'q',
				'blogsearch.google.la'=>'q',
				'blogsearch.google.li'=>'q',
				'blogsearch.google.lk'=>'q',
				'blogsearch.google.co.ls'=>'q',
				'blogsearch.google.lt'=>'q',
				'blogsearch.google.lu'=>'q',
				'blogsearch.google.lv'=>'q',
				'blogsearch.google.com.ly'=>'q',
				'blogsearch.google.co.ma'=>'q',
				'blogsearch.google.md'=>'q',
				'blogsearch.google.mn'=>'q',
				'blogsearch.google.ms'=>'q',
				'blogsearch.google.com.mt'=>'q',
				'blogsearch.google.mu'=>'q',
				'blogsearch.google.mv'=>'q',
				'blogsearch.google.mw'=>'q',
				'blogsearch.google.com.mx'=>'q',
				'blogsearch.google.com.my'=>'q',
				'blogsearch.google.com.na'=>'q',
				'blogsearch.google.com.nf'=>'q',
				'blogsearch.google.com.ng'=>'q',
				'blogsearch.google.com.ni'=>'q',
				'blogsearch.google.nl'=>'q',
				'blogsearch.google.no'=>'q',
				'blogsearch.google.com.np'=>'q',
				'blogsearch.google.nr'=>'q',
				'blogsearch.google.nu'=>'q',
				'blogsearch.google.co.nz'=>'q',
				'blogsearch.google.com.om'=>'q',
				'blogsearch.google.com.pa'=>'q',
				'blogsearch.google.com.pe'=>'q',
				'blogsearch.google.com.ph'=>'q',
				'blogsearch.google.com.pk'=>'q',
				'blogsearch.google.pl'=>'q',
				'blogsearch.google.pn'=>'q',
				'blogsearch.google.com.pr'=>'q',
				'blogsearch.google.pt'=>'q',
				'blogsearch.google.com.py'=>'q',
				'blogsearch.google.com.qa'=>'q',
				'blogsearch.google.ro'=>'q',
				'blogsearch.google.ru'=>'q',
				'blogsearch.google.rw'=>'q',
				'blogsearch.google.com.sa'=>'q',
				'blogsearch.google.com.sb'=>'q',
				'blogsearch.google.sc'=>'q',
				'blogsearch.google.se'=>'q',
				'blogsearch.google.com.sg'=>'q',
				'blogsearch.google.sh'=>'q',
				'blogsearch.google.si'=>'q',
				'blogsearch.google.sk'=>'q',
				'blogsearch.google.sn'=>'q',
				'blogsearch.google.sm'=>'q',
				'blogsearch.google.st'=>'q',
				'blogsearch.google.com.sv'=>'q',
				'blogsearch.google.co.th'=>'q',
				'blogsearch.google.com.tj'=>'q',
				'blogsearch.google.tk'=>'q',
				'blogsearch.google.tm'=>'q',
				'blogsearch.google.to'=>'q',
				'blogsearch.google.tp'=>'q',
				'blogsearch.google.com.tr'=>'q',
				'blogsearch.google.tt'=>'q',
				'blogsearch.google.com.tw'=>'q',
				'blogsearch.google.com.ua'=>'q',
				'blogsearch.google.co.ug'=>'q',
				'blogsearch.google.co.uk'=>'q',
				'blogsearch.google.com.uy'=>'q',
				'blogsearch.google.co.uz'=>'q',
				'blogsearch.google.com.vc'=>'q',
				'blogsearch.google.co.ve'=>'q',
				'blogsearch.google.vg'=>'q',
				'blogsearch.google.co.vi'=>'q',
				'blogsearch.google.com.vn'=>'q',
				'blogsearch.google.vu'=>'q',
				'blogsearch.google.ws'=>'q',
				'blogsearch.google.co.yu'=>'q',
				'blogsearch.google.co.za'=>'q',
				'blogsearch.google.co.zm'=>'q',
				'blogsearch.google.co.zw'=>'q',
				'images.google.com'=>'q',
				'images.google.ae'=>'q',
				'images.google.com.af'=>'q',
				'images.google.com.ag'=>'q',
				'images.google.com.ai'=>'q',
				'images.google.am'=>'q',
				'images.google.com.ar'=>'q',
				'images.google.as'=>'q',
				'images.google.at'=>'q',
				'images.google.com.au'=>'q',
				'images.google.az'=>'q',
				'images.google.ba'=>'q',
				'images.google.com.bd'=>'q',
				'images.google.be'=>'q',
				'images.google.bg'=>'q',
				'images.google.com.bh'=>'q',
				'images.google.bi'=>'q',
				'images.google.com.bn'=>'q',
				'images.google.com.bo'=>'q',
				'images.google.com.br'=>'q',
				'images.google.bs'=>'q',
				'images.google.co.bw'=>'q',
				'images.google.com.by'=>'q',
				'images.google.com.bz'=>'q',
				'images.google.ca'=>'q',
				'images.google.cd'=>'q',
				'images.google.cg'=>'q',
				'images.google.ch'=>'q',
				'images.google.ci'=>'q',
				'images.google.co.ck'=>'q',
				'images.google.cl'=>'q',
				'images.google.cn'=>'q',
				'images.google.com.co'=>'q',
				'images.google.co.cr'=>'q',
				'images.google.com.cu'=>'q',
				'images.google.cz'=>'q',
				'images.google.de'=>'q',
				'images.google.dj'=>'q',
				'images.google.dk'=>'q',
				'images.google.dm'=>'q',
				'images.google.com.do'=>'q',
				'images.google.com.ec'=>'q',
				'images.google.ee'=>'q',
				'images.google.com.eg'=>'q',
				'images.google.es'=>'q',
				'images.google.com.et'=>'q',
				'images.google.fi'=>'q',
				'images.google.com.fj'=>'q',
				'images.google.fm'=>'q',
				'images.google.fr'=>'q',
				'images.google.ge'=>'q',
				'images.google.gg'=>'q',
				'images.google.com.gi'=>'q',
				'images.google.gl'=>'q',
				'images.google.gm'=>'q',
				'images.google.gp'=>'q',
				'images.google.gr'=>'q',
				'images.google.com.gt'=>'q',
				'images.google.gy'=>'q',
				'images.google.com.hk'=>'q',
				'images.google.hn'=>'q',
				'images.google.hr'=>'q',
				'images.google.ht'=>'q',
				'images.google.hu'=>'q',
				'images.google.co.id'=>'q',
				'images.google.ie'=>'q',
				'images.google.co.il'=>'q',
				'images.google.im'=>'q',
				'images.google.co.in'=>'q',
				'images.google.is'=>'q',
				'images.google.it'=>'q',
				'images.google.je'=>'q',
				'images.google.com.jm'=>'q',
				'images.google.jo'=>'q',
				'images.google.co.jp'=>'q',
				'images.google.co.ke'=>'q',
				'images.google.com.kh'=>'q',
				'images.google.ki'=>'q',
				'images.google.kg'=>'q',
				'images.google.co.kr'=>'q',
				'images.google.kz'=>'q',
				'images.google.la'=>'q',
				'images.google.li'=>'q',
				'images.google.lk'=>'q',
				'images.google.co.ls'=>'q',
				'images.google.lt'=>'q',
				'images.google.lu'=>'q',
				'images.google.lv'=>'q',
				'images.google.com.ly'=>'q',
				'images.google.co.ma'=>'q',
				'images.google.md'=>'q',
				'images.google.mn'=>'q',
				'images.google.ms'=>'q',
				'images.google.com.mt'=>'q',
				'images.google.mu'=>'q',
				'images.google.mv'=>'q',
				'images.google.mw'=>'q',
				'images.google.com.mx'=>'q',
				'images.google.com.my'=>'q',
				'images.google.com.na'=>'q',
				'images.google.com.nf'=>'q',
				'images.google.com.ng'=>'q',
				'images.google.com.ni'=>'q',
				'images.google.nl'=>'q',
				'images.google.no'=>'q',
				'images.google.com.np'=>'q',
				'images.google.nr'=>'q',
				'images.google.nu'=>'q',
				'images.google.co.nz'=>'q',
				'images.google.com.om'=>'q',
				'images.google.com.pa'=>'q',
				'images.google.com.pe'=>'q',
				'images.google.com.ph'=>'q',
				'images.google.com.pk'=>'q',
				'images.google.pl'=>'q',
				'images.google.pn'=>'q',
				'images.google.com.pr'=>'q',
				'images.google.pt'=>'q',
				'images.google.com.py'=>'q',
				'images.google.com.qa'=>'q',
				'images.google.ro'=>'q',
				'images.google.ru'=>'q',
				'images.google.rw'=>'q',
				'images.google.com.sa'=>'q',
				'images.google.com.sb'=>'q',
				'images.google.sc'=>'q',
				'images.google.se'=>'q',
				'images.google.com.sg'=>'q',
				'images.google.sh'=>'q',
				'images.google.si'=>'q',
				'images.google.sk'=>'q',
				'images.google.sn'=>'q',
				'images.google.sm'=>'q',
				'images.google.st'=>'q',
				'images.google.com.sv'=>'q',
				'images.google.co.th'=>'q',
				'images.google.com.tj'=>'q',
				'images.google.tk'=>'q',
				'images.google.tm'=>'q',
				'images.google.to'=>'q',
				'images.google.tp'=>'q',
				'images.google.com.tr'=>'q',
				'images.google.tt'=>'q',
				'images.google.com.tw'=>'q',
				'images.google.com.ua'=>'q',
				'images.google.co.ug'=>'q',
				'images.google.co.uk'=>'q',
				'images.google.com.uy'=>'q',
				'images.google.co.uz'=>'q',
				'images.google.com.vc'=>'q',
				'images.google.co.ve'=>'q',
				'images.google.vg'=>'q',
				'images.google.co.vi'=>'q',
				'images.google.com.vn'=>'q',
				'images.google.vu'=>'q',
				'images.google.ws'=>'q',
				'images.google.co.yu'=>'q',
				'images.google.co.za'=>'q',
				'images.google.co.zm'=>'q',
				'images.google.co.zw'=>'q',
				'news.google.com'=>'q',
				'news.google.ae'=>'q',
				'news.google.com.af'=>'q',
				'news.google.com.ag'=>'q',
				'news.google.com.ai'=>'q',
				'news.google.am'=>'q',
				'news.google.com.ar'=>'q',
				'news.google.as'=>'q',
				'news.google.at'=>'q',
				'news.google.com.au'=>'q',
				'news.google.az'=>'q',
				'news.google.ba'=>'q',
				'news.google.com.bd'=>'q',
				'news.google.be'=>'q',
				'news.google.bg'=>'q',
				'news.google.com.bh'=>'q',
				'news.google.bi'=>'q',
				'news.google.com.bn'=>'q',
				'news.google.com.bo'=>'q',
				'news.google.com.br'=>'q',
				'news.google.bs'=>'q',
				'news.google.co.bw'=>'q',
				'news.google.com.by'=>'q',
				'news.google.com.bz'=>'q',
				'news.google.ca'=>'q',
				'news.google.cd'=>'q',
				'news.google.cg'=>'q',
				'news.google.ch'=>'q',
				'news.google.ci'=>'q',
				'news.google.co.ck'=>'q',
				'news.google.cl'=>'q',
				'news.google.cn'=>'q',
				'news.google.com.co'=>'q',
				'news.google.co.cr'=>'q',
				'news.google.com.cu'=>'q',
				'news.google.cz'=>'q',
				'news.google.de'=>'q',
				'news.google.dj'=>'q',
				'news.google.dk'=>'q',
				'news.google.dm'=>'q',
				'news.google.com.do'=>'q',
				'news.google.com.ec'=>'q',
				'news.google.ee'=>'q',
				'news.google.com.eg'=>'q',
				'news.google.es'=>'q',
				'news.google.com.et'=>'q',
				'news.google.fi'=>'q',
				'news.google.com.fj'=>'q',
				'news.google.fm'=>'q',
				'news.google.fr'=>'q',
				'news.google.ge'=>'q',
				'news.google.gg'=>'q',
				'news.google.com.gi'=>'q',
				'news.google.gl'=>'q',
				'news.google.gm'=>'q',
				'news.google.gp'=>'q',
				'news.google.gr'=>'q',
				'news.google.com.gt'=>'q',
				'news.google.gy'=>'q',
				'news.google.com.hk'=>'q',
				'news.google.hn'=>'q',
				'news.google.hr'=>'q',
				'news.google.ht'=>'q',
				'news.google.hu'=>'q',
				'news.google.co.id'=>'q',
				'news.google.ie'=>'q',
				'news.google.co.il'=>'q',
				'news.google.im'=>'q',
				'news.google.co.in'=>'q',
				'news.google.is'=>'q',
				'news.google.it'=>'q',
				'news.google.je'=>'q',
				'news.google.com.jm'=>'q',
				'news.google.jo'=>'q',
				'news.google.co.jp'=>'q',
				'news.google.co.ke'=>'q',
				'news.google.com.kh'=>'q',
				'news.google.ki'=>'q',
				'news.google.kg'=>'q',
				'news.google.co.kr'=>'q',
				'news.google.kz'=>'q',
				'news.google.la'=>'q',
				'news.google.li'=>'q',
				'news.google.lk'=>'q',
				'news.google.co.ls'=>'q',
				'news.google.lt'=>'q',
				'news.google.lu'=>'q',
				'news.google.lv'=>'q',
				'news.google.com.ly'=>'q',
				'news.google.co.ma'=>'q',
				'news.google.md'=>'q',
				'news.google.mn'=>'q',
				'news.google.ms'=>'q',
				'news.google.com.mt'=>'q',
				'news.google.mu'=>'q',
				'news.google.mv'=>'q',
				'news.google.mw'=>'q',
				'news.google.com.mx'=>'q',
				'news.google.com.my'=>'q',
				'news.google.com.na'=>'q',
				'news.google.com.nf'=>'q',
				'news.google.com.ng'=>'q',
				'news.google.com.ni'=>'q',
				'news.google.nl'=>'q',
				'news.google.no'=>'q',
				'news.google.com.np'=>'q',
				'news.google.nr'=>'q',
				'news.google.nu'=>'q',
				'news.google.co.nz'=>'q',
				'news.google.com.om'=>'q',
				'news.google.com.pa'=>'q',
				'news.google.com.pe'=>'q',
				'news.google.com.ph'=>'q',
				'news.google.com.pk'=>'q',
				'news.google.pl'=>'q',
				'news.google.pn'=>'q',
				'news.google.com.pr'=>'q',
				'news.google.pt'=>'q',
				'news.google.com.py'=>'q',
				'news.google.com.qa'=>'q',
				'news.google.ro'=>'q',
				'news.google.ru'=>'q',
				'news.google.rw'=>'q',
				'news.google.com.sa'=>'q',
				'news.google.com.sb'=>'q',
				'news.google.sc'=>'q',
				'news.google.se'=>'q',
				'news.google.com.sg'=>'q',
				'news.google.sh'=>'q',
				'news.google.si'=>'q',
				'news.google.sk'=>'q',
				'news.google.sn'=>'q',
				'news.google.sm'=>'q',
				'news.google.st'=>'q',
				'news.google.com.sv'=>'q',
				'news.google.co.th'=>'q',
				'news.google.com.tj'=>'q',
				'news.google.tk'=>'q',
				'news.google.tm'=>'q',
				'news.google.to'=>'q',
				'news.google.tp'=>'q',
				'news.google.com.tr'=>'q',
				'news.google.tt'=>'q',
				'news.google.com.tw'=>'q',
				'news.google.com.ua'=>'q',
				'news.google.co.ug'=>'q',
				'news.google.co.uk'=>'q',
				'news.google.com.uy'=>'q',
				'news.google.co.uz'=>'q',
				'news.google.com.vc'=>'q',
				'news.google.co.ve'=>'q',
				'news.google.vg'=>'q',
				'news.google.co.vi'=>'q',
				'news.google.com.vn'=>'q',
				'news.google.vu'=>'q',
				'news.google.ws'=>'q',
				'news.google.co.yu'=>'q',
				'news.google.co.za'=>'q',
				'news.google.co.zm'=>'q',
				'news.google.co.zw'=>'q',            
                'metager1.de'=>'q',
                'metager2.de'=>'q',
                'suche.t-online.de'=>'q',
                'search.abacho.com'=>'q',
                'search.ch'=>'q',
                'search.com'=>'q',
                'search.blogger.com'=>'q',
                'web.de'=>'q',
                'infoseeker.de'=>'q',
                'hotbot.de'=>'q',
                'suche.freenet.de'=>'q',
                'fireball.de'=>'q',
                'aolsvc.de'=>'q',
                'de.altavista.com'=>'q',
                'de.ask.com'=>'q',
                'ask.com'=>'q',
                'search.yahoo.com'=>'p',
                'de.search.yahoo.com'=>'p',
                'at.search.yahoo.com'=>'p',
                'ch.search.yahoo.com'=>'p',
                'images.search.yahoo.com'=>'p',
                'de.images.search.yahoo.com'=>'p',
                'at.images.search.yahoo.com'=>'p',
                'ch.images.search.yahoo.com'=>'p',
                'movies.search.yahoo.com'=>'p',
                'de.movies.search.yahoo.com'=>'p',
                'at.movies.search.yahoo.com'=>'p',
                'ch.movies.search.yahoo.com'=>'p',
                'search.msn.com'=>'q',
                'altavista.com'=>'q',
                'web.ask.com'=>'q',
                'del.icio.us'=>'p',
                'search.dmoz.org'=>'search',
                'feedster.com'=>'p',
                'ixquick.com'=>'query',
                'gigablast.com'=>'q',
                'kartoo.com'=>'q',
                'suche.lycos.de'=>'query',
                'suche.lycos.at'=>'query',
                'suche.lycos.ch'=>'query',
                'search.lycos.com'=>'query',
                'mamma.com'=>'query',
                's.teoma.com'=>'q',
                'webshots.com'=>'query',
                'wich.de'=>'search',
                'dino-online.de'=>'query',
                'allesklar.de'=>'words',
                'excite.de'=>'search',
                'search.abacho.com'=>'q',
                'suche.fireball.de'=>'query',
                'suche.t-online.de'=>'q',
                'search.live.com'=>'q',
                'suche-de.aolsvc.de'=>'q'                
            );

            // split referer information by php
            $ref_info = parse_url($_SERVER['HTTP_REFERER']);
            // cut leading 'www.' if found
            $referer = (substr($ref_info['host'],0,4) == 'www.') ? substr($ref_info['host'],4) : $ref_info['host'];
            // get searchword delimiter
            $delimiter=isset($searchhosts[$referer]) ? $searchhosts[$referer] : FALSE;

            if($delimiter!=FALSE){
                // split querystring by search engine specific delimiter
                $query_parts = explode( $delimiter.'=', $_SERVER['HTTP_REFERER']);
                $query_parts = explode( '&', $query_parts[1] );
                $query_parts = urldecode( $query_parts[0] );
                // replace whitespaces
                $query_parts = preg_replace(array('=\'=','=\"='),'',$query_parts);
                // array of single searchwords -> split by possible sub delimiter
                $searchwords = preg_split('=[\+\s\.,]+=', $query_parts);
                // lowercase all words
                $searchwords = array_map('strtolower', $searchwords);
                // return the resulting string
                return(implode(' ',$searchwords));
            }else{
                return FALSE;
            }
        }
        function debug($h,$t){
            #echo "<div style=\"width:100%;text-align:left;\"><h2>".$h.":</h2><pre>";var_dump($t);echo "</pre></div>";
        }
        
        function getDebug($header,$var){
            /*
            $ret= '<pre><h2>'.$header.'</h2>'; // This is for correct handling of newlines
            ob_start();
            var_dump($var);
            $a=ob_get_contents();
            ob_end_clean();
            $ret.= (htmlspecialchars($a,ENT_QUOTES)); // Escape every HTML special chars (especially > and < )
            $ret.='</pre>';
            return $ret;
        */
            return "";
         }
        /**
          * Generates array of visitor information extracting data from
          * global array $_SERVER
          *
          * @return array
          */
        function addEntry(){
            // get remote adress, try to resolve proxies
             if(isset($_SERVER['HTTP_CLIENT_IP'])){
                $entry[0]=$_SERVER['HTTP_CLIENT_IP'];
            }elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
                    $entry[0] = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }elseif(isset($_SERVER['REMOTE_ADDR'])){
                $entry[0]=$_SERVER['REMOTE_ADDR'];
            }else $entry[0]=FALSE;
            
            $this->realIP=$entry[0];
            // get time, host, referer, agent, language, querystring
            $entry[1]   = current_time('timestamp');
            
            #$entry[1]   = 1219410330 -300 +(19*60*60)+1;
            $this->debugstring.=$this->getDebug("Request time",array($entry[1],gmdate("d:m:Y H:i",$entry[1])));
            #$_SERVER['HTTP_USER_AGENT']="Googlebot/2.1 (+http://www.googlebot.com/bot.html)";
            
            $entry[2]   = isset($_SERVER['REMOTE_HOST'])?$_SERVER['REMOTE_HOST']:FALSE;
            $entry[3]   = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:FALSE;
            
            $entry[4]   = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:FALSE;
            $entry[9]   = isset($_SERVER['QUERY_STRING'])?"/".$_SERVER['QUERY_STRING']:FALSE;
            $this->debugstring.=$this->getDebug("User Agent",$entry[4]);       

        $this->cystats_data=$entry;
        $this->getUserData();
        }


        /**
          * Checks if given string has been already counted in
          * TABLESTATISTICS.
          *
          * @param $n string item to search for
          * @param $t integer type of item, e.g. CYSTATS_SEARCHWORD, as defined in defines.php
          *
          * @return bool
          */
        function isInSearchwordArray($n,$t){
            global $wpdb;        
                $q='SELECT COUNT(name)
                FROM '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS.'
                WHERE
                    type=\''.$t.'\'
                    AND name=\''.$wpdb->escape($n).'\'' ;
            $r=$wpdb->get_var($q);
            if($r!=0 ){
                return(TRUE);
            }else{
                return(FALSE);
            }
        }

        /**
          * Main database update function.
          *
          * Calculates visit time data, gets stored statistic data, extracts
          * wordpress searchwords, external searchwords and generates appropriate
          * database update or insertion strings for.$wpdb->prefix.CYSTATS_TABLE_STATISTICS.
          * Eventually stores visits raw data in.$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW.
          *
          * @param $info array visitors data array
          * @param $db mysql_resource handler
          */
        function update(){
            global $wpdb;
            $info=&$this->cystats_info;
 
            $year=gmdate("Y",$info['timestamp']);
            $month=gmdate("m",$info['timestamp']);
            $week=gmdate("W",$info['timestamp']);
            $day=strtotime(gmdate('d-M-Y',$info['timestamp'])."");
            $this->debugstring.=$this->getDebug("Calculated year",$year);
            $this->debugstring.=$this->getDebug("Calculated month",$month);
            $this->debugstring.=$this->getDebug("Calculated week",$week);
            $this->debugstring.=$this->getDebug("Calculated day",$day);
            $this->debugstring.=$this->getDebug("Calculated day gmdated",gmdate("d:m:Y H:i:s",$day));

            #var_dump(date("d",$day));
            #var_dump($month);
            #var_dump($year);
            #var_dump(gmdate("H:i:s",$info['timestamp']));
            #var_dump($week);

		// user visits greater than option user_level_tracking will not be saved and counted
		global $userdata;
		get_currentuserinfo();
		$cystats_userlevel=get_option('cystats_userlevel_tracking');
		 
		
		// user_agent blocking
		$block_ua=FALSE;
		if(!empty($this->ignore_ua) && ($this->ignore_ua!==FALSE)){			
			foreach($this->ignore_ua AS $iua){
				if(preg_match($iua,$this->realUA)){
				    $block_ua=TRUE;
				    break;
				}
			}
		}

		// IP blocking
		$block_ip=FALSE;
		if(!empty($this->ignore_ip) && ($this->ignore_ip !== FALSE)){			
			foreach($this->ignore_ip AS $iip){
				if($this->IPCheck($iip,$this->realIP)) $block_ip=TRUE	;
			}
		}
		
		// page/post id blocking
		$block_page=FALSE;
		if(!empty($this->ignore_pages) && ($this->ignore_pages !== FALSE)){			
			foreach($this->ignore_pages AS $ipgs){
				if($ipgs == $this->_pageid && ($this->_pagetype == 1 || $this->_pagetype == 2)) {
				    $block_page=TRUE;
				    break;
			    }
			}
		}
		
		$block_postbrowser=FALSE;
		if($info['method']==0 AND $info['type']==CYSTATS_BROWSER){
		    $block_postbrowser=TRUE;
		    $info['type']=CYSTATS_UNKNOWN;
		    $info['os']=FALSE;
        }else $block_postbrowser=FALSE;
		
		if( 
			( empty($userdata) || $userdata->user_level<=$cystats_userlevel ) 
			&& ( !isset($_COOKIE['CyStatsHide']) || $_COOKIE['CyStatsHide']==0)
			&& ($block_ip === FALSE)
			&& ($block_ua === FALSE)
			&& ($block_page === FALSE)
			&& ($block_postbrowser === FALSE)
		){
			
		#if(1==1){	
			// get data row
			// maybe this query can be optimized ???
            $q='SELECT name,type,val1,val2,val3
                    FROM '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS.'
                    WHERE
                        name=\'hits\' OR
                        name=\'visits\' OR
                        name=\'hits_nobots\' OR
                        name=\'visits_nobots\' OR
                        name=\'raw_count\' OR
                        (name=\'post_count\' AND type='.CYSTATS_POSTCOUNT.'   AND val1='.$this->_pageid.' AND val2=0) OR
                        (name=\'user_count\' AND type='.CYSTATS_USERCOUNT.'   AND val1='.$this->_userid.') OR

                        (type='.CYSTATS_HITSMONTH.'   AND val1='.$year.' AND val2='.$month.') OR
                        (type='.CYSTATS_VISITSMONTH.' AND val1='.$year.' AND val2='.$month.') OR

                        (type='.CYSTATS_HITSYEAR.'    AND val1='.$year.') OR
                        (type='.CYSTATS_VISITSYEAR.'  AND val1='.$year.') OR

                        (type='.CYSTATS_HITSWEEK.'    AND val1='.$year.' AND val2='.$week.') OR
                        (type='.CYSTATS_VISITSWEEK.'  AND val1='.$year.' AND val2='.$week.') OR

                        (type='.CYSTATS_HITSDAY.'     AND val1='.$year.' AND val2='.$day.') OR
                        (type='.CYSTATS_VISITSDAY.'   AND val1='.$year.' AND val2='.$day.') OR

                        (type='.CYSTATS_HITSMONTH_NOBOTS.'   AND val1='.$year.' AND val2='.$month.') OR
                        (type='.CYSTATS_VISITSMONTH_NOBOTS.' AND val1='.$year.' AND val2='.$month.') OR

                        (type='.CYSTATS_HITSYEAR_NOBOTS.'    AND val1='.$year.') OR
                        (type='.CYSTATS_VISITSYEAR_NOBOTS.'  AND val1='.$year.') OR

                        (type='.CYSTATS_HITSWEEK_NOBOTS.'    AND val1='.$year.' AND val2='.$week.') OR
                        (type='.CYSTATS_VISITSWEEK_NOBOTS.'  AND val1='.$year.' AND val2='.$week.') OR

                        (type='.CYSTATS_HITSDAY_NOBOTS.'     AND val1='.$year.' AND val2='.$day.') OR
                        (type='.CYSTATS_VISITSDAY_NOBOTS.'   AND val1='.$year.' AND val2='.$day.')
                    ';

                $r = $wpdb->get_results($q,ARRAY_A);
                $this->debugstring.=$this->getDebug("Querystring static stats:",$q);
                $this->debugstring.=$this->getDebug("Query static stats error:",mysql_error());
                $this->debugstring.=$this->getDebug("Sizeof(r)=:",sizeof($r));
                $this->debugstring.=$this->getDebug("empty(r)=:",empty($r));
                $this->debugstring.=$this->getDebug("Querystring static stats result:",$r);
                if (sizeof($r)){     
                    foreach($r AS $row){
                        $stats[$row['name']]=array(stripslashes($row['val1']),$row['val2'],$row['val3'],$row['type']);
                    }
                }
				$this->debugstring.=$this->getDebug("Initialized static table",$stats);
				$updates=array();
				$inserts=array();
				

				/*
				 * Extraxt searchwords from referer and build
				 * proper update/insert query strings
				 */
				$searchstring_type=0; 
				$searchstring='';
				$searchstring=$this->extractSearchwords();
				if($searchstring){
					$searchstring_type=1;
					$info['referer_type']=1; // referer type searchengines
				}else{
					$searchstring=$this->extractBlogSearchwords();
					if($searchstring){
						$searchstring_type=2;  
						$info['referer_type']=2; // referer type local                 
					}
				}


					if($info['type']==CYSTATS_BROWSER){
						#echo "<br>Browser detected";
						if($this->_pagetype == 1){
							if(isset($stats['post_count'])){
								#echo "Updating pagecount";
								$updates[]=array('post_count',CYSTATS_POSTCOUNT,$this->_pageid,0);
							}
							else{
								#echo "new pagecount";
								$inserts[]=array('post_count',CYSTATS_POSTCOUNT,$this->_pageid,0);
							}
						}
					}					


			// update user hit counter
			if($this->_userid>=0){
				if(isset($stats['user_count'])){
					$updates[]=array('user_count',CYSTATS_USERCOUNT,$this->_userid,0);
				}
				else{
					$inserts[]=array('user_count',CYSTATS_USERCOUNT,$this->_userid,0);
				}
			}

			// update raw data counter
				if(isset($stats['raw_count'])){
					$updates[]=array('raw_count',CYSTATS_RAWCOUNT,0,0);
				}
				else{
					$inserts[]=array('raw_count',CYSTATS_RAWCOUNT,0,0);
				}

            // update hits/all
                if(isset($stats['hits'])){
                    $updates[]=array('hits',CYSTATS_HITS,0,0);
                }
                else{
                    $inserts[]=array('hits',CYSTATS_HITS,0,0);
                    $hittrap=1;
                }
            // update hits/year
                if(isset($stats['hits_year']) && $stats['hits_year'][0]==$year){
                    $updates[]=array('hits_year',CYSTATS_HITSYEAR,$year,0);
                }
                else{
                    $inserts[]=array('hits_year',CYSTATS_HITSYEAR,$year,0);
                }

			// update hits/month
				if(isset($stats['hits_month']) && $stats['hits_month'][0]==$year && $stats['hits_month'][1]==$month){
					$updates[]=array('hits_month',CYSTATS_HITSMONTH,$year,$month);
				}
				else{
					$inserts[]=array('hits_month',CYSTATS_HITSMONTH,$year,$month);
				}

			// update hits/week
				if(isset($stats['hits_week']) && $stats['hits_week'][0]==$year && $stats['hits_week'][1]==$week){
					$updates[]=array('hits_week',CYSTATS_HITSWEEK,$year,$week);
				}
				else{
					$inserts[]=array('hits_week',CYSTATS_HITSWEEK,$year,$week);
				}

			// update hits/day
				if(isset($stats['hits_day']) && $stats['hits_day'][0]==$year && $stats['hits_day'][1]==$day){
					$updates[]=array('hits_day',CYSTATS_HITSDAY,$year,$day);
				}
				else{
					$inserts[]=array('hits_day',CYSTATS_HITSDAY,$year,$day);
				}

				# update counters for no_bot statistics
				if($info['type']==0){
				    $this->debugstring.=$this->getDebug("Entering no bot stats as type says",$info['type']);
				// update hits/all
					if(isset($stats['hits_nobots'])){
						$updates[]=array('hits_nobots',CYSTATS_HITS_NOBOTS,0,0);
					}
					else{
						$inserts[]=array('hits_nobots',CYSTATS_HITS_NOBOTS,0,0);
					}
				// update hits/year
					if(isset($stats['hits_year_nobots']) && $stats['hits_year_nobots'][0]==$year){
						$updates[]=array('hits_year_nobots',CYSTATS_HITSYEAR_NOBOTS,$year,0);
					}
					else{
						$inserts[]=array('hits_year_nobots',CYSTATS_HITSYEAR_NOBOTS,$year,0);
					}

				// update hits/month
					if(isset($stats['hits_month_nobots']) && $stats['hits_month_nobots'][0]==$year && $stats['hits_month_nobots'][1]==$month){
						$updates[]=array('hits_month_nobots',CYSTATS_HITSMONTH_NOBOTS,$year,$month);
					}
					else{
						$inserts[]=array('hits_month_nobots',CYSTATS_HITSMONTH_NOBOTS,$year,$month);
					}

				// update hits/week
					if(isset($stats['hits_week_nobots']) && $stats['hits_week_nobots'][0]==$year && $stats['hits_week_nobots'][1]==$week){
						$updates[]=array('hits_week_nobots',CYSTATS_HITSWEEK_NOBOTS,$year,$week);
					}
					else{
						$inserts[]=array('hits_week_nobots',CYSTATS_HITSWEEK_NOBOTS,$year,$week);
					}

				// update hits/day
					if(isset($stats['hits_day_nobots']) && $stats['hits_day_nobots'][0]==$year && $stats['hits_day_nobots'][1]==$day){
						$updates[]=array('hits_day_nobots',CYSTATS_HITSDAY_NOBOTS,$year,$day);
					}
					else{
						$inserts[]=array('hits_day_nobots',CYSTATS_HITSDAY_NOBOTS,$year,$day);
					}
					
				}

			// check last time ip stored 
			#$tm_start = array_sum(explode(' ', microtime()));
			#echo $secs_total = array_sum(explode(' ', microtime())) - $tm_start;

				$delta=$info['timestamp']-get_option('cystats_visit_deltatime');
				// maybe this query i faster than the query below due to the LIMIT 1 and
				// stopping after first fount item?
				$q='SELECT count(stamp)
					FROM '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW.'
					WHERE   (stamp>\''.$delta.'\'
							AND remote_addr=\''.($info['remote_addr']+0).'\')
							ORDER BY stamp DESC LIMIT 1';
				// slower query ?
				#$q='SELECT COUNT(stamp)
				#    FROM '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW.'
				#    WHERE   (stamp>\''.$delta.'\'
				#            AND remote_addr=\''.$info['remote_addr'].'\')
				#            ORDER BY stamp DESC';
				$locked=$wpdb->get_var($q);
				$this->debugstring.=$this->getDebug("Timedelta:",$delta);
				$this->debugstring.=$this->getDebug("Locked query:",$q);
				$this->debugstring.=$this->getDebug("Locked query error:",mysql_error());
				$this->debugstring.=$this->getDebug("Locked:",$locked);
				
				if($locked==0){
				// update visits/all
					if(isset($stats['visits'])){
						$updates[]=array('visits',CYSTATS_VISITS,0,0);
					}
					else{
						$inserts[]=array('visits',CYSTATS_VISITS,0,0);
					}
				// update visits/year
					if(isset($stats['visits_year']) && $stats['visits_year'][0]==$year){
						$updates[]=array('visits_year',CYSTATS_VISITSYEAR,$year,0);
					}
					else{
						$inserts[]=array('visits_year',CYSTATS_VISITSYEAR,$year,0);
					}

				// update post hit counter
				#echo "<br>TESTING";
				#var_dump($info);

			// update visits/month
					if(isset($stats['visits_month']) && $stats['visits_month'][0]==$year && $stats['visits_month'][1]==$month){
						$updates[]=array('visits_month',CYSTATS_VISITSMONTH,$year,$month);
					}
					else{
						$inserts[]=array('visits_month',CYSTATS_VISITSMONTH,$year,$month);
					}

			// update visits/week
					if(isset($stats['visits_week']) && $stats['visits_week'][0]==$year && $stats['visits_week'][1]==$week){
						$updates[]=array('visits_week',CYSTATS_VISITSWEEK,$year,$week);
					}
					else{
						$inserts[]=array('visits_week',CYSTATS_VISITSWEEK,$year,$week);
					}
			// update visits/day
					if(isset($stats['visits_day']) && $stats['visits_day'][0]==$year && $stats['visits_day'][1]==$day){
						$updates[]=array('visits_day',CYSTATS_VISITSDAY,$year,$day);
					}
					else{
						$inserts[]=array('visits_day',CYSTATS_VISITSDAY,$year,$day);
					}

					 if($info['type']==0){
					// update visits/all
						if(isset($stats['visits_nobots'])){
							$updates[]=array('visits_nobots',CYSTATS_VISITS_NOBOTS,0,0);
						}
						else{
							$inserts[]=array('visits_nobots',CYSTATS_VISITS_NOBOTS,0,0);
						}
					// update visits/year
						if(isset($stats['visits_year_nobots']) && $stats['visits_year_nobots'][0]==$year){
							$updates[]=array('visits_year_nobots',CYSTATS_VISITSYEAR_NOBOTS,$year,0);
						}
						else{
							$inserts[]=array('visits_year_nobots',CYSTATS_VISITSYEAR_NOBOTS,$year,0);
						}

				// update visits/month
						if(isset($stats['visits_month_nobots']) && $stats['visits_month_nobots'][0]==$year && $stats['visits_month_nobots'][1]==$month){
							$updates[]=array('visits_month_nobots',CYSTATS_VISITSMONTH_NOBOTS,$year,$month);
						}
						else{
							$inserts[]=array('visits_month_nobots',CYSTATS_VISITSMONTH_NOBOTS,$year,$month);
						}

				// update visits/week
						if(isset($stats['visits_week_nobots']) && $stats['visits_week_nobots'][0]==$year && $stats['visits_week_nobots'][1]==$week){
							$updates[]=array('visits_week_nobots',CYSTATS_VISITSWEEK_NOBOTS,$year,$week);
						}
						else{
							$inserts[]=array('visits_week_nobots',CYSTATS_VISITSWEEK_NOBOTS,$year,$week);
						}
				// update visits/day
						if(isset($stats['visits_day_nobots']) && $stats['visits_day_nobots'][0]==$year && $stats['visits_day_nobots'][1]==$day){
							$updates[]=array('visits_day_nobots',CYSTATS_VISITSDAY_NOBOTS,$year,$day);
						}
						else{
							$inserts[]=array('visits_day_nobots',CYSTATS_VISITSDAY_NOBOTS,$year,$day);
						}
					
					}
				}

			foreach($inserts AS $v){
				$q='INSERT INTO '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS.' (name,type,val1,val2,val3) VALUES (\''.$wpdb->escape($v[0]).'\',\''.$v[1].'\',\''.$v[2].'\',\''.$v[3].'\',\'1\')';
				$r=$wpdb->query($q);
				$this->debugstring.=$this->getDebug("Updated inserts error:",mysql_error());
			}
			foreach($updates AS $v){
				$q='UPDATE '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS.' SET val3=val3+1 WHERE name=\''.$wpdb->escape($v[0]).'\' AND type=\''.$v[1].'\''.(($v[2]!=0)?' AND val1=\''.$v[2].'\'':'').''.(($v[3]!=0)?' AND val2=\''.$v[3].'\'':'');
				$r=$wpdb->query($q);
								$this->debugstring.=$this->getDebug("Updated updates error:",mysql_error());

			}
			$info['timestamp']=intval($info['timestamp']);
			$entrypage=($locked==0)?1:0;
				
            $this->debugstring.=$this->getDebug("Inserts",$inserts);
            $this->debugstring.=$this->getDebug("Updates",$updates);			
			// set remote adr to 0 if saving disabled via options panel
			#if(get_option('cystats_noip') == 1){
			#	$info['remote_addr']=0;
			#}
			
			// save some bytes
			#if($locked){
			#    $info['os']             = '';
			#    $info['cid']            = '';
			#    $info['remote_host']    = '';
			#    $info['username']       = '';
			#    $info['accept_language'] = '';
			#}
			// insert new data row
			$query='INSERT INTO '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW.'
					(
						remote_addr,
						http_user_agent,
						http_accept_language,
						page,
						pagetype,
						pageid,
						stamp,
						browser,
						browserversion,
						browsertype,
						os,
						referer,
						method,
						searchstring,
						searchstringtype,
						referertype,
						entrypage
						)
					VALUES
					 (
						\''.$wpdb->escape($info['remote_addr']).'\',
						\''.$wpdb->escape($info['agent']).'\',
						\''.$wpdb->escape($info['accept_language']).'\',
						\''.$wpdb->escape($info['page']).'\',
						\''.$wpdb->escape($this->_pagetype).'\',
						\''.$wpdb->escape($this->_pageid).'\',
						\''.$info['timestamp'].'\',
						\''.$wpdb->escape($info['name']).'\',
						\''.$wpdb->escape($info['version']).'\',
						\''.$wpdb->escape($info['type']).'\',
						\''.$wpdb->escape($info['os']).'\',
						\''.$wpdb->escape($info['referer']).'\',
						\''.$wpdb->escape($info['method']).'\',
						\''.$wpdb->escape($searchstring).'\',
						\''.$wpdb->escape($searchstring_type).'\',
						\''.$wpdb->escape($info['referer_type']).'\',
						\''.$wpdb->escape($entrypage).'\'
						)';
				   
			$wpdb->query($query);
			$this->debugstring.=$this->getDebug("Insert Raw Table",$query);

            $q='SELECT count(type)
                    FROM '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS.'
                    WHERE
                        type='.CYSTATS_HITS;
            $hittrap=$wpdb->get_var($q);
			#if($hittrap>1 && $hittrap<4)mail("", "Cywhale.de BugTrap", $this->debugstring,"from: ");
			// cleanup
			
			#echo $this->debugstring;
			$clean_last_time = get_option('cystats_last_cleanup');
			$clean_interval = get_option('cystats_cleanup_interval');
			if($info['timestamp']>=($clean_last_time+$clean_interval)){
				// do some visit removing
				$query="DELETE FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW." WHERE stamp < ".($info['timestamp']-get_option('cystats_rawtable_max'));
				$wpdb->query($query);
				// set recent cleanup time
				update_option('cystats_last_cleanup',$info['timestamp']);
			}
			
			//delete CIDs older than one minute
			#$query="UPDATE ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW." SET cid='' WHERE stamp<".($info['timestamp']-60);        
			#$wpdb->query($query);

			unset($info);
			unset($inserts);
			unset($updates);
		} // end userlevel check
        }
    } // end of class statistics
} // end class checking



function c_str_split($text, $split = 1)
{
if (!is_string($text)) return false;
if (!is_numeric($split) && $split < 1) return false;
$len = strlen($text);
$array = array();
$s = 0;
$e=$split;
while ($s <$len)
    {
        $e=($e <$len)?$e:$len;
        $array[] = substr($text, $s,$e);
        $s = $s+$e;
    }
return $array;
}
?>
