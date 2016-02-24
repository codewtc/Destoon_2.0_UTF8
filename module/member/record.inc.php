<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$condition = "username='$_username'";
switch($action) {
	case 'charge':
		$PAY = cache_read('pay.php');	
		$PAY['card']['name'] = '充值卡';
		$_status = array('<span style="color:blue;">未知</span>', '<span style="color:red;">失败</span>', '<span style="color:#FF00FF;">作废</span>', '<span style="color:green;">成功</span>', '<span style="color:green;">人工</span>');
		isset($fromtime) or $fromtime = '';
		isset($totime) or $totime = '';
		isset($bank) or $bank = '';
		if($bank) $condition .= " AND bank='$bank'";
		if($fromtime) $condition .= " AND sendtime>".(strtotime($fromtime.' 00:00:00'));
		if($totime) $condition .= " AND sendtime<".(strtotime($totime.' 23:59:59'));
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_charge WHERE $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$charges = array();
		$amount = $fee = $money = 0;
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_charge WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['sendtime'] = timetodate($r['sendtime'], 5);
			$r['receivetime'] = $r['receivetime'] ? timetodate($r['receivetime'], 5) : '--';
			$r['dstatus'] = $_status[$r['status']];
			$amount += $r['amount'];
			$fee += $r['fee'];
			$money += $r['money'];
			$charges[] = $r;
		}
		$head_title = '充值记录';
	break;
	case 'cash':
		$BANKS = explode('|', trim($MOD['cash_banks']));
		$_status = array('<span style="color:blue;">等待受理</span>', '<span style="color:#666666;">拒绝申请</span>', '<span style="color:red;">支付失败</span>', '<span style="color:green;">付款成功</span>');
		isset($fromtime) or $fromtime = '';
		isset($totime) or $totime = '';
		isset($type) or $type = 0;
		isset($bank) or $bank = '';
		if($bank) $condition .= " AND bank='$bank'";
		if($fromtime) $condition .= " AND addtime>".(strtotime($fromtime.' 00:00:00'));
		if($totime) $condition .= " AND addtime<".(strtotime($totime.' 23:59:59'));
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_cash WHERE $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$cashs = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_cash WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		$amount = $fee = 0;
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['edittime'] = $r['edittime'] ? timetodate($r['edittime'], 5) : '--';
			$r['dstatus'] = $_status[$r['status']];
			$amount += $r['amount'];
			$fee += $r['fee'];
			$cashs[] = $r;
		}
		$head_title = '提现记录';
	break;
	default:
		$BANKS = explode('|', trim($MOD['pay_banks']));
		$sfields = array('按条件', '金额', '银行', '事由', '备注');
		$dfields = array('reason', 'amount', 'bank', 'reason', 'note');
		isset($fields) && isset($dfields[$fields]) or $fields = 0;
		isset($fromtime) or $fromtime = '';
		isset($totime) or $totime = '';
		isset($type) or $type = 0;
		$fields_select = dselect($sfields, 'fields', '', $fields);
		if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
		if($fromtime) $condition .= " AND addtime>".(strtotime($fromtime.' 00:00:00'));
		if($totime) $condition .= " AND addtime<".(strtotime($totime.' 23:59:59'));
		if($type) $condition .= $type == 1 ? " AND amount>0" : " AND amount<0" ;
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_record WHERE $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$records = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}finance_record WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
		$income = $expense = 0;
		while($r = $db->fetch_array($result)) {
			$r['addtime'] = timetodate($r['addtime'], 5);
			$r['amount'] > 0 ? $income += $r['amount'] : $expense += $r['amount'];
			$records[] = $r;
		}
		$head_title = '资金流水';
	break;
}
include template('record', $module);
?>