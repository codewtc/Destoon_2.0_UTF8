<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="tt">发送邮件</div>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="send" value="1"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">收件人 <span class="f_red">*</span></td>
<td>
	<input type="radio" name="sendtype" value="1" id="s1" onclick="ck(1);" checked/> <label for="s1">单收件人</label>
	<input type="radio" name="sendtype" value="2" id="s2" onclick="ck(2);"/> <label for="s2">多收件人</label>
	<input type="radio" name="sendtype" value="3" id="s3" onclick="ck(3);"/> <label for="s3">列表群发</label>
</td>
</tr>
<tbody id="t1" style="display:;">
<tr>
<td class="tl">邮件地址 <span class="f_red">*</span></td>
<td><input type="text" size="30" name="email" value="<?php echo $email;?>"/></td>
</tr>
</tbody>
<tbody id="t2" style="display:none;">
<tr>
<td class="tl">邮件地址 <span class="f_red">*</span></td>
<td class="f_gray"><textarea name="emails" rows="4" cols="50"></textarea> [一行一个邮件地址]</td>
</tr>
</tbody>
<tbody id="t3" style="display:none;">
<tr>
<td class="tl">邮件列表 <span class="f_red">*</span></td>
<td class="f_red">
<?php
	$mails = glob(DT_ROOT.'/file/email/*.txt');
	if($mails) {
		echo '<select name="mail" id="maillist"><option value="0">请选择邮件列表</option>';
		foreach($mails as $m) {
			$tmp = basename($m);
			echo '<option value="'.$tmp.'">'.$tmp.'</option>';
		}
		echo '</select>';
	} else {
		echo '无邮件列表';
	}
?>
&nbsp;&nbsp;<a href="javascript:" onclick="if($('maillist').value != 0){window.open('file/email/'+$('maillist').value);}else{alert('请先选择邮件列表');$('maillist').focus();}" class="t">[查看选中]</a>&nbsp;&nbsp;<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=make" class="t">[获取列表]</a>
</td>
</tr>
<tr>
<td class="tl">每轮发送邮件数 <span class="f_red">*</span></td>
<td><input type="text" size="5" name="pernum" id="pernum" value="5"/></td>
</tr>
</tbody>
<tr>
<td class="tl">邮件标题 <span class="f_red">*</span></td>
<td><input type="text" size="60" name="title" id="title"/> <span id="dtitle" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">发件人邮箱</td>
<td><input type="text" size="30" name="sender" id="sender" value="<?php echo $DT['mail_sender'];?>"/></td>
</tr>
<tr>
<td class="tl">发件人名称</td>
<td><input type="text" size="30" name="name" id="name" value="<?php echo $DT['mail_name'];?>"/></td>
</tr>
<tr>
<td class="tl">邮件内容 <span class="f_red">*</span></td>
<td>
<textarea name="content" id="content" class="dsn"></textarea><?php echo deditor($moduleid, 'content', 'Default', '95%', 350);?>
<br/><span id="dcontent" class="f_red"></span>
</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value=" 重 置 " class="btn"/></div>
</form>
<?php load('clear.js'); ?>
<script type="text/javascript">
var i = 1;
function ck(id) {
	$('t'+i).style.display='none';
	$('t'+id).style.display='';
	i = id;
}
function check() {
	var l;
	var f;
	f = 'title';
	l = $(f).value.length;
	if(l < 2) {
		Dmsg('标题最少2字，当前已输入'+l+'字', f);
		return false;
	}
	f = 'content';
	l = FCKLen();
	if(l < 5) {
		Dmsg('内容最少5字，当前已输入'+l+'字', f);
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(0);</script>
</body>
</html>