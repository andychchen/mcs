<?php
/*
Plugin Name: footerURLGen
Plugin URI: http://www.netfirms.com
Description: Adds content to footer
Version: 1.0
Author: Barry Taylor (Netfirms)
Author URI: http://www.netfirms.com
*/
add_filter ('wp_footer', 'footerURLGen');

function footerURLGen ()
{
  $content = @file_get_contents("/usr/local/conf/footers/wordpress.txt");
  if ($content !== false) {
    echo $content;
  } else {
    echo '<div style="clear: both;padding: 5px;text-align: center;">';
    echo 'Powered by <a href="http://www.netfirms.com/">Netfirms</a>';
    echo '</div>';
  }
}
?>
