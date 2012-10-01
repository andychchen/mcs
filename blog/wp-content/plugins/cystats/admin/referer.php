<?php
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
}
else{
    echo '<h2>'.__('CyStats Referer').'</h2><p>';
  echo "<p>";
        $r=$statistics->getStatsData("referer","WHERE referertype=0  AND referer!='' AND stamp>".(strtotime('today 0:00')));
        $statistics->outputStatsData($r,htmlspecialchars(__('Referer today','cystats')).' (<a class="th-link" href="admin.php?page=cystats-referer&amp;cystats_allreferertoday" title="'.htmlspecialchars(__('Get more referers...','cystats')).'" >'.htmlspecialchars(__('More referers...','cystats')).'</a>)');
    echo "</p>";

    echo "<p>";
        $r=$statistics->getStatsData("referer","WHERE referertype=0  AND referer!='' AND stamp BETWEEN ".(strtotime('today 0:00')-(3600*24))." AND ".(strtotime('today 0:00')));
        $statistics->outputStatsData($r,htmlspecialchars(__('Referer yesterday','cystats')).' (<a class="th-link" href="admin.php?page=cystats-referer&amp;cystats_allrefereryesterday" title="'.htmlspecialchars(__('Get more referers...','cystats')).'" >'.htmlspecialchars(__('More referers...','cystats')).'</a>)');
    echo "</p>";
        echo "<p>";
        $r=$statistics->getStatsData("referer","WHERE referertype=0  AND referer!=''");
        $statistics->outputStatsData($r,htmlspecialchars(__('External referer','cystats')).' (<a class="th-link" href="admin.php?page=cystats-referer&amp;cystats_allreferer" title="'.htmlspecialchars(__('Get more referers...','cystats')).'" >'.htmlspecialchars(__('More referers...','cystats')).'</a>)');
    echo "</p><p>";
        $r=$statistics->getStatsData("referer","WHERE referertype=1  AND referer!=''");
        $statistics->outputStatsData($r,htmlspecialchars(__('Searchengine referer','cystats')).' (<a class="th-link" href="admin.php?page=cystats-referer&amp;cystats_allsearchenginereferer" title="'.htmlspecialchars(__('Get more referers...','cystats')).'" >'.htmlspecialchars(__('More referers...','cystats')).'</a>)');
    echo "</p>";
  

    if(get_option('cystats_localreferer_tracking')==1){
        echo "<p>";
            $r=$statistics->getStatsData("referer","WHERE referertype=2  AND referer!=''");
            $statistics->outputStatsData($r,htmlspecialchars(__('Internal referer','cystats')));
        echo '</p>';    
    }
}
echo '</div>';
?>

