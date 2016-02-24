<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';

$MG['cash'] or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');

$MOD['cash_enable'] or message('系统暂未开启会员提现', $MOD['linkurl'], 5);
require DT_ROOT.'/include/post.func.php';
$member = $db->get_one("SELECT truename,password,money,bank,account FROM {$DT_PRE}member WHERE username='$_username'");
$BANKS = explode('|', trim($MOD['cash_banks']));
switch($action) {
	case 'setting':
		if($submit) {
			is_payword($_username, $password) or message('支付密码不正确');
			in_array($bank, $BANKS) or message('请选择收款方式');
			$account or message('请填写收款帐号');
			$account = htmlspecialchars($account);
			$db->query("UPDATE {$DT_PRE}member SET bank='$bank',account='$account' WHERE username='$_username' ");
			dmsg('设置成功', $MOD['linkurl'].'cash.php?success=1');
		} else {
			$bank_select = '<select name="bank"><option value="">请选择</option>';
			foreach($BANKS as $k=>$v) {
				$bank_select .= '<option value="'.$v.'"'.($v == $member['bank'] ? 'selected' : '').'>'.$v.'</option>';
			}
			$bank_select .= '</select>';
			$head_title = '帐号设置';
		}
	break;
	case 'confirm':
		$amount or message('请填写提现金额');
		if($MOD['cash_min'] && $amount < $MOD['cash_min']) message('单次提现最小金额为:'.$MOD['cash_min']);
		if($MOD['cash_max'] && $amount > $MOD['cash_max']) message('单次提现最大金额为:'.$MOD['cash_max']);
		if($MOD['cash_times']) {
			$r = $db->get_one("SELECT COUNT(*) as num FROM {$DT_PRE}finance_cash WHERE username='$_username' AND addtime>$DT_TIME-3600*24");
			if($r['num'] >= $MOD['cash_times']) message('24小时内最多可提现'.$MOD['cash_times'].'次，请稍候再操作', $MOD['linkurl'].'record.php?action=cash', 6);
		}
		$amount = dround($amount);
		$fee = 0;
		if($MOD['cash_fee']) {
			$fee = dround($amount*$MOD['cash_fee']/100);
			if($MOD['cash_fee_min'] && $fee < $MOD['cash_fee_min']) $fee = $MOD['cash_fee'];
			if($MOD['cash_fee_max'] && $fee > $MOD['cash_fee_max']) $fee = $MOD['cash_fee'];
		}
		$money = $amount + $fee;
		if($submit) {
			captcha($captcha);
			if($money > $_money) message('提现金额大于可用余额');
			is_payword($_username, $password) or message('支付密码不正确');
			$db->query("INSERT INTO {$DT_PRE}finance_cash (username,bank,account,truename,amount,fee,addtime,ip) VALUES ('$_username','$member[bank]','$member[account]','$member[truename]','$amount','$fee','$DT_TIME','$DT_IP')");
			$db->query("UPDATE {$DT_PRE}member SET money=money-$money,money_lock=money_lock+$money WHERE username='$_username'");
			message('您的提现申请已经提交，请等待工作人员的处理<br/>在此期间，该笔资金将被冻结', $MOD['linkurl'].'record.php?action=cash', 6);
		} else {
			$head_title = '提现确认';
		}
	break;
	default:
		if(!$member['bank'] || !$member['account']) message('请先设置收款帐号', '?action=setting');
		$head_title = '申请提现';
	break;
}
include template('cash', $module);
?>