<?php
/*
Plugin Name: wpSLEEP
Text Domain: wpsleep
Domain Path: /lang
Description: Easy date and time control for custom parts of a blog post. Graphical and textual elements can be hide or unhide at a specific time.
Author: Sergej M&uuml;ller
Author URI: http://www.wpSEO.org
Plugin URI: http://playground.ebiene.de/498/wordpress-plugin-wpsleep-time-control-for-parts-of-a-post/
Version: 0.6
*/


if (!function_exists ('is_admin')) {
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}
class wpSLEEP {
function wpSLEEP() {
if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) OR (defined('DOING_AJAX') && DOING_AJAX) OR (defined('DOING_CRON') && DOING_CRON)) {
return;
}
$this->visible = false;
add_shortcode(
'wpsleep',
array(
$this,
'exe'
)
);
}
function exe($attr, $content) {
$start = @$attr['start'];
$end = @$attr['end'];
$alt = @$attr['alt'];
$time = current_time('timestamp');
$regtime = '/^([\d]{1,2}+):([\d]{1,2}+)$/';
$regdate = '/^([\d]{1,2}+)[\.]+([\d]{1,2}+)[\.]+([\d]{4})([\s]*([\d]{1,2}+):([\d]{1,2}+))?$/';
if ($start) {
if (preg_match($regtime, $start, $sdata) && $time >= mktime($sdata[1], $sdata[2])) {
if ($end && preg_match($regtime, $end, $edata) && $time >= mktime($edata[1], $edata[2])) {
return $alt;
}
$this->visible = true;
return $content;
} else if (preg_match($regdate, $start, $sdata) && checkdate($sdata[2], $sdata[1], $sdata[3]) && $time >= mktime($sdata[5], $sdata[6], 0, $sdata[2], $sdata[1], $sdata[3])) {
if ($end && preg_match($regdate, $end, $edata) && checkdate($edata[2], $edata[1], $edata[3]) && $time >= mktime($edata[5], $edata[6], 0, $edata[2], $edata[1], $edata[3])) {
return $alt;
}
$this->visible = true;
return $content;
}
return $alt;
} else if ($end && preg_match($regdate, $end, $edata) && checkdate($edata[2], $edata[1], $edata[3]) && $time < mktime($edata[5], $edata[6], 0, $edata[2], $edata[1], $edata[3])) {
$this->visible = true;
return $content;
}
return $alt;
}
}
$GLOBALS['wpSLEEP'] = new wpSLEEP();