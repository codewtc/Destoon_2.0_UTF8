<?php
defined('IN_DESTOON') or exit('Access Denied');
$menus = array (
    array('充值记录', '?moduleid='.$moduleid.'&file=charge'),
    array('交易记录', '?moduleid='.$moduleid.'&file=trade'),
    array('提现记录', '?moduleid='.$moduleid.'&file=cash'),
);
$PAY = cache_read('pay.php');
$PAY['card']['name'] = '充值卡';
$dstatus = array('未知', '失败', '作废', '成功', '人工');
switch($action) {
	case 'check':	
		$itemid or msg('请选择记录');
		$itemid = implode(',', $itemid);
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_charge WHERE itemid IN ($itemid) AND status<2");
		$i = 0;
		while($r = $db->fetch_array($result)) {
			$money = $r['amount'] + $r['fee'];
			money_add($r['username'], $r['amount']);
			record_add($r['username'], $r['amount'], $PAY[$r['bank']]['name'], $_username, '在线充值', '人工');
			$db->query("UPDATE {$DT_PRE}finance_charge SET money='$money',status=4,editor='$_username',receivetime=$DT_TIME WHERE itemid=$r[itemid]");
			$i++;
		}
		dmsg('审核成功'.$i.'条记录', $forward);
	break;
	case 'recycle':
		$itemid or msg('请选择记录');
		$itemid = implode(',', $itemid);
		$db->query("UPDATE {$DT_PRE}finance_charge SET status=2,editor='$_username',receivetime=$DT_TIME WHERE itemid IN ($itemid) AND status=0");
		dmsg('作废成功'.$db->affected_rows().'条记录', $forward);
	break;
	case 'delete':
		$itemid or msg('请选择记录');
		$itemid = implode(',', $itemid);
		$db->query("DELETE FROM {$DT_PRE}finance_charge WHERE itemid IN ($itemid) AND status=0");
		dmsg('删除成功'.$db->affected_rows().'条记录', $forward);
	break;
	case 'export':
		$dfields = array('username', 'username', 'amount', 'fee', 'money', 'editor');
		$dorder  = array('itemid DESC', 'amount DESC', 'amount ASC', 'charge DESC', 'charge ASC', 'sendtime DESC', 'sendtime ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($dfromtime) or $dfromtime = '';
		isset($dtotime) or $dtotime = '';
		isset($bank) or $bank = '';
		$status = isset($status) && isset($dstatus[$status]) ? intval($status) : '';
		isset($order) && isset($dorder[$order]) or $order = 0;
		$condition = '';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($bank) $condition .= " AND bank='$bank'";
		if($dfromtime) $condition .= " AND sendtime>".(strtotime($dfromtime.' 00:00:00'));
		if($dtotime) $condition .= " AND sendtime<".(strtotime($dtotime.' 23:59:59'));
		if($status !== '') $condition .= " AND status=$status";
		$data = '流水号,充值金额,手续费,实收金额,会员名称,支付平台,下单时间,支付时间,操作人,状态'."\n";
		$amount = $fee = $money = 0;
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_charge WHERE 1 $condition ORDER BY $dorder[$order] LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['sendtime'] = timetodate($r['sendtime'], 5);
			$r['receivetime'] = $r['receivetime'] ? timetodate($r['receivetime'], 5) : '--';
			$r['editor'] or $r['editor'] = 'system';
			$r['dstatus'] = $dstatus[$r['status']];
			$amount += $r['amount'];
			$fee += $r['fee'];
			$money += $r['money'];
			$data .= $r['itemid'].','.$r['amount'].','.$r['fee'].','.$r['money'].','.$r['username'].','.$PAY[$r['bank']]['name'].','.$r['sendtime'].','.$r['receivetime'].','.$r['editor'].','.$dstatus[$r['status']]."\n";
		}
		$data .= '小计,'.$amount.','.$fee.','.$money.',,,,,,,';
		ob_start();
		header('Cache-control: max-age=31536000');
		header('Expires: '.gmdate('D, d M Y H:i:s', $DT_TIME + 31536000).' GMT');
		header('Content-Length: '.strlen($data));
		header('Content-Disposition: attachment; filename=充值记录-'.date('Y-m-d-H-i-s', $DT_TIME).'.csv');
		header('Content-Type:application/octet-stream');
		echo $data;
		exit;
	break;
	default:
		$_status = array('<span style="color:blue;">未知</span>', '<span style="color:red;">失败</span>', '<span style="color:#FF00FF;">作废</span>', '<span style="color:green;">成功</span>', '<span style="color:green;">人工</span>');
		$sfields = array('按条件', '会员名', '充值金额', '手续费', '实收金额', '操作人');
		$dfields = array('username', 'username', 'amount', 'fee', 'money', 'editor');
		$sorder  = array('结果排序方式', '充值金额降序', '充值金额升序', '手续费降序', '手续费升序', '实收金额降序', '实收金额升序', '下单时间降序', '下单时间升序', '支付时间降序', '支付时间升序');
		$dorder  = array('itemid DESC', 'amount DESC', 'amount ASC', 'fee DESC', 'fee ASC', 'money DESC', 'money ASC', 'sendtime DESC', 'sendtime ASC', 'reveicetime DESC', 'reveicetime ASC');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($fromtime) or $fromtime = '';
		isset($totime) or $totime = '';
		isset($bank) or $bank = '';
		$status = isset($status) && isset($dstatus[$status]) ? intval($status) : '';
		isset($order) && isset($dorder[$order]) or $order = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		$status_select = dselect($dstatus, 'status', '状态', $status, '', 1, '', 1);
		$order_select  = dselect($sorder, 'order', '', $order);
		$condition = '';
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($bank) $condition .= " AND bank='$bank'";
		if($fromtime) $condition .= " AND sendtime>".(strtotime($fromtime.' 00:00:00'));
		if($totime) $condition .= " AND sendtime<".(strtotime($totime.' 23:59:59'));
		if($status !== '') $condition .= " AND status=$status";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_charge WHERE 1 $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$charges = array();
		$amount = $fee = $money = 0;
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_charge WHERE 1 $condition ORDER BY $dorder[$order] LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['sendtime'] = timetodate($r['sendtime'], 5);
			$r['receivetime'] = $r['receivetime'] ? timetodate($r['receivetime'], 5) : '--';
			$r['editor'] or $r['editor'] = 'system';
			$r['dstatus'] = $_status[$r['status']];
			$amount += $r['amount'];
			$fee += $r['fee'];
			$money += $r['money'];
			$charges[] = $r;
		}
		include tpl('charge', $module);
	break;
}
?>