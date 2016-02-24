<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$_status = array(
	'<span style="color:#0000FF;">买家发起订单<br/>等待卖家确认</span>',
	'<span style="color:#FF6600;">卖家已确认订单<br/>等待买家付款</span>',
	'<span style="color:#008080;">买家已付款<br/>等待卖家发货</span>',
	'<span style="color:#FF0000;">卖家已发货<br/>等待买家确认</span>',
	'<span style="color:#008000;">交易成功</span>',
	'<span style="color:#FF0000;text-decoration:underline;">买家申请退款</span>',
	'<span style="color:#0000FF;text-decoration:underline;">已退款给买家</span>',
	'<span style="color:#FF6600;text-decoration:underline;">已付款给卖家</span>',
	'<span style="color:#888888;text-decoration:line-through;">买家关闭交易</span>',
	'<span style="color:#888888;text-decoration:line-through;">卖家关闭交易</span>',
);
$dstatus = array(
	'买家发起订单,等待卖家确认',
	'卖家已确认订单,等待买家付款',
	'买家已付款,等待卖家发货',
	'卖家已发货,等待买家确认',
	'交易成功',
	'买家申请退款',
	'已退款给买家',
	'已付款给卖家',
	'买家关闭交易',
	'卖家关闭交易',
);
$step = isset($step) ? trim($step) : '';
if($action == 'update') {
	$itemid or message();
	$td = $db->get_one("SELECT * FROM {$DT_PRE}finance_trade WHERE itemid=$itemid");
	$td or message('订单不存在');
	$td['adddate'] = timetodate($td['addtime'], 5);
	$td['updatedate'] = timetodate($td['updatetime'], 5);
	switch($step) {
		case 'edit_price'://卖家修改价格
			if($td['status'] != 0 || $td['seller'] != $_username) message('您无权进行此操作');
			if($submit) {
				$fee = dround($fee);
				$fee or message('请填写附加金额');
				$fee_name = htmlspecialchars(trim($fee_name));
				$fee_name or message('请填写附加金额名称');
				$status = isset($confirm_order) ? 1 : 0;
				$db->query("UPDATE {$DT_PRE}finance_trade SET fee='$fee',fee_name='$fee_name',status='$status',updatetime='$DT_TIME' WHERE itemid=$itemid");
				message('订单修改成功', $forward, 5);
			} else {
				$head_title = '修改价格';
			}
		break;
		case 'detail'://订单详情
			if($td['buyer'] != $_username && $td['seller'] != $_username) message('您无权进行此操作');
			$td['total'] = $td['amount'] + $td['fee'];
			$head_title = '订单详情';
		break;
		case 'confirm_order'://卖家确认订单
			if($td['status'] != 0 || $td['seller'] != $_username) message('您无权进行此操作');
			$db->query("UPDATE {$DT_PRE}finance_trade SET status=1,updatetime='$DT_TIME' WHERE itemid=$itemid");

			$touser = $td['buyer'];
			$title = '站内交易提醒，您有一笔交易需要付款(T'.$itemid.')';
			$content = '卖家 <a href="'.userurl($_username).'" class="t">'.$_username.'</a> 于 <span class="f_gray">'.timetodate($DT_TIME, 5).'</span> 确认了您的订单<br/><a href="'.linkurl($MOD['linkurl'], 1).'trade.php?action=order&itemid='.$itemid.'" class="t" target="_blank">&raquo; 请点这里立即处理或查看详情</a>';
			$content = ob_template('messager', 'mail');
			send_message($touser, $title, $content);

			message('订单已确认，请等待买家付款', $forward, 5);
		break;
		case 'pay'://买家付款
			if($td['status'] != 1 || $td['buyer'] != $_username) message('您无权进行此操作');
			$money = $td['amount'] + $td['fee'];
			if($money > $_money) message('您的帐户余额不足，请先充值', $MOD['linkurl'].'charge.php?action=pay&amount='.($money-$_money));
			if($submit) {
				is_payword($_username, $password) or message('支付密码不正确');
				$db->query("UPDATE {$DT_PRE}member SET money=money-$money,money_lock=money_lock+$money WHERE username='$_username'");
				$db->query("UPDATE {$DT_PRE}finance_trade SET status='2',updatetime='$DT_TIME' WHERE itemid=$itemid");

				$touser = $td['seller'];
				$title = '站内交易提醒，您有一笔交易需要发货(T'.$itemid.')';
				$content = '买家 <a href="'.userurl($_username).'" class="t">'.$_username.'</a> 于 <span class="f_gray">'.timetodate($DT_TIME, 5).'</span> 支付了您的订单<br/><a href="'.linkurl($MOD['linkurl'], 1).'trade.php?itemid='.$itemid.'" class="t" target="_blank">&raquo; 请点这里立即处理或查看详情</a>';
				$content = ob_template('messager', 'mail');
				send_message($touser, $title, $content);

				message('支付成功，资金暂时被锁定，请等待卖家发货', $forward, 5);
			} else {
				$head_title = '订单支付';
			}
		break;
		case 'refund'://买家申请退款
			$gone = $DT_TIME - $td['updatetime'];//是否超时
			if($td['status'] != 3 || $td['buyer'] != $_username || $gone > ($MOD['trade_day']*86400 + $td['add_time']*3600)) message('您无权进行此操作');
			$money = $td['amount'] + $td['fee'];
			if($submit) {
				$content or message('请填写理由及证据');
				$db->query("UPDATE {$DT_PRE}finance_trade SET status='5',updatetime='$DT_TIME',buyer_reason='$content' WHERE itemid=$itemid");
				message('您的退款申请已经提交，请等待网站处理', $forward, 5);
			} else {
				$head_title = '申请退款';
			}
		break;
		case 'send_goods'://卖家发货
			if($td['status'] != 2 || $td['seller'] != $_username) message('您无权进行此操作');
			if($submit) {
				$send_type = htmlspecialchars($send_type);
				$send_no = htmlspecialchars($send_no);
				$send_time = htmlspecialchars($send_time);
				$db->query("UPDATE {$DT_PRE}finance_trade SET status='3',updatetime='$DT_TIME',send_type='$send_type',send_no='$send_no',send_time='$send_time' WHERE itemid=$itemid");

				$touser = $td['buyer'];
				$title = '站内交易提醒，您有一笔交易需要收货(T'.$itemid.')';
				$content = '卖家 <a href="'.userurl($_username).'" class="t">'.$_username.'</a> 于 <span class="f_gray">'.timetodate($DT_TIME, 5).'</span> 已经发货<br/><a href="'.linkurl($MOD['linkurl'], 1).'trade.php?action=order&itemid='.$itemid.'" class="t" target="_blank">&raquo; 请点这里立即处理或查看详情</a>';
				$content = ob_template('messager', 'mail');
				send_message($touser, $title, $content);

				message('已经确认发货，请等待买家确认收货', $forward, 5);
			} else {
				$head_title = '确认发货';
				$send_types = explode('|', trim($MOD['send_types']));
				$send_time = timetodate($DT_TIME, 5);
			}
		break;
		case 'add_time'://卖家延迟买家确认时间
			if($td['status'] != 3 || $td['seller'] != $_username) message('您无权进行此操作');
			if($submit) {
				$add_time = intval($add_time);
				if(!$add_time) message('延长的时间不能为0');
				$db->query("UPDATE {$DT_PRE}finance_trade SET add_time='$add_time' WHERE itemid=$itemid");
				message('买家确认时间延长成功', $forward);
			} else {
				$head_title = '延长买家确认时间';
			}
		break;
		case 'receive_goods'://确认收货
			$gone = $DT_TIME - $td['updatetime'];//是否超时
			if($td['status'] != 3 || $td['buyer'] != $_username || $gone > ($MOD['trade_day']*86400 + $td['add_time']*3600)) message('您无权进行此操作');
			$money = $td['amount'] + $td['fee'];
			money_lock($_username, -$money);
			record_add($_username, -$money, '站内', 'system', '订单货到付款', '('.$td['seller'].')订单流水号:'.$itemid);
			money_add($td['seller'], $money);
			record_add($td['seller'], $money, '站内', 'system', '订单货到付款', '('.$_username.')订单流水号:'.$itemid);
			$db->query("UPDATE {$DT_PRE}finance_trade SET status='4',updatetime='$DT_TIME' WHERE itemid=$itemid");

			$touser = $td['seller'];
			$title = '站内交易提醒，您有一笔交易已经成功(T'.$itemid.')';
			$content = '买家 <a href="'.userurl($_username).'" class="t">'.$_username.'</a> 于 <span class="f_gray">'.timetodate($DT_TIME, 5).'</span> 确认收货，交易完成<br/><a href="'.linkurl($MOD['linkurl'], 1).'trade.php?itemid='.$itemid.'" class="t" target="_blank">&raquo; 请点这里立即处理或查看详情</a>';
			$content = ob_template('messager', 'mail');
			send_message($touser, $title, $content);

			message('恭喜！此订单交易成功', $forward, 5);
		break;
		case 'get_pay'://买家处理订单超时，卖家请求付款
			$gone = $DT_TIME - $td['updatetime'];
			if($td['status'] != 3 || $td['seller'] != $_username || $gone < ($MOD['trade_day']*86400 + $td['add_time']*3600)) message('您无权进行此操作');
			$money = $td['amount'] + $td['fee'];
			money_lock($td['buyer'], -$money);
			record_add($td['buyer'], -$money, '站内', 'system', '订单货到付款', '('.$td['seller'].')订单流水号'.$itemid.'[买家超时]');
			money_add($_username, $money);
			record_add($_username, $money, '站内', 'system', '订单货到付款', '('.$_username.')订单流水号:'.$itemid.'[买家超时]');
			$db->query("UPDATE {$DT_PRE}finance_trade SET status='4',updatetime='$DT_TIME' WHERE itemid=$itemid");
			message('恭喜！已收到货款，此订单交易成功', $forward, 5);
			
		break;
		case 'close'://关闭交易
			if($_username == $td['seller']) {//卖家关闭
				if($td['status'] == 0) {//关闭未确认的交易
					$db->query("UPDATE {$DT_PRE}finance_trade SET status='9',updatetime='$DT_TIME' WHERE itemid=$itemid");
					dmsg('交易已关闭', $forward);
				} else if($td['status'] == 1) {//关闭已确认的交易
					$db->query("UPDATE {$DT_PRE}finance_trade SET status='9',updatetime='$DT_TIME' WHERE itemid=$itemid");
					dmsg('交易已关闭', $forward);
				} else if($td['status'] == 2) {//关闭已付款的交易
					$money = $td['amount'] + $td['fee'];
					$db->query("UPDATE {$DT_PRE}member SET money=money+$money,money_lock=money_lock-$money WHERE username='$td[buyer]'");
					$db->query("UPDATE {$DT_PRE}finance_trade SET status='9',updatetime='$DT_TIME' WHERE itemid=$itemid");
					dmsg('交易已关闭', $forward);
				} else if($td['status'] == 8) {//直接删除买家关闭的交易
					$db->query("DELETE FROM {$DT_PRE}finance_trade WHERE itemid=$itemid");
					dmsg('订单删除成功', $forward);
				} else { 
					message('您无权进行此操作');
				}
				message('交易已关闭', $forward);
			} else if($_username == $td['buyer']) {//买家关闭
				if($td['status'] == 0) {//卖家尚未确认，直接删除
					$db->query("DELETE FROM {$DT_PRE}finance_trade WHERE itemid=$itemid");
					dmsg('订单删除成功', $forward);
				} else if($td['status'] == 1) {//卖家已确认的交易，买家关闭
					$db->query("UPDATE {$DT_PRE}finance_trade SET status='8',updatetime='$DT_TIME' WHERE itemid=$itemid");
					dmsg('交易已关闭', $forward);
				} else if($td['status'] == 9) {//直接删除卖家关闭的交易
					$db->query("DELETE FROM {$DT_PRE}finance_trade WHERE itemid=$itemid");
					dmsg('订单删除成功', $forward);
				} else {
					message('您无权进行此操作');
				}
			}
		break;
	}
} else if($action == 'pay') {
	$MG['trade_pay'] or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');
	if($submit) {
		$seller = trim($seller);
		$seller or message('请填写收款会员名');
		$seller == $_username and message('收款人不能是自己');
		is_user($seller) or message('收款会员名不存在，请确认');
		$amount = dround($amount);
		$amount > 0 or message('请填写付款金额');
		$note = htmlspecialchars($note);
		$note or message('请填写付款说明');
		is_payword($_username, $password) or message('支付密码不正确');
		if($type) {//直接付款
			$_money > $amount or message('您的帐户余额不足，请先充值', $MOD['linkurl'].'charge.php?action=pay&amount='.($amount-$_money));
			set_cookie('uploads', '');
			money_add($_username, -$amount);
			record_add($_username, -$amount, '站内', 'system', '站内付款', '('.$seller.')'.$note);
			money_add($seller, $amount);
			record_add($seller, $amount, '站内', 'system', '站内收款', '('.$_username.')'.$note);
			$touser = $seller;
			$title = '站内收入提醒，您收到一笔付款';
			$content = '<a href="'.userurl($_username).'" class="t">'.$_username.'</a> 于 <span class="f_gray">'.timetodate($DT_TIME, 5).'</span> 向您支付了 <span class="f_blue">'.$amount.'元</span> 的站内付款<br/>备注：<span class="f_gray">'.$note.'</span>';
			$content = ob_template('messager', 'mail');
			send_message($touser, $title, $content);
			message('直接付款成功，会员['.$seller.']将直接收到您的付款', $MOD['linkurl'].'record.php', 5);
		} else {
			$title = htmlspecialchars($title);
			$title or message('请填写商品或服务名称');
			$linkurl = $linkurl && $linkurl != 'http://' ? htmlspecialchars($linkurl) : '';
			$thumb = htmlspecialchars($thumb);
			$buyer_postcode = htmlspecialchars($buyer_postcode);
			$buyer_address = htmlspecialchars($buyer_address);
			$buyer_name = htmlspecialchars($buyer_name);
			$buyer_phone = htmlspecialchars($buyer_phone);
			clear_upload($thumb);
			$db->query("INSERT INTO {$DT_PRE}finance_trade (buyer,seller,title,linkurl,thumb,amount,addtime,updatetime,note, buyer_postcode,buyer_address,buyer_name,buyer_phone) VALUES ('$_username','$seller','$title','$linkurl','$thumb','$amount','$DT_TIME','$DT_TIME','$note','$buyer_postcode','$buyer_address','$buyer_name','$buyer_phone')");

			$itemid = $db->insert_id();
			$touser = $seller;
			$_title = $title;
			$title = '站内交易提醒，您有一笔交易需要确认(T'.$itemid.')';
			$content = '<a href="'.userurl($_username).'" class="t">'.$_username.'</a> 于 <span class="f_gray">'.timetodate($DT_TIME, 5).'</span> 向您订购了：<br/>'.($linkurl ? '<a href="'.$linkurl.'" target="_blank" class="t">' : '').'<strong>'.$_title.'</strong>'.($linkurl ? '</a>' : '').'<br/>交易流水号为：<span class="f_red">'.$itemid.'</span> &nbsp;订单金额为：<span class="f_blue">'.$amount.'元</span><br/><a href="'.linkurl($MOD['linkurl'], 1).'trade.php?itemid='.$itemid.'" class="t" target="_blank">&raquo; 请点这里立即处理或查看详情</a>';
			$content = ob_template('messager', 'mail');
			send_message($touser, $title, $content);

			message('订单已经发出，请等待卖家确认', '?action=order', 5);
		}
	} else {
		$m = $db->get_one("SELECT m.truename,m.mobile,c.postcode,c.address FROM {$DT_PRE}member m,{$DT_PRE}company c WHERE m.userid=c.userid AND m.userid='$_userid'");
		$head_title = '我要付款';
	}
} else if($action == 'order') {
	$MG['trade_buy'] or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');
	$sfields = array('按条件', '商品或服务', '金额', '附加金额', '附加名称', '卖家', '发货方式', '物流号码', '备注');
	$dfields = array('title', 'title ', 'amount', 'fee', 'fee_name', 'seller', 'send_type', 'send_no', 'note');
	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($fromtime) or $fromtime = '';
	isset($totime) or $totime = '';
	$status = isset($status) && isset($dstatus[$status]) ? intval($status) : '';
	$fields_select = dselect($sfields, 'fields', '', $fields);
	$status_select = dselect($dstatus, 'status', '状态', $status, '', 1, '', 1);
	$condition = "buyer='$_username'";
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($fromtime) $condition .= " AND addtime>".(strtotime($fromtime.' 00:00:00'));
	if($totime) $condition .= " AND addtime<".(strtotime($totime.' 23:59:59'));
	if($status !== '') $condition .= " AND status='$status'";
	if($itemid) $condition .= " AND itemid='$itemid'";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_trade WHERE $condition");
	$pages = pages($r['num'], $page, $pagesize);		
	$trades = array();
	$result = $db->query("SELECT * FROM {$DT_PRE}finance_trade WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
	$amount = $fee = $money = 0;
	while($r = $db->fetch_array($result)) {
		if($r['status'] == 3) {
			$gone = $DT_TIME - $r['updatetime'];
			if($gone > ($MOD['trade_day']*86400 + $r['add_time']*3600)) {
				$r['lefttime'] = 0;
			} else {
				$r['lefttime'] = secondstodate($MOD['trade_day']*86400 + $r['add_time']*3600 - $gone);
			}
		}
		$r['addtime'] = str_replace(' ', '<br/>', timetodate($r['addtime'], 5));
		$r['updatetime'] = str_replace(' ', '<br/>', timetodate($r['updatetime'], 5));
		$r['dstatus'] = $_status[$r['status']];
		$r['money'] = $r['amount'] + $r['fee'];
		$amount += $r['amount'];
		$fee += $r['fee'];
		$trades[] = $r;
	}
	$money = $amount + $fee;
	$forward = urlencode($DT_URL);
	$head_title = '发出的订单';
} else {
	$MG['trade_sell'] or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');
	$sfields = array('按条件', '商品或服务', '金额', '附加金额', '附加名称', '买家', '买家姓名', '买家地址', '买家邮编', '买家电话', '发货方式', '物流号码', '备注');
	$dfields = array('title', 'title ', 'amount', 'fee', 'fee_name', 'buyer', 'buyer_name', 'buyer_address', 'buyer_postcode', 'buyer_phone', 'send_type', 'send_no', 'note');
	isset($fields) && isset($dfields[$fields]) or $fields = 0;
	isset($fromtime) or $fromtime = '';
	isset($totime) or $totime = '';
	$status = isset($status) && isset($dstatus[$status]) ? intval($status) : '';
	$fields_select = dselect($sfields, 'fields', '', $fields);
	$status_select = dselect($dstatus, 'status', '状态', $status, '', 1, '', 1);
	$condition = "seller='$_username'";
	if($keyword) $condition .= " AND $dfields[$fields] LIKE '%$keyword%'";
	if($fromtime) $condition .= " AND addtime>".(strtotime($fromtime.' 00:00:00'));
	if($totime) $condition .= " AND addtime<".(strtotime($totime.' 23:59:59'));
	if($status !== '') $condition .= " AND status='$status'";
	if($itemid) $condition .= " AND itemid='$itemid'";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_trade WHERE $condition");
	$pages = pages($r['num'], $page, $pagesize);		
	$trades = array();
	$result = $db->query("SELECT * FROM {$DT_PRE}finance_trade WHERE $condition ORDER BY itemid DESC LIMIT $offset,$pagesize");
	$amount = $fee = $money = 0;
	while($r = $db->fetch_array($result)) {
		if($r['status'] == 3) {
			$gone = $DT_TIME - $r['updatetime'];
			if($gone > ($MOD['trade_day']*86400 + $r['add_time']*3600)) {
				$r['lefttime'] = 0;
			} else {
				$r['lefttime'] = secondstodate($MOD['trade_day']*86400 + $r['add_time']*3600 - $gone);
			}
		}
		$r['addtime'] = str_replace(' ', '<br/>', timetodate($r['addtime'], 5));
		$r['updatetime'] = str_replace(' ', '<br/>', timetodate($r['updatetime'], 5));
		$r['dstatus'] = $_status[$r['status']];
		$r['money'] = $r['amount'] + $r['fee'];
		$amount += $r['amount'];
		$fee += $r['fee'];
		$trades[] = $r;
	}
	$money = $amount + $fee;
	$forward = urlencode($DT_URL);
	$head_title = '收到的订单';
}
include template('trade', $module);
?>