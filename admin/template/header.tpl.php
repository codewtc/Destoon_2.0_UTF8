<?php
defined('IN_DESTOON') or exit('Access Denied');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=<?php echo $CFG['charset'];?>"/>
<title><?php echo $DT['sitename']; ?> - 网站管理 - Powered By Destoon V<?php echo DT_VERSION; ?> R<?php echo DT_RELEASE;?></title>
<meta name="robots" content="noindex,nofollow"/>
<meta name="author" content="Destoon B2B System. www.destoon.com"/>
<meta http-equiv="x-ua-compatible" content="ie=7"/>
<link rel="stylesheet" href="<?php echo IMG_PATH; ?>style.css" type="text/css"/>
<script type="text/javascript" src="<?php echo DT_PATH;?>javascript/ie.js"></script>
<script type="text/javascript">
var DTPath = '<?php echo DT_PATH;?>';
var SKPath = '<?php echo SKIN_PATH;?>';
<?php if(DT_DOMAIN) { ?>if(!isIE8) document.domain = '<?php echo DT_DOMAIN;?>';<?php } ?>
</script>
<script type="text/javascript" src="<?php echo DT_PATH;?>javascript/common.js"></script>
<script type="text/javascript" src="<?php echo DT_PATH;?>javascript/admin.js"></script>
</head>
<body>
<div id="msgbox" onmouseover="closemsg();" style="display:none;"></div>
<?php dmsg();?>