<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
$install = file_get_contents(CACHE_ROOT.'/install.dat');
$url = 'http://www.destoon.com/client.php?action='.$action.'&product=b2b&version='.DT_VERSION.'&release='.DT_RELEASE.'&charset='.$CFG['charset'].'&install='.$install.'&os='.PHP_OS.'&soft='.urlencode($_SERVER['SERVER_SOFTWARE']).'&php='.urlencode(phpversion()).'&mysql='.urlencode(mysql_get_server_info()).'&url='.urlencode($DT_URL).'&site='.urlencode($DT['sitename']).'&auth='.strtoupper(md5($DT_URL.$install.$_SERVER['SERVER_SOFTWARE']));
dheader($url);
?>