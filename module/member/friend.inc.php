<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';

$MG['friend_limit'] > -1 or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');

require DT_ROOT.'/include/post.func.php';
$TYPE = get_type('friend-'.$_userid);
$forward or $forward = $MOD['linkurl'].'friend.php';
switch($action) {
	case 'add':
		if($MG['friend_limit']) {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}friend WHERE userid=$_userid");
			if($r['num'] >= $MG['friend_limit']) dalert('最多可添加'.$MG['friend_limit'].'条记录 当前已添加'.$r['num'].'条记录', 'goback');
		}
		if($submit) {
			if(!$name) message('请填写姓名');
			if($username && $db->get_one("SELECT username FROM {$DT_PRE}friend WHERE userid=$_userid AND username='$username'")) message('该会员已经是您的商友了');
			if($email && !is_email($email)) message('Email格式不正确');
			if($msn && !is_email($msn)) message('MSN格式不正确');
			if($qq && !is_numeric($qq)) message('QQ格式不正确');
			if($username && !$homepage) $homepage = userurl($username);
			$fields = array(
				'typeid' => intval($typeid),
				'username' => $username,
				'name' => $name,
				'style' => $style,
				'company' => $company,
				'career' => $career,
				'telephone' => $telephone,
				'mobile' => $mobile,
				'homepage' => $homepage,
				'email' => $email,
				'msn' => $msn,
				'qq' => $qq,
				'note' => $note,
				);
			$fields = dhtmlspecialchars($fields);
			$fields['userid'] = $_userid;
			$fields['addtime'] = $DT_TIME;
			$sqlk = $sqlv = '';
			foreach($fields as $k=>$v) {
				$sqlk .= ','.$k; $sqlv .= ",'$v'";
			}
			$sqlk = substr($sqlk, 1); $sqlv = substr($sqlv, 1);
			$db->query("INSERT INTO {$DT_PRE}friend ($sqlk) VALUES ($sqlv)");
			dmsg('添加成功', '?');
		} else {
			$username = isset($username) ? trim($username) : '';
			$name = $homepage = $company = $career = $telephone = $msn = $qq = '';
			if($username) {
				$r = $db->get_one("SELECT * FROM {$DT_PRE}member m,{$DT_PRE}company c WHERE m.userid=c.userid and m.username='$username' limit 0,1");
				if($r) {
					$name = $r['truename'];
					$homepage = userurl($username);
					$company = $r['company'];
					$career = $r['career'];
					$telephone = $r['telephone'];
					$msn = $r['msn'];
					$qq = $r['qq'];
				}
			}
			$type_select = type_select('friend-'.$_userid, 0, 'typeid', '默认');
			$head_title = '添加商友';
		}
		break;
	case 'edit':
		$itemid or message();
		if($submit) {
			if(!$name) message('请填写姓名');
			if($email && !is_email($email)) message('Email格式不正确');
			if($msn && !is_email($msn)) message('MSN格式不正确');
			if($qq && !is_numeric($qq)) message('QQ格式不正确');
			if($username && !$homepage) $homepage = userurl($username);
			$fields = array(
				'typeid' => intval($typeid),
				'listorder' => intval($listorder),
				'username' => $username,
				'name' => $name,
				'style' => $style,
				'company' => $company,
				'career' => $career,
				'telephone' => $telephone,
				'mobile' => $mobile,
				'homepage' => $homepage,
				'email' => $email,
				'msn' => $msn,
				'qq' => $qq,
				'note' => $note,
				);
			$fields = dhtmlspecialchars($fields);
			$sql = '';
			foreach($fields as $k=>$v) {
				$sql .= ",$k='$v'";
			}
			$sql = substr($sql, 1);
			$db->query("UPDATE {$DT_PRE}friend SET $sql WHERE itemid=$itemid AND userid=$_userid ");
			dmsg('修改成功', $forward);
		} else {
			$r = $db->get_one("SELECT * FROM {$DT_PRE}friend WHERE itemid=$itemid AND userid=$_userid");
			$r or message();
			extract($r);
			$type_select = type_select('friend-'.$_userid, 0, 'typeid', '默认', $typeid);
			$head_title = '修改商友';
		}
		break;
	case 'delete':
		$itemid or message('请选择你要删除的商友');
		$itemids = is_array($itemid) ? implode(',', $itemid) : intval($itemid);
		$db->query("DELETE FROM {$DT_PRE}friend WHERE userid=$_userid AND itemid IN($itemids)");
		dmsg('删除成功', $forward);
		break;
	default:
		$sfields = array('按条件', '姓名', '公司', '职位', '电话', '手机', '主页', 'Email', 'MSN', 'QQ', '会员', '备注');
		$dfields = array('company', 'name', 'company', 'career', 'telephone', 'mobile', 'homepage', 'email', 'msn', 'qq', 'username', 'note');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$typeid = isset($typeid) ? ($typeid === '' ? -1 : intval($typeid)) : -1;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$type_select = type_select('friend-'.$_userid, 0, 'typeid', '默认', $typeid, '', '所有分类');
		$condition = "userid=$_userid";
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($typeid > -1) $condition .= " AND typeid=$typeid";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}friend WHERE $condition");
		$pages = pages($r['num'], $page, $pagesize);
		if($MG['friend_limit']) {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}friend WHERE userid=$_userid");
			$limit_used = $r['num'];
			$limit_free = $MG['friend_limit'] > $limit_used ? $MG['friend_limit'] - $limit_used : 0;
		}
		$friends = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}friend WHERE $condition ORDER BY listorder DESC,itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['dcompany'] = set_style($r['company'], $r['style']);
			$r['type'] = $r['typeid'] && isset($TYPE[$r['typeid']]) ? set_style($TYPE[$r['typeid']]['typename'], $TYPE[$r['typeid']]['style']) : '默认';
			$friends[] = $r;
		}
		if(count($friends)%2 == 1) $friends[] = array(); //Fix Cells
		$head_title = '我的商友';
}
include template('friend', $module);
?>