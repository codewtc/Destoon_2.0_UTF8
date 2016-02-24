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
<table cellpadding="0" cellspacing="0" width="100%" height="100%">
<tr>
<td class="side" title="点击关闭/打开侧栏" onclick="dside();">
<div id="side" class="side_on">&nbsp;</div>
</td>
</tr>
</table>
<script type="text/javascript">
function dside() {
	if($('side').className == 'side_on') {
		$('side').className = 'side_off';
		top.document.getElementsByName("fra")[0].cols = '0,7,*';
	} else {
		$('side').className = 'side_on';
		top.document.getElementsByName("fra")[0].cols = '188,7,*';
	}
}
</script>
</body>
</html>