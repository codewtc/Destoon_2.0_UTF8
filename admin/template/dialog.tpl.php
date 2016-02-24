<?php 
defined('IN_DESTOON') or exit('Access Denied');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=<?php echo $CFG['charset']; ?>" />
<title>提示信息 - Powered By Destoon <?php echo DT_VERSION; ?></title>
<link rel="stylesheet" href="<?php echo IMG_PATH; ?>style.css" type="text/css" />
<script type="text/javascript" src="<?php echo DT_PATH;?>javascript/ie.js"></script>
<script type="text/javascript">
var DTPath = '<?php echo DT_PATH;?>';
var SKPath = '<?php echo SKIN_PATH;?>';
<?php if(DT_DOMAIN) { ?>if(!isIE8) document.domain = '<?php echo DT_DOMAIN;?>';<?php } ?>
</script>
<script type="text/javascript" src="<?php echo DT_PATH;?>javascript/common.js"></script>
<script type="text/javascript" src="<?php echo DT_PATH;?>javascript/admin.js"></script>
</head>
</body>
<div id="box">
<?php echo $dcontent; ?>
</div>
<script type="text/javascript">
try{parent.$('dload').style.display='none';parent.$('diframe').style.height = $('box').scrollHeight+'px';} catch(e){}
</script>
</body>
</html>