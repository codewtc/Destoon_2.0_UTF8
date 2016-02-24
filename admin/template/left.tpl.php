<?php
defined('IN_DESTOON') or exit('Access Denied');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=<?php echo $CFG['charset'];?>" />
<title><?php echo $DT['sitename']; ?> - 网站管理 - Powered By Destoon V<?php echo DT_VERSION; ?> R<?php echo DT_RELEASE;?></title>
<link rel="stylesheet" href="<?php echo IMG_PATH; ?>style.css" type="text/css" />
<script type="text/javascript" src="<?php echo DT_PATH;?>javascript/ie.js"></script>
<script type="text/javascript">
var DTPath = '<?php echo DT_PATH;?>';
var SKPath = '<?php echo SKIN_PATH;?>';
</script>
<script type="text/javascript" src="<?php echo DT_PATH;?>javascript/common.js"></script>
<script type="text/javascript" src="<?php echo DT_PATH;?>javascript/admin.js"></script>
<base target="main"/>
</head>
<body style="overflow-x:hidden;">
<?php if($_level == 2) { ?>
<table cellpadding="0" cellspacing="0" width="188" height="100%">
<tr>
<td id="bar" class="bar" valign="top" align="center"><img src="<?php echo IMG_PATH;?>bar2on.gif" width="25" height="85" alt="" /><img src="<?php echo IMG_PATH;?>barnav.gif" width="25" height="1" alt=""/></td>
<td valign="top" class="barmain">
<div class="bartop">
<table cellpadding="0" cellspacing="0" width="100%">
<tr height="20">
<td width="5"></td>
<td id="name">我的面板</td>
<td width="40">
<a href="<?php echo DT_PATH;?>" target="_blank"><img src="<?php echo IMG_PATH;?>home.gif" width="8" height="8" alt="网站首页"/></a>&nbsp;
<a href="javascript:window.location.reload();" target="_self"><img src="<?php echo IMG_PATH;?>reload.gif" width="8" height="8" title="刷新菜单"/></a>&nbsp;
<a href="?file=logout" target="_top" onclick="if(!confirm('确实要注销登录吗?')) return false;"><img src="<?php echo IMG_PATH;?>quit.gif" width="8" height="8" alt="注销登录"/></a>
</td>
<td width="5"></td>
</tr>
</table>
</div>
<div id="menu">
	<dl> 
	<dt onclick="s(this)" onmouseover="this.className='dt_on';" onmouseout="this.className='';">使用帮助</dt> 
	<dd onclick="c(this);" style="display:none;"><a href="?file=destoon&action=license">使用协议</a></dd>
	<dd onclick="c(this);" style="display:none;"><a href="?file=destoon&action=doc">在线文档</a></dd>
	<dd onclick="c(this);" style="display:none;"><a href="?file=destoon&action=support">技术支持</a></dd>
	<dd onclick="c(this);" style="display:none;"><a href="?file=destoon&action=bbs">官方论坛</a></dd>
	<dd onclick="c(this);" style="display:none;"><a href="?file=destoon&action=feedback">信息反馈</a></dd>
	<dd onclick="c(this);" style="display:none;"><a href="?file=destoon&action=update">检查更新</a></dd>
	<dd onclick="c(this);" style="display:none;"><a href="?file=destoon&action=about">关于软件</a></dd>
	</dl>
	<dl>
	<dt onclick="s(this)" onmouseover="this.className='dt_on';" onmouseout="this.className='';">我的面板</dt>
	<dd onclick="c(this);"><a href="?action=main">系统首页</a></dd>
	<dd onclick="c(this);"><a href="?file=mymenu">定义面板</a></dd>
	<?php
		foreach($mymenu as $menu) {
	?>
	<dd onclick="c(this);"><a href="<?php echo $menu['url'];?>"><?php echo set_style($menu['title'], $menu['style']);?></a></dd>
	<?php
		}
	?>
	</dl>
</div>
</td>
</tr>
</table>
<?php } else { ?>
<table cellpadding="0" cellspacing="0" width="188" height="100%">
<tr>
<td id="bar" class="bar" valign="top" align="center"><img src="<?php echo IMG_PATH;?>bar1on.gif" width="25" height="85" alt="" id="b_1" onclick="show(1);"/><img src="<?php echo IMG_PATH;?>barnav.gif" width="25" height="1" alt="" id="n_1"/><img src="<?php echo IMG_PATH;?>bar2.gif" width="25" height="85" alt="" id="b_2" onclick="show(2);"/><img src="<?php echo IMG_PATH;?>barnav.gif" width="19" height="1" alt="" id="n_2"/><img src="<?php echo IMG_PATH;?>bar3.gif" width="25" height="85" alt="" id="b_3" onclick="show(3);"/><img src="<?php echo IMG_PATH;?>barnav.gif" width="19" height="1" alt="" id="n_3"/><img src="<?php echo IMG_PATH;?>bar4.gif" width="25" height="85" alt="" id="b_4" onclick="show(4);"/><img src="<?php echo IMG_PATH;?>barnav.gif" width="19" height="1" alt="" id="n_4"/></td>
<td valign="top" class="barmain">
<div class="bartop">
<table cellpadding="0" cellspacing="0" width="100%">
<tr height="20">
<td width="5"></td>
<td id="name">&nbsp;</td>
<td width="40">
<a href="<?php echo DT_PATH;?>" target="_blank"><img src="<?php echo IMG_PATH;?>home.gif" width="8" height="8" alt="网站首页"/></a>&nbsp;
<a href="javascript:window.location.reload();" target="_self"><img src="<?php echo IMG_PATH;?>reload.gif" width="8" height="8" title="刷新菜单"/></a>&nbsp;
<a href="?file=logout" target="_top" onclick="if(!confirm('确实要注销登录吗?')) return false;"><img src="<?php echo IMG_PATH;?>quit.gif" width="8" height="8" alt="注销登录"/></a>
</td>
<td width="5"></td>
</tr>
</table>
</div>
<div id="menu">&nbsp;</div>
</td>
</tr>
</table>
<div style="display:none;">
	<div id="m_1">
	<dl> 
	<dt onclick="s(this)" onmouseover="this.className='dt_on';" onmouseout="this.className='';">系统设置</dt> 
	<dd onclick="c(this);"><a href="?file=setting">网站设置</a></dd>
	<dd onclick="c(this);"><a href="?file=module">模块管理</a></dd>
	<dd onclick="c(this);"><a href="?file=area">地区管理</a></dd>
	<dd onclick="c(this);"><a href="?file=admin">管理员管理</a></dd>
	</dl>
	<dl> 
	<dt onclick="s(this)" onmouseover="this.className='dt_on';" onmouseout="this.className='';">更新数据</dt> 
	<dd onclick="c(this);"><a href="?file=html" onclick="return confirm('确定要开始更新全站页面吗？此操作比较耗费服务器资源和时间 ');">更新全站</a></dd>
	<dd onclick="c(this);"><a href="?action=html">生成首页</a></dd>
	<dd onclick="c(this);"><a href="?action=tag">更新标签</a></dd>
	<dd onclick="c(this);"><a href="?action=cache">更新缓存</a></dd>
	</dl>

	<dl> 
	<dt onclick="s(this)" onmouseover="this.className='dt_on';" onmouseout="this.className='';">系统工具</dt>
	<dd onclick="c(this);"><a href="?file=database">数据库维护</a></dd>
	<dd onclick="c(this);"><a href="?file=template">模板管理</a></dd>
	<dd onclick="c(this);"><a href="?file=tag">标签向导</a></dd>
	<dd onclick="c(this);"><a href="?file=skin">风格管理</a></dd>
	<dd onclick="c(this);"><a href="?file=log">操作日志</a></dd>
	<dd onclick="c(this);"><a href="?file=banip">禁止IP</a></dd>
	<dd onclick="c(this);"><a href="?file=question">问题验证</a></dd>
	<dd onclick="c(this);"><a href="?file=banword">词语过滤</a></dd>
	<dd onclick="c(this);"><a href="?file=keyword">关键词管理</a></dd>
	<dd onclick="c(this);"><a href="?file=data">数据导入</a></dd>
	</dl>
	</div>
	<div id="m_2">
	<dl>
	<dt onclick="s(this)" onmouseover="this.className='dt_on';" onmouseout="this.className='';">使用帮助</dt> 
	<dd onclick="c(this);" style="display:none;"><a href="?file=destoon&action=license">使用协议</a></dd>
	<dd onclick="c(this);" style="display:none;"><a href="?file=destoon&action=doc">在线文档</a></dd>
	<dd onclick="c(this);" style="display:none;"><a href="?file=destoon&action=support">技术支持</a></dd>
	<dd onclick="c(this);" style="display:none;"><a href="?file=destoon&action=bbs">官方论坛</a></dd>
	<dd onclick="c(this);" style="display:none;"><a href="?file=destoon&action=feedback">信息反馈</a></dd>
	<dd onclick="c(this);" style="display:none;"><a href="?file=destoon&action=update">检查更新</a></dd>
	<dd onclick="c(this);" style="display:none;"><a href="?file=destoon&action=about">关于软件</a></dd>
	</dl>
	<dl>
	<dt onclick="s(this)" onmouseover="this.className='dt_on';" onmouseout="this.className='';">我的面板</dt>
	<dd onclick="c(this);"><a href="?action=main">系统首页</a></dd>
	<dd onclick="c(this);"><a href="?file=mymenu">定义面板</a></dd>
	<?php
		foreach($mymenu as $menu) {
	?>
	<dd onclick="c(this);"><a href="<?php echo $menu['url'];?>"><?php echo set_style($menu['title'], $menu['style']);?></a></dd>
	<?php
		}
	?>
	</dl>
	</div>
	<div id="m_3">
	<?php
	foreach($MODULE as $v) {
		if($v['moduleid'] > 2) {
			$menuinc = DT_ROOT.'/module/'.$v['module'].'/admin/menu.inc.php';
			if(is_file($menuinc)) {
				extract($v);
				include $menuinc;
				echo '<dl id="dl_'.$moduleid.'">';
				echo '<dt onclick="m('.$moduleid.');" onmouseover="this.className=\'dt_on\';" onmouseout="this.className=\'\';">'.($moduleid == 3 ? '扩展功能' : $name.'管理').'</dt>';
				foreach($menu as $m) {
					echo '<dd onclick="c(this);" style="display:none;"><a href="'.$m[1].'">'.$m[0].'</a></dd>';
				}
				echo '</dl>';
			}
		}
	}
	?>
	</div>	
	<div id="m_4">
	<?php
		$menuinc = DT_ROOT.'/module/'.$MODULE[2]['module'].'/admin/menu.inc.php';
		if(is_file($menuinc)) {
			extract($MODULE[2]);
			include $menuinc;
			echo '<dl id="dl_'.$moduleid.'">';
			echo '<dt id="dt_'.$moduleid.'" onclick="s($(\'dt_4\'));s(this);" onmouseover="this.className=\'dt_on\';" onmouseout="this.className=\'\';">'.$name.'管理</dt>';
			foreach($menu as $m) {
				echo '<dd onclick="c(this);"><a href="'.$m[1].'">'.$m[0].'</a></dd>';
			}
			echo '</dl>';
		}
	?>
	<?php
		$menuinc = DT_ROOT.'/module/'.$MODULE[4]['module'].'/admin/menu.inc.php';
		if(is_file($menuinc)) {
			extract($MODULE[4]);
			include $menuinc;
			echo '<dl id="dl_'.$moduleid.'">';
			echo '<dt id="dt_'.$moduleid.'" onclick="s($(\'dt_2\'));s(this);" onmouseover="this.className=\'dt_on\';" onmouseout="this.className=\'\';">'.$name.'管理</dt>';
			foreach($menu as $m) {
				echo '<dd onclick="c(this);" style="display:none;"><a href="'.$m[1].'">'.$m[0].'</a></dd>';
			}
			echo '</dl>';
		}
	?>
	<dl id="dl_pay"> 
	<dt id="dt_pay" onclick="s(this)" onmouseover="this.className='dt_on';" onmouseout="this.className='';">财务管理</dt> 
	<dd onclick="c(this);"><a href="?moduleid=2&file=record">资金流水</a></dd>
	<dd onclick="c(this);"><a href="?moduleid=2&file=charge">充值记录</a></dd>
	<dd onclick="c(this);"><a href="?moduleid=2&file=trade">交易记录</a></dd>
	<dd onclick="c(this);"><a href="?moduleid=2&file=cash">提现记录</a></dd>
	<dd onclick="c(this);"><a href="?moduleid=2&file=card">充值卡管理</a></dd>
	</dl>

	<dl id="dl_oth"> 
	<dt id="dt_oth" onclick="s(this)" onmouseover="this.className='dt_on';" onmouseout="this.className='';">会员相关</dt> 
	<dd onclick="c(this);"><a href="?moduleid=2&file=ask">客服中心</a></dd>
	<dd onclick="c(this);"><a href="?moduleid=2&file=sendmail">发送邮件</a></dd>
	<dd onclick="c(this);"><a href="?moduleid=2&file=mail">邮件订阅</a></dd>
	<dd onclick="c(this);"><a href="?moduleid=2&file=message">站内信件</a></dd>
	<dd onclick="c(this);"><a href="?moduleid=2&file=loginlog">登录日志</a></dd>
	</dl>
	</div>
</div>
<script type="text/javascript">
var names = ['', '系统维护', '我的面板', '功能模块', '会员管理'];
function show(ID) {
	var imgdir = '<?php echo IMG_PATH;?>';
	$('menu').innerHTML = $('m_'+ID).innerHTML;
	$('name').innerHTML = names[ID];
	for(i=1;i<names.length;i++) {
		if(i==ID) {
			$('b_'+i).src = imgdir+'bar'+i+'on.gif';
			if(i==1) {$('n_1').style.width = '25px';} else {$('n_'+i).style.width = '25px';$('n_'+(i-1)).style.width = '25px';}
		} else {
			$('b_'+i).src = imgdir+'bar'+i+'.gif';
			if(ID == 1) {$('n_'+i).style.width = '19px';} else if(i!=(ID-1) && i!=(ID+1)) {$('n_'+i).style.width = '19px';}
		}
		$('b_'+i).title = names[i];
	}
}
show(2);
</script>
<?php } ?>
<script type="text/javascript">
function c(ID) {
	var dds = $('menu').getElementsByTagName('dd');
	for(var i=0;i<dds.length;i++) {
		dds[i].className = dds[i] == ID ? 'dd_on' : '';
		if(dds[i] == ID) $(ID).firstChild.blur();
	}
}
function s(ID) {
	var dds = $(ID).parentNode.getElementsByTagName('dd');
	for(var i=0;i<dds.length;i++) {
		dds[i].style.display = dds[i].style.display == 'none' ? '' : 'none';
	}
}
function m(ID) {
	var dls = $('m_3').getElementsByTagName('dl');
	for(var i=0;i<dls.length;i++) {
		var dds = $(dls[i].id).getElementsByTagName('dd');
		for(var j=0;j<dds.length;j++) {
			dds[j].style.display = dls[i].id == 'dl_'+ID ? dds[j].style.display == 'none' ? '' : 'none' : 'none';
		}
	}
}
</script>
</body>
</html>