<?php
defined('IN_DESTOON') or exit('Access Denied');
require MOD_ROOT.'/ad.class.php';
$TYPE = array('广告类型', '代码广告', '文字链接', '图片广告', 'Flash广告', '排名广告', '幻灯片广告');
isset($pid) or $pid = 0;
isset($aid) or $aid = 0;
$menus = array (
    array('添加广告位', '?moduleid='.$moduleid.'&file='.$file.'&action=add_place'),
    array('广告位管理', '?moduleid='.$moduleid.'&file='.$file),
    array('广告管理', '?moduleid='.$moduleid.'&file='.$file.'&action=list&pid='.$pid),
    array('更新广告', '?moduleid='.$moduleid.'&file='.$file.'&action=tohtml'),
);
$do = new ad();
$do->pid = $pid;
$do->aid = $aid;
$this_forward = '?moduleid='.$moduleid.'&file='.$file.'&action=list&pid='.$pid.'&page='.$page;
$this_place_forward = '?moduleid='.$moduleid.'&file='.$file.'&page='.$page;
switch($action) {
	case 'add':
		$pid or msg();
		if($submit) {
			if($ad['typeid'] == 6) {
				$S = array();
				foreach($slide as $v) {
					if($v['url'] && $v['thumb']) $S[] = $v;
				}
				if(count($S) < 2) msg('最少需要设置两张幻灯图片，链接地址和图片地址均不能为空');
				$order = array();
				foreach ($S as $k => $v) {
					$order[$k]  = $v['order'];
				}
				array_multisort($order, SORT_ASC, $S);
				$ad['code'] = '';
				foreach ($S as $k => $v) {
					$ad['code']  .= intval($v['order']).'|'.trim($v['url']).'|'.trim($v['thumb'])."\n";
				}
			}
			if($do->is_ad($ad)) {
				$do->add($ad);
				dmsg('添加成功', $this_forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			$p = $do->get_one_place();
			$fromtime = timetodate($DT_TIME, 3);
			include tpl('ad_add', $module);
		}
	break;
	case 'edit':
		$aid or msg();
		if($submit) {
			if($ad['typeid'] == 6) {
				$S = array();
				foreach($slide as $v) {
					if($v['url'] && $v['thumb']) $S[] = $v;
				}
				if(count($S) < 2) msg('最少需要设置两张幻灯图片，链接地址和图片地址均不能为空');
				$order = array();
				foreach ($S as $k => $v) {
					$order[$k]  = $v['order'];
				}
				array_multisort($order, SORT_ASC, $S);
				$ad['code'] = '';
				foreach ($S as $k => $v) {
					$ad['code']  .= intval($v['order']).'|'.trim($v['url']).'|'.trim($v['thumb'])."\n";
				}
			}
			if($do->is_ad($ad)) {
				$do->edit($ad);
				dmsg('修改成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			extract($do->get_one());
			$do->pid = $pid;
			$p = $do->get_one_place();
			$fromtime = timetodate($fromtime, 3);
			$totime = timetodate($totime, 3);
			include tpl('ad_edit', $module);
		}
	break;
	case 'delete':
		$aids or msg('请选择广告');
		$do->delete($aids);
		dmsg('删除成功', $this_forward);
	break;
	case 'list':
		$pid or msg('请指定广告位', $this_place_forward);
		$p = $do->get_one_place();
		$sfields = array('按条件', '广告名称', '广告介绍', '会员名');
		$dfields = array('title', 'title', 'introduce', 'username');
		$sorder  = array('结果排序方式', '添加时间降序', '添加时间升序', '开始时间降序', '开始时间升序', '结束时间降序', '结束时间升序', '浏览次数降序', '浏览次数升序');
		$dorder  = array('fromtime DESC', 'addtime DESC', 'addtime ASC', 'fromtime DESC', 'fromtime ASC', 'totime DESC', 'totime ASC', 'hits DESC', 'hits ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($order) && isset($dorder[$order]) or $order = 0;
		isset($typeid) or $typeid = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$order_select  = dselect($sorder, 'order', '', $order);
		$condition = "pid=$pid";
		$type_select  = dselect($TYPE, 'typeid', '广告类型', $typeid);
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		$ads = $do->get_list($condition, $dorder[$order]);
		include tpl('ad_list', $module);
	break;
	case 'add_place':
		if($submit) {
			if($do->is_place($place)) {
				$do->add_place($place);
				dmsg('添加成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			include tpl('ad_add_place', $module);
		}
	break;
	case 'edit_place':
		$pid or msg();
		if($submit) {
			if($do->is_place($place)) {
				$do->edit_place($place);
				dmsg('修改成功', $forward);
			} else {
				msg($do->errmsg);
			}
		} else {
			$r = $do->get_one_place();
			$mid = $r['moduleid'];
			unset($r['moduleid']);
			extract($r);
			include tpl('ad_edit_place', $module);
		}
	break;
	case 'view':
		$ad = $aid ? true : false;
		if($ad) {
			$aid or msg();
			$a = $do->get_one();
			extract($a);
			$do->pid = $pid;
			if($url && $stat) $url = $MOD['linkurl'].'redirect.php?aid='.$aid;
			if($typeid == 6) {
				$pics = $links = array();
				$code = explode("\n", trim($code));
				foreach($code as $k=>$c) {
					$c = explode("|", $c);
					$links[] = $c[1];
					$pics[] = $c[2];
				}
			}
		} else {
			$pid or msg();
		}
		$p = $do->get_one_place();
		extract($p);
		$filename = 'ad_'.$pid.'.htm';
		if($typeid == 5) {
			if($ad) {
				if($a['key_word']) {
					$filename = 'ad_m'.$moduleid.'_k'.urlencode($key_word).'.htm';
				} else if($a['key_catid']) {
					$filename = 'ad_m'.$moduleid.'_c'.$key_catid.'.htm';
				} else {
					$filename = 'ad_m'.$moduleid.'.htm';
				}
			} else {
				$filename = 'ad_m'.$moduleid.'.htm';
			}
		}
		$destoon_task = '';
		$moduleid or $moduleid = 3;
		$head_title = $ad ? '广告 ['.$title.'] 预览' : '广告位 ['.$name.'] 预览';
		include template('ad', $module);
	break;
	case 'delete_place':
		$pids or msg('请选择广告位');
		$do->delete_place($pids);
		dmsg('删除成功', $this_place_forward);
	break;
	case 'order_place':
		$do->order_place($listorder);
		dmsg('排序成功', $this_place_forward);
	break;
	case 'tohtml':
		$_result = $db->query("SELECT * FROM {$DT_PRE}ad_place");
		while($p = $db->fetch_array($_result)) {//When USE tohtml(),Can't get MOD && CATEGORY
			$pid = $p['pid'];
			if($p['moduleid']) {
				$MOD = cache_read('module-'.$p['moduleid'].'.php');
				$CATEGORY = cache_read('category-'.$r['moduleid'].'.php');
			}
			include MOD_ROOT.'/ad.html.php';
		}		
		cache_clear_ad();
		dmsg('更新成功', $this_place_forward);
	break;
	default:
		isset($typeid) or $typeid = 0;
		$condition = '1';
		$type_select  = dselect($TYPE, 'typeid', '', $typeid);
		if($keyword) $condition .= " AND name LIKE '%$keyword%'";
		if($typeid) $condition .= " AND typeid=$typeid";
		$places = $do->get_list_place($condition);
		include tpl('ad', $module);
	break;
}
?>