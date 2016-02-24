<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
$filename = DT_ROOT.'/'.$DT['index'].'.'.$DT['file_ext'];
if(!$DT['index_html']) {
	if(is_file($filename)) unlink($filename);
	return false;
}
$destoon_task = "moduleid=1&html=index";
$AREA = cache_read('area.php');
$LETTER = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
$seo_title = $DT['seo_title'];
ob_start();
include template('index');
$data = ob_get_contents();
ob_clean();
file_put($filename, $data);
return true;
?>