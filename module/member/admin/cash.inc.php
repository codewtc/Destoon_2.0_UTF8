<?php
defined('IN_DESTOON') or exit('Access Denied');
$menus = array (
    array('充值记录', '?moduleid='.$moduleid.'&file=charge'),
    array('交易记录', '?moduleid='.$moduleid.'&file=trade'),
    array('提现记录', '?moduleid='.$moduleid.'&file=cash'),
);
$BANKS = explode('|', trim($MOD['cash_banks']));
$dstatus = array('等待受理', '拒绝申请', '支付失败', '付款成功');
$_status = array('<span style="color:blue;">等待受理</span>', '<span style="color:#666666;">拒绝申请</span>', '<span style="color:red;">支付失败</span>', '<span style="color:green;">付款成功</span>');
if($action == 'edit' || $action == 'show') {
			$itemid or msg('未指定记录');
			$item = $db->get_one("SELECT * FROM {$DT_PRE}finance_cash WHERE itemid=$itemid ");
			$item or msg('记录不存在');
			$item['addtime'] = date('Y-m-d H:i', $item['addtime']);
			$item['edittime'] = date('Y-m-d H:i', $item['edittime']);
			$member = $db->get_one("SELECT * FROM {$DT_PRE}member WHERE username='$item[username]'");
		}
switch($action) {
	case 'edit':
		if($item['status'] > 0) msg('此申请已受理');
		if($submit) {
			isset($status) or msg('请指定受理结果');
			$money = $item['amount'] + $item['fee'];
			if($status == 3) {
				money_lock($member['username'], -$money);
				record_add($member['username'], -$item['amount'], $item['bank'], $_username, '提现成功');
				record_add($member['username'], -$item['fee'], $item['bank'], $_username, '提现手续费');
			} else if($status == 2 || $status == 1) {
				$note or msg('请填写原因备注');
				money_lock($member['username'], -$money);
				money_add($member['username'], $money);
			} else {
				msg();
			}
			$db->query("UPDATE {$DT_PRE}finance_cash SET status=$status,editor='$_username',edittime=$DT_TIME,note='$note' WHERE itemid=$itemid");
			dmsg('受理成功', $forward);
		} else {
			include tpl('cash_edit', $module);
		}
	break;
	case 'show':
		if($item['status'] == 0) msg('申请尚未受理');
		include tpl('cash_show', $module);
	break;
	case 'export':
		$dfields = array('username', 'username', 'bank', 'reason', 'note', 'editor');
		$dorder  = array('itemid DESC', 'amount DESC', 'amount ASC', 'addtime DESC', 'addtime ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$status = isset($status) && isset($dstatus[$status]) ? intval($status) : '';	isset($dfromtime) or $dfromtime = '';
		isset($dtotime) or $dtotime = '';
		isset($bank) or $bank = '';
		isset($order) && isset($dorder[$order]) or $order = 0;
		$condition = '';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($bank) $condition .= " AND bank='$bank'";
		if($dfromtime) $condition .= " AND addtime>".(strtotime($dfromtime.' 00:00:00'));
		if($dtotime) $condition .= " AND addtime<".(strtotime($dtotime.' 23:59:59'));
		if($status !== '') $condition .= " AND status='$status'";
		$data = '流水号,金额,手续费,会员名称,收款方式,申请时间,受理时间,受理人,状态'."\n";
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_cash WHERE 1 $condition ORDER BY $dorder[$order]");
		$amount = $fee = 0;
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['edittime'] = $r['edittime'] ? timetodate($r['edittime'], 5) : '--';
			$r['dstatus'] = $dstatus[$r['status']];
			$data .= $r['itemid'].','.$r['amount'].','.$r['fee'].','.$r['username'].','.$r['bank'].','.$r['addtime'].','.$r['edittime'].','.$r['editor'].','.$r['dstatus']."\n";
			$amount += $r['amount'];
			$fee += $r['fee'];
		}
		$data .= '小计,'.$amount.','.$fee.',,,,,,';
		ob_start();
		header('Cache-control: max-age=31536000');
		header('Expires: '.gmdate('D, d M Y H:i:s', $DT_TIME + 31536000).' GMT');
		header('Content-Length: '.strlen($data));
		header('Content-Disposition: attachment; filename=提现记录-'.date('Y-m-d-H-i-s', $DT_TIME).'.csv');
		header('Content-Type:application/octet-stream');
		echo $data;
		exit;
	default:
		$sfields = array('按条件', '会员名', '金额', '手续费', '收款方式', '收款帐号', '收款人', '备注', '受理人');
		$dfields = array('username', 'username', 'bank', 'amount', 'fee', 'note', 'editor');
		$sorder  = array('排序方式', '金额降序', '金额升序', '手续费降序', '手续费升序', '时间降序', '时间升序');
		$dorder  = array('itemid DESC', 'amount DESC', 'amount ASC', 'fee DESC', 'fee ASC', 'addtime DESC', 'addtime ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		$status = isset($status) && isset($dstatus[$status]) ? intval($status) : '';
		isset($fromtime) or $fromtime = '';
		isset($totime) or $totime = '';
		isset($bank) or $bank = '';
		isset($order) && isset($dorder[$order]) or $order = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$status_select = dselect($dstatus, 'status', '状态', $status, '', 1, '', 1);
		$order_select = dselect($sorder, 'order', '', $order);
		$condition = '';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($bank) $condition .= " AND bank='$bank'";
		if($fromtime) $condition .= " AND addtime>".(strtotime($fromtime.' 00:00:00'));
		if($totime) $condition .= " AND addtime<".(strtotime($totime.' 23:59:59'));
		if($status !== '') $condition .= " AND status='$status'";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_cash WHERE 1 $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$cashs = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_cash WHERE 1 $condition ORDER BY $dorder[$order] LIMIT $offset,$pagesize");
		$amount = $fee = 0;
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['edittime'] = $r['edittime'] ? timetodate($r['edittime'], 5) : '--';
			$r['dstatus'] = $_status[$r['status']];
			$amount += $r['amount'];
			$fee += $r['fee'];
			$cashs[] = $r;
		}
		include tpl('cash', $module);
	break;
}
?>