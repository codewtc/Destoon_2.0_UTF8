<?php 
defined('IN_DESTOON') or exit('Access Denied');
$r = $db->get_one("SELECT content FROM {$DT_PRE}company_data WHERE userid=$COM[userid]");
$content = $r['content'];
$COM['thumb'] = $COM['thumb'] ? $COM['thumb'] : SKIN_PATH.'image/company.jpg';
include template('introduce', $template);
?>