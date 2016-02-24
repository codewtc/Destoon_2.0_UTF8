<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$MG['mail'] or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');
$TYPE = get_type('mail', 1);
$TYPE or message('系统暂未启用商机订阅');
foreach($TYPE as $k=>$v) {
	$TYPE[$k]['typename'] = set_style($v['typename'], $v['style']);
}
$r = $db->get_one("SELECT * FROM {$DT_PRE}mail_list WHERE username='$_username'");
switch($action) {
	case 'cancel':
		if($r) {
			$db->query("DELETE FROM {$DT_PRE}mail_list WHERE username='$_username'");
		} else {
			message('您尚未订阅任何商机');
		}
		dmsg('退订成功', $MOD['linkurl'].'mail.php?success=1');
	break;
	case 'show':
		$itemid or message();
		$r = $db->get_one("SELECT * FROM {$DT_PRE}mail WHERE itemid=$itemid");
		$r or message('邮件列表不存在');
		$r['editdate'] = timetodate($r['edittime'], 5);
		$r['adddate'] = timetodate($r['addtime'], 5);
	break;
	case 'list':
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}mail");
		$pages = pages($r['num'], $page, $pagesize);		
		$mails = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}mail ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['editdate'] = timetodate($r['edittime'], 5);
			$r['adddate'] = timetodate($r['addtime'], 5);
			$mails[] = $r;
		}
		$head_title = '邮件列表';
	break;
	default:
		if($submit) {
			(isset($typeids) && is_array($typeids) && $typeids) or message('请选择商机分类，如果要取消订阅，请直接点击退订按钮', $MOD['linkurl'].'mail.php');
			foreach($typeids as $t) {
				$_typeids .= intval($t).',';
			}
			$_typeids = ','.$_typeids;
			if($r) {
				$db->query("UPDATE {$DT_PRE}mail_list SET email='$_email',typeids='$_typeids',edittime='$DT_TIME' WHERE username='$_username'");
			} else {
				$db->query("INSERT INTO {$DT_PRE}mail_list (username,email,typeids,addtime,edittime) VALUES ('$_username','$_email','$_typeids','$DT_TIME','$DT_TIME')");
			}
			dmsg('订阅更新成功', 'mail.php?success=1');
		} else {
			$mytypeids = array();
			if($r) {
				$r['typeids'] = substr($r['typeids'], 1, -1);
				$mytypeids = explode(',', $r['typeids']);
				$addtime = timetodate($r['addtime'], 5);
				$edittime = timetodate($r['edittime'], 5);
			}
			$_TYPE = $TYPE;
			$TYPE = array();
			foreach($_TYPE as $v) {
				$TYPE[] = $v;
			}
			$head_title = '我的订阅';
		}
	break;
}
include template('mail', $module);
?>