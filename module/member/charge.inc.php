<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$PAY = cache_read('pay.php');
$amount = isset($amount) ? dround($amount) : '';
switch($action) {
	case 'card':
		if(!preg_match("/^[0-9a-zA-z]{6,}$/", $number)) message('请填写正确的充值卡卡号');
		if(!preg_match("/^[0-9]{6,}$/", $password)) message('请填写正确的充值卡密码');
		$card = $db->get_one("SELECT * FROM {$DT_PRE}finance_card WHERE number='$number'");
		if($card) {
			if($card['updatetime']) message('充值卡无效');
			if($card['totime'] < $DT_TIME) message('充值卡已过有效期');
			if($card['password'] != $password) message('充值卡密码错误');
			$db->query("INSERT INTO {$DT_PRE}finance_charge (username,bank,amount,money,sendtime,receivetime,editor,status,note) VALUES ('$_username','card', '$card[amount]','$card[amount]','$DT_TIME','$DT_TIME','system','3','$number')");
			$db->query("UPDATE {$DT_PRE}finance_card SET username='$_username',updatetime='$DT_TIME',ip='$DT_IP' WHERE itemid='$card[itemid]'");
			money_add($_username, $card['amount']);
			record_add($_username, $card['amount'], '充值卡', 'system', '充值卡充值', '卡号:'.$number);
			message('充值卡充值成功', $MOD['linkurl'].'record.php?action=charge');
		} else {
			message('无效的充值卡卡号');
		}
	break;
	case 'confirm':
		$amount or message('请填写支付金额');
		isset($PAY[$bank]) or message('请选择支付平台');
		$PAY[$bank]['enable'] or message('此支付平台尚未启用');
		$fee = $PAY[$bank]['percent'] ? dround($amount*$PAY[$bank]['percent']/100) : 0;
		$charge = $fee + $amount;
		if($submit) {
			$db->query("INSERT INTO {$DT_PRE}finance_charge (username,bank,amount,fee,sendtime) VALUES ('$_username','$bank','$amount','$fee','$DT_TIME')");
			$orderid = $db->insert_id();
			$receive_url = linkurl($MOD['linkurl'], 1).'charge.php';
			include DT_ROOT.'/api/'.$bank.'/send.inc.php';
			exit;
		} else {
			$head_title = '充值确认';
		}
	break;
	case 'pay':
		$head_title = '帐户充值';
	break;
	default:
		$_POST = $_DPOST;
		$_GET = $_DGET;
		$head_title = '完成充值';
		//$passed = true;
		$charge_errcode = '';
		$charge_status = 0;
		/*
		0 支付失败
		1 支付成功
		2 支付异常 与客服联系
		*/
		$r = $db->get_one("SELECT * FROM {$DT_PRE}finance_charge WHERE username='$_username' ORDER BY itemid DESC");
		if($r) {
			$charge_orderid = $r['itemid'];
			$charge_money = $r['amount'] + $r['fee'];
			$charge_amount = $r['amount'];
			if($r['status'] == 0) {
				$receive_url = '';
				$bank = $r['bank'];
				$editor = 'R'.$bank;
				$note = '';
				include DT_ROOT.'/api/'.$bank.'/receive.inc.php';
				if($charge_status == 1) {
					$db->query("UPDATE {$DT_PRE}finance_charge SET status=3,money=$charge_money,receivetime='$DT_TIME',editor='$editor' WHERE itemid=$charge_orderid");
					money_add($r['username'], $r['amount']);
					record_add($r['username'], $r['amount'], $PAY[$bank]['name'], 'system', '在线充值', '订单ID:'.$charge_orderid);
				} else {
					$db->query("UPDATE {$DT_PRE}finance_charge SET status=1,receivetime='$DT_TIME',editor='$editor',note='$note' WHERE itemid=$charge_orderid");//支付失败
				}
			} else if($r['status'] == 1) {
				$charge_status = 2;		
				$charge_errcode = '订单状态为失败，ID'.$charge_orderid;
			} else if($r['status'] == 2) {
				$charge_status = 2;		
				$charge_errcode = '订单状态为作废，ID'.$charge_orderid;
			} else {
				$charge_status = 1;
			}
		} else {
			$charge_status = 2;		
			$charge_errcode = '未找到充值纪录';
		}
		break;
	}
	include template('charge', $module);
?>