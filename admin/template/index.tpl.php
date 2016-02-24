<?php
defined('IN_DESTOON') or exit('Access Denied');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=<?php echo $CFG['charset'];?>" />
<meta name="robots" content="noindex,nofollow"/>
<title><?php echo $DT['sitename']; ?> - 网站管理 - Powered By Destoon V<?php echo DT_VERSION; ?> R<?php echo DT_RELEASE;?></title>
</head>
<noscript><br/><br/><br/><center><h1>您的浏览器不支持JavaScript,请更换支持JavaScript的浏览器</h1></center></noscript>
<noframes><br/><br/><br/><center><h1>您的浏览器不支持框架,请更换支持框架的浏览器</h1></center></noframes>
<frameset cols="188,7,*" frameborder="no" border="0" framespacing="0" name="fra"> 
<frame name="left" noresize scrolling="auto" src="?action=left">
<frame name="nav" noresize scrolling="no" src="?action=side">
<frame name="main" noresize scrolling="yes" src="?action=main">
</frameset>
</html>