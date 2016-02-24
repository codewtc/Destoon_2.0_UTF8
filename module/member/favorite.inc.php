<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';

$MG['favorite_limit'] > -1 or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');

require DT_ROOT.'/include/post.func.php';
$TYPE = get_type('favorite-'.$_userid);
$forward or $forward = $MOD['linkurl'].'favorite.php';
switch($action) {
	case 'add':
		if($MG['favorite_limit']) {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}favorite WHERE userid=$_userid");
			if($r['num'] >= $MG['favorite_limit']) dalert('最多可添加'.$MG['favorite_limit'].'条记录 当前已添加'.$r['num'].'条记录', 'goback');
		}
		if($submit) {
			$title = trim($title);
			$url = trim($url);
			if(!$title) message('请填写标题');
			if(!$url) message('请填写地址');
			$fields = array(
				'typeid' => intval($typeid),
				'title' => $title,
				'style' => $style,
				'url' => $url,
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
			$db->query("INSERT INTO {$DT_PRE}favorite ($sqlk) VALUES ($sqlv)");
			dmsg('添加成功', '?');
		} else {
			$title = isset($title) ? trim($title) : '';
			$url = isset($url) ? trim($url) : '';
			$type_select = type_select('favorite-'.$_userid, 0, 'typeid', '默认');
			$head_title = '添加收藏';
		}
		break;
	case 'edit':
		$itemid or message();
		if($submit) {
			$title = trim($title);
			$url = trim($url);
			if(!$title) message('请填写标题');
			if(!$url) message('请填写地址');
			$fields = array(
				'typeid' => intval($typeid),
				'listorder' => intval($listorder),
				'title' => $title,
				'style' => $style,
				'url' => $url,
				'note' => $note,
				);
			$fields = dhtmlspecialchars($fields);
			$sql = '';
			foreach($fields as $k=>$v) {
				$sql .= ",$k='$v'";
			}
			$sql = substr($sql, 1);
			$db->query("UPDATE {$DT_PRE}favorite SET $sql WHERE itemid=$itemid AND userid=$_userid");
			dmsg('修改成功', $forward);
		} else {
			$r = $db->get_one("SELECT * FROM {$DT_PRE}favorite WHERE itemid=$itemid AND userid=$_userid");
			$r or message();
			extract($r);
			$type_select = type_select('favorite-'.$_userid, 0, 'typeid', '默认', $typeid);
			$head_title = '修改收藏';
		}
		break;
	case 'delete':
		$itemid or message('请选择你要删除的收藏');
		$itemids = is_array($itemid) ? implode(',', $itemid) : intval($itemid);
		$db->query("DELETE FROM {$DT_PRE}favorite WHERE userid=$_userid AND itemid IN($itemids)");
		dmsg('删除成功', $forward);
		break;
	default:
		$sfields = array('按条件', '标题', '网址', '备注');
		$dfields = array('title', 'title', 'url', 'note');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$typeid = isset($typeid) ? ($typeid === '' ? -1 : intval($typeid)) : -1;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$type_select = type_select('favorite-'.$_userid, 0, 'typeid', '默认', $typeid, '', '所有分类');
		$condition = '';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($typeid > -1) $condition .= " AND typeid=$typeid";

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}favorite WHERE userid=$_userid $condition");
		$pages = pages($r['num'], $page, $pagesize);
		if($MG['favorite_limit']) {
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}favorite WHERE userid=$_userid");
			$limit_used = $r['num'];
			$limit_free = $MG['favorite_limit'] > $limit_used ? $MG['favorite_limit'] - $limit_used : 0;
		}		
		$favorites = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}favorite WHERE userid=$_userid $condition ORDER BY listorder DESC,itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['title'] = set_style($r['title'], $r['style']);
			$r['type'] = $r['typeid'] && isset($TYPE[$r['typeid']]) ? set_style($TYPE[$r['typeid']]['typename'], $TYPE[$r['typeid']]['style']) : '默认';
			$favorites[] = $r;
		}
		$head_title = '我的收藏';
}
include template('favorite', $module);
?>