<?php
// This is only here to give a good example of a custom feed.
$day = '';
if (count($events))
{
    $today = date('m d Y');
    $yesterday = date('m d Y', time()-86400);
    ?>
    <?php
    foreach ($events as $result)
    {
        $timestamp = $result->get_date();
        if ($today == date('m d Y', $timestamp)) $this_day = 'Today';
        else if ($yesterday == date('m d Y', $timestamp)) $this_day = 'Yesterday';
        else $this_day = ucfirst(htmlentities(date($day_format, $timestamp)));
        
        list($label, $items) = $result->feed->get_render_output($result);
        ?>

			<?php if ( $day != $this_day ) { ?><h3><?php echo $this_day; ?></h3><?php $day = $this_day; } ?>
			
			<ul class="lifestream-item lifestream_feedid_<?php echo $result->feed->get_constant('ID'); ?>">
				<li class="lifestream-content"><?php if ($result->feed->options['show_label'] || count($items) > 1) { echo $label; } ?><?php if (count($items) == 1) { ?><p><?php echo $items[0]; ?></p><?php } else { printf(' <small class="lifestream_more">(<a href="#" onclick="lifestream_toggle(this, \'%1$d\', \'%2$s\', \'%3$s\');return false;">%2$s</a>)</small><div class="lifestream_events">%4$s</div>', $result->id, __('Show Details', 'lifestream'), __('Hide Details', 'lifestream'), $result->feed->render_group_items($result->id, $items, $result)); } ?></li>
			</ul>        
        
        <?php
    } ?>
    <?php
}
else
{
    ?>
    <p class="lifestream"><?php _e('There are no events to show at this time.', 'lifestream'); ?></p>
    <?php
}
?>
