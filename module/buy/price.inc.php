<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';

$itemid = intval($itemid);
$itemid or dheader($MOD['linkurl']);

$MG['price_limit'] > -1 or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');

$limit_used = $limit_free = 0;
if($MG['price_limit']) {
	$today = strtotime(timetodate($DT_TIME, 3).' 00:00:00');
	$sql = $_userid ? "fromuser='$_username'" : "ip='$DT_IP'";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}message WHERE $sql AND addtime>$today AND typeid=2 AND status=3");
	$limit_used = $r['num'];
	$limit_used < $MG['price_limit'] or dalert('今日可报价'.$MG['price_limit'].'次 当前已报价'.$limit_used.'次', 'goback');
	$limit_free = $MG['price_limit'] > $limit_used ? $MG['price_limit'] - $limit_used : 0;
}

$item = $db->get_one("SELECT title,linkurl,totime,username,company,vip FROM {$table} WHERE itemid=$itemid AND status>2");
$item or dalert('信息不存在或正在审核', $MOD['linkurl']);

if($item['totime'] && $DT_TIME > $item['totime']) dalert('此信息已过期', 'goback');
if($item['username']) {
	if($_username == $item['username']) dalert('请不要给自己的信息报价', 'goback');
} else {
	dalert('该企业未注册本站会员，无法收到报价', 'goback');
}

$linkurl = linkurl($MOD['linkurl'].$item['linkurl'], 1);
require DT_ROOT.'/include/post.func.php';
if($_userid) {
	$user = userinfo($_username);
	$company = $user['company'];
	$truename = $user['truename'];
	$telephone = $user['telephone'] ? $user['telephone'] : $user['mobile'];
	$email = $user['mail'] ? $user['mail'] : $user['email'];
	$qq = $user['qq'];
	$msn = $user['msn'];
}
$need_captcha = $MOD['captcha_price'] == 2 ? $MG['captcha'] : $MOD['captcha_price'];
$need_question = $MOD['question_price'] == 2 ? $MG['question'] : $MOD['question_price'];
if($submit) {
	captcha($captcha, $need_captcha);
	question($answer, $need_question);
	$title = htmlspecialchars(trim($title));
	if(!$title) message('请填写主题');
	$content = htmlspecialchars(trim($content));
	if(!$content) message('请填写内容');
	if(!$_userid) {
		$truename = htmlspecialchars(trim($truename));
		if(!$truename)  message('请填写联系人');
		if(!is_email($email)) message('请填写电子邮件');
		$telephone = htmlspecialchars(trim($telephone));
		$company = htmlspecialchars(trim($company));
		$qq = htmlspecialchars(trim($qq));
		$msn = htmlspecialchars(trim($msn));
	}
	$content = nl2br($content);
	$content .= '<hr size="1"/>联系人：'.$truename;
	$content .= '<br/>电子邮件：'.$email;
	if($company) $content .= '<br/>公司名：'.$company;
	if($telephone) $content .= '<br/>联系电话：'.$telephone;
	if(is_numeric($qq)) $content .= '<br/>联系QQ：'.$qq;
	if(is_email($msn)) $content .= '<br/>联系MSN：'.$msn;
	if(is_date($date)) $content .= '<hr size="1"/>我希望在 '.$date.' 之前回复';	
	$message = '产品信息：<a href="'.$linkurl.'"><strong>'.$item['title'].'</strong></a><br/>'.$content;
	if(send_message($item['username'], $title, $message, 2, $_username)) {
		message('报价单发送成功', $linkurl);
	} else {
		dalert($_userid ? '报价发送失败，对方可能拒绝您的报价' : '报价发送失败，对方可能拒绝非会员的报价', $linkurl);
	}
} else {	
	$iask = explode('|', trim($MOD['price_ask']));
	$date = timetodate($DT_TIME + 5*86400, 3);
	$title = '我对您发布的“'.$item['title'].'”很感兴趣';
	$head_title = '报价单'.$DT['seo_delimiter'].$item['title'].$DT['seo_delimiter'].$MOD['name'];
	include template('price', $module);
}
?>