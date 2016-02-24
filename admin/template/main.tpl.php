<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div id="tips_update" style="display:none;">
<div class="tt">系统更新提示</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td><div style="padding:20px 30px 20px 20px;" title="当前版本V<?php echo DT_VERSION; ?> 更新时间<?php echo DT_RELEASE;?>"><img src="<?php echo IMG_PATH;?>tips_update.gif" width="32" height="32" align="absmiddle"/>&nbsp;&nbsp; <span class="f_red">您的当前软件版本有新的更新，请注意升级</span>&nbsp;&nbsp;最新版本：V<span id="last_v"><?php echo DT_VERSION; ?></span> 更新时间：<span id="last_r"><?php echo DT_RELEASE; ?></span>&nbsp;&nbsp;
<input type="button" value="检查更新" class="btn" onclick="window.location='?file=destoon&action=update';"/></div></td>
</tr>
</table>
</div>
<?php if($mysql_tip) { ?>
<div class="tt">数据备份提示</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td height="30"><div style="padding:20px 30px 20px 20px;"><img src="<?php echo IMG_PATH;?>tips_backup.gif" width="32" height="32" align="absmiddle"/>&nbsp;&nbsp; <span class="f_blue"><?php echo $mysql_tip;?></span>&nbsp;&nbsp;
<input type="button" value="备份数据" class="btn" onclick="window.location='?file=database';"/></div></td>
</tr>
</table>
<?php } ?>
<div class="tt"><span class="f_r px11">IP:<?php echo $user['loginip']; ?>&nbsp;&nbsp;</span>欢迎管理员，<?php echo $_username;?></div>
<form method="post" action="?">
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">管理级别</td>
<td width="30%">&nbsp;<?php echo $_level == 1 ? '超级管理员' : '普通管理员'; ?></td>
<td class="tl">登录次数</td>
<td width="30%">&nbsp;<?php echo $user['logintimes']; ?> 次</td>
</tr>
<tr>
<td class="tl">站内信件</td>
<td>&nbsp;<a href="<?php echo $MODULE[2]['linkurl'].'message.php';?>" target="_blank">收件箱[<?php echo $_message ? '<strong class="f_red">'.$_message.'</strong>' : $_message;?>]</a></td>
<td class="tl">登录时间</td>
<td>&nbsp;<?php echo timetodate($user['logintime'], 5); ?> </td>
</tr>
<tr>
<td class="tl">工作便笺</td>
<td colspan="2"><textarea name="note" style="width:98%;height:50px;overflow:visible;color:#444444;"><?php echo $note;?></textarea></td>
<td><input type="submit" name="submit" value=" 保 存 " class="btn"/></td>
</tr>
</table>
</form>
<div id="destoon"></div>
<div class="tt">统计信息</div>
<table cellpadding="2" cellspacing="1" class="tb">

<tr>
<td class="tl"><a href="?moduleid=2&file=ask" class="t">待受理客服中心</a></td>
<td>&nbsp;<a href="?moduleid=2&file=ask&status=0"><span id="ask"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>
<td class="tl"><a href="?moduleid=2&file=charge" class="t">待受理在线充值</a></td>
<td>&nbsp;<a href="?moduleid=2&file=charge&status=0"><span id="charge"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>
<td class="tl"><a href="?moduleid=2&file=cash" class="t">待受理资金提现</a></td>
<td>&nbsp;<a href="?moduleid=2&file=cash&status=0"><span id="cash"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>
<td class="tl"><a href="?moduleid=2&file=trade&status=5" class="t">待受理会员交易</a></td>
<td>&nbsp;<a href="?moduleid=2&file=trade"><span id="trade"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>
</tr>


