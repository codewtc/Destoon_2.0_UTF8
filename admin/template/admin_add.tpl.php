<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" onsubmit="return check();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="add"/>
<div class="tt">添加管理员</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">会员名 <span class="f_red">*</span></td>
<td><input type="text" size="20" name="username" id="username"/>&nbsp; <a href="?moduleid=2&action=add" class="t">如果会员还没有注册，请点这里添加</a> <span id="dusername" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">管理员类别 <span class="f_red">*</span></td>
<td><input type="radio" name="level" value="2" checked="checked" id="level_2"/><label for="level_2"> 普通管理员</label> <span class="f_gray">拥有系统分配的权限</span>
<br/>
<input type="radio" name="level" value="1" id="level_1"/><label for="level_1"> 超级管理员</label> <span class="f_gray">拥有除创始人特权外的所有权限</span></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value=" 下一步 " class="btn"></div>
</form>
<script type="text/javascript">
function check() {
	var l;
	var f;
	f = 'username';
	l = $(f).value;
	if(l == '') {
		Dmsg('请填写会员名', f);
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(0);</script>
</body>
</html>