<?php
echo '<div class="wrap">';

if($_GET['month']||$_GET['day']||$_GET['year']){
    echo "<h2>".htmlspecialchars(__('CyStats Time/Posts','cystats'))."</h2>";
    $d=intval($_GET['day']);
    $m=intval($_GET['month']);
    $y=intval($_GET['year']);
    $statistics->getPostsByDate(FALSE,$d,$m,$y);
}

else{
echo "<h2>".htmlspecialchars(__('CyStats Time','cystats'))."</h2>";

    echo '<div style="margin:5px;width:48%;float:left;">';
        #$r=$statistics->getTimeHours();
        #$statistics->outputTimeStatsData($r,htmlspecialchars(__('Visits per hour','cystats')));
        $r=$statistics->getDaily(0);#getYearDays();
        $statistics->outputTimeStatsData($r,htmlspecialchars(__('Visits per day','cystats')));
    echo "</div>";
    echo '<div style="margin:5px;width:48%;float:right;">';
        $r=$statistics->getDaily(1);#getYearDays();
        $statistics->outputTimeStatsData($r,htmlspecialchars(__('Hits per day','cystats')));
    echo "</div>";

echo "<br style=\"clear:both;\" />";

    echo '<div style="margin:5px;width:48%;float:left;">';
        $r=$statistics->getTimeHours();
        $statistics->outputTimeStatsData($r,htmlspecialchars(__('Visits per hour (live database table)','cystats')));
    echo "</div>";
    echo '<div style="margin:5px;width:48%;float:right;">';
        #$r=$statistics->getYearDays();
        #$statistics->outputTimeStatsData($r,'Zugriffe/Tage (Live) ');
        $r=$statistics->getWeekDays(True);
        $statistics->outputTimeStatsData($r,htmlspecialchars(__('Visits per weekday (live database table)','cystats')));
    echo "</div>";

echo "<br style=\"clear:both;\" />";

    echo '<div style="margin:5px;width:48%;float:left;">';
        $r=$statistics->getNumberStats(CYSTATS_VISITSWEEK);
        $statistics->outputTimeStatsData($r,htmlspecialchars(__('Visits per week','cystats')));
    echo "</div>";

    echo '<div style="margin:5px;width:48%;float:right;">';
        $r=$statistics->getNumberStats(CYSTATS_HITSWEEK);
        $statistics->outputTimeStatsData($r,htmlspecialchars(__('Hits per week','cystats')));
    echo "</div>";
echo "<br style=\"clear:both;\" />";




    echo '<div style="margin:5px;width:48%;float:left;">';
        $r=$statistics->getNumberStats(CYSTATS_VISITSMONTH);
        $statistics->outputTimeStatsData($r,htmlspecialchars(__('Visits per month','cystats')));
    echo "</div>";

    echo '<div style="margin:5px;width:48%;float:right;">';
        $r=$statistics->getNumberStats(CYSTATS_HITSMONTH);
        $statistics->outputTimeStatsData($r,htmlspecialchars(__('Hits per month','cystats')));
    echo "</div>";
echo "<br style=\"clear:both;\" />";

    echo '<div style="margin:5px;width:48%;float:left;">';
        $r=$statistics->getNumberStats(CYSTATS_VISITSYEAR);
        $statistics->outputTimeStatsData($r,htmlspecialchars(__('Visits per year','cystats')));
    echo "</div>";

    echo '<div style="margin:5px;width:48%;float:right;">';
        $r=$statistics->getNumberStats(CYSTATS_HITSYEAR);#," val1='".gmdate('Y',time())."' "
        $statistics->outputTimeStatsData($r,htmlspecialchars(__('Hits per year','cystats')));
    echo "</div>";
echo "<br style=\"clear:both;\" />";
}
?>
</div>
