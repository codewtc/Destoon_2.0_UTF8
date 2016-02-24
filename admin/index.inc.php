<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
switch($action) {
	case 'cache':
		isset($step) or $step = 0;
		if($step == 1) {
			cache_clear('module');
			cache_module();
			msg('系统设置更新成功', '?action='.$action.'&step='.($step+1));
		} else if($step == 2) {
			cache_clear_tag(1);
			msg('标签调用缓存更新成功', '?action='.$action.'&step='.($step+1));
		} else if($step == 3) {
			cache_clear_sql(0);
			msg('SQL缓存更新成功', '?action='.$action.'&step='.($step+1));
		} else if($step == 4) {
			cache_clear('php', 'dir', 'tpl');
			msg('模板缓存更新成功', '?action='.$action.'&step='.($step+1));
		} else if($step == 5) {
			cache_clear('cat');
			cache_category();
			msg('分类缓存更新成功', '?action='.$action.'&step='.($step+1));
		} else if($step == 6) {
			cache_clear('area');
			cache_area();
			msg('地区缓存更新成功', '?action='.$action.'&step='.($step+1));
		} else if($step == 7) {
			cache_clear('fields');
			cache_fields();
			cache_clear('option');
			cache_option();
			cache_product();
			cache_quote_product();
			msg('自定义字段更新成功', '?action='.$action.'&step='.($step+1));
		} else if($step == 8) {
			cache_clear('group');
			cache_group();
			cache_clear('php', 'dir', 'php');
			cache_clear('type');
			cache_type();
			cache_clear('keylink');
			cache_keylink();
			cache_pay();
			cache_clear_ad();
			cache_banip();
			cache_banword();
			cache_bancomment();
			msg('全部缓存更新成功');
		} else {
			msg('正在开始更新缓存', '?action='.$action.'&step=1');
		}
	break;
	case 'tag':
		cache_clear_tag(1);
		cache_clear_sql(0);
		cache_clear('php', 'dir', 'tpl');
		msg('标签调用缓存更新成功');
	break;
	case 'template':
		cache_clear('php', 'dir', 'tpl');
		msg('模板缓存更新成功');
	break;
	case 'html':
		cache_clear_ad();
		cache_clear_tag(1);
		cache_clear_sql(0);
		tohtml('index');
		msg('首页更新成功');
	break;
	case 'phpinfo':
		phpinfo();
	break;
	case 'password':
		if($submit) {
			if(!$oldpassword) msg('请输入现有密码');
			if(!$password) msg('请输入新密码');
			if(strlen($password) < 6) msg('新密码最少6位，请修改');
			if($password != $cpassword) msg('两次输入的密码不一致，请检查');
			$r = $db->get_one("SELECT password FROM {$DT_PRE}member WHERE userid='$_userid'");
			if($r['password'] != md5(md5($oldpassword)))  msg('现有密码错误，请检查');
			if($password == $oldpassword) msg('新密码不能与现有密码相同');
			$password = md5(md5($password));
			$db->query("UPDATE {$DT_PRE}member SET password='$password' WHERE userid='$_userid'");
			msg('管理员密码修改成功', '?action=main');
		} else {
			$menus = array (
			array('系统首页', '?action=main'),
			array('修改密码', '?action=password'),
			array('商务中心', $MODULE[2]['linkurl'], 'target="_blank"'),
			array('网站首页', DT_PATH, 'target="_blank"'),
			array('安全退出', '?file=logout','target="_top" onclick="if(!confirm(\'确定要退出管理吗?\')) return false;"'),
			);
			include tpl('password');
		}
	break;
	case 'side':
		cache_clear_ad();
		cache_clear_sql(strtolower(random(2)));
		include tpl('side');
	break;
	case 'main':
		if($submit) {
			$db->query("UPDATE {$DT_PRE}company_data SET mynote='$note' WHERE userid=$_userid");
			dmsg('更新成功', '?action=main');
		} else {
			$menus = array (
			array('系统首页', '?action=main'),
			array('修改密码', '?action=password'),
			array('商务中心', $MODULE[2]['linkurl'], 'target="_blank"'),
			array('网站首页', DT_PATH, 'target="_blank"'),
			array('安全退出', '?file=logout','target="_top" onclick="return confirm(\'确定要退出管理吗?\');"'),
			);
			$user = $db->get_one("SELECT loginip,logintime,logintimes FROM {$DT_PRE}member WHERE userid=$_userid ");
			$note = $db->get_one("SELECT mynote FROM {$DT_PRE}company_data WHERE userid=$_userid");
			$note = $note['mynote'];
			$install = file_get_contents(CACHE_ROOT.'/install.dat');
			if(!$install) {
				$install = $DT_TIME;
				file_put(DT_ROOT.'/cache/install.dat', $DT_TIME);
			}
			$mysql_tip = '';
			$mysql_bak = DT_ROOT.'/file/backup/backup.dat';
			if(is_file($mysql_bak)) {
				if($DT_TIME - filemtime($mysql_bak) > 3600*24*7) $mysql_tip = '网站已经超过7天没有备份数据库了，建议立即备份';
			} else {
				$mysql_tip = '网站还没有备份过数据库，建议立即备份';
			}
			$notice_url = 'http://www.destoon.com/client.php?action=notice&product=b2b&version='.DT_VERSION.'&release='.DT_RELEASE.'&charset='.$CFG['charset'].'&install='.$install.'&os='.PHP_OS.'&soft='.urlencode($_SERVER['SERVER_SOFTWARE']).'&php='.urlencode(phpversion()).'&mysql='.urlencode(mysql_get_server_info()).'&url='.urlencode($DT_URL).'&site='.urlencode($DT['sitename']).'&auth='.strtoupper(md5($DT_URL.$install.$_SERVER['SERVER_SOFTWARE']));
			$install = timetodate($install, 5);
			include tpl('main');
		}
	break;
	case 'count':
		$today = strtotime(timetodate($DT_TIME, 3).' 00:00:00');
		//
		//待受理客服中心
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}ask WHERE status=0", "CACHE", '60');
		$num = intval($r['num']);
		echo 'try{document.getElementById("ask").innerHTML="'.$num.'";}catch(e){}';

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_charge WHERE status=0", "CACHE", '60');
		$num = intval($r['num']);
		echo 'try{document.getElementById("charge").innerHTML="'.$num.'";}catch(e){}';

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_cash WHERE status=0", "CACHE", '60');
		$num = intval($r['num']);
		echo 'try{document.getElementById("cash").innerHTML="'.$num.'";}catch(e){}';

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}finance_trade WHERE status=5", "CACHE", '60');
		$num = intval($r['num']);
		echo 'try{document.getElementById("trade").innerHTML="'.$num.'";}catch(e){}';
		//待审核公司新闻
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}news WHERE status=2", "CACHE", '60');
		$num = intval($r['num']);
		echo 'try{document.getElementById("news").innerHTML="'.$num.'";}catch(e){}';

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}credit WHERE status=2", "CACHE", '60');
		$num = intval($r['num']);
		echo 'try{document.getElementById("credit").innerHTML="'.$num.'";}catch(e){}';

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}link WHERE status=2 AND username!=''", "CACHE", '60');
		$num = intval($r['num']);
		echo 'try{document.getElementById("comlink").innerHTML="'.$num.'";}catch(e){}';

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}keyword WHERE status=2", "CACHE", '60');
		$num = intval($r['num']);
		echo 'try{document.getElementById("keyword").innerHTML="'.$num.'";}catch(e){}';

		//待审核排名推广

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}spread WHERE status=2", "CACHE", '60');
		$num = intval($r['num']);
		echo 'try{document.getElementById("spread").innerHTML="'.$num.'";}catch(e){}';

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}guestbook WHERE edittime=0", "CACHE", '60');
		$num = intval($r['num']);
		echo 'try{document.getElementById("guestbook").innerHTML="'.$num.'";}catch(e){}';

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}comment WHERE status=2", "CACHE", '60');
		$num = intval($r['num']);
		echo 'try{document.getElementById("comment").innerHTML="'.$num.'";}catch(e){}';

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}link WHERE status=2 AND username=''", "CACHE", '60');
		$num = intval($r['num']);
		echo 'try{document.getElementById("link").innerHTML="'.$num.'";}catch(e){}';

		//会员
		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}member");
		$num = intval($r['num']);
		echo 'try{document.getElementById("member").innerHTML="'.$num.'";}catch(e){}';

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}vip WHERE status=2", "CACHE", '60');

		$num = intval($r['num']);
		echo 'try{document.getElementById("member_vip").innerHTML="'.$num.'";}catch(e){}';

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}member WHERE groupid=4", "CACHE", '60');
		$num = intval($r['num']);
		echo 'try{document.getElementById("member_check").innerHTML="'.$num.'";}catch(e){}';

		$r = $db->get_one("SELECT COUNT(*) AS num FROM {$DT_PRE}member WHERE regtime>$today", "CACHE", '60');
		$num = intval($r['num']);
		echo 'try{document.getElementById("member_new").innerHTML="'.$num.'";}catch(e){}';

		foreach($MODULE as $m) {
			if($m['moduleid'] < 5 || $m['islink']) continue;
			$table = $DT_PRE.(in_array($m['module'], array('article', 'info')) ?  $m['module'].'_'. $m['moduleid'] : $m['module']);
			//ALL
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table}", 'CACHE');
			$num = intval($r['num']);
			echo 'try{$("m_'.$m['moduleid'].'").innerHTML="'.$num.'";}catch(e){}';
			//PUB
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE status=3", 'CACHE');
			$num = intval($r['num']);
			echo 'try{$("m_'.$m['moduleid'].'_1").innerHTML="'.$num.'";}catch(e){}';
			//CHECK
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE status=2", "CACHE", '60');
			$num = intval($r['num']);
			echo 'try{$("m_'.$m['moduleid'].'_2").innerHTML="'.$num.'";}catch(e){}';
			//NEW
			$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE addtime>$today", "CACHE", '30');
			$num = intval($r['num']);
			echo 'try{$("m_'.$m['moduleid'].'_3").innerHTML="'.$num.'";}catch(e){}';

			if($m['moduleid'] == 9) {
				$table = $DT_PRE.'resume';
				//ALL
				$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table}", 'CACHE');
				$num = intval($r['num']);
				echo 'try{$("m_resume").innerHTML="'.$num.'";}catch(e){}';
				//PUB
				$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE status=3", 'CACHE');
				$num = intval($r['num']);
				echo 'try{$("m_resume_1").innerHTML="'.$num.'";}catch(e){}';
				//CHECK
				$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE status=2", "CACHE", '60');
				$num = intval($r['num']);
				echo 'try{$("m_resume_2").innerHTML="'.$num.'";}catch(e){}';
				//NEW
				$r = $db->get_one("SELECT COUNT(*) AS num FROM {$table} WHERE addtime>$today", "CACHE", '30');
				$num = intval($r['num']);
				echo 'try{$("m_resume_3").innerHTML="'.$num.'";}catch(e){}';
			}
		}
	break;
	case 'left':
		$mymenu = cache_read('menu-'.$_userid.'.php');
		include tpl('left');
	break;
	default:
		include tpl('index');
	break;
}
?>