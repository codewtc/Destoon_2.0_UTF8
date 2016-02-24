<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';

$MG['inquiry_limit'] > -1 or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');
$limit_used = $limit_free = 0;
if($MG['inquiry_limit']) {
	if(is_array($itemid) && count($itemid) > $MG['inquiry_limit']) dalert('最多可选择 '.$MG['inquiry_limit'].' 条信息', 'goback');
	$today = strtotime(timetodate($DT_TIME, 3).' 00:00:00');
	$sql = $_userid ? "fromuser='$_username'" : "ip='$DT_IP'";
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}message WHERE $sql AND addtime>$today AND typeid=1 AND status=3");
	$limit_used = $r['num'];
	$limit_used < $MG['inquiry_limit'] or dalert('今日可询价'.$MG['inquiry_limit'].'次 当前已询价'.$limit_used.'次', 'goback');
	$limit_free = $MG['inquiry_limit'] > $limit_used ? $MG['inquiry_limit'] - $limit_used : 0;
}

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
$need_captcha = $MOD['captcha_inquiry'] == 2 ? $MG['captcha'] : $MOD['captcha_inquiry'];
$need_question = $MOD['question_inquiry'] == 2 ? $MG['question'] : $MOD['question_inquiry'];
if($submit) {
	preg_match("/^[0-9\,]{1,}$/", $itemids) or dalert('请指定需要询价的信息', 'goback');
	captcha($captcha, $need_captcha);
	question($answer, $need_question);
	$title = htmlspecialchars(trim($title));
	if(!$title) message('请填写询价标题');
	$content = htmlspecialchars(trim($content));
	if(!$content) message('请填写询价内容');
	if(!$_userid) {
		$truename = htmlspecialchars(trim($truename));
		if(!$truename)  message('请填写联系人');
		if(!is_email($email)) message('请填写电子邮件');
		$telephone = htmlspecialchars(trim($telephone));
		$company = htmlspecialchars(trim($company));
		$qq = htmlspecialchars(trim($qq));
		$msn = htmlspecialchars(trim($msn));
	}
	$type = htmlspecialchars(implode(',', $type));
	$content = nl2br($content);
	if($type) $content = '我想了解的产品信息有:'.$type.'<br/>'.$content;
	$content .= '<hr size="1"/>联系人：'.$truename;
	$content .= '<br/>电子邮件：'.$email;
	if($company) $content .= '<br/>公司名：'.$company;
	if($telephone) $content .= '<br/>联系电话：'.$telephone;
	if(is_numeric($qq)) $content .= '<br/>联系QQ：'.$qq;
	if(is_email($msn)) $content .= '<br/>联系MSN：'.$msn;
	if(is_date($date)) $content .= '<hr size="1"/>我希望在 '.$date.' 之前回复';	
	$result = $db->query("SELECT * FROM {$table} WHERE itemid IN ($itemids) AND status=3 LIMIT 30");
	$i = $j = 0;
	while($r = $db->fetch_array($result)) {
		$linkurl = $MOD['linkurl'].$r['linkurl'];
		$message = '产品信息：<a href="'.$linkurl.'"><strong>'.$r['title'].'</strong></a><br/>'.$content;
		++$i;
		if(send_message($r['username'], $title, $message, 1, $_username)) ++$j;
	}
	if($i == 1) $forward = $linkurl;
	dalert('共发送'.$i.'条，成功'.$j.'条(部分会员可能拒绝您的询价)', $forward);
} else {
	$itemid or dheader($MOD['linkurl']);
	$itemids = is_array($itemid) ? implode(',', $itemid) : $itemid;
	$list = array();
	$result = $db->query("SELECT * FROM {$table} WHERE itemid IN ($itemids) AND status>2 LIMIT 30");
	while($r = $db->fetch_array($result)) {
		if(!$r['username'] || $r['username'] == $_username) continue;
		$list[] = $r;
	}
	$total = count($list);
	if($total < 1) dalert('不能对未注册会员或自己发布的信息询价', 'goback');
	$itype = explode('|', trim($MOD['inquiry_type']));
	$iask = explode('|', trim($MOD['inquiry_ask']));
	$date = timetodate($DT_TIME + 5*86400, 3);
	$title = $total == 1 ? '我对您发布的“'.$list[0]['title'].'”很感兴趣' : '我对您在“'.$DT['sitename'].'”发布的信息很感兴趣';
	$head_title = ($total == 1 ? '询价单'.$DT['seo_delimiter'].$list[0]['title'] : '批量询价').$DT['seo_delimiter'].$MOD['name'];
	include template('inquiry', $module);
}
?>