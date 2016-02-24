<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
$resume = isset($resume) ? 1 : 0;

if($resume) {

$MG['resume_limit'] > -1 or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');
require MOD_ROOT.'/resume.class.php';
$do = new resume($moduleid);
$table = $DT_PRE.'resume';
if(in_array($action, array('add', 'edit'))) {
	$FD = cache_read('fields-'.substr($table, strlen($DT_PRE)).'.php');
	if($FD) require DT_ROOT.'/include/fields.func.php';
	isset($post_fields) or $post_fields = array();
}

$sql = $_userid ? "username='$_username'" : "ip='$DT_IP'";
$limit_used = $limit_free = $need_captcha = $need_question = $fee_add = 0;
if(in_array($action, array('', 'add'))) {
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $sql");
	$limit_used = $r['num'];
	$limit_free = $MG['resume_limit'] > $limit_used ? $MG['resume_limit'] - $limit_used : 0;
}
switch($action) {
	case 'add':
		if($MG['resume_limit'] && $limit_used >= $MG['resume_limit']) dalert('最多可发布'.$MG['resume_limit'].'条简历 当前已发布'.$limit_used.'条', $_userid ? $MODULE[2]['linkurl'].$DT['file_my'].'?mid='.$mid : $MODULE[2]['linkurl'].$DT['file_my']);
		if($MG['day_limit']) {
			$today = strtotime(timetodate($DT_TIME, 3).' 00:00:00');
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $sql AND addtime>$today");
			if($r && $r['num'] >= $MG['day_limit']) dalert('24小时内最多发布'.$MG['day_limit'].'条简历', $_userid ? $MODULE[2]['linkurl'].$DT['file_my'].'?mid='.$mid : $MODULE[2]['linkurl'].$DT['file_my']);
		}

		if($MG['resume_free_limit'] > 0) {
			$fee_add = ($MOD['fee_add'] && !$MG['fee_mode'] && $limit_used >= $MG['resume_free_limit'] && $_userid) ? dround($MOD['fee_add']) : 0;
		} else {
			$fee_add = 0;
		}

		$need_captcha = $MOD['captcha_add_resume'] == 2 ? $MG['captcha'] : $MOD['captcha_add_resume'];
		$need_question = $MOD['question_add_resume'] == 2 ? $MG['question'] : $MOD['question_add_resume'];

		if($submit) {
			if($fee_add) {
				$fee_add < $_money or dalert('发布信息收费 '.$fee_add.' 元，当前余额不足，请先充值');
				is_payword($_username, $password) or dalert('您的支付密码不正确');
			}
			if($MG['add_limit']) {
				$last = $db->get_one("SELECT addtime FROM {$table} WHERE $sql ORDER BY itemid DESC");
				if($last && $DT_TIME - $last['addtime'] < $MG['add_limit']) dalert('信息发布过快，请隔'.$MG['add_limit'].'秒再提交');
			}
			$msg = captcha($captcha, $need_captcha, true);
			if($msg) dalert($msg);
			$msg = question($answer, $need_question, true);
			if($msg) dalert($msg);
			if($do->pass($post)) {
				$CAT = cache_read('category_'.$post['catid'].'.php');
				if(!check_group($_groupid, $CAT['group_add'])) dalert('您所在的会员组没有权限在分类 ['.$CAT['catname'].'] 发布信息，请更换分类');
				$post['addtime'] = $post['level'] = $post['fee'] = 0;
				$post['style'] = $post['template'] = $post['note'] = '';
				$need_check =  $MOD['check_add_resume'] == 2 ? $MG['check'] : $MOD['check_add_resume'];
				$post['status'] = get_status(3, $need_check);
				$post['username'] = $_username;
				if($FD) fields_check($post_fields);
				$do->add($post);
				if($FD) fields_update($post_fields, $table, $do->itemid);

				if($fee_add) {
					money_add($_username, -$fee_add);
					record_add($_username, -$fee_add, '站内', 'system', '站内支付', '发布['.$MODULE[$mid]['name'].']'.$post['title'].'(信息ID:'.$do->itemid.')');
				}
				
				$msg = '添加成功';
				if($post['status'] == 2) $msg = $msg.' 请等待审核';
				if($_userid) {
					set_cookie('dmsg', $msg);
					$forward = $MODULE[2]['linkurl'].$DT['file_my'].'?mid='.$mid.'&resume=1&status='.$post['status'];
					dalert('', '', 'parent.window.location="'.$forward.'";');
				} else {
					dalert($msg, '', 'parent.window.location=parent.window.location;');
				}
			} else {
				dalert($do->errmsg);
			}
		} else {
			if($itemid) {
				$MG['copy'] && $_userid or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');
				$do->itemid = $itemid;
				$r = $do->get_one();
				if(!$r || $r['username'] != $_username) message();
				extract($r);
				list($byear, $bmonth, $bday) = explode('-', $birthday);
			} else {
				foreach($do->fields as $v) {
					$$v = '';
				}
				$content = '';
				$gender = 1;
				$byear = 19;
				$bmonth = $bday = $experience = $marriage = $type = 1;
				$education = 3;
				$minsalary = 1000;
				$maxsalary = 0;
				$open = 3;
				if($_userid) {
					$r = $db->get_one("SELECT * FROM {$DT_PRE}resume a,{$DT_PRE}resume_data c WHERE a.itemid=c.itemid AND a.username='$_username' ORDER BY a.edittime DESC LIMIT 0,1");
					if($r) {
						extract($r);
						list($byear, $bmonth, $bday) = explode('-', $birthday);
					} else {
						$user = userinfo($_username);
						$truename = $user['truename'];
						$email = $user['email'];
						$mobile = $user['mobile'];
						$gender = $user['gender'];
						$areaid = $user['areaid'];
						$telephone = $user['telephone'];
						$address = $user['address'];
						$msn = $user['msn'];
						$qq = $user['qq'];
					}
				}
			}
			$item = array();
		}
	break;
	case 'edit':
		$itemid or message();
		$do->itemid = $itemid;
		$item = $do->get_one();
		if(!$item || $item['username'] != $_username) message();
		//招聘&简历 应随时可修改不限制时间
		if($submit) {
			if($do->pass($post)) {
				$post['addtime'] = timetodate($item['addtime']);
				$post['level'] = $item['level'];
				$post['fee'] = $item['fee'];
				$post['style'] = $item['style'];
				$post['template'] = $item['template'];
				$post['note'] = $item['note'];
				$need_check =  $MOD['check_add_resume'] == 2 ? $MG['check'] : $MOD['check_add_resume'];
				$post['status'] = get_status($item['status'], $need_check);
				$post['username'] = $_username;
				if($FD) fields_check($post_fields);
				$do->edit($post);
				if($FD) fields_update($post_fields, $table, $do->itemid);

				set_cookie('dmsg', '修改成功');
				dalert('', '', 'parent.window.location="'.$forward.'"');
			} else {
				dalert($do->errmsg);
			}
		} else {
			extract($item);				
			list($byear, $bmonth, $bday) = explode('-', $birthday);
		}
	break;
	case 'delete':
		$itemid or message();
		$do->itemid = $itemid;
		$item = $do->get_one();
		if(!$item || $item['username'] != $_username) message();
		$do->recycle($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'update':
		$do->_update($_username);
		dmsg('更新成功', $forward);
	break;
	case 'apply_delete':
		$itemid or message();
		$apply = $db->get_one("SELECT * FROM {$DT_PRE}job_apply WHERE applyid='$itemid' AND apply_username='$_username'");
		if($apply) {
			if($apply['status']>0) $db->query("UPDATE {$DT_PRE}job SET apply=apply-1 WHERE itemid='$apply[jobid]'");
			$db->query("DELETE FROM {$DT_PRE}job_apply WHERE applyid='$itemid'");
		}
		dmsg('删除成功', $forward);
	break;
	case 'apply':
		$condition = '';
		if($keyword) $condition .= " AND j.keyword LIKE '%$keyword%'";if($catid) $condition .= ($CATEGORY[$catid]['child']) ? " AND j.catid IN (".$CATEGORY[$catid]['arrchildid'].")" : " AND j.catid=$catid";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}job_apply a LEFT JOIN {$DT_PRE}resume r ON a.resumeid=r.itemid LEFT JOIN {$DT_PRE}job j ON a.jobid=j.itemid WHERE a.apply_username='$_username' $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$lists = array();
		$result = $db->query("SELECT a.*,r.title AS resumetitle,j.title,j.linkurl FROM {$DT_PRE}job_apply a LEFT JOIN {$DT_PRE}resume r ON a.resumeid=r.itemid LEFT JOIN {$DT_PRE}job j ON a.jobid=j.itemid WHERE a.apply_username='$_username' $condition ORDER BY a.applyid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$lists[] = $r;
		}
	break;
	case 'refresh':

		$MG['refresh_limit'] > -1 or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');

		$itemid or message();
		$do->itemid = $itemid;
		$item = $do->get_one();
		if(!$item || $item['username'] != $_username) message();

		if($MG['refresh_limit'] && $DT_TIME - $item['edittime'] < $MG['refresh_limit']) dalert($MG['refresh_limit'].'秒内只能刷新一次', $forward);

		$do->refresh($itemid);
		dmsg('更新成功', $forward);
	break;
	default:
		$status = isset($status) ? intval($status) : 3;
		in_array($status, array(1, 2, 3, 4)) or $status = 3;
		$condition = "username='$_username'";
		$condition .= " AND status=$status";
		if($keyword) $condition .= " AND keyword LIKE '%$keyword%'";
		if($catid) $condition .= ($CATEGORY[$catid]['child']) ? " AND catid IN (".$CATEGORY[$catid]['arrchildid'].")" : " AND catid=$catid";
		$lists = $do->get_list($condition);
	break;
}
$head_title = '简历管理';
if($_userid) {
	$nums = array();
	for($i = 1; $i < 4; $i++) {
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}resume WHERE username='$_username' AND status=$i");
		$nums[$i] = $r['num'];
	}
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}job_apply WHERE apply_username ='$_username'");
	$nums['apply'] = $r['num'];
}
include template('my_resume', 'member');

} else {//招聘

$MG['job_limit'] > -1 or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');

require MOD_ROOT.'/job.class.php';
$do = new job($moduleid);

if(in_array($action, array('add', 'edit'))) {
	$FD = cache_read('fields-'.substr($table, strlen($DT_PRE)).'.php');
	if($FD) require DT_ROOT.'/include/fields.func.php';
	isset($post_fields) or $post_fields = array();
}

$sql = $_userid ? "username='$_username'" : "ip='$DT_IP'";
$limit_used = $limit_free = $need_captcha = $need_question = $fee_add = 0;
if(in_array($action, array('', 'add'))) {
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $sql");
	$limit_used = $r['num'];
	$limit_free = $MG['job_limit'] > $limit_used ? $MG['job_limit'] - $limit_used : 0;
}

switch($action) {
	case 'add':
		if($MG['job_limit'] && $limit_used >= $MG['job_limit']) dalert('最多可发布'.$MG['job_limit'].'条招聘 当前已发布'.$limit_used.'条', $_userid ? $MODULE[2]['linkurl'].$DT['file_my'].'?mid='.$mid : $MODULE[2]['linkurl'].$DT['file_my']);
		if($MG['day_limit']) {
			$today = strtotime(timetodate($DT_TIME, 3).' 00:00:00');
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE $sql AND addtime>$today");
			if($r && $r['num'] >= $MG['day_limit']) dalert('24小时内最多发布'.$MG['day_limit'].'条招聘', $_userid ? $MODULE[2]['linkurl'].$DT['file_my'].'?mid='.$mid : $MODULE[2]['linkurl'].$DT['file_my']);
		}

		if($MG['job_free_limit'] > 0) {
			$fee_add = ($MOD['fee_add'] && !$MG['fee_mode'] && $limit_used >= $MG['job_free_limit'] && $_userid) ? dround($MOD['fee_add']) : 0;
		} else {
			$fee_add = 0;
		}

		$need_captcha = $MOD['captcha_add'] == 2 ? $MG['captcha'] : $MOD['captcha_add'];
		$need_question = $MOD['question_add'] == 2 ? $MG['question'] : $MOD['question_add'];

		if($submit) {
			if($fee_add) {
				$fee_add < $_money or dalert('发布信息收费 '.$fee_add.' 元，当前余额不足，请先充值');
				is_payword($_username, $password) or dalert('您的支付密码不正确');
			}

			if(!$_userid) {
				if(strlen($post['company']) < 10) dalert('请填写正确的公司名称');
			}

			if($MG['add_limit']) {
				$last = $db->get_one("SELECT addtime FROM {$table} WHERE $sql ORDER BY itemid DESC");
				if($last && $DT_TIME - $last['addtime'] < $MG['add_limit']) dalert('信息发布过快，请隔'.$MG['add_limit'].'秒再提交');
			}
			$msg = captcha($captcha, $need_captcha, true);
			if($msg) dalert($msg);
			$msg = question($answer, $need_question, true);
			if($msg) dalert($msg);

			if($do->pass($post)) {
				$CAT = cache_read('category_'.$post['catid'].'.php');
				if(!check_group($_groupid, $CAT['group_add'])) dalert('您所在的会员组没有权限在分类 ['.$CAT['catname'].'] 发布信息，请更换分类');
				$post['addtime'] = $post['level'] = $post['fee'] = 0;
				$post['style'] = $post['template'] = $post['note'] = '';
				$need_check =  $MOD['check_add'] == 2 ? $MG['check'] : $MOD['check_add'];
				$post['status'] = get_status(3, $need_check);
				$post['username'] = $_username;
				if($FD) fields_check($post_fields);
				$do->add($post);
				if($FD) fields_update($post_fields, $table, $do->itemid);

				if($fee_add) {
					money_add($_username, -$fee_add);
					record_add($_username, -$fee_add, '站内', 'system', '站内支付', '发布['.$MODULE[$mid]['name'].']'.$post['title'].'(信息ID:'.$do->itemid.')');
				}
				
				$msg = '添加成功';
				if($post['status'] == 2) $msg = $msg.' 请等待审核';
				if($_userid) {
					set_cookie('dmsg', $msg);
					$forward = $MODULE[2]['linkurl'].$DT['file_my'].'?mid='.$mid.'&status='.$post['status'];
					dalert('', '', 'parent.window.location="'.$forward.'";');
				} else {
					dalert($msg, '', 'parent.window.location=parent.window.location;');
				}
			} else {
				dalert($do->errmsg);
			}
		} else {
			if($itemid) {
				$MG['copy'] or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');
				$do->itemid = $itemid;
				$r = $do->get_one();
				if(!$r || $r['username'] != $_username) message();
				extract($r);
				$totime = $totime ? timetodate($totime, 3) : '';
			} else {
				foreach($do->fields as $v) {
					$$v = '';
				}
				$content = '';	
				$r = $db->get_one("SELECT * FROM {$DT_PRE}member m LEFT JOIN {$DT_PRE}company c ON m.userid=c.userid WHERE m.userid='$_userid'");
				$truename = $r['truename'];
				$email = $r['email'];
				$mobile = $r['mobile'];
				$areaid = $r['areaid'];
				$telephone = $r['telephone'];
				$address = $r['address'];
				$msn = $r['msn'];
				$qq = $r['qq'];
				$total = 1;
				$minage = 18;
				$maxage = 0;
			}
			$item = array();
		}
	break;
	case 'edit':
		$itemid or message();
		$do->itemid = $itemid;
		$item = $do->get_one();
		if(!$item || $item['username'] != $_username) message();
		//招聘&简历 应随时可修改不限制时间
		if($submit) {
			if($do->pass($post)) {
				$CAT = cache_read('category_'.$post['catid'].'.php');
				if(!check_group($_groupid, $CAT['group_add'])) dalert('您所在的会员组没有权限在分类 ['.$CAT['catname'].'] 发布信息，请更换分类');
				$post['addtime'] = timetodate($item['addtime']);
				$post['level'] = $item['level'];
				$post['fee'] = $item['fee'];
				$post['style'] = $item['style'];
				$post['template'] = $item['template'];
				$need_check =  $MOD['check_add'] == 2 ? $MG['check'] : $MOD['check_add'];
				$post['status'] = get_status($item['status'], $need_check);
				$post['username'] = $_username;
				if($FD) fields_check($post_fields);
				$do->edit($post);
				if($FD) fields_update($post_fields, $table, $do->itemid);

				set_cookie('dmsg', '修改成功');
				dalert('', '', 'parent.window.location="'.$forward.'"');
			} else {
				dalert($do->errmsg);
			}
		} else {
			extract($item);
			$totime = $totime ? timetodate($totime, 3) : '';
		}
	break;
	case 'delete':
		$itemid or message();
		$do->itemid = $itemid;
		$item = $do->get_one();
		if(!$item || $item['username'] != $_username) message();
		$do->recycle($itemid);
		dmsg('删除成功', $forward);
	break;
	case 'update':
		$do->_update($_username);
		dmsg('更新成功', $forward);
	break;
	case 'resume_show':
		$itemid or message();
		$resumeid or message();
		$db->query("UPDATE {$DT_PRE}job_apply SET status=2 WHERE applyid='$itemid' AND job_username='$_username' AND status=1");
		dheader($MOD['linkurl'].rewrite('resume.php?itemid='.$resumeid));
	break;
	case 'resume_delete':
		$itemid or message();
		$apply = $db->get_one("SELECT * FROM {$DT_PRE}job_apply WHERE applyid='$itemid' AND job_username='$_username' AND status>0");
		if($apply) {
			$db->query("UPDATE {$DT_PRE}job_apply SET status=0 WHERE applyid='$itemid'");
			$db->query("UPDATE {$DT_PRE}job SET apply=apply-1 WHERE itemid='$apply[jobid]'");
		}
		dmsg('删除成功', $forward);
	break;
	case 'resume_invite':
		$itemid or message();
		$apply = $db->get_one("SELECT * FROM {$DT_PRE}job_apply WHERE applyid='$itemid' AND job_username='$_username'");
		$apply or message('信息不存在');
		$resume = $db->get_one("SELECT * FROM {$DT_PRE}resume WHERE itemid='$apply[resumeid]'");
		$resume or message('简历不存在或已经删除');
		if(!$resume['username']) message('此简历发布者未注册，无法直接发送通知');
		if($resume['status'] != 3) message('此简历已经关闭');
		$job = $db->get_one("SELECT * FROM {$DT_PRE}job WHERE itemid='$apply[jobid]' AND status=3");
		$job or message('招聘不存在或已经删除');
		if($job['totime'] && $job['totime'] < $DT_TIME) message('招聘信息已经过期');
		$title = $job['company'].'邀请您面试';
		$joburl = linkurl($MOD['linkurl'].$job['linkurl'], 1);
		$content = $resume['truename'].'，您好：<br/><br/>';
		$content .= '本公司已经收到您向 <a href="'.$joburl.'" target="_blank">'.$job['title'].'</a> 投递的简历，现邀请您面试。<br/><br/>';
		$content .= '联系人：'.$job['truename'].'<br/>';
		$content .= '联系电话：'.$job['telephone'].'<br/>';
		$content .= '电子邮件：'.$job['email'].'<br/>';
		if($job['mobile']) $content .= '联系手机：'.$job['mobile'].'<br/>';
		if($job['address']) $content .= '联系地址：'.$job['address'].'<br/>';
		if($job['qq']) $content .= '联系QQ：'.$job['qq'].'<br/>';
		if($job['msn']) $content .= '联系MSN：'.$job['msn'].'<br/>';
		$db->query("UPDATE {$DT_PRE}job_apply SET status=3 WHERE applyid='$itemid' AND job_username='$_username' AND status>0");
?>
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html;charset=<?php echo $CFG['charset'];?>">
		<title>正在发送...</title>
		</head>
		<body onload="document.getElementById('invite').submit();">
		<form action="<?php echo $MODULE[2]['linkurl'];?>message.php" method="post" id="invite">
		<input type="hidden" name="action" value="send" />
		<input type="hidden" name="touser" value="<?php echo $apply['apply_username'];?>" />
		<input type="hidden" name="title" value="<?php echo $title;?>" />
		<textarea name="content" style="display:none;"><?php echo $content;?></textarea>
		</form>
		</body>
		</html>
<?php
	exit;
	break;
	case 'resume':
		$condition = '';
		if($keyword) $condition .= " AND r.keyword LIKE '%$keyword%'";
		if($itemid) $condition .= " AND j.itemid=$itemid";
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}job_apply a LEFT JOIN {$DT_PRE}resume r ON a.resumeid=r.itemid LEFT JOIN {$DT_PRE}job j ON a.jobid=j.itemid WHERE a.job_username='$_username' AND a.status>0 $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$lists = array();
		$result = $db->query("SELECT a.*,r.truename,r.catid,r.gender,r.education,r.school,r.areaid,r.age,r.experience,j.title,j.linkurl FROM {$DT_PRE}job_apply a LEFT JOIN {$DT_PRE}resume r ON a.resumeid=r.itemid LEFT JOIN {$DT_PRE}job j ON a.jobid=j.itemid WHERE a.job_username='$_username' AND a.status>0 $condition ORDER BY a.applyid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$lists[] = $r;
		}
	break;
	case 'talent_delete':
		$itemid or message();
		$db->query("DELETE FROM {$DT_PRE}job_talent WHERE username='$_username' AND talentid=$itemid");
		dmsg('删除成功', $forward);
	break;
	case 'talent':
		$condition = '';
		if($keyword) $condition .= " AND r.keyword LIKE '%$keyword%'";
		if($catid) $condition .= ($CATEGORY[$catid]['child']) ? " AND r.catid IN (".$CATEGORY[$catid]['arrchildid'].")" : " AND r.catid=$catid";

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}job_talent t LEFT JOIN {$DT_PRE}resume r ON t.resumeid=r.itemid WHERE t.username='$_username' $condition");
		$pages = pages($r['num'], $page, $pagesize);		
		$lists = array();
		$result = $db->query("SELECT * FROM {$DT_PRE}job_talent t LEFT JOIN {$DT_PRE}resume r ON t.resumeid=r.itemid WHERE t.username='$_username' $condition ORDER BY t.talentid DESC LIMIT $offset,$pagesize");
		while($r = $db->fetch_array($result)) {
			$r['parentid'] = $CATEGORY[$r['catid']]['parentid'] ? $CATEGORY[$r['catid']]['parentid'] : $r['catid'];
			$lists[] = $r;
		}
	break;
	case 'refresh':

		$MG['refresh_limit'] > -1 or dalert('您所在的会员组没有权限使用此功能，请升级', 'goback');

		$do->_update($_username);
		$itemid or message();
		$do->itemid = $itemid;
		$r = $do->get_one();
		if(!$r || $r['username'] != $_username) message();

		if($MG['refresh_limit'] && $DT_TIME - $r['edittime'] < $MG['refresh_limit']) dalert($MG['refresh_limit'].'秒内只能刷新一次', $forward);

		$do->refresh($itemid);
		dmsg('更新成功', $forward);
	break;
	default:
		$status = isset($status) ? intval($status) : 3;
		in_array($status, array(1, 2, 3, 4)) or $status = 3;
		$condition = "username='$_username'";
		$condition .= " AND status=$status";
		$typeid = isset($typeid) ? ($typeid === '' ? -1 : intval($typeid)) : -1;
		if($keyword) $condition .= " AND keyword LIKE '%$keyword%'";
		if($catid) $condition .= ($CATEGORY[$catid]['child']) ? " AND catid IN (".$CATEGORY[$catid]['arrchildid'].")" : " AND catid=$catid";
		if($typeid >=0 ) $condition .= " AND typeid=$typeid";
		$timetype = strpos($MOD['order'], 'add') !== false ? 'add' : '';
		$lists = $do->get_list($condition, $MOD['order']);
	break;
}
$head_title = '招聘管理';
if($_userid) {
	$nums = array();
	for($i = 1; $i < 5; $i++) {
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}job WHERE username='$_username' AND status=$i");
		$nums[$i] = $r['num'];
	}
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}job_talent WHERE username='$_username'");
	$nums['talent'] = $r['num'];
	$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}job_apply WHERE job_username ='$_username' AND status>0");
	$nums['resume'] = $r['num'];
}
include template('my_job', 'member');

}
?>