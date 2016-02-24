<?php
/* 网银在线 http://www.chinabank.com.cn 接受服务器通知地址 */
$_DPOST = $_POST;
require '../common.inc.php';
error_reporting(0);
$_POST = $_DPOST;
if(!$_POST) exit('fail');
$bank = 'chinabank';
$PAY = cache_read('pay.php');
if(!$PAY[$bank]['enable']) exit('error');
if(!$PAY[$bank]['keycode']) exit('error');
require DT_ROOT.'/module/member/global.func.php';
$key = $PAY[$bank]['keycode'];
$v_oid     =trim($_POST['v_oid']);      
$v_pmode   =trim($_POST['v_pmode']);      
$v_pstatus =trim($_POST['v_pstatus']);      
$v_pstring =trim($_POST['v_pstring']);      
$v_amount  =trim($_POST['v_amount']);     
$v_moneytype  =trim($_POST['v_moneytype']);     
$remark1   =trim($_POST['remark1' ]);     
$remark2   =trim($_POST['remark2' ]);     
$v_md5str  =trim($_POST['v_md5str' ]);     
                           
$md5string = strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key)); //拼凑加密串
if($v_md5str == $md5string) {	
   if($v_pstatus == "20") {
		//支付成功
		//商户系统的逻辑处理（例如判断金额，判断支付状态(20成功,30失败),更新订单状态等等）
		$r = $db->get_one("SELECT * FROM {$DT_PRE}finance_charge WHERE itemid='$v_oid'");
		if($r) {
			if($r['status'] == 0) {
				$charge_orderid = $r['itemid'];
				$charge_money = $r['amount'] + $r['fee'];
				$charge_amount = $r['amount'];
				$editor = 'N'.$bank;
				if($v_amount == $charge_money) {
					$db->query("UPDATE {$DT_PRE}finance_charge SET status=3,money=$charge_money,receivetime='$DT_TIME',editor='$editor' WHERE itemid=$charge_orderid");
					money_add($r['username'], $r['amount']);
					record_add($r['username'], $r['amount'], $PAY[$bank]['name'], 'system', '在线充值', '订单ID:'.$charge_orderid);
					exit('ok');
				} else {
					$note = '充值金额不匹配S:'.$charge_money.'R:'.$v_amount;
					$db->query("UPDATE {$DT_PRE}finance_charge SET status=1,receivetime='$DT_TIME',editor='$editor',note='$note' WHERE itemid=$charge_orderid");//支付失败
					log_write($note, 'nchinabank');
					exit('error');
				}
			} else if($r['status'] == 1) {
				exit('error');
			} else if($r['status'] == 2) {
				exit('error');
			} else {
				exit('ok');
			}
		} else {
			log_write('通知订单号不存在R:'.$v_oid, 'nchinabank');
			exit('error');
		}
	}
}
exit('error');
?>