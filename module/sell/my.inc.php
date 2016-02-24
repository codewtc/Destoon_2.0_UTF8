<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';

$MG['sell_limit'] > -1 or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');

$MTYPE = get_type('product-'.$_userid);
require DT_ROOT.'/include/post.func.php';
require MOD_ROOT.'/sell.class.php';
$do = new sell($moduleid);

if(in_array($action, array('add', 'edit'))) {
	$FD = cache_read('fields-'.substr($table, strlen($DT_PRE)).'.php');
	if($FD) require DT_ROOT.'/include/fields.func.php';
	isset($post_fields) or $post_fields = array();
	$PT = $MOD['product_option'];
	if($PT) require MOD_ROOT.'/product.func.php';
	isset($post_option) or $post_option = array();
}

$sql = $_userid ? "username='$_username'" : "ip='$DT_IP'";
$limit_used = $limit_free = $need_captcha = $need_question = $fee_add = 0;
if(in_array($action, array('', 'add'))) {
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $sql");
	$limit_used = $r['num'];
	$limit_free = $MG['sell_limit'] > $limit_used ? $MG['sell_limit'] - $limit_used : 0;
}

switch($action) {
	case 'add':
		if($MG['sell_limit'] && $limit_used >= $MG['sell_limit']) dalert('最多可发布'.$MG['sell_limit'].'条'.$MOD['name'].' 当前已发布'.$limit_used.'条', $_userid ? $MODULE[2]['linkurl'].$DT['file_my'].'?mid='.$mid : $MODULE[2]['linkurl'].$DT['file_my']);
		if($MG['day_limit']) {
			$today = strtotime(timetodate($DT_TIME, 3).' 00:00:00');
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $sql AND addtime>$today");
			if($r && $r['num'] >= $MG['day_limit']) dalert('24小时内最多发布'.$MG['day_limit'].'条'.$MOD['name'], $_userid ? $MODULE[2]['linkurl'].$DT['file_my'].'?mid='.$mid : $MODULE[2]['linkurl'].$DT['file_my']);
		}

		if($MG['sell_free_limit'] > 0) {
			$fee_add = ($MOD['fee_add'] && !$MG['fee_mode'] && $limit_used >= $MG['sell_free_limit'] && $_userid) ? dround($MOD['fee_add']) : 0;
		} else {
			$fee_add = 0;
		}

		$need_captcha = $MOD['captcha_add'] == 2 ? $MG['captcha'] : $MOD['captcha_add'];
		$need_question = $MOD['question_add'] == 2 ? $MG['question'] : $MOD['question_add'];

		if($submit) {
			if($fee_add) {
				$fee_add < $_money or dalert('发布信息收费 '.$fee_add.' 元，当前余额不足，请先充值');
				is_payword($_username, $password) or dalert('您的支付密码不正确');
			}

			if(!$_userid) {
				if(strlen($post['company']) < 10) dalert('请填写正确的公司名称');
				if(!isset($AREA[$post['areaid']])) dalert('请选择所在地区');
				if(strlen($post['truename']) < 4) dalert('请填写联系人姓名');
				if(strlen($post['mobile']) < 7) dalert('请填写正确的联系手机');
			}

			if($MG['add_limit']) {
				$last = $db->get_one("SELECT addtime FROM {$table} WHERE $sql ORDER BY itemid DESC");
				if($last && $DT_TIME - $last['addtime'] < $MG['add_limit']) dalert('信息发布过快，请隔'.$MG['add_limit'].'秒再提交');
			}
			$msg = captcha($captcha, $need_captcha, true);
			if($msg) dalert($msg);
			$msg = question($answer, $need_question, true);
			if($msg) dalert($msg);

			if($do->pass($post)) {
				$CAT = cache_read('category_'.$post['catid'].'.php');
				if(!check_group($_groupid, $CAT['group_add'])) dalert('您所在的会员组没有权限在分类 ['.$CAT['catname'].'] 发布信息，请更换分类');
				if($MOD['upload_thumb'] && $_userid) {
					if(strlen($post['thumb']) > 5) {
						if(strpos(get_cookie('uploads'), $post['thumb']) === false) dalert('请上传第一张产品图片');
					} else {
						dalert('请上传第一张产品图片');
					}
				}
				$post['addtime'] = $post['level'] = $post['fee'] = 0;
				$post['style'] = $post['template'] = $post['note'] = '';
				if(!$IMVIP) $post['thumb1'] = $post['thumb2'] = '';
				$need_check =  $MOD['check_add'] == 2 ? $MG['check'] : $MOD['check_add'];
				$post['status'] = get_status(3, $need_check);
				$post['username'] = $_username;
				if($FD) fields_check($post_fields);
				if($PT) product_check($post_product);
				$do->add($post);
				if($FD) fields_update($post_fields, $table, $do->itemid);
				if($PT) product_update($post_product, $do->itemid, $post['pid']);

				if($fee_add) {
					money_add($_username, -$fee_add);
					record_add($_username, -$fee_add, '站内', 'system', '站内支付', '发布['.$MODULE[$mid]['name'].']'.$post['title'].'(信息ID:'.$do->itemid.')');
				}
				
				$msg = '添加成功';
				if($post['status'] == 2) $msg = $msg.' 请等待审核';
				if($_userid) {
					set_cookie('dmsg', $msg);
					$forward = $MODULE[2]['linkurl'].$DT['file_my'].'?mid='.$mid.'&status='.$post['status'];
					dalert('', '', 'parent.window.location="'.$forward.'";');
				} else {
					dalert($msg, '', 'parent.window.location=parent.window.location;');
				}
			} else {
				dalert($do->errmsg);
			}
		} else {
			if($itemid) {
				$MG['copy'] && $_userid or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');

				$do->itemid = $itemid;
				$r = $do->get_one();
				if(!$r || $r['username'] != $_username) message();
				extract($r);
				$totime = $totime > $DT_TIME ? timetodate($totime, 3) : timetodate($DT_TIME+$MOD['over_days']*86400, 3);
			} else {
				foreach($do->fields as $v) {
					$$v = '';
				}
				$content = '';
				$days = 3;
				$totime = timetodate($DT_TIME+$MOD['over_days']*86400, 3);
				$item = array();
			}
			$maxtime = timetodate($DT_TIME+$MOD['max_days']*86400, 3);
			$maxdate = timetodate($DT_TIME+$MOD['max_days']*86400, 'Ymd');
			$mycatid_select = type_select('product-'.$_userid, 0, 'post[mycatid]', '默认');
			$item = array();
		}
	break;
	case 'edit':
		$itemid or message();
		$do->itemid = $itemid;
		$item = $do->get_one();
		if(!$item || $item['username'] != $_username) message();

		if($MG['edit_limit'] < 0) message('信息不允许被修改');
		if($MG['edit_limit'] && $DT_TIME - $item['addtime'] > $MG['edit_limit']*86400) message('此信息发布已经超过 '.$MG['edit_limit'].' 天，不可再修改');

		if($submit) {
			if($do->pass($post)) {
				$CAT = cache_read('category_'.$post['catid'].'.php');
				if(!check_group($_groupid, $CAT['group_add'])) dalert('您所在的会员组没有权限在分类 ['.$CAT['catname'].'] 发布信息，请更换分类');
				$post['addtime'] = timetodate($item['addtime']);
				$post['level'] = $item['level'];
				$post['fee'] = $item['fee'];
				$post['style'] = $item['style'];
				$post['template'] = $item['template'];
				$post['note'] = $item['note'];
				if(!$IMVIP) {
					$post['thumb1'] = $item['thumb1'];
					$post['thumb2'] = $item['thumb2'];
				}
				$need_check =  $MOD['check_add'] == 2 ? $MG['check'] : $MOD['check_add'];
				$post['status'] = get_status($item['status'], $need_check);
				$post['username'] = $_username;
				if($FD) fields_check($post_fields);
				if($PT) product_check($post_product);
				$do->edit($post);
				if($FD) fields_update($post_fields, $table, $do->itemid);
				if($PT) product_update($post_product, $do->itemid, $post['pid']);

				set_cookie('dmsg', '修改成功');
				dalert('', '', 'parent.window.location="'.$forward.'"');
			} else {
				dalert($do->errmsg);
			}
		} else {
			extract($item);
			$totime = timetodate($totime, 3);
			$maxtime = timetodate($DT_TIME+$MOD['max_days']*86400, 3);
			$maxdate = timetodate($DT_TIME+$MOD['max_days']*86400, 'Ymd');
			$mycatid_select = type_select('product-'.$_userid, 0, 'post[mycatid]', '默认', $mycatid);
		}
	break;
	case 'delete':
		$itemid or message();
		$do->itemid = $itemid;
		$item = $do->get_one();
		if(!$item || $item['username'] != $_username) message();
		$do->recycle($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'refresh':

		$MG['refresh_limit'] > -1 or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');

		$do->_update($_username);
		$itemid or message();
		$do->itemid = $itemid;
		$item = $do->get_one();
		if(!$item || $item['username'] != $_username) message();

		if($MG['refresh_limit'] && $DT_TIME - $item['edittime'] < $MG['refresh_limit']) dalert($MG['refresh_limit'].'秒内只能刷新一次', $forward);

		$do->refresh($itemid);
		dmsg('更新成功', $forward);
	break;
	default:
		$status = isset($status) ? intval($status) : 3;
		in_array($status, array(1, 2, 3, 4)) or $status = 3;
		$typeid = isset($typeid) ? ($typeid === '' ? -1 : intval($typeid)) : -1;
		$mycatid = isset($mycatid) ? ($mycatid === '' ? -1 : intval($mycatid)) : -1;
		$mycat_select = type_select('product-'.$_userid, 0, 'mycatid', '默认', $mycatid, '', '自定义分类');

		$condition = "username='$_username' AND status=$status";
		if($keyword) $condition .= " AND keyword LIKE '%$keyword%'";
		if($catid) $condition .= ($CATEGORY[$catid]['child']) ? " AND catid IN (".$CATEGORY[$catid]['arrchildid'].")" : " AND catid=$catid";
		if($typeid >= 0) $condition .= " AND typeid=$typeid";
		if($mycatid >= 0) $condition .= " AND mycatid=$mycatid";

		$timetype = strpos($MOD['order'], 'add') !== false ? 'add' : '';
		$lists = $do->get_list($condition, $MOD['order']);
		foreach($lists as $k=>$v) {
			$lists[$k]['mycat'] = $v['mycatid'] && isset($MTYPE[$v['mycatid']]) ? set_style($MTYPE[$v['mycatid']]['typename'], $MTYPE[$v['mycatid']]['style']) : '默认';
		}
	break;
}
$head_title = $MOD['name'].'管理';
if($_userid) {
	$nums = array();
	for($i = 1; $i < 5; $i++) {
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE username='$_username' AND status=$i");
		$nums[$i] = $r['num'];
	}
}
include template('my_'.$module, 'member');
?>