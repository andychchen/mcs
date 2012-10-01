<?php
echo '<div class="wrap">';
echo "<h2>".htmlspecialchars(__('CyStats Blog','cystats'))."</h2>";


echo '<div style="margin:5px;width:48%;float:left;">';
echo '<table class="cystats">';
echo '<tr><th class="cystats" style="text-align:left;">'.htmlspecialchars(__('Recent commented posts','cystats')).'</th></tr>';
echo '<tr><td><div class="container_visits_raw_data" style="width:100%;"><table style="width:100%;">';
cystats_getRecentCommented(20,'<tr><td class="cystats1">','</td></tr>');
echo '</table>';
echo '</div></td></tr></table>';
echo '</div>';


echo '<div style="margin:5px;width:48%;float:right;">';
echo '<table class="cystats" >';
echo '<tr><th class="cystats" colspan="4" style="text-align:left;">'.htmlspecialchars(__('Most active comment authors','cystats')).'</th></tr>';
echo '<tr><td><div class="container_visits_raw_data"><table style="width:100%;">';
cystats_getTopCommenters(20,'<tr><td class="cystats1">','</td></tr>');
echo '</table>';
echo '</div></td></tr></table>';
echo '</div>';
echo "<br style='clear:left;' />";



echo '<div style="margin:5px;width:48%;float:left;">';
        $r=$statistics->getTagsData();
        $statistics->outputSimpleStatsData($r,9999,htmlspecialchars(__('Tags','cystats')));
    echo '</div>';
echo '<div style="margin:5px;width:48%;float:right;">';
        $r=$statistics->getCategoriesData();
        $statistics->outputSimpleStatsData($r,9999,htmlspecialchars(__('Categories','cystats')));
    echo '</div>';


echo "<br style='clear:left;' />";
echo '<div style="margin:5px;width:99%;">';
echo '<table class="cystats" style="width:100%;"><tr>';
echo '<th colspan="6" class="cystats" style="text-align:left;">'.htmlspecialchars(__('Database statistics','cystats')).'</th>';
echo '</tr>';
echo '<tr><td><div class="container_visits_raw_data"><table class="sortable"><thead>';
echo '<tr>
<th class="sorttable_nosort">&#160;</th>
<th>'.str_replace(' ','&#160;',htmlspecialchars(__('Rows','cywhale'))).'</th>
<th>'.str_replace(' ','&#160;',htmlspecialchars(__('Avg. row length','cywhale'))).'</th>
<th>'.str_replace(' ','&#160;',htmlspecialchars(__('Data length','cywhale'))).'</th>
<th>'.str_replace(' ','&#160;',htmlspecialchars(__('Index length','cywhale'))).'</th>
<th>'.str_replace(' ','&#160;',htmlspecialchars(__('Reserved length','cywhale'))).'</th>
</tr></thead>';
$statistics->get_database_stats();
echo '</table></div>';
echo '</td></tr></table>';
echo '</div>';
echo "<br style='clear:left;' />";

echo '</div>';
?>
