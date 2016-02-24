<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';

$itemid or dheader($MOD['linkurl']);

$MG['trade_buy'] or daler('您所在的会员组没有权限使用此功能，请升级', 'goback');

$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid AND status>2");
$item or dalert('信息不存在或正在审核', $MOD['linkurl']);
extract($item);

if($totime && $DT_TIME > $totime) dalert('此信息已过期', 'goback');
if(!$price || !$unit || !$minamount) dalert('此信息未设置价格或计量单位或起订量，无法在线订购', 'goback');
if($username) {
	if($_username == $username) dalert('请不要给自己的信息下单', 'goback');
} else {
	dalert('该企业未注册本站会员，无法收到订单', 'goback');
}

$linkurl = linkurl($MOD['linkurl'].$linkurl, 1);
$userurl = userurl($username);
$thumb = $thumb ? imgurl($thumb, 1) : '';
$linkurl = linkurl($linkurl, 1);
if($submit) {
	if(!$number) message('请填写订货总量');
	if($minamount && $number < $minamount) message('订货总量不能小于最小起订量');
	if($amount && $number > $amount) message('订货总量不能大于供货总量');
	$order_amount = dround($number*$price);
	$user = userinfo($_username);
	$head_title = '确认订单'.$DT['seo_delimiter'].$title;
} else {
	$head_title = '订购产品'.$DT['seo_delimiter'].$title;
}
include template('order', $module);
?>