<tr>
<td class="tl"><a href="?moduleid=2&file=news&action=check" class="t">待审核公司新闻</a></td>
<td>&nbsp;<a href="?moduleid=2&file=news&action=check"><span id="news"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>
<td class="tl"><a href="?moduleid=2&file=credit&action=check" class="t">待审核荣誉资质</a></td>
<td>&nbsp;<a href="?moduleid=2&file=credit&action=check"><span id="credit"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>
<td class="tl"><a href="?moduleid=2&file=link&action=check" class="t">待审核公司链接</a></td>
<td>&nbsp;<a href="?moduleid=2&file=link&action=check"><span id="comlink"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>

<td class="tl"><a href="?file=keyword&status=2" class="t">待审核搜索关键词</a></td>
<td>&nbsp;<a href="?file=keyword&status=2"><span id="keyword"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>
</tr>


<tr>
<td class="tl"><a href="?moduleid=3&file=spread&action=check" class="t">待审核排名推广</a></td>
<td>&nbsp;<a href="?moduleid=3&file=spread&action=check"><span id="spread"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>
<td class="tl"><a href="?moduleid=3&file=guestbook" class="t">待回复网站留言</a></td>
<td>&nbsp;<a href="?moduleid=3&file=guestbook"><span id="guestbook"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>
<td class="tl"><a href="?moduleid=3&file=comment&action=check" class="t">待审核评论</a></td>
<td>&nbsp;<a href="?moduleid=3&file=comment&action=check"><span id="comment"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>

<td class="tl"><a href="?moduleid=3&file=link&action=check" class="t">待审核友情链接</a></td>
<td>&nbsp;<a href="?moduleid=3&file=link&action=check"><span id="link"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>
</tr>

<tr>
<td class="tl"><a href="?moduleid=2" class="t">会员</a></td>

<td width="10%">&nbsp;<a href="?moduleid=2"><span id="member"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>

<td class="tl"><a href="?moduleid=4&file=vip&action=check" class="t"><?php echo VIP;?>申请</a></td>

<td width="10%">&nbsp;<a href="?moduleid=4&file=vip&action=check"><span id="member_vip"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>

<td class="tl"><a href="?moduleid=2&action=check" class="t">待审核</a></td>

<td width="10%">&nbsp;<a href="?moduleid=2&action=check"><span id="member_check"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>


<td class="tl"><a href="?moduleid=2&action=add" class="t">今日新增</a></td>

<td width="10%">&nbsp;<a href="?moduleid=2"><span id="member_new"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>
</tr>


