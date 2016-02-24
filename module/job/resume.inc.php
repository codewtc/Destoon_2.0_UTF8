<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$itemid or message('', $MOD['linkurl']);

$table = $DT_PRE.'resume';
$table_data = $DT_PRE.'resume_data';
$item = $db->get_one("SELECT * FROM {$table} WHERE itemid=$itemid AND status=3");
if($item) {
	if($item['open'] != 3) {
		$head_title = '抱歉，您要访问的简历已经关闭';
		exit(include template('show-notfound', 'message'));
	}
} else {
	$head_title = '抱歉，您要访问的简历不存在或被删除';
	exit(include template('show-notfound', 'message'));
}

$content = $db->get_one("SELECT content FROM {$table_data} WHERE itemid=$itemid");
$content = $content['content'];
$print = isset($print) ? 1 : 0;
extract($item);
$CAT = cache_read('category_'.$catid.'.php');
if(!check_group($_groupid, $MOD['group_show_resume']) || !check_group($_groupid, $CAT['group_show'])) {
	$head_title = '抱歉，您所在的会员组没有权限访问此页面';
	exit(include template('noright', 'message'));
}

$parentid = $CATEGORY[$catid]['parentid'] ? $CATEGORY[$catid]['parentid'] : $catid;
$adddate = timetodate($addtime, 3);
$editdate = timetodate($edittime, 3);
$linkurl = linkurl($MOD['linkurl'].$linkurl, 1);

$user_status = 4;
$fee = get_fee($item['fee'], $MOD['fee_view_resume']);
if(check_group($_groupid, $MOD['group_contact_resume'])) {
	if($MG['fee_mode']) {
		$user_status = 3;
	} else {
		if($fee) {
			$pay_item = $moduleid.'-'.$itemid.'-';
			if($_userid) {
				$p = $db->get_one("SELECT itemid FROM {$DT_PRE}finance_pay WHERE item='$pay_item' AND username='$_username'");
				if($p) {
					$user_status = 3;
				} else {
					$user_status = 2;
					$item['title'] = $truename.'的求职简历';
					$pay_url = linkurl($MODULE[2]['linkurl'], 1).'pay.php?item='.$pay_item.'&fee='.$fee.'&sign='.crypt_sign($_username.$pay_item.$fee.$linkurl.$item['title']).'&title='.urlencode($item['title']).'&forward='.urlencode($linkurl);
				}
			} else {
				$user_status = 0;
			}
		} else {
			$user_status = 3;
		}
	}
} else {
	$user_status = $_userid ? 1 : 0;
}
if($_username && $_username == $item['username']) $user_status = 3;
$description = '';

if($print) {
	if($user_status != 3) message('您无权访问此页', $linkurl);
	include template('print_resume', $module);
} else {
	$db->query("UPDATE {$table} SET hits=hits+1 WHERE itemid=$itemid");

	include DT_ROOT.'/include/seo.inc.php';
	$seo_title = $truename.'的求职简历'.$seo_delimiter.$seo_catname.$seo_modulename.$seo_delimiter.$seo_sitename;
	$head_keywords = $keyword;
	$head_description = $introduce ? $introduce : $title;

	$template = $item['template'] ? $item['template'] : 'resume';
	include template($template, $module);
}
?>