<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';

$MG['ask'] or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');

require DT_ROOT.'/include/post.func.php';
$TYPE = get_type('ask', 1);
$TYPE or message('系统暂未启用客服中心');
$forward or $forward = $MOD['linkurl'].'ask.php';
$dstatus = array('待受理', '<span style="color:blue;">受理中</span>', '<span style="color:green;">已解决</span>', '<span style="color:red;">未解决</span>');
switch($action) {
	case 'add':
		if($submit) {
			$typeid = intval($typeid);
			if(!$typeid || !isset($TYPE[$typeid])) message('请选择分类');
			if(empty($title)) message('请填写标题');
			if(empty($content)) message('请填写内容');
			$fields = array(
				'typeid' => $typeid,
				'title' => $title,
				);
			$fields = dhtmlspecialchars($fields);
			$fields['content'] = $content;
			$fields['username'] = $_username;
			$fields['addtime'] = $DT_TIME;
			$sqlk = $sqlv = '';
			foreach($fields as $k=>$v) {
				$sqlk .= ','.$k; $sqlv .= ",'$v'";
			}
			$sqlk = substr($sqlk, 1); $sqlv = substr($sqlv, 1);
			$db->query("INSERT INTO {$DT_PRE}ask ($sqlk) VALUES ($sqlv)");
			dmsg('提交成功', $MOD['linkurl'].'ask.php?success=1');
		} else {
			$type_select = type_select('ask', 1, 'typeid', '请选择分类', 0, 'id="typeid"');
			$head_title = '提交新问题';
		}
		break;
	case 'edit':
		$itemid or message();
		$r = $db->get_one("SELECT * FROM {$DT_PRE}ask WHERE itemid=$itemid AND username='$_username'");
		$r or message();
		if($r['status'] > 0) message('此问题不可再修改');
		if($submit) {
			$typeid = intval($typeid);
			if(!$typeid || !isset($TYPE[$typeid])) message('请选择分类');		
			if(empty($title)) message('请填写标题');
			if(empty($content)) message('请填写内容');
			$fields = array(
				'typeid' => $typeid,
				'title' => $title,
				);
			$fields = dhtmlspecialchars($fields);
			$fields['content'] = $content;
			$sql = '';
			foreach($fields as $k=>$v) {
				$sql .= ",$k='$v'";
			}
			$sql = substr($sql, 1);
			$db->query("UPDATE {$DT_PRE}ask SET $sql WHERE itemid=$itemid AND username='$_username' ");
			dmsg('修改成功', $forward);
		} else {			
			extract($r);
			$type_select = type_select('ask', 1, 'typeid', '请选择分类', $typeid);
			$head_title = '修改问题';
		}
		break;
	case 'show':
		$itemid or message();
		$r = $db->get_one("SELECT * FROM {$DT_PRE}ask WHERE itemid=$itemid AND username='$_username'");
		$r or message();
		extract($r);
		$addtime = timetodate($addtime, 5);
		$admintime = $admintime ? timetodate($admintime, 5) : '';
		$stars = array('', '<span style="color:red;">不满意</span>', '基本满意', '<span style="color:green;">非常满意</span>');
		$head_title = '问题查看';
		break;
	case 'star':
		$itemid or message();
		isset($star) or message('请选择您的满意程度');
		$star = in_array($star, array(1, 2, 3)) ? $star : 3;
		$db->query("UPDATE {$DT_PRE}ask SET star=$star WHERE star=0 and username='$_username' AND itemid=$itemid");
		dmsg('评分成功', '?action=show&itemid='.$itemid);
		break;
	case 'delete':
		$itemid or message();
		$db->query("DELETE FROM {$DT_PRE}ask WHERE username='$_username' AND itemid=$itemid");
		dmsg('删除成功', $forward);
		break;
	default:
		$typeid = isset($typeid) ? intval($typeid) : '';
		$condition = '';
		if($typeid) $condition .= " AND typeid=$typeid";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}ask WHERE username='$_username' $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$asks = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}ask WHERE username='$_username' $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['dstatus'] = $dstatus[$r['status']];
			$r['type'] = $r['typeid'] && isset($TYPE[$r['typeid']]) ? set_style($TYPE[$r['typeid']]['typename'], $TYPE[$r['typeid']]['style']) : '默认';
			$asks[] = $r;
		}
		$head_title = '问题及解答';
}
include template('ask', $module);
?>