<?php
defined('IN_DESTOON') or exit('Access Denied');
include IN_ROOT.'/header.tpl.php';
?>
<div class="head">
	<div>
		<strong>欢迎使用，Destoon B2B网站管理系统V<?php echo DT_VERSION;?> <?php echo strtoupper($CFG['charset']);?> 安装向导</strong><br/>
		本向导将引导您完成DESTOON软件安装，请仔细阅读以下软件使用协议：
	</div>
</div>
<div class="body">
<div>
<textarea style="width:100%;height:215px;">
<?php echo $license;?>
</textarea>
</div>
</div>
<div class="foot">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width="215">
<div class="progress">
<div id="progress"></div>
</div>
</td>
<td id="percent"></td>
<td height="40" align="right">

<form action="index.php" method="post" id="dform">
<input type="hidden" name="step" value="2"/>
<input type="submit" value="我同意(I)"/>
<input type="button" value="打印(P)" onclick="Print();"/>
&nbsp;&nbsp;
<input type="button" value="取消(C)" onclick="if(confirm('您确定要退出安装向导吗？')) window.close();"/>
</form>
<textarea style="display:none;" id="license">
<?php echo nl2br($license);?>
</textarea>
<script type="text/javascript">
function Print() {
	var w = window.open('','','');
	w.opener = null;
	w.document.write('<html><head><meta http-equiv="Content-Type" content="text/html;charset=<?php echo $CFG['charset'];?>" /></head><body><div style="width:650px;font-size:10pt;line-height:19px;font-family:Verdana,Arial;">'+$('license').value+'</div></body></html>');
	w.window.print();
}
</script>
<script type="text/javascript" src="http://www.destoon.com/install.php?release=<?php echo DT_RELEASE;?>&charset=<?php echo $CFG['charset'];?>"></script>
<?php
include IN_ROOT.'/footer.tpl.php';
?>