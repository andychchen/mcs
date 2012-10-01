<?php
/*
* Returns number of published posts
* @return integer
*/
function cystats_countPosts($showmode=TRUE){
    global $wpdb;
    $count = $wpdb->get_var("SELECT COUNT(post_status) FROM $wpdb->posts WHERE post_status = 'publish' AND  post_password = '' AND post_type = 'post'");
    $count = ($count==FALSE)?0:$count; 
    if($showmode==TRUE){
       echo $count;
    }else{
        return($count);
    }
}

/*
* Returns number of approved comments
* @return integer
*/
function cystats_countComments($showmode=TRUE){
    global $wpdb;
    $count = $wpdb->get_var("SELECT COUNT(comment_approved) FROM $wpdb->comments WHERE comment_approved = '1'");
    $count = ($count==FALSE)?0:$count; 
    if($showmode==TRUE){
       echo $count;
    }else{
        return($count);
    }
}

/*
* Returns number of authors
* @param bool $showmode, true echoes value, false returns value
* @return integer
*
*/
function cystats_countAuthors($showmode=TRUE){
    global $wpdb;    
    $count = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users WHERE user_activation_key = ''");
    $count = ($count==FALSE)?0:$count;
    if($showmode==TRUE){
       echo $count;
    }else{
        return($count);
    }
}

/*
* Returns number of published pages
* @param bool $showmode, true echoes value, false returns value
* @return integer
*/
function cystats_countPages($showmode=TRUE){
    global $wpdb;    
    $count = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->posts WHERE post_type = 'page' AND post_status = 'publish'");
    $count = ($count==FALSE)?0:$count;
    if($showmode==TRUE){
       echo $count;
    }else{
        return($count);
    }
}

/*
* Returns number of links
* @param bool $showmode, true echoes value, false returns value
* @return integer
*/
function cystats_countLinks($showmode=TRUE){
    global $wpdb;
    $count = $wpdb->get_var("SELECT COUNT(link_id) FROM $wpdb->links");
    $count = ($count==FALSE)?0:$count;    
    if($showmode==TRUE){
       echo $count;
    }else{
        return($count);
    }
}


/*
* Returns number of categories
* @return integer
*/
function cystats_countCategories($showmode=TRUE){
    global $wpdb;
    $wpv = (float)get_bloginfo('version');
    if($wpv<2.3){
        $count = $wpdb->get_var("SELECT COUNT(category_nicename) FROM $wpdb->categories");
        $count = ($count==FALSE)?0:$count;
    }else{
        $count=$wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->terms." AS t INNER JOIN ".$wpdb->term_taxonomy." AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy ='category'");
    }
    if($showmode==TRUE){
       echo $count;
    }else{
        return($count);
    }
}

