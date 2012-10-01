<?php
echo'<div class="wrap">';
echo'<h2>'.htmlspecialchars(__('CyStats Pages','cystats')).'</h2>';


echo'<p>';
    $statistics->setLimit(20);
    $r=$statistics->getStatsData("page"," WHERE pagetype!=11 AND pagetype!=12 AND pagetype!=13 AND pagetype!=14 AND stamp >'".(strtotime('today 0:00'))."'");
    $statistics->outputStatsData($r,htmlspecialchars(__('Most visited blogpages today','cystats')));
echo'</p>';


echo'<p>';
    $statistics->setLimit(20);
    $r=$statistics->getStatsData("page"," WHERE pagetype!=11 AND pagetype!=12 AND pagetype!=13 AND pagetype!=14");
    $statistics->outputStatsData($r,htmlspecialchars(__('Most visited blogpages','cystats')));
echo'</p>';


echo'<p>';
    $statistics->setLimit(20);
    $r=$statistics->getStatsData("entry","");
    $statistics->outputStatsData($r,htmlspecialchars(__('Entrypages','cystats')));
echo'</p>';


echo'<p>';
    $statistics->setLimit(20);
    $r=$statistics->getStatsData("page"," WHERE pagetype=11 ");
    $statistics->outputStatsData($r,htmlspecialchars(__('404 error pages','cystats')));
echo'</p>';

echo'<p>';
    $statistics->setLimit(30);
    $r=$statistics->getStatsData("pagetype","");
    $statistics->outputStatsData($r,htmlspecialchars(__('Pagetypes','cystats')));
echo'</p>';

echo'</div>';
?>

