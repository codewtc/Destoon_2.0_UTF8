<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$MOD['link_enable'] or dheader(DT_PATH);
require DT_ROOT.'/include/post.func.php';
$TYPE = get_type('link', 1);
require MOD_ROOT.'/link.class.php';
$do = new dlink();
$typeid = isset($typeid) ? intval($typeid) : 0;
if($action == 'reg') {
	($TYPE && $MOD['link_reg']) or message('系统未开启在线申请功能，请直接与我们联系');
	if($submit) {
		captcha($captcha, 1);
		$post = dhtmlspecialchars($post);
		if($do->pass($post)) {
			$r = $db->get_one("SELECT itemid FROM {$DT_PRE}link WHERE linkurl='$post[linkurl]'");
			if($r) message('您所申请的网址已经提交过了，请勿重复申请');
			$post['status'] = 2;
			$post['level'] = 0;
			$do->add($post);
			message('申请已提交，请等待审核', './');
		} else {
			message($do->errmsg);
		}
	} else {
		$type_select = type_select('link', 1, 'post[typeid]', '请选择分类', 0, 'id="typeid"');
		$head_title = $head_keywords = $head_description = '申请链接'.$DT['seo_delimiter'].'友情链接';
		include template('link', $module);
	}
} else {
	$head_title = $head_keywords = $head_description = '友情链接';
	include template('link', $module);
}
?>