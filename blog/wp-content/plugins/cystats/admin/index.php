<?php
global $wpdb;
    echo '<div class="wrap">';


if(isset($_GET['cystats_allreferer'])){
    echo '<h2>'.htmlspecialchars(__('CyStats External Referer','cystats')).'</h2><p>';
        $statistics->setLimit(100);
        global $wpdb;
        $itemcount=$wpdb->get_var(
            "SELECT COUNT(referer)
            FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW." 
            WHERE referertype=0 AND referer!=''" 
        );
        
        $pages=round($itemcount/100)-1;
        $plimit='';
        if(!isset($_GET['p'])){
            $p=1;
        }else{
            $p=intval($_GET['p']);
            if($p<0||$p>$pages){
            $p=1;
            }        
        }
        $plimit=(($p-1)*100).',100';
        echo htmlspecialchars(__('Page navigation','cystats')).': ';
        for($i=1;$i<=$pages;$i++){
            if($i==$p){
                echo ($i).'&#160;';
            }else{
                echo '<a href="admin.php?page=cystats-referer&amp;cystats_allreferer&amp;p='.$i.'" title="'.htmlspecialchars(__('Visit page','cystats')).' '.$i.'">'.$i.'&#160;</a>';
            }
        }
        $r=$statistics->getStatsData("referer","WHERE referertype=0  AND referer!=''",'',$plimit);
        $statistics->outputStatsData($r,htmlspecialchars(__('External referer','cystats')),'400px',FALSE);
    echo "</p>";    

}elseif(isset($_GET['cystats_allsearchenginereferer'])){
    echo '<h2>'.htmlspecialchars(__('CyStats Searchengine Referer','cystats')).'</h2><p>';
        $statistics->setLimit(100);
        global $wpdb;
        $itemcount=$wpdb->get_var(
            "SELECT COUNT(referer)
            FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW." 
            WHERE referertype=1  AND referer!=''"
        );
        
        $pages=round($itemcount/100)-1;
        $plimit='';
        if(!isset($_GET['p'])){
            $p=1;
        }else{
            $p=intval($_GET['p']);
            if($p<0||$p>$pages){
            $p=1;
            }        
        }
        $plimit=(($p-1)*100).',100';
        echo htmlspecialchars(__('Page navigation','cystats')).': ';
        for($i=1;$i<=$pages;$i++){
            if($i==$p){
                echo ($i).'&#160;';
            }else{
                echo '<a href="admin.php?page=cystats-referer&amp;cystats_allsearchenginereferer&amp;p='.$i.'" title="'.htmlspecialchars(__('Visit page','cystats')).' '.$i.'">'.$i.'&#160;</a>';
            }
        }
        $r=$statistics->getStatsData("referer","WHERE referertype=1  AND referer!=''",'',$plimit);
        $statistics->outputStatsData($r,htmlspecialchars(__('Searchengine referer','cystats')),'400px',FALSE);
    echo "</p>";    
}elseif(isset($_GET['cystats_allreferertoday'])){
    echo '<h2>'.htmlspecialchars(__('CyStats Referer Today','cystats')).'</h2><p>';
        $statistics->setLimit(100);
        global $wpdb;
        $itemcount=$wpdb->get_var(
            "SELECT COUNT(referer)
            FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW." 
            WHERE referertype=0  AND referer!='' AND stamp>".(strtotime('today 0:00'))
        );
        
        $pages=round($itemcount/100)-1;
        $plimit='';
        if(!isset($_GET['p'])){
            $p=1;
        }else{
            $p=intval($_GET['p']);
            if($p<0||$p>$pages){
            $p=1;
            }        
        }
        $plimit=(($p-1)*100).',100';
        echo htmlspecialchars(__('Page navigation','cystats')).': ';
        for($i=1;$i<=$pages;$i++){
            if($i==$p){
                echo ($i).'&#160;';
            }else{
                echo '<a href="admin.php?page=cystats-referer&amp;cystats_allreferertoday&amp;p='.$i.'" title="'.htmlspecialchars(__('Visit page','cystats')).' '.$i.'">'.$i.'&#160;</a>';
            }
        }
        $r=$statistics->getStatsData("referer","WHERE referertype=0  AND referer!='' AND stamp>".(strtotime('today 0:00')),'',$plimit);
        $statistics->outputStatsData($r,htmlspecialchars(__('Referer today','cystats')),'400px',FALSE);
    echo "</p>";    

}elseif(isset($_GET['cystats_allrefereryesterday'])){
    echo '<h2>'.htmlspecialchars(__('CyStats Referer Yesterday','cystats')).'</h2><p>';
        $statistics->setLimit(100);
        global $wpdb;
        $itemcount=$wpdb->get_var(
            "SELECT COUNT(referer)
            FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW." 
            WHERE referertype=0  AND referer!='' AND stamp BETWEEN ".(strtotime('today 0:00')-(3600*24))." AND ".(strtotime('today 0:00'))
        );
        
        $pages=round($itemcount/100)-1;
        $plimit='';
        if(!isset($_GET['p'])){
            $p=1;
        }else{
            $p=intval($_GET['p']);
            if($p<0||$p>$pages){
            $p=1;
            }        
        }
        $plimit=(($p-1)*100).',100';
        echo htmlspecialchars(__('Page navigation','cystats')).': ';
        for($i=1;$i<=$pages;$i++){
            if($i==$p){
                echo ($i).'&#160;';
            }else{
                echo '<a href="admin.php?page=cystats-referer&amp;cystats_allrefereryesterday&amp;p='.$i.'" title="'.htmlspecialchars(__('Visit page','cystats')).' '.$i.'">'.$i.'&#160;</a>';
            }
        }
        $r=$statistics->getStatsData("referer","WHERE referertype=0  AND referer!='' AND stamp BETWEEN ".(strtotime('today 0:00')-(3600*24))." AND ".(strtotime('today 0:00')),'',$plimit);
        $statistics->outputStatsData($r,htmlspecialchars(__('Referer today','cystats')),'400px',FALSE);
    echo "</p>";    
}elseif(isset($_GET['cystats_allvisits'])){

    echo "<h2>".htmlspecialchars(__('Visits history','cystats'))."</h2>";
    echo '<div style="margin:5px;width:99%;">';
            

             // begin pagination calculation
            global $wpdb;
            $itemcount=$wpdb->get_var(
                "SELECT COUNT(remote_addr)
                FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW
            );
             $pages=round($itemcount/get_option('cystats_visits_displayrows'))-1;
            $plimit='';
            if(!isset($_GET['p'])){
                $p=1;
            }else{
                $p=intval($_GET['p']);
                if($p<0||$p>$pages){
                $p=1;
                }        
            }
            $plimit=(($p-1)*get_option('cystats_visits_displayrows')).','.get_option('cystats_visits_displayrows');
            if($pages>0){
                echo htmlspecialchars(__('Page navigation','cystats')).': ';
                for($i=1;$i<=$pages;$i++){
                    if($i==$p){
                        echo ($i).'&#160;';
                    }else{
                        echo '<a href="admin.php?page=cystats/includes/admin.php&amp;p='.$i.'&amp;cystats_allvisits" title="'.htmlspecialchars(__('Visit page','cystats')).' '.$i.'">'.$i.'&#160;</a>';
                    }
                }
            }
        $range=explode(',',$plimit);
        $range=$range[0].'-'.($range[0]+get_option('cystats_visits_displayrows'));
        $statistics->set_cut_length(50);
        $r=$statistics->getVisitsData('remote_addr',$plimit);
        $statistics->outputVisitsData($r,htmlspecialchars(__('Last hits','cystats')).' ('.$range.'/'.$itemcount.')',500);
    echo '</div>';
            unset($r);
}elseif ('cystats_delete_wp_searchwordstrings' == $_POST['action']){
    $r=1;
    $r = $wpdb->query("
            UPDATE 
            ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW."
            SET searchstring=''
            WHERE searchstringtype=2
        ");  
    if($r){
        echo '<div id="message" class="updated fade"><p>'.htmlspecialchars(__('Searchstrings successfully deleted','cystats')).'.</p></div>';        
        echo '</div>';die();
    }else{
        echo '<div id="message" class="updated fade"><p>'.htmlspecialchars(__('Nothing deleted','cystats')).'.</p></div>';                
        echo '</div>';die();
    }      
}elseif ('cystats_delete_searchwordstrings' == $_POST['action']){
    $r = $wpdb->query("
            UPDATE 
            ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW." 
            SET searchstring=''
            WHERE searchstringtype=1
        ");    
    if($r){
        echo '<div id="message" class="updated fade"><p>'.htmlspecialchars(__('Searchstrings successfully deleted','cystats')).'.</p></div>';        
        echo '</div>';die();
    }else{
        echo '<div id="message" class="updated fade"><p>'.htmlspecialchars(__('Nothing deleted','cystats')).'.</p></div>';                
        echo '</div>';die();
    }      
}else{ 
    echo "<h2>".htmlspecialchars(__('CyStats Index','cystats'))."</h2>";

$cystats_currenttime=current_time('timestamp');

echo '<div style="margin:5px;width:48%;float:left;">';
echo '<p><table class="cystats" >';
echo '<tr><th class="cystats" colspan="4" style="text-align:left;">'.htmlspecialchars(__('Blog information','cystats')).'</th></tr>';
echo '<tr><td><div><table>';

echo '  <tr>
        <td class="cystats0 firstcol">'.$statistics->safespace(htmlspecialchars(__('First post date','cystats'))).'</td>
        <td class="cystats0" style="width:100%;text-align:right;"><span class="count_result">'.cystats_firstPost(FALSE).'</span></td>
        </tr>';
echo '  <tr>
        <td class="cystats1 firstcol">'.$statistics->safespace(htmlspecialchars(__('Days since first post','cystats'))).'</td>
        <td class="cystats1" style="width:100%;text-align:right;"><span class="count_result">'.cystats_firstPostDays(FALSE).'</span></td>
        </tr>';

echo '  <tr>
        <td class="cystats1 firstcol">'.str_replace(' ','&#160;',htmlspecialchars(__('Avg. posts per day','cystats'))).'</td>
        <td class="cystats1" style="width:100%;text-align:right;"><span class="count_result">'.cystats_countAvgPostsPerDay(FALSE).'</span></td>
        </tr>';
echo '  <tr>
        <td class="cystats1 firstcol">'.str_replace(' ','&#160;',htmlspecialchars(__('Avg. comments per day','cystats'))).'</td>
        <td class="cystats1" style="width:100%;text-align:right;"><span class="count_result">'.cystats_countAvgCommentsPerDay(FALSE).'</span></td>
        </tr>';

echo '</table>';
echo '</div></td></tr></table>';
echo '</p></div>';


global $wpdb;
$q='SELECT  COUNT( remote_addr ) as pc
FROM `'.$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW.'`
WHERE browsertype =0 
GROUP BY remote_addr';

$pc=$wpdb->get_results($q);
$allpages=$multipage=$onepage=0;
foreach($pc AS $pcl)
    if($pcl->pc!=1){$multipage+=1;}else{$onepage+=1;} 
$allpages=$multipage+$onepage;
if(($allpages/100)!=0){
    $bouncerate=$onepage/($allpages/100);
}else{
    $bouncerate=0;
    }
    echo '<div style="margin:5px;width:49%;float:right;">';
    echo '<h3>'.htmlspecialchars(__('Welcome to CyStats - a WordPress statistics plugin','cystats')).'</h3>
    <p>'.preg_replace(
        array(
            '=%CountFeedReaders%=','=%CountVisitors%=',
            '=%BounceRate%=','=%CountSearchengineReferer%=',
            '=%DonationLinkStart%=','=%DonationLinkEnd%='
            ),
        array(
            '<strong>'.cystats_countFeedreadersToday(FALSE).'</strong>',
            '<strong>'.cystats_countVisits(TODAY,FALSE).'</strong>',
            '<strong>'.sprintf("%01.2f",$bouncerate).'</strong>',
            '<strong>'.cystats_getSearchengineRefererCount(FALSE).'</strong>',
            '<strong><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=weingaertner%2emichael%40gmx%2ede&item_name=CyStats%20WordPress%20Statistik%20Plugin&no_shipping=1&no_note=1&tax=0&currency_code=EUR&lc=DE&bn=PP%2dDonationsBF&charset=UTF%2d8" title="Easy donation via PayPal">',
            '</a></strong>'
            ),
        htmlspecialchars(__(
        'You have approximately %CountFeedReaders% 
        visits to your feeds and %CountVisitors% human visitors to your website 
        today, the average bounce rate is %BounceRate%%, %CountSearchengineReferer% 
        visitors came via search engines. If you are using CyStats frequently 
        please consider making a %DonationLinkStart% donation via PayPal%DonationLinkEnd%.'
        ,'cystats'))).'</p></div>';

echo "<br style='clear:both;' />";















echo'<h2>'.htmlspecialchars(__('Hits and visits','cystats')).'</h2>';
    echo '<div style="margin:5px;width:49%;float:left;">';
        $r0[0]=$statistics->getNumberStats(CYSTATS_HITS_NOBOTS);
        $r0[1]=$statistics->getNumberStats(CYSTATS_VISITS_NOBOTS);
        $statistics->outputNumberStatsData($r0,FALSE,htmlspecialchars(__('Hits (without bots)','cystats')),TRUE);
    echo '</div>';
    echo '<div style="margin:5px;width:48%;float:right;">';
        $r[0]=$statistics->getNumberStats(CYSTATS_HITS);
        $r[1]=$statistics->getNumberStats(CYSTATS_VISITS);
        $r[0][0]['value']=$r[0][0]['value']-$r0[0][0]['value'];
        $r[1][0]['value']=$r[1][0]['value']-$r0[1][0]['value'];
        $statistics->outputNumberStatsData($r,FALSE,htmlspecialchars(__('Hits (bots)','cystats')),TRUE);
        unset($r);
    echo '</div>';

echo '<br style="clear:both;"/>';

    echo '<div style="margin:5px;width:49%;float:left;">';
        $r0[0]=$statistics->getNumberStats(CYSTATS_HITSDAY_NOBOTS," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".strtotime(gmdate('d-M-Y',$cystats_currenttime))."'");
        $r0[1]=$statistics->getNumberStats(CYSTATS_VISITSDAY_NOBOTS," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".strtotime(gmdate('d-M-Y',$cystats_currenttime))."'");    
        $r1[0]=$statistics->getNumberStats(CYSTATS_HITSDAY_NOBOTS," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".strtotime(gmdate('d-M-Y',$cystats_currenttime).' -1 day')."'");
        $r1[1]=$statistics->getNumberStats(CYSTATS_VISITSDAY_NOBOTS," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".strtotime(gmdate('d-M-Y',$cystats_currenttime).' -1 day')."'");    
        
        $statistics->outputNumberStatsData($r0,$r1,htmlspecialchars(__('Todays traffic (without bots)','cystats')));
        
    echo '</div>';
    echo '<div style="margin:5px;width:48%;float:right;">';
        $r[0]=$statistics->getNumberStats(CYSTATS_HITSDAY," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".strtotime(gmdate('d-M-Y',$cystats_currenttime))."'");
        $r[1]=$statistics->getNumberStats(CYSTATS_VISITSDAY," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".strtotime(gmdate('d-M-Y',$cystats_currenttime))."'");
        $r2[0]=$statistics->getNumberStats(CYSTATS_HITSDAY," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".strtotime(gmdate('d-M-Y',$cystats_currenttime).' -1 day')."'");
        $r2[1]=$statistics->getNumberStats(CYSTATS_VISITSDAY," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".strtotime(gmdate('d-M-Y',$cystats_currenttime).' -1 day')."'");    
        $r[0][0]['value']=$r[0][0]['value']-$r0[0][0]['value'];
        $r[1][0]['value']=$r[1][0]['value']-$r0[1][0]['value'];
        $r2[0][0]['value']=$r2[0][0]['value']-$r1[0][0]['value'];
        $r2[1][0]['value']=$r2[1][0]['value']-$r1[1][0]['value'];
        $statistics->outputNumberStatsData($r,$r2,htmlspecialchars(__('Todays traffic (bots)','cystats')));
        unset($r);
        unset($r0);
        unset($r1);
        unset($r2);
    echo '</div>';

    echo "<br style='clear:left;' />";

    echo '<div style="margin:5px;width:49%;float:left;">';
        #$r=$statistics->getTimeHours();
        #$statistics->outputTimeStatsData($r,htmlspecialchars(__('Visits per hour','cystats')));
        $r=$statistics->getDaily(0);#getYearDays();
        $statistics->outputTimeStatsData($r,htmlspecialchars(__('Visits per day','cystats')));
        unset($r);
    echo "</div>";
    echo '<div style="margin:5px;width:48%;float:right;">';
        $r=$statistics->getDaily(1);#getYearDays();
        $statistics->outputTimeStatsData($r,htmlspecialchars(__('Hits per day','cystats')));
        unset($r);
    echo "</div>";

    echo "<br style='clear:both;' />";


    echo '<div style="margin:5px;width:49%;float:left;">';
    	$lw=sprintf("%02d",(intval(gmdate('W',$cystats_currenttime))-1));
    	$last_week=($lw=='00')?'52':$lw;
    	$year=($last_week=='52')?sprintf ("%04d",(intval(gmdate('Y',$cystats_currenttime))-1)):sprintf ("%04d",(intval(gmdate('Y',$cystats_currenttime))));
    	$lm=sprintf ("%02d",(intval(gmdate('m',$cystats_currenttime))-1));
    	$last_month=($lm=='00')?'12':$lm;
    	$ly=sprintf ("%04d",(intval(gmdate('Y',$cystats_currenttime))-1));
    	$last_year=$ly;
    	
        $r0[0]=$statistics->getNumberStats(CYSTATS_HITSWEEK_NOBOTS," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".sprintf ("%02d",(intval(gmdate('W',$cystats_currenttime))))."'");
        $r0[1]=$statistics->getNumberStats(CYSTATS_VISITSWEEK_NOBOTS," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".sprintf ("%02d",(intval(gmdate('W',$cystats_currenttime))))."'");
        $r1[0]=$statistics->getNumberStats(CYSTATS_HITSWEEK_NOBOTS," val1='".$year."' AND val2='".$last_week."'");
        $r1[1]=$statistics->getNumberStats(CYSTATS_VISITSWEEK_NOBOTS," val1='".$year."' AND val2='".$last_week."'"); 
        $statistics->outputNumberStatsData($r0,$r1,htmlspecialchars(__('This weeks traffic (without bots)','cystats')));
        #var_dump($r0);var_dump($r1);
    echo '</div>';
    echo '<div style="margin:5px;width:48%;float:right;">';
        $r[0]=$statistics->getNumberStats(CYSTATS_HITSWEEK," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".gmdate('W',$cystats_currenttime)."'");
        $r[1]=$statistics->getNumberStats(CYSTATS_VISITSWEEK," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".gmdate('W',$cystats_currenttime)."'");
        $r2[0]=$statistics->getNumberStats(CYSTATS_HITSWEEK," val1='".$year."' AND val2='".$last_week."'");
        $r2[1]=$statistics->getNumberStats(CYSTATS_VISITSWEEK," val1='".$year."' AND val2='".$last_week."'"); 
        $r[0][0]['value']=$r[0][0]['value']-$r0[0][0]['value'];
        $r[1][0]['value']=$r[1][0]['value']-$r0[1][0]['value'];
        $r2[0][0]['value']=$r2[0][0]['value']-$r1[0][0]['value'];
        $r2[1][0]['value']=$r2[1][0]['value']-$r1[1][0]['value'];
        $statistics->outputNumberStatsData($r,$r2,htmlspecialchars(__('This weeks traffic (bots)','cystats')));
    echo '</div>';

    echo "<br style='clear:left;' />";

    echo '<div style="margin:5px;width:49%;float:left;">';
        $r0[0]=$statistics->getNumberStats(CYSTATS_HITSMONTH_NOBOTS," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".gmdate('m',$cystats_currenttime)."'");
        $r0[1]=$statistics->getNumberStats(CYSTATS_VISITSMONTH_NOBOTS," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".gmdate('m',$cystats_currenttime)."'");
        $r1[0]=$statistics->getNumberStats(CYSTATS_HITSMONTH_NOBOTS," val1='".$year."' AND val2='".$last_month."'");
        $r1[1]=$statistics->getNumberStats(CYSTATS_VISITSMONTH_NOBOTS," val1='".$year."' AND val2='".$last_month."'");
        $statistics->outputNumberStatsData($r0,$r1,htmlspecialchars(__('This months traffic (without bots)','cystats')));
    echo '</div>';
    echo '<div style="margin:5px;width:48%;float:right;">';
        $r[0]=$statistics->getNumberStats(CYSTATS_HITSMONTH," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".gmdate('m',$cystats_currenttime)."'");
        $r[1]=$statistics->getNumberStats(CYSTATS_VISITSMONTH," val1='".gmdate('Y',$cystats_currenttime)."' AND val2='".gmdate('m',$cystats_currenttime)."'");
        $r2[0]=$statistics->getNumberStats(CYSTATS_HITSMONTH," val1='".$year."' AND val2='".$last_month."'");
        $r2[1]=$statistics->getNumberStats(CYSTATS_VISITSMONTH," val1='".$year."' AND val2='".$last_month."'");
        $r[0][0]['value']=$r[0][0]['value']-$r0[0][0]['value'];
        $r[1][0]['value']=$r[1][0]['value']-$r0[1][0]['value'];
        $r2[0][0]['value']=$r2[0][0]['value']-$r1[0][0]['value'];
        $r2[1][0]['value']=$r2[1][0]['value']-$r1[1][0]['value'];
        $statistics->outputNumberStatsData($r,$r2,htmlspecialchars(__('This months traffic (bots)','cystats')));
    echo '</div>';

    echo "<br style='clear:left;' />";

    $r0=$r1=array();
    echo '<div style="margin:5px;width:49%;float:left;">';
        $r0[0]=$statistics->getNumberStats(CYSTATS_HITSYEAR_NOBOTS," val1='".gmdate('Y',$cystats_currenttime)."'");
        $r0[1]=$statistics->getNumberStats(CYSTATS_VISITSYEAR_NOBOTS," val1='".gmdate('Y',$cystats_currenttime)."'");
        $r1[0]=$statistics->getNumberStats(CYSTATS_HITSYEAR_NOBOTS," val1='".$last_year."'");
        $r1[1]=$statistics->getNumberStats(CYSTATS_VISITSYEAR_NOBOTS," val1='".$last_year."'");

        $statistics->outputNumberStatsData($r0,$r1,htmlspecialchars(__('This years traffic (without bots)','cystats')));
    echo '</div>';
    echo '<div style="margin:5px;width:48%;float:right;">';
        $r[0]=$statistics->getNumberStats(CYSTATS_HITSYEAR," val1='".gmdate('Y',$cystats_currenttime)."'");
        $r[1]=$statistics->getNumberStats(CYSTATS_VISITSYEAR," val1='".gmdate('Y',$cystats_currenttime)."'");
        $r2[0]=$statistics->getNumberStats(CYSTATS_HITSYEAR," val1='".$last_year."'");
        $r2[1]=$statistics->getNumberStats(CYSTATS_VISITSYEAR," val1='".$last_year."'");
        $r[0][0]['value']=$r[0][0]['value']-$r0[0][0]['value'];
        $r[1][0]['value']=$r[1][0]['value']-$r0[1][0]['value'];
        $r2[0][0]['value']=$r2[0][0]['value']-$r1[0][0]['value'];
        $r2[1][0]['value']=$r2[1][0]['value']-$r1[1][0]['value'];

        $statistics->outputNumberStatsData($r,$r2,htmlspecialchars(__('This years traffic (bots)','cystats')));
    echo '</div>';
    echo "<br style='clear:both;' />";

echo'<h2>'.htmlspecialchars(__('Pages and Comments','cystats')).'</h2>';

    echo '<div style="margin:5px;width:49%;float:left;">';
    $statistics->setLimit(20);
    $r=$statistics->getStatsData("page"," WHERE pagetype!=11 AND pagetype!=12 AND pagetype!=13 AND pagetype!=14 AND stamp >'".(strtotime('today 0:00'))."'");
    $statistics->outputStatsData($r,htmlspecialchars(__('Most visited blogpages today','cystats')));
    unset($r);
    echo '</div>';
    echo '<div style="margin:5px;width:48%;float:right;">';
    $statistics->setLimit(20);
    $r=$statistics->getStatsData("page"," WHERE pagetype!=11 AND pagetype!=12 AND pagetype!=13 AND pagetype!=14");
    $statistics->outputStatsData($r,htmlspecialchars(__('Most visited blogpages','cystats')));
    unset($r);
    echo '</div>';
    echo "<br style='clear:both;' />";





echo '<div style="margin:5px;width:49%;float:left;">';
echo '<table class="cystats" >';
echo '<tr><th class="cystats" style="text-align:left;">'.htmlspecialchars(__('Most commented posts','cystats')).'</th></tr>';
echo '<tr><td><div class="container_visits_raw_data"><table style="width:100%;">';
cystats_getMostCommented(20,'<tr><td class="cystats1" style="width:100%;">','</td></tr>');
echo '</table>';
echo '</div></td></tr></table>';
echo '</div>';

echo '<div style="margin:5px;width:48%;float:right;">';
    $r=$statistics->getStatsData("page","WHERE page!='' AND pagetype=12");
    $statistics->outputStatsData($r,htmlspecialchars(__('Most read feeds','cystats')));
    unset($r);
echo '</div>';




echo "<br style='clear:both;' />";

    echo '<h2>'.htmlspecialchars(__('Referer','cystats')).'</h2>';
    echo '<div style="margin:5px;width:49%;float:left;overflow:hidden;">';
        $r=$statistics->getStatsData("referer","WHERE referertype=0  AND referer!='' AND stamp>".(strtotime('today 0:00')));
        $statistics->outputStatsData($r,htmlspecialchars(__('Referer today','cystats')).' (<a class="th-link" href="admin.php?page=cystats/includes/admin.php&amp;cystats_allreferertoday" title="'.htmlspecialchars(__('Get more referers...','cystats')).'" >'.htmlspecialchars(__('More referers...','cystats')).'</a>)');
        unset($r);
    echo '</div>';
    echo '<div style="margin:5px;width:48%;float:right;overflow:hidden;">';
        $r=$statistics->getStatsData("referer","WHERE referertype=0  AND referer!='' AND stamp BETWEEN ".(strtotime('today 0:00')-(3600*24))." AND ".(strtotime('today 0:00')));
        $statistics->outputStatsData($r,htmlspecialchars(__('Referer yesterday','cystats')).' (<a class="th-link" href="admin.php?page=cystats/includes/admin.php&amp;cystats_allrefereryesterday" title="'.htmlspecialchars(__('Get more referers...','cystats')).'" >'.htmlspecialchars(__('More referers...','cystats')).'</a>)');
        unset($r);
    echo '</div>';
    echo "<br style='clear:both;' />";



echo '<h2>'.htmlspecialchars(__('Searchwords','cystats')).'</h2><p>';

    global $wpdb;
    $searchwords = $wpdb->get_results("
        SELECT DISTINCT(searchstring) AS item,
        COUNT(searchstring) AS itemcount
        FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW."
        WHERE searchstringtype=1
        AND searchstring!=''
        GROUP BY searchstring
        ORDER BY itemcount DESC
    ");
    $counter = $wpdb->get_var("
        SELECT COUNT(*)
        FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW."
        WHERE searchstringtype=1 
        AND searchstring!=''
    ");
    echo '<div style="margin:5px;width:49%;float:left;">';
    echo '<table class="cystats" >';
    echo '<tr><th class="cystats" colspan="4" style="text-align:left;">'.htmlspecialchars(__('External searchengine searchphrases','cystats')).'</th></tr>';
    echo '<tr><td><div style="height:250px;overflow:auto;"><table><tr><td>';
    
    $bar=0;
    foreach($searchwords AS $k=>$v){
        #echo '<br>'.$v->item.' '.$v->itemcount.' '.$counter;
        $pc = round(($v->itemcount/$counter * 100));
        if ($pc < 1)$bar = 1;
        elseif($pc > 99)$bar = 100;
        else $bar = $pc;        
        echo '<tr>
        <td class="cystats'.($k%2).'" style="width:100%;">'.str_replace(' ','&#160;',$statistics->cutString(htmlspecialchars(stripslashes($v->item)))).'</td>
        <td class="cystats'.($k%2).'"><span class="count_result">'.$v->itemcount.'</span></td>
        <td class="cystats'.($k%2).'">'.$pc.'%</td>
        <td class="cystats'.($k%2).'"><div style="height:1em;width:'.$bar.'px;background-image:url(../wp-content/plugins/cystats/gfx/bar.gif)">&nbsp;</div></td>
        </tr>';

    }
    echo '</div></td></tr></table>';
    echo '</table>';
    echo '</div>';


    $searchwords = $wpdb->get_results("
        SELECT DISTINCT(searchstring) AS item,
        COUNT(searchstring) AS itemcount
        FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW."
        WHERE searchstringtype=2
        AND searchstring!=''
        GROUP BY searchstring
        ORDER BY itemcount DESC
    ");
    $counterwp = $wpdb->get_var("
        SELECT COUNT(*)
        FROM ".$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW."
        WHERE searchstringtype=2 
        AND searchstring!=''
    ");
    echo '<div style="margin:5px;width:48%;float:right;">';
    echo '<table class="cystats" >';
    echo '<tr><th class="cystats" colspan="4" style="text-align:left;">'.htmlspecialchars(__('Internal blogsearch searchphrases','cystats')).'</th></tr>';
    echo '<tr><td><div style="height:250px;overflow:auto;"><table><tr><td>';

    $bar=0;
     foreach($searchwords AS $k=>$v){
        $pc = round(($v->itemcount/$counterwp * 100));
        if ($pc < 1)$bar = 1;
        elseif($pc > 99)$bar = 100;
        else $bar = $pc;        
        echo '
        <tr>
        <td class="cystats'.($k%2).'" style="width:100%;">'.str_replace(' ','&#160;',$statistics->cutString(htmlspecialchars(stripslashes($v->item)))).'</td>
        <td class="cystats'.($k%2).'"><span class="count_result">'.$v->itemcount.'</span></td>
        <td class="cystats'.($k%2).'">'.$pc.'%</td>
        <td class="cystats'.($k%2).'"><div style="height:1em;width:'.$bar.'px;background-image:url(../wp-content/plugins/cystats/gfx/bar.gif)">&nbsp;</div></td>
        </tr>';

    }
    echo '</div></td></tr></table>';
    echo '</table>';
echo '</div>';
echo '<br style="clear:both;"/>';
        unset($searchwords);



echo '<div style="margin:5px;width:49%;float:left;text-align:right;">';
?>
    <form name="form1" method="post" action="<?php echo $location; ?>">
        <input name="action" value="cystats_delete_searchwordstrings" type="hidden" />
        <input name="cystats_searchwordstrings_delete" type="submit" value="<?php htmlspecialchars(_e('Delete searchwordstrings','cystats'));?>"/>
        <span style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Delete all searchword strings','cystats')).'&#160;('.$counter.')';?></span>
    </form>
<?php
echo '</div>';



echo '<div style="margin:5px;width:48%;float:right;text-align:right;">';
?>
    <form name="form1" method="post" action="<?php echo $location; ?>">
        <input name="action" value="cystats_delete_wp_searchwordstrings" type="hidden" />
        <input name="cystats_searchwordwpstrings_delete" type="submit" value="<?php htmlspecialchars(_e('Delete searchwordstrings','cystats'));?>"/>
        <span style="display:block;color:#a4a4a4;font-size:.8em;padding-left:1em;"><?php echo htmlspecialchars(_e('Delete all searchword strings','cystats')).'&#160;('.$counterwp.')';?></span>
    </form>
<?php
echo '</div>';

echo '<br class="br_clear"/>';








    #echo "-->";
    echo '<div style="margin:5px;width:99%;">';
        $r=$statistics->getVisitsData('remote_addr','0,100');
        $statistics->outputVisitsData($r,htmlspecialchars(__('Last hits','cystats')).' (<a class="th-link" href="admin.php?page=cystats/includes/admin.php&amp;cystats_allvisits" title="'.htmlspecialchars(__('Get more visits...','cystats')).'" >'.htmlspecialchars(__('More visits...','cystats')).'</a>)');
    echo '</div>';
}

echo '</div>';
?>

