<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
if($_userid) {
	if($_groupid < 5) message('您所在的会员组不能申请', $MODULE[2]['linkurl']);
	$r = $db->get_one("SELECT vip FROM {$DT_PRE}company WHERE userid=$_userid");
	if($r['vip']) message('您已经是'.VIP.'会员了了，请不要重复申请', $MODULE[2]['linkurl']);
	$r = $db->get_one("SELECT itemid,status,note FROM {$DT_PRE}vip WHERE username='$_username'");
	if($r) {
		if($r['status'] == 1) message('抱歉，您的申请已经被拒绝'.($r['note'] ? '<br>理由是:'.$r['note'].'<br>如果要继续申请，请与本站联系' : ''), $MODULE[2]['linkurl'], 5);
		if($r['status'] == 2) message('您已经申请过了，请不要重复提交', $MODULE[2]['linkurl']);
	}
}
if($submit) {
	login();
	$content or message('内容不能为空');
	$content = dhtmlspecialchars($content);
	$db->query("INSERT INTO {$DT_PRE}vip (content,userid,username,company,addtime,status) VALUES ('$content','$_userid','$_username', '$_company', '$DT_TIME', '2')");
	 message('您的申请已经成功提交，稍后会有客户经理与您取得联系', $MOD['linkurl'], 10);
} else {
	$GROUPS = array();
	$GROUP = cache_read('group.php');
	foreach($GROUP as $k=>$v) {
		if($k > 4) {
			$G = cache_read('group-'.$k.'.php');
			$G['moduleids'] = isset($G['moduleids']) ? explode(',', $G['moduleids']) : array();
			$GROUPS[$k] = $G;
		}
	}
	$cols = count($GROUPS)+1;
	$percent = dround(100/$cols).'%';
	if($_userid) {
		$r = $db->get_one("SELECT * FROM {$DT_PRE}member m,{$DT_PRE}company c WHERE m.userid=c.userid AND m.userid=$_userid");		
	}
	$DM = $MODULE;
	$DM[9]['name'] = '招聘';
	$DM[-9]['moduleid'] = -9;
	$DM[-9]['name'] = '简历';
	$DM[-9]['linkurl'] = $DM[9]['linkurl'];
	$head_title = VIP.'服务';
	include template('vip', $module);
}
?>