<?php
foreach ($MODULE as $m) {
	if($m['moduleid'] < 5 || $m['islink']) continue;
?>

<?php 
if($m['moduleid'] == 9) $m['name'] = '招聘';
?>

<tr>
<td class="tl"><a href="<?php echo $m['linkurl'];?>" class="t" target="_blank"><?php echo $m['name'];?></a></td>

<td>&nbsp;<a href="?moduleid=<?php echo $m['moduleid'];?>"><span id="m_<?php echo $m['moduleid'];?>"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>

<td class="tl"><a href="?moduleid=<?php echo $m['moduleid'];?>" class="t">已发布</a></td>

<td>&nbsp;<a href="?moduleid=<?php echo $m['moduleid'];?>"><span id="m_<?php echo $m['moduleid'];?>_1"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>

<td class="tl"><a href="?moduleid=<?php echo $m['moduleid'];?>&action=check" class="t">待审核</a></td>

<td>&nbsp;<a href="?moduleid=<?php echo $m['moduleid'];?>&action=check"><span id="m_<?php echo $m['moduleid'];?>_2"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>

<td class="tl"><a href="?moduleid=<?php echo $m['moduleid'];?>&action=add" class="t">今日新增</a></td>

<td>&nbsp;<a href="?moduleid=<?php echo $m['moduleid'];?>"><span id="m_<?php echo $m['moduleid'];?>_3"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>
</tr>


<?php
if($m['moduleid'] == 9) {
	$m['name'] = '简历';
?>
<tr>
<td class="tl"><a href="<?php echo $m['linkurl'];?>" class="t" target="_blank"><?php echo $m['name'];?></a></td>

<td>&nbsp;<a href="?moduleid=<?php echo $m['moduleid'];?>&file=resume"><span id="m_resume"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>

<td class="tl"><a href="?moduleid=<?php echo $m['moduleid'];?>&file=resume" class="t">已发布</a></td>

<td>&nbsp;<a href="?moduleid=<?php echo $m['moduleid'];?>"><span id="m_resume_1"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>

<td class="tl"><a href="?moduleid=<?php echo $m['moduleid'];?>&file=resume&action=check" class="t">待审核</a></td>

<td>&nbsp;<a href="?moduleid=<?php echo $m['moduleid'];?>&file=resume&action=check"><span id="m_resume_2"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>

<td class="tl"><a href="?moduleid=<?php echo $m['moduleid'];?>&file=resume&action=add" class="t">今日新增</a></td>

<td>&nbsp;<a href="?moduleid=<?php echo $m['moduleid'];?>"><span id="m_resume_3"><img src="<?php echo IMG_PATH;?>count.gif" width="10" height="10" alt="正在统计"/></span></a></td>
</tr>

<?php } ?>

<?php
}
?>
</table>
<div class="tt">系统信息</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">程序信息</td>
<td>&nbsp;<a href="?file=destoon&action=update" class="t">Destoon B2B Version <?php echo DT_VERSION;?> Release <?php echo DT_RELEASE;?> [检查更新]</a></td>
</tr>
<tr>
<td class="tl">安装时间</td>
<td>&nbsp;<?php echo $install;?></td>
</tr>
<tr>
<td class="tl">授权查询</td>
<td>&nbsp;<a href="?file=destoon&action=authorization" target="_blank" title="域名授权查询">点击查询</a></td>
</tr>
<tr>
<td class="tl">官方网站</td>
<td>&nbsp;<a href="http://www.destoon.com" target="_blank">http://www.destoon.com</a></td>
</tr>
<tr>
<td class="tl">支持论坛</td>
<td>&nbsp;<a href="http://bbs.destoon.com" target="_blank">http://bbs.destoon.com</a></td>
</tr>
<tr>
<td class="tl">使用帮助</td>
<td>&nbsp;<a href="http://help.destoon.com" target="_blank">http://help.destoon.com</a></td>
</tr>
<tr>
<td class="tl">服务器时间</td>
<td>&nbsp;<?php echo timetodate($DT_TIME, 'Y-m-d H:i:s l');?></td>
</tr>
<?php if($_level == 1) {?>
<tr>
<td class="tl">服务器信息</td>
<td>&nbsp;<?php echo PHP_OS.'&nbsp;'.$_SERVER["SERVER_SOFTWARE"];?> [<?php echo gethostbyname($_SERVER['SERVER_NAME']);?>:<?php echo $_SERVER["SERVER_PORT"];?>] <a href="?action=phpinfo" target="_blank">[详细信息]</a></td>
</tr>
<tr>
<td class="tl">数据库版本</td>
<td>&nbsp;MySQL <?php echo $db->version();?></td>
</tr>
<?php } ?>
</table>
<div class="tt">使用协议</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td style="padding:10px;"><textarea style="width:100%;height:100px;"><?php echo file_get_contents(DT_ROOT.'/license.txt');?></textarea></td>
</tr>
</table>
<script type="text/javascript">Menuon(0);</script>
<script type="text/javascript" src="?action=count"></script>
<script type="text/javascript" src="<?php echo $notice_url;?>"></script>
<script type="text/javascript">
var destoon_release = <?php echo DT_RELEASE;?>;
if(typeof destoon_lastrelease == 'undefined') {
	//
} else {
	var lastrelease = parseInt(destoon_lastrelease.replace('-', '').replace('-', ''));
	if(destoon_release < lastrelease) {
		$('tips_update').style.display = '';
		$('last_v').innerHTML = destoon_lastversion;
		$('last_r').innerHTML = destoon_lastrelease;
	}
}
</script>
</body>
</html>