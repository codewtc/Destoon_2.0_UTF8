<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$itemid or message('', $MOD['linkurl']);
login();

if(!check_group($_groupid, $MOD['group_talent'])) {
	$head_title = '抱歉，您所在的会员组没有权限访问此页面';
	exit(include template('noright', 'message'));
}

$item = $db->get_one("SELECT * FROM {$DT_PRE}resume WHERE itemid=$itemid AND status=3");
$item or message('', $MOD['linkurl']);
if($item['open'] != 3) message('此简历已经关闭', $MOD['linkurl']);
if($item['username'] == $_username) message('您不能向添加自己', $MOD['linkurl']);

$linkurl = $MOD['linkurl'].$item['linkurl'];
$item = $db->get_one("SELECT * FROM {$DT_PRE}job_talent WHERE resumeid=$itemid AND username='$_username'");
if($item) message('此简历已经存在于人才库', $linkurl);
$db->query("INSERT INTO {$DT_PRE}job_talent (resumeid,username,jointime) VALUES ('$itemid','$_username','$DT_TIME')");
message('添加成功', $linkurl);
?>