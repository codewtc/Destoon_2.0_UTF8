<?php
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$username = $domain = '';
if(isset($homepage) && preg_match("/^[a-z0-9]{2,}$/", $homepage)) {
	$username = $homepage;
} else if($CFG['com_domain']) {
	$host = $_SERVER['HTTP_HOST'];
	if(strpos(DT_URL, $host) === false && strpos($MODULE[4]['linkurl'], $host) === false) {
		$www = str_replace($CFG['com_domain'], '', $host);
		if(preg_match("/^[a-z0-9]{2,}$/", $www)) {
			$username = $homepage = $www;
		} else {
			$c = $db->get_one("SELECT username FROM {$DT_PRE}company WHERE domain='$host'");
			if($c) {
				$username = $homepage = $c['username'];
				$domain = $host;
			}
		}
	}
}

if($username) {
	$COM = $db->get_one("SELECT * FROM {$table} c, {$table_member} m WHERE c.userid=m.userid AND c.username='$username' AND m.groupid>4");
	if(!$COM) {//公司不存在
		$head_title = '公司不存在';
		include template('com-notfound', 'message');
		exit;
	}
	if(!$COM['edittime']) {//资料不完整
		$seo_title = $COM['company'];
		include template('com-opening', 'message');
		exit;
	}

	$domain = $COM['domain'];
	if($domain) {
		//跳转到顶级域名
		if(strpos($DT_URL, $domain) === false) {
			$subdomain = userurl($username);
			if(strpos($DT_URL, $subdomain) === false) {
				dheader('http://'.$domain.'/');
			} else {
				dheader(str_replace($subdomain, 'http://'.$domain.'/', $DT_URL));
			}
		}
		$DT['rewrite'] = intval($CFG['com_rewrite']);//顶级域名可能无法Rewrite
	}

	$linkurl = userurl($username, '', $domain);
	if($COM['linkurl'] != $linkurl) $COM['linkurl'] = $linkurl;

	$userid = $COM['userid'];
	$r = $db->get_one("SELECT content FROM {$DT_PRE}company_data WHERE userid=$userid");
	$COM['content'] = $COM['intro'] = $r['content'];
	$COM['thumb'] = $COM['thumb'] ? $COM['thumb'] : SKIN_PATH.'image/company.jpg';
	$COM['year'] = vip_year($COM['fromtime']);

	$COMGROUP = cache_read('group-'.$COM['groupid'].'.php');
	if(!isset($COMGROUP['homepage']) || !$COMGROUP['homepage']) {
		$head_title = $COM['company'];
		$head_keywords = $COM['keyword'];
		$head_description = $COM['introduce'];
		$member = $COM;
		$content = $COM['content'];
		include template('show', $module);
		exit;
	}
	//Rewrite
	isset($rewrite) or $rewrite = '';
	if($rewrite) {
		if(substr($rewrite, -1) == '/') $rewrite = substr($rewrite, 0, -1);
		$r = explode('/', $rewrite);
		$rc = count($r);
		if(isset($file) && $file) {
			if($rc%2 == 0) {
				for($i = 0; $i < $rc; $i++) {
					$$r[$i] = $r[++$i];
				}
			}
		} else {
			$file = $r[0];
			if($rc%2 == 1) {
				for($i = 1; $i < $rc; $i++) {
					$$r[$i] = $r[++$i];
				}
			}
		}
	}

	isset($file) or $file = 'homepage';
	$MFILE = array('introduce', 'sell', 'buy', 'news', 'credit', 'job', 'contact', 'link', 'homepage');
	in_array($file, $MFILE) or dheader($MOD['linkurl']);

	//默认设置
	$HMENU = array('公司介绍', '供应产品', '采购清单', '新闻中心', '荣誉资质', '人才招聘', '联系方式', '友情链接');
	$HSIDE = array('网站公告', '新闻中心', '产品分类', '联系方式', '站内搜索', '荣誉资质', '友情链接');
	$SFILE = array('announce', 'news', 'type', 'contact', 'search', 'credit', 'link');
	$HMAIN = array('推荐产品', '公司介绍', '最新供应', '公司新闻', '荣誉资质', '联系方式');
	$IFILE = array('elite', 'introduce', 'sell', 'news', 'credit', 'contact');


	$_menu_show = '0,1,2,3,4,5,6,7';
	$_menu_order = '0,1,2,3,4,5,6,7';
	$_menu_num = '1,16,30,30,10,30,1,100';
	$_menu_name = implode(',' , $HMENU);

	$_main_show = '0,1,2';
	$_main_order = '0,1,2,3,4,5';
	$_main_num = '10,1,10,5,3,1';
	$_main_name = implode(',' , $HMAIN);

	$_side_show = '0,1,2,4,5,6';
	$_side_order = '0,1,2,3,4,5,6';
	$_side_num = '1,5,10,1,1,5,5';
	$_side_name = implode(',' , $HSIDE);

	$HOME = get_company_setting($COM['userid']);

	//MENU
	isset($HOME['menu_show']) or $HOME['menu_show'] = $_menu_show;
	$menu_show = explode(',', $HOME['menu_show']);
	isset($HOME['menu_order']) or $HOME['menu_order'] = $_menu_order;
	$menu_order = explode(',', $HOME['menu_order']);
	isset($HOME['menu_num']) or $HOME['menu_num'] = $_menu_num;
	$menu_num = explode(',', $HOME['menu_num']);
	isset($HOME['menu_name']) or $HOME['menu_name'] = $_menu_name;
	$menu_name = explode(',', $HOME['menu_name']);
	$_HMENU = array();
	asort($menu_order);
	$hide_file = array();
	foreach($menu_order as $k=>$v) {
		if(in_array($k, $menu_show)) {
			$_HMENU[$k] = $menu_name[$k];
		} else {
			$hide_file[] = $MFILE[$k];
		}
	}
	if($hide_file && in_array($file, $hide_file)) dheader($COM['linkurl']);//不显示隐藏菜单

	$HMENU = $_HMENU;
	$MENU = array();
	$menuid = 0;
	foreach($HMENU as $k=>$v) {
		$MENU[$k]['name'] = $v;
		$MENU[$k]['file'] = $MFILE[$k];
		$MENU[$k]['pagesize'] = $menu_num[$k];
		$MENU[$k]['linkurl'] = userurl($username, 'file='.$MFILE[$k], $domain);
		if($file == $MFILE[$k]) $menuid = $k;
	}

	//SIDE	
	isset($HOME['side_show']) or $HOME['side_show'] = $_side_show;
	$side_show = explode(',', $HOME['side_show']);
	isset($HOME['side_order']) or $HOME['side_order'] = $_side_order;
	$side_order = explode(',', $HOME['side_order']);
	isset($HOME['side_num']) or $HOME['side_num'] = $_side_num;
	$side_num = explode(',', $HOME['side_num']);
	isset($HOME['side_name']) or $HOME['side_name'] = $_side_name;
	$side_name = explode(',', $HOME['side_name']);
	$_HSIDE = array();
	asort($side_order);
	foreach($side_order as $k=>$v) {
		if(in_array($k, $side_show)) $_HSIDE[$k] = $side_name[$k];
	}
	$HSIDE = $_HSIDE;

	$side_pos = isset($HOME['side_pos']) && $HOME['side_pos'] ? 1 : 0;
	$side_width = isset($HOME['side_width']) && $HOME['side_width'] ? $HOME['side_width'] : 200;
	$intro_length = isset($HOME['intro_length']) && $HOME['intro_length'] ? intval($HOME['intro_length']) : 1000;
	
	$COM['intro'] = dsubstr(strip_tags($COM['intro']), $intro_length, '...');

	$skin = 'default';
	$template = 'homepage';
	if($COMGROUP['styleid']) {
		$r = $db->get_one("SELECT * FROM {$DT_PRE}style WHERE itemid=$COMGROUP[styleid]");
		if($r) {
			$skin = $r['skin'];
			$template = $r['template'];
		}
	}
	if($COM['skin']) $skin = $COM['skin'];
	if($COM['template']) $template = $COM['template'];
	if($COM['banner']) file_ext($COM['banner']) == 'swf' or $COM['banner'] = '';

	//会员预览
	if($username == $_username && $file == 'homepage') {
		$preview = isset($preview) ? intval($preview) : 0;
		if($preview) {//Preivew
			$preview = $db->get_one("SELECT * FROM {$DT_PRE}style WHERE itemid={$preview}");
			if($preview) {
				$skin = $preview['skin'];
				$template = $preview['template'];
			}
		}
	}
	$could_comment = intval($MOD['comment']) ? true : false;
	if($domain) $could_comment = false;
	$could_contact = check_group($_groupid, $MOD['group_contact']);
	if($username == $_username || $domain) $could_contact = true;

	$HSPATH = SKIN_PATH.'homepage/'.$skin.'/';//风格目录
	$background = isset($HOME['background']) && $HOME['background'] ? $HOME['background'] : '';
	$banner = isset($HOME['banner']) && $HOME['banner'] ? $HOME['banner'] : (is_file(DT_ROOT.'/skin/'.$CFG['skin'].'/homepage/'.$skin.'/banner.jpg') ? $HSPATH.'banner.jpg' : '');
	$bgcolor = isset($HOME['bgcolor']) && $HOME['bgcolor'] ? $HOME['bgcolor'] : '';
	$logo = isset($HOME['logo']) && $HOME['logo'] ? $HOME['logo'] : '';
	$css = isset($HOME['css']) && $HOME['css'] ? $HOME['css'] : '';
	$announce = isset($HOME['announce']) && $HOME['announce'] ? $HOME['announce'] : '';
	$map = isset($HOME['map']) && $HOME['map'] ? $HOME['map'] : '';

	$head_title = $MENU[$menuid]['name'];
	$seo_keywords = isset($HOME['seo_keywords']) && $HOME['seo_keywords'] ? $HOME['seo_keywords'] : '';
	$seo_description = isset($HOME['seo_description']) && $HOME['seo_description'] ? $HOME['seo_description'] : '';
	
	$head_keywords = $seo_keywords ? $seo_keywords : $COM['company'].','.$COM['business'];
	$head_description = $seo_description ? $seo_description : $COM['introduce'];

	(@include MOD_ROOT.'/'.$file.'.inc.php') or dheader($MOD['linkurl']);
} else {
	//防止恶意绑定IP
	if(strpos($DT_URL, $MOD['linkurl']) === false) exit(header("HTTP/1.1 404 Not Found"));
	if(!check_group($_groupid, $MOD['group_index'])) {
		$head_title = '抱歉，您所在的会员组没有权限访问此页面';
		include template('noright', 'message');
		exit;
	}

	include DT_ROOT.'/include/seo.inc.php';
	if($MOD['seo_index']) {
		eval("\$seo_title = \"$MOD[seo_index]\";");
	} else {
		$seo_title = $seo_modulename.$seo_delimiter.$seo_sitename;
	}

	$template = $MOD['template'] ? $MOD['template'] : 'index';
	include template($template, $module);
}
?>