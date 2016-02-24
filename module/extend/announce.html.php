<?php 
defined('IN_DESTOON') or exit('Access Denied');
if(!$MOD['announce_html'] || !$itemid) return false;
$item = $db->get_one("SELECT * FROM {$DT_PRE}announce WHERE itemid=$itemid AND islink=0");
if(!$item) return false;
extract($item);
$TYPE = get_type('announce', 1);
$adddate = timetodate($addtime, 3);
$fromdate = $fromtime ? timetodate($fromtime, 3) : '不限';
$todate = $totime ? timetodate($totime, 3) : '不限';

$head_title = $head_keywords = $head_description = $title.$DT['seo_delimiter'].'公告中心';

$destoon_task = "moduleid=$moduleid&html=announce&itemid=$itemid";
$template = $item['template'] ? $item['template'] : 'announce';
ob_start();
include template($template, $module);
$data = ob_get_contents();
ob_clean();
file_put(DT_ROOT.'/announce/'.$itemid.'.'.$DT['file_ext'], $data);
return true;
?>