<?php 
defined('IN_DESTOON') or exit('Access Denied');
$could_contact or dalert('您所在的会员组无权查看联系方式', 'goback');
$could_message = check_group($_groupid, $MOD['group_message']);
if($username == $_username || $domain) $could_message = true;
include template('contact', $template);
?>