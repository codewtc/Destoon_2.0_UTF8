<?php 
defined('IN_DESTOON') or exit('Access Denied');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=<?php echo $CFG['charset']; ?>" />
<meta name="robots" content="noindex,nofollow"/>
<title>管理员登录 - Powered By Destoon <?php echo DT_VERSION; ?></title>
<link rel="stylesheet" href="<?php echo IMG_PATH; ?>login.css" type="text/css" />
<script type="text/javascript" src="<?php echo DT_PATH;?>javascript/ie.js"></script>
<script type="text/javascript">
var DTPath = '<?php echo DT_PATH;?>';
var SKPath = '<?php echo SKIN_PATH;?>';
<?php if(DT_DOMAIN) { ?>document.domain = '<?php echo DT_DOMAIN;?>';<?php } ?>
</script>
<script type="text/javascript" src="<?php echo DT_PATH;?>javascript/common.js"></script>
</head>
</body>
<noscript><br/><br/><br/><center><h1>您的浏览器不支持JavaScript,请更换支持JavaScript的浏览器</h1></center></noscript>
<noframes><br/><br/><br/><center><h1>您的浏览器不支持框架,请更换支持框架的浏览器</h1></center></noframes>
<table cellpadding="0" cellspacing="0" width="400"  align="center">
<tr>
<td height="100"></td>
</tr>
<tr>
<td>
	<div class="msg">
		<div class="head"><div class="mr">&nbsp;</div><div class="ml">管理员登录 IP:<?php echo $DT_IP;?></div></div>
		<div class="content">
		<form method="post" action="?"  onsubmit="return Dcheck();">
		<input type="hidden" name="file" value="<?php echo $file;?>"/>
		<input name="forward" type="hidden" value="<?php echo $forward;?>"/>
		<table cellpadding="2" cellspacing="1" width="100%">
		<tr>
		<td colspan="2" height="50"><a href="http://www.destoon.com/" target="_blank"><img src="<?php echo IMG_PATH;?>spacer.gif" width="290" height="30" title="Powered By www.destoon.com" alt=""/></a></td>
		</tr>
		<tr>
		<td height="20" colspan="2" class="tip"><img src="<?php echo IMG_PATH;?>lock.gif"/> 您尚未登录或登录超时，请登录后继续操作...</td>
		</tr>
		<tr>
		<td>&nbsp;户&nbsp;&nbsp;&nbsp;名</td>
		<td><input name="username" type="text" id="username" class="inp" style="width:140px;" value="<?php echo $username;?>"/></td>
		</tr>
		<tr>
		<td>&nbsp;密&nbsp;&nbsp;&nbsp;码</td>
		<td><?php include template('password', 'chip');?></td>
		</tr>
		<?php if($DT['captcha_admin']) { ?>
		<tr>
		<td>&nbsp;验证码</td>
		<td><?php include template('captcha', 'chip');?></td>
		</tr>
		<?php } ?>
		<tr>
		<td></td>
		<td><input type="submit" name="submit" value=" 登 录 " class="btn" tabindex="4">&nbsp;&nbsp;<input type="reset" name="reset" value=" 重 置 " class="btn">
		</td>
		</tr>
		<script type="text/javascript">
			if(screen.width<1000) document.write('<tr><td colspan="2"><span style="color:red">系统提示:</span><br/>后台最佳显示宽度为等于或大于1024px<br/>您的当前显示器屏幕宽度为'+screen.width+'px<br/>建议您在后台管理时关闭左侧栏或更换显示器</td></tr>');
		</script>

		</table>
		</form>
		</div>
	</div>
</td>
</tr>
</table>
<script type="text/javascript">
if($('username').value == '') {
	$('username').focus();
} else {
	$('password').focus();
}
function Dcheck() {
	if($('username').value == '') {
		confirm('请填写会员名');
		$('username').focus();
		return false;
	}
	if($('password').value == '') {
		confirm('请填写密码');
		$('password').focus();
		return false;
	}
	<?php if($DT['captcha_admin']) { ?>
	if(!is_captcha($('captcha').value)) {
		confirm('请填写验证码');
		$('captcha').focus();
		return false;
	}
	<?php } ?>
	return true;
}
</script>
</body>
</html>