/*
* Returns days since first post
* @return integer
*/
function cystats_firstPostDays($showmode=TRUE){
    global $wpdb;
    $first = $wpdb->get_var("
        SELECT post_date_gmt
        FROM
            $wpdb->posts
        WHERE post_status = 'publish'
        ORDER BY post_date_gmt
        LIMIT 1
    ");
    
    $days = intval((time() - strtotime($first) ) / (60*60*24));
    if($showmode==TRUE){
       echo $days;
    }else{
        return($days);
    }

}

/*
* Returns date and time of first post
* @return string
*/
function cystats_firstPost($showmode=TRUE){
    global $wpdb;
    $first = $wpdb->get_var("
        SELECT post_date_gmt
        FROM
            $wpdb->posts
        WHERE post_status = 'publish'
        ORDER BY post_date_gmt
        LIMIT 1
    ");
    $firstpostdate=mysql2date('d.m.Y, H:i',$first);
    if($showmode==TRUE){
       echo $firstpostdate;
    }else{
        return($firstpostdate);
    }

}

/*
* Return number of blog users in database
* @param bool $showmode true displays result, false returns result
* @returns integer
*/
function cystats_countUsers($showmode=TRUE){
    global $wpdb;
    $count=$wpdb->get_var("SELECT COUNT(ID) FROM ".$wpdb->users);
    $count = ($count==FALSE)?0:$count;    
    if($showmode==TRUE){
       echo $count;
    }else{
        return($count);
    }
}

/**
 * Gets top commenters as strings like 'name (comments)'
 * 
 * @param int $topcount number of commenters to get
 * @param string $pre string to set before commenters name, e.g. '<li>'
 * @param string $pos string to set after commentcount, e.g. '</li>'
 * @return array
 */
function cystats_getTopCommenters($topcount, $pre='', $pos='', $showmode=TRUE){
    global $wpdb;
    $q = "SELECT 
            comment_author, comment_author_url, comment_author_email,
            COUNT(comment_ID) AS commentcount 
            FROM $wpdb->comments 
            WHERE comment_approved = '1' AND comment_author != '' 
            GROUP BY comment_author ORDER BY commentcount
            DESC LIMIT ".intval($topcount);    
    $r = $wpdb->get_results($q,ARRAY_A);
    $ret=array();
    if($r){
        foreach($r AS $row){
            $dummy = $pre;
            $dummy.= (!empty($row['comment_author_url'])) ? '<a href="'.$row['comment_author_url'].'">'.$row['comment_author'].'</a> ('.$row['commentcount'].')'.$pos : $row['comment_author'].' ('.$row['commentcount'].')'.$pos; 
        $ret[]=$dummy;
        }
    }
    if($showmode==TRUE){
        foreach($ret AS $row){
            echo $row;
        }
    }else{
        return($ret);
    }
}




/*
* Returns number of feedreaders today
* @param bool $showmode, true echoes value, false returns value
* @return integer
*/
function cystats_countFeedreadersToday($showmode=TRUE){
    global $wpdb;
    $count = $wpdb->get_var("
        SELECT 
            COUNT(DISTINCT(remote_addr))  
        FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW." 
        WHERE pagetype=12 
        AND stamp>".strtotime('today 0:00')."
        ");
    $count = ($count==FALSE)?0:$count;
    if($showmode==TRUE){
       echo $count;
    }else{
        return($count);
    }
}

/*
* Returns number of feedreaders today
* @param bool $showmode, true echoes value, false returns value
* @return integer
*/
function cystats_countUsersOnline($showmode=TRUE){
    global $wpdb;
    $count = $wpdb->get_var("
        SELECT 
            COUNT(DISTINCT(remote_addr)) 
        FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW." 
        WHERE stamp>".(current_time('timestamp')-get_option('cystats_visit_deltatime'))." 
        ");
    $count = ($count==FALSE)?0:$count;
    if($showmode==TRUE){
       echo $count;
    }else{
        return($count);
    }
}




/**
 * Gets recent posts as array of strings 
 * 
 * @param int $limit number of posts to get
 * @param string $pre string to set before postname, e.g. '<li>'
 * @param string $pos string to set after postname, e.g. '</li>'
 * @param bool $showmode, true echoes value, false returns value
 * @return array
 */
function cystats_getRecentPosts($limit, $pre='', $pos='', $showmode=TRUE){
    global $wpdb;
    $q="
        SELECT ".$wpdb->posts.".post_title,".$wpdb->posts.".guid,  ".$wpdb->posts.".ID
        FROM ".$wpdb->posts." LEFT JOIN ".$wpdb->users." 
        ON ".$wpdb->users.".ID =".$wpdb->posts.".post_author 
        WHERE post_date < '".current_time('mysql')."' 
        AND post_password = '' 
        AND post_status = 'publish' 
        ORDER  BY post_date 
        DESC LIMIT ".$limit;
    $r = $wpdb->get_results($q,ARRAY_A);
    $ret=array();
    if($r){
        foreach($r AS $row){
            $dummy = $pre;
            $dummy.= '<a href="'.$row['guid'].'">'.$row['post_title'].'</a>'.$pos;
        $ret[]=$dummy;
        }
    }
    if($showmode==TRUE){
        foreach($ret AS $row){
            echo $row;
        }
    }else{
        return($ret);
    }
}

/**
 * Gets most commented posts as array of strings 
 * 
 * @param int $limit number of comments to get
 * @param string $pre string to set before postname, e.g. '<li>'
 * @param string $pos string to set after postname, e.g. '</li>'
 * @param bool $showmode, true echoes value, false returns value
 * @return array
 */
function cystats_getMostCommented($limit, $pre='', $pos='', $showmode=TRUE){
    global $wpdb;
    $q="SELECT "
          .$wpdb->posts.".post_title,".$wpdb->posts.".guid, 
          COUNT(".$wpdb->comments.".comment_post_ID) AS 'commentcount' 
        FROM ".$wpdb->posts." LEFT JOIN ".$wpdb->comments." 
        ON ".$wpdb->posts.".ID = ".$wpdb->comments.".comment_post_ID 
        WHERE comment_approved = '1' 
        AND post_date < '".current_time('mysql')."' 
        AND post_password = '' 
        AND post_status = 'publish' 
        GROUP BY ".$wpdb->comments.".comment_post_ID 
        ORDER  BY commentcount 
        DESC LIMIT ".$limit;
    $r = $wpdb->get_results($q,ARRAY_A);
    $ret=array();
    if($r){
        foreach($r AS $row){
            $dummy = $pre;
            $dummy.= '<a href="'.$row['guid'].'">'.$row['post_title'].'</a> ('.$row['commentcount'].')'.$pos;
        $ret[]=$dummy;
        }
    }
    if($showmode==TRUE){
        foreach($ret AS $row){
            echo $row;
        }
    }else{
        return($ret);
    }
}

/**
 * Gets most commented posts as array of strings 
 * 
 * @param int $limit number of comments to get
 * @param string $pattern, string to be returned or shown, subpatterns 
 * will be replaced:
 *                         %post_permalink%
 *                         %post_title%
 *                         %post_comments%
 * Example use: cystats_getMostCommentedExt(
 *                     5,
 *                     '<li>
 *                         <a href="%post_permalink%" title="Go to">%post_title%</a>
 *                         (%post_comments% Kommentare)
 *                     </li>');
 * @param bool $showmode, true echoes value, false returns value
 * @return array
 */
function cystats_getMostCommentedExt($limit, $pattern, $showmode=TRUE){
    global $wpdb;
    $q="SELECT "
          .$wpdb->posts.".post_title,".$wpdb->posts.".guid, 
          COUNT(".$wpdb->comments.".comment_post_ID) AS 'commentcount' 
        FROM ".$wpdb->posts." LEFT JOIN ".$wpdb->comments." 
        ON ".$wpdb->posts.".ID = ".$wpdb->comments.".comment_post_ID 
        WHERE comment_approved = '1' 
        AND post_date < '".current_time('mysql')."' 
        AND post_password = '' 
        AND post_status = 'publish' 
        GROUP BY ".$wpdb->comments.".comment_post_ID 
        ORDER  BY commentcount 
        DESC LIMIT ".$limit;
    $r = $wpdb->get_results($q,ARRAY_A);
    $ret=array();
    if($r){
        foreach($r AS $row){
            $dummy = $pattern;
            $dummy = str_replace('%post_permalink%',$row['guid'],$dummy);
            $dummy = str_replace('%post_title%',$row['post_title'],$dummy);
            $dummy = str_replace('%post_comments%',$row['commentcount'],$dummy);
        $ret[]=$dummy;
        }
    }
    if($showmode==TRUE){
        foreach($ret AS $row){
            echo $row;
        }
    }else{
        return($ret);
    }
}

/**
 * Gets most visited posts as array of strings 
 * 
 * @param int $limit number of posts to get
 * @param string $pre string to set before post title, e.g. '<li>'
 * @param string $pos string to set after post title, e.g. '</li>'
 * @param bool $showmode, true echoes value, false returns value
 * @return array
 */
function cystats_getMostVisited($limit, $pre, $pos, $showmode=TRUE) {
    global $wpdb;
    $ret = array();
    $q = "SELECT  
            val1 AS item, 
            val3 AS value
        FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."
        WHERE type=".CYSTATS_POSTCOUNT." 
        ORDER BY val3 DESC
        LIMIT ".$wpdb->escape($limit);
    $r = $wpdb->get_results($q,ARRAY_A);
    if(is_array($r)){
    foreach ($r AS $row){
        #$percent = round(($row['value']/$max * 100));
        $postdata=$wpdb->get_results("SELECT post_title,guid FROM ".$wpdb->posts." WHERE ID=".(intval($row['item'])),ARRAY_A);
        $row['item']='<a href="'.$postdata[0]['guid'].'">'.str_replace(" ","&nbsp;",$postdata[0]['post_title']).'</a>';
        $ret[] = $pre.$row['item'].'&#160;('.$row['value'].')'.$pos;
    }
    if($showmode==TRUE){
        foreach($ret AS $row){
            echo $row;
        }
    }else{
        return($ret);
    }    
    }else return FALSE;
}


function cystats_getMostVisitedExt($limit, $pattern, $showmode=TRUE) {
    global $wpdb;
    $ret = array();
    $q = "SELECT  
            val1 AS item, 
            val3 AS value
        FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."
        WHERE type=".CYSTATS_POSTCOUNT." 
        ORDER BY val3 DESC
        LIMIT ".$wpdb->escape($limit);
    $r = $wpdb->get_results($q,ARRAY_A);
    if(!empty($r)){
        foreach($r AS $row){
            $postdata=$wpdb->get_results("SELECT post_title,guid FROM ".$wpdb->posts." WHERE ID=".(intval($row['item'])),ARRAY_A);
            $dummy = $pattern;
            $dummy = str_replace('%post_permalink%',$postdata[0]['guid'],$dummy);
            $dummy = str_replace('%post_title%',$postdata[0]['post_title'],$dummy);
            $dummy = str_replace('%post_visits%',$row['value'],$dummy);
        $ret[]=$dummy;
        }
    }else $ret=array(FALSE);
    if($showmode==TRUE){
        foreach($ret AS $row){
            echo $row;
        }
    }else{
        return($ret);
    }    
}


/**
 * Get post visits 
 * @param integer $id
 * @param bool $showmode, true echoes value, false returns value
 * @return array
 */
function cystats_getPostVisitsByID($id,$showmode=TRUE) {
    global $wpdb;
    $ret = array();
    $q = "SELECT  
            val3
        FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS."
        WHERE type=".CYSTATS_POSTCOUNT." 
         AND val1='".$id."'";
    $count = $wpdb->get_var($q);
    $count = ($count==FALSE)?0:$count;
    if($showmode==TRUE){
       echo $count;
    }else{
        return($count);
    }
}

/**
 * Gets recent comments as array of strings 
 * 
 * @param int $limit number of comments to get
 * @param string $pre string to set before commentname, e.g. '<li>'
 * @param string $pos string to set after commentname, e.g. '</li>'
 * @param bool $showmode, true echoes value, false returns value
 * @return array
 */
function cystats_getRecentCommented($limit, $pre='', $pos='', $showmode=TRUE){
    global $wpdb;
    #$dateformat= preg_replace("=([a-zA-Z]{1})=","%\\1",preg_replace(array("=F=","=j="),array("M","d"),get_option('date_format')))." ".preg_replace("=([a-zA-Z]{1})=","%\\1",get_option('time_format'));
    $q="
        SELECT ".$wpdb->posts.".post_title,".$wpdb->posts.".ID, MAX(comment_date) AS cd 
        FROM ".$wpdb->posts." INNER JOIN ".$wpdb->comments." 
        ON ".$wpdb->posts.".ID = ".$wpdb->comments.".comment_post_ID  
        WHERE comment_approved = '1' 
        AND post_date < '".current_time('mysql')."' 
        AND post_password = '' 
        AND (post_status = 'publish' OR post_status = 'static') 
        GROUP BY post_title
        ORDER  BY cd DESC 
        LIMIT ".$limit;

    $r = $wpdb->get_results($q,ARRAY_A);
    #var_dump($r);die();
    $ret=array();
    if($r){
        foreach($r AS $row){
            $dummy = $pre;
            $dummy.= '<a href="'.get_permalink($row['ID']).'">'.$row['post_title'].'</a> ('.mysql2date(get_settings("date_format").", " . get_settings("time_format"),$row['cd'] ).')'.$pos;
        $ret[]=$dummy;
        }
    }
    if($showmode==TRUE){
        foreach($ret AS $row){
            echo $row;
        }
    }else{
        return($ret);
    }
    
}
/*
 * Get visit count for $mode
 *     'today',
 *     'yesterday',
 *     'week',
 *     'month'
 *     'year',
 *     'all'
 * 
 * @param string $mode
 * @param string $showmode, true echoes value, false returns value
 * @returns integer
 */
function cystats_countVisits($mode, $showmode=TRUE){
    global $wpdb;
    $t=time();
    $year=gmdate("Y",$t);
    $month=gmdate("m",$t);
    $week=gmdate("W",$t);
    $day=strtotime(date('d-M-Y',$t));
    $yesterday=strtotime(date('d-M-Y',($t-(60*60*24))));

    $where='(type=\''.CYSTATS_VISITSDAY_NOBOTS.'\'   AND val1=\''.$year.'\' AND val2=\''.$day.'\')';
    if($mode=='today'){
        $where='(type=\''.CYSTATS_VISITSDAY_NOBOTS.'\'   AND val1=\''.$year.'\' AND val2=\''.$day.'\')';
    }elseif($mode=='yesterday'){
        $where='(type=\''.CYSTATS_VISITSDAY_NOBOTS.'\'   AND val1=\''.$year.'\' AND val2=\''.$yesterday.'\')';
    }elseif($mode=='week'){
        $where='(type=\''.CYSTATS_VISITSWEEK_NOBOTS.'\'  AND val1=\''.$year.'\' AND val2=\''.$week.'\')';
    }elseif($mode=='month'){
        $where='(type=\''.CYSTATS_VISITSMONTH_NOBOTS.'\' AND val1=\''.$year.'\' AND val2=\''.$month.'\')';
    }elseif($mode=='year'){
        $where='(type=\''.CYSTATS_VISITSYEAR_NOBOTS.'\'  AND val1=\''.$year.'\')';
    }elseif($mode=='all'){
        $where='name=\'visits_nobots\'';
    };
    

    // get data row
    // name,type,val1,val2,
    $q='SELECT val3
        FROM '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS.'
        WHERE '.$where;

    $count = $wpdb->get_var($q); 
    $count = ($count==FALSE)?0:$count;
    
    if($mode=='all'){
        $count+=intval(get_option('cystats_visits_delta'));
    }
    if($showmode==TRUE){
       echo $count;
    }else{
        return($count);
    } 
}

/*
 * Get hit count for $mode
 *     'today',
 *     'yesterday',
 *     'week',
 *     'month'
 *     'year',
 *     'all'
 * 
 * @param string $mode
 * @param string $showmode, true echoes value, false returns value
 * @returns integer
 */
function cystats_countHits($mode, $showmode=TRUE){
    global $wpdb;
    $t=time();
    $year=gmdate("Y",$t);
    $month=gmdate("m",$t);
    $week=gmdate("W",$t);
    $day=strtotime(date('d-M-Y',$t));
    $yesterday=strtotime(date('d-M-Y',($t-(60*60*24))));

    $where='(type=\''.CYSTATS_HITSDAY_NOBOTS.'\'   AND val1=\''.$year.'\' AND val2=\''.$day.'\')';
    if($mode=='today'){
        $where='(type=\''.CYSTATS_HITSDAY_NOBOTS.'\'   AND val1=\''.$year.'\' AND val2=\''.$day.'\')';
    }elseif($mode=='yesterday'){
        $where='(type=\''.CYSTATS_HITSDAY_NOBOTS.'\'   AND val1=\''.$year.'\' AND val2=\''.$yesterday.'\')';
    }elseif($mode=='week'){
        $where='(type=\''.CYSTATS_HITSWEEK_NOBOTS.'\'  AND val1=\''.$year.'\' AND val2=\''.$week.'\')';
    }elseif($mode=='month'){
        $where='(type=\''.CYSTATS_HITSMONTH_NOBOTS.'\' AND val1=\''.$year.'\' AND val2=\''.$month.'\')';
    }elseif($mode=='year'){
        $where='(type=\''.CYSTATS_HITSYEAR_NOBOTS.'\'  AND val1=\''.$year.'\')';
    }elseif($mode=='all'){
        $where='name=\'hits_nobots\'';
    };
    

    // get data row
    // name,type,val1,val2,
    $q='SELECT val3
        FROM '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS.'
        WHERE '.$where;

    $count = $wpdb->get_var($q); 
    $count = ($count==FALSE)?0:$count;
    
    if($mode=='all'){
        $count+=intval(get_option('cystats_hits_delta'));
    }
    if($showmode==TRUE){
       echo $count;
    }else{
        return($count);
    } 
}

/**
 * Gets recent comments as array of strings 
 * 
 * @param int $limit number of comments to get
 * @param string $pattern, string containing subpatterns to be replaced
 *         %comment_post_permalink%
 *         %comment_post_title%
 *         %comment_author%
 *         %comment_author_url%
 *         %comment_date%
 *         %comment_time%
 * 
 * Example usage: 
 * 
 * cystats_getRecentCommentedExt(5,
 *     '<li>
 *     <a href="%comment_author_url%">%comment_author%</a> 
 *     in <a href="%comment_post_permalink%">%comment_post_title%</a>
 *     <br/><span style="font-size:10px;">%comment_date%,%comment_time%</span>
 *     </li>');

 * @param bool $showmode, true echoes value, false returns value
 * @return array
 */
function cystats_getRecentCommentedExt($limit, $pattern, $showmode=TRUE){
    global $wpdb;
    #$dateformat= preg_replace("=([a-zA-Z]{1})=","%\\1",preg_replace(array("=F=","=j="),array("M","d"),get_option('date_format')))." ".preg_replace("=([a-zA-Z]{1})=","%\\1",get_option('time_format'));
    /*
    $q="
        SELECT post_title,guid,ID, (comment_date) AS cd,
        comment_author, 
        comment_ID, 
        comment_author_url
        FROM ".$wpdb->comments." INNER JOIN ".$wpdb->posts." 
        ON ID = comment_post_ID 
        WHERE comment_approved = '1' 
        AND comment_type='' 
        AND post_date < '".current_time('mysql')."' 
        AND post_password = ''
        AND (post_status = 'publish' OR post_status = 'static') 
		GROUP BY comment_post_ID
        ORDER  BY comment_date DESC 
        LIMIT ".$limit;
	*/
	$q="
		SELECT DISTINCT comment_author_url, comment_ID, comment_date, comment_author, guid, post_title 
		FROM ".$wpdb->comments." 
		LEFT JOIN ".$wpdb->posts." ON ".$wpdb->posts.".ID=".$wpdb->comments.".comment_post_ID 
		WHERE 
			(post_status = 'publish' OR post_status = 'static') 
			AND comment_approved= '1' AND  post_password = '' AND comment_type = '' 
		ORDER BY comment_date DESC 
		LIMIT ".$limit;
    $r = $wpdb->get_results($q,ARRAY_A);
    #var_dump($r);die();
    $ret=array();
    if($r){
        foreach($r AS $row){
            if(empty($row['comment_author_url'])){
                $row['comment_author_url']=$row['guid'].'#comment-'.$row['comment_ID'];
            }
            $dummy = $pattern;
            $dummy = str_replace('%comment_post_permalink%',htmlspecialchars($row['guid']),$dummy);
            $dummy = str_replace('%comment_permalink%',htmlspecialchars($row['guid'].'#comment-'.$row['comment_ID']),$dummy);
            $dummy = str_replace('%comment_post_title%',$row['post_title'],$dummy);
            $dummy = str_replace('%comment_author%',htmlspecialchars($row['comment_author']),$dummy);
            $dummy = str_replace('%comment_author_url%',htmlspecialchars($row['comment_author_url']),$dummy);
            $dummy = str_replace('%comment_date%',mysql2date(get_settings("date_format"),$row['comment_date'] ),$dummy);
            $dummy = str_replace('%comment_time%',mysql2date(get_settings("time_format"),$row['comment_date'] ),$dummy);
        $ret[]=$dummy;
        }
    }
    if($showmode==TRUE){
        foreach($ret AS $row){
            echo $row;
        }
    }else{
        return($ret);
    }
    
}

/*
* Returns average posts per day
* @return integer
*/
function cystats_countAvgPostsPerDay($showmode=TRUE){
    $days=cystats_firstPostDays(FALSE);
    $posts=cystats_countPosts(FALSE);
    $avg=($days==0)?0:sprintf("%01.2f",($posts/$days));
    if($showmode==TRUE){
        echo $avg;
    }else{
        return($avg);
    }
    
}

/*
* Returns average comments per day
* @return integer
*/
function cystats_countAvgCommentsPerDay($showmode=TRUE){
    $days=cystats_firstPostDays(FALSE);
    $posts=cystats_countComments(FALSE);
    $avg=($days==0)?0:sprintf("%01.2f",($posts/$days));
    if($showmode==TRUE){
        echo $avg;
    }else{
        return($avg);
    }
    
}

function cystats_getSearchengineRefererCount($showmode=TRUE){
    global $wpdb;
    $itemcount=$wpdb->get_var(
        "SELECT COUNT(referer)
         FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW." 
         WHERE referertype=1  AND referer!='' AND stamp>".(strtotime('today 0:00'))
        );
    if($showmode==TRUE){
        echo $itemcount;
    }else{
        return($itemcount);
    }
}


?>
