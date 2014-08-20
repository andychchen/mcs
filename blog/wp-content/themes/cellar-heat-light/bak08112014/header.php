<?php
function get_data($url) {
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<link rel="stylesheet" href="http://www.michiganchineseschool.org/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!--[if IE 6]>
<style type="text/css">
img, div, h1, h2, h3, h4 { 
	behavior: url(<?php bloginfo('stylesheet_directory'); ?>/iepngfix.htc);
}
</style>
<![endif]-->
<?php wp_head(); ?>
</head>
  <body leftmargin="0" topmargin="0" style="background-color: rgb(115,
    91, 61);" marginheight="0" marginwidth="0">
    <center>
      <table id="container" border="0" cellpadding="0" cellspacing="0"
        width="1043">
        <tbody>
          <tr>
            <td colspan="3" height="135">
              <table id="up" border="0" cellpadding="0" cellspacing="0"
                height="135" width="1042">
                <tbody>
                  <tr>
                    <td height="85" width="230"><img
                        src="http://www.michiganchineseschool.org/images/mcs_titleL.jpg" alt="" height="203"
                        width="224"></td>
                    <td height="85" width="663"> <img
                        src="http://www.michiganchineseschool.org/images/mcs_titleC.jpg" alt="" height="223"
                        width="644"></td>
                    <td colspan="2" height="85">
                      <p>&nbsp; </p>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="4" bgcolor="#a8c1e5" height="10"> <img
                        src="http://www.michiganchineseschool.org/images/spacer.gif" alt="" height="10"
                        width="900"></td>
                  </tr>
                  <tr>
                    <td height="24" width="230"> <img
                        src="http://www.michiganchineseschool.org/images/bg_05.jpg" alt="" height="24"
                        width="230"></td>
                    <td height="24" width="663"> <img
                        src="http://www.michiganchineseschool.org/images/bg_06.gif" alt="" height="24"
                        width="663"></td>
                    <td height="24" width="150"><img
                        src="http://www.michiganchineseschool.org/images/bg_08.jpg" alt="" height="24"
                        width="150"></td>
                  </tr>
                  <tr>
                    <td colspan="4" height="16"> <img
                        src="http://www.michiganchineseschool.org/images/spacer.gif" alt="" height="16"
                        width="900"></td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
  <tr>
            <td id="left" height="523" valign="top" width="200"><?php echo(get_data('http://www.michiganchineseschool.org/menu.html')); ?>
              <br>
            </td>
            <td colspan="2" id="contents" align="left" height="523"
              valign="top">
   