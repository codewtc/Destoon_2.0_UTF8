<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';

$this_month = date('n', $DT_TIME);
$this_year  = date('Y', $DT_TIME);
$next_month = $this_month == 12 ? 1 : $this_month + 1;
$next_year  = $this_month == 12 ? $this_year + 1 : $this_year;
$next_time = strtotime($next_year.$next_month.'01 00:00:00');
$spread_max = $MOD['spread_max'] ? $MOD['spread_max'] : 10;

if($action == 'buy') {
	$MG['spread'] or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');

	$spread_url = extendurl('spread');
	$kw or dheader($spread_url);
	$r = $db->get_one("SELECT * FROM {$DT_PRE}spread WHERE username='$_username' AND mid=$mid AND word='$kw' AND totime>$DT_TIME");
	if($r) message('您已经购买过此关键字了', $spread_url);
	isset($mid) or $mid = 5;
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}spread WHERE mid=$mid AND status=3 AND word='$kw' AND fromtime>=$next_time");
	if($r['num'] > $spread_max) message('本轮竞价已经结束', $spread_url);
	$p = $db->get_one("SELECT * FROM {$DT_PRE}spread_price WHERE word='$kw'");
	if($mid == 4) {
		$price = $p['company_price'] ? $p['company_price'] : $MOD['spread_company_price'];
	} else if($mid == 5) {
		$price = $p['sell_price'] ? $p['sell_price'] : $MOD['spread_sell_price'];
	} else if($mid == 6) {
		$price = $p['buy_price'] ? $p['buy_price'] : $MOD['spread_buy_price'];
	} else {
		dheader($spread_url);
	}
	$step = $MOD['spread_step'];
	$month = $MOD['spread_month'] ? $MOD['spread_month'] : 1;
	if($submit) {
		$buy_price = dround($buy_price);
		if($buy_price < $price) message('出价不能低于起价');
		if(($buy_price-$price)%$step != 0) message('请按加价幅度加价');
		$buy_month = intval($buy_month);
		if($buy_month < 1 || $buy_month > $month) message('请选择正确的月份');
		$amount = $buy_price*$buy_month;
		if($amount > $_money) message('帐户余额不足，请充值');
		is_payword($_username, $password) or message('您的支付密码不正确');
		$buy_tid = $mid == 4 ? $_userid : intval($buy_tid);
		if(!$buy_tid) message('请填写信息ID');
		if($mid == 5 || $mid == 6) {
			$table = $DT_PRE.($mid == 5 ? 'sell' : 'buy');
			$item = $db->get_one("SELECT itemid FROM {$table} WHERE itemid='$buy_tid' AND status=3 AND username='$_username'");
			if(!$item) message('信息ID不是您发布的，请核实');
		}
		$months = $next_month + $buy_month;
		$year = floor($months/12);
		if($months%12 == 0) {
			$to_month = 12;
			$to_year = $next_year + $year - 1;
		} else {
			$to_month = $months%12;
			$to_year = $next_year + $year;
		}
		$totime = strtotime($to_year.$to_month.'01 00:00:00');
		$status = $MOD['spread_check'] ? 2 : 3;
		money_add($_username, -$amount);
		record_add($_username, -$amount, '站内', 'system', '站内支付', $kw.$MODULE[$mid]['name'].'排名'.'(信息ID:'.$buy_tid.')');
		$db->query("INSERT INTO {$DT_PRE}spread (mid,tid,word,price,company,username,addtime,fromtime,totime,status) VALUES ('$mid','$buy_tid','$kw','$buy_price','$_company','$_username','$DT_TIME','$next_time','$totime','$status')");
		message('关键词购买成功', $spread_url.'index.php?kw='.urlencode($kw));
	} else {
		$item = $db->get_one("SELECT itemid FROM {$DT_PRE}spread ORDER BY rand()");
		$destoon_task = "moduleid=$moduleid&html=spread&itemid=$item[itemid]";
		$title = urlencode('购买'.$MODULE[$mid]['name'].$kw.'排名');
		$head_title = $kw.$DT['seo_delimiter'].'排名推广';
		include template('spread_buy', $module);
	}
} else {
	$head_title = '排名推广';
	if($kw) {
		$p = $db->get_one("SELECT * FROM {$DT_PRE}spread_price WHERE word='$kw'");
		if($p) {
			$sell_price = $p['sell_price'] ? $p['sell_price'] : $MOD['spread_sell_price'];
			$buy_price = $p['buy_price'] ? $p['buy_price'] : $MOD['spread_buy_price'];
			$company_price = $p['company_price'] ? $p['company_price'] : $MOD['spread_company_price'];
		} else {
			$sell_price = $MOD['spread_sell_price'];
			$buy_price = $MOD['spread_buy_price'];
			$company_price = $MOD['spread_company_price'];
		}
		$head_title = $kw.$DT['seo_delimiter'].$head_title;
		$sell_record = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}spread WHERE mid=5 AND status=3 AND word='$kw' AND fromtime>=$next_time ORDER BY price DESC,itemid ASC");
		while($r = $db->fetch_array($result)) {
			$sell_record[] = $r;
		}
		$sell_count = count($sell_record);

		$buy_record = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}spread WHERE mid=6 AND status=3 AND word='$kw' AND fromtime>=$next_time ORDER BY price DESC,itemid ASC");
		while($r = $db->fetch_array($result)) {
			$buy_record[] = $r;
		}
		$buy_count = count($buy_record);

		$company_record = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}spread WHERE mid=4 AND status=3 AND word='$kw' AND fromtime>=$next_time ORDER BY price DESC,itemid ASC");
		while($r = $db->fetch_array($result)) {
			$company_record[] = $r;
		}
		$company_count = count($company_record);
	}	
	$item = $db->get_one("SELECT itemid FROM {$DT_PRE}spread ORDER BY rand()");
	$destoon_task = "moduleid=$moduleid&html=spread&itemid=$item[itemid]";
	include template('spread', $module);
}
?>