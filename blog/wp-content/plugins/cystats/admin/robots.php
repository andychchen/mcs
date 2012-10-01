<?php
echo '<div class="wrap">';
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

echo '<div style="margin:5px;width:48%;float:left;">';
$r=$statistics->getStatsData("browser","WHERE browsertype='".CYSTATS_TOOL."'");
$statistics->outputStatsData($r,htmlspecialchars(__('Tools & Scripts','cystats')),FALSE,'browser');
echo '</div>';
echo '<div style="margin:5px;width:48%;float:right;">';
$r=$statistics->getStatsData("http_user_agent","WHERE browsertype='".CYSTATS_UNKNOWN."'");
$statistics->outputStatsData($r,htmlspecialchars(__('Unknown user agents','cystats')));
echo '</div>';

echo "<br style='clear:left;' />";
echo '</div>';
?>

