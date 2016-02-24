<?php
defined('IN_DESTOON') or exit('Access Denied');
if($dir) dir_delete(DT_ROOT.'/'.$dir);
$db->query("DROP TABLE IF EXISTS `".$DT_PRE."exhibit`");
$db->query("DROP TABLE IF EXISTS `".$DT_PRE."exhibit_data`");
?>