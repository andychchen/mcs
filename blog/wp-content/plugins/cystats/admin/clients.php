<?php
echo '<div class="wrap">';
echo "<h2>".htmlspecialchars(__('CyStats Clients','cystats'))."</h2>";

if(isset($_GET['cystats_allos'])){
    global $wpdb;
    $statistics->setLimit($wpdb->get_var('SELECT COUNT(*) FROM '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW));
        $r=$statistics->getStatsData('os');
        $statistics->outputStatsData($r,htmlspecialchars(__('Operating system','cystats')),'400px','os');
}elseif(isset($_GET['cystats_allbrowser'])){
    global $wpdb;
    $statistics->setLimit($wpdb->get_var('SELECT COUNT(*) FROM '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW));
    $statistics->set_browser_full_info(FALSE);
    $r=$statistics->getStatsData("browser","WHERE browsertype='".CYSTATS_BROWSER."'");
    
    $statistics->outputStatsData($r,htmlspecialchars(__('Browser','cystats')),'400px','browser');
    $statistics->set_browser_full_info(TRUE);
}elseif(isset($_GET['cystats_allbrowserversion'])){
    global $wpdb;
    $statistics->setLimit($wpdb->get_var('SELECT COUNT(*) FROM '.$wpdb->prefix.CYSTATS_TABLE_STATISTICS_RAW));
    $r=$statistics->getStatsData("browser","WHERE browsertype='".CYSTATS_BROWSER."'");
    $statistics->outputStatsData($r,htmlspecialchars(__('Browser versions','cystats')),'400px','browser');
}else{
    echo '<div style="margin:5px;width:48%;float:left;">';
        $statistics->set_browser_full_info(FALSE);
        $r=$statistics->getStatsData("browser","WHERE browsertype='".CYSTATS_BROWSER."'");
        $statistics->set_browser_full_info(TRUE);
        $statistics->outputStatsData($r,htmlspecialchars(__('Browser','cystats')).' (<a class="th-link" href="admin.php?page=cystats-clients&amp;cystats_allbrowser" title="'.htmlspecialchars(__('More...','cystats')).'" >'.htmlspecialchars(__('More...','cystats')).'</a>)',FALSE,'browser');
    echo '</div>';

    echo '<div style="margin:5px;width:48%;float:right;">';
        $r=$statistics->getStatsData("os");
        $statistics->outputStatsData($r,htmlspecialchars(__('Operating system','cystats')).' (<a class="th-link" href="admin.php?page=cystats-clients&amp;cystats_allos" title="'.htmlspecialchars(__('More...','cystats')).'" >'.htmlspecialchars(__('More...','cystats')).'</a>)',FALSE,'os');
    echo '</div>';


    echo "<br style='clear:both;' />";

    echo '<div style="margin:5px;width:48%;float:left;">';
        $r=$statistics->getStatsData("browser","WHERE browsertype='".CYSTATS_BROWSER."'");
        $statistics->outputStatsData($r,htmlspecialchars(__('Browser versions','cystats')).' (<a class="th-link" href="admin.php?page=cystats-clients&amp;cystats_allbrowserversion" title="'.htmlspecialchars(__('More...','cystats')).'" >'.htmlspecialchars(__('More...','cystats')).'</a>)',FALSE,'browser');
    echo '</div>';



echo '<div style="margin:5px;width:48%;float:right;">';
$r=$statistics->getStatsData("browser","WHERE browsertype='".CYSTATS_TOOL."'");
$statistics->outputStatsData($r,htmlspecialchars(__('Tools & Scripts','cystats')),FALSE,'browser');
echo '</div>';

    echo "<br style='clear:both;' />";

echo '<h2>'.htmlspecialchars(__('CyStats Robots & Tools','cystats')).'</h2><p>';
$statistics->setLimit(get_option('cystats_rawtable_max'));

echo '<div style="margin:5px;width:48%;float:left;">';
$r=$statistics->getStatsData("browser","WHERE browsertype='".CYSTATS_SEARCH."'");
$statistics->outputStatsData($r,htmlspecialchars(__('Searchengines','cystats')),FALSE,'browser');
echo '</div>';
echo '<div style="margin:5px;width:48%;float:right;">';
$r=$statistics->getStatsData("browser","WHERE browsertype='".CYSTATS_FEED."'");
$statistics->outputStatsData($r,htmlspecialchars(__('Email / Feedreader','cystats')),FALSE,'browser');
echo '</div>';

echo "<br style='clear:left;' />";

echo '<div style="margin:5px;width:99%;">';
$r=$statistics->getStatsData("http_user_agent","WHERE browsertype='".CYSTATS_UNKNOWN."'");
$statistics->outputStatsData($r,htmlspecialchars(__('Unknown user agents','cystats')));
echo '</div>';

echo "<br style='clear:left;' />";

}
echo '</div>';

?>
