<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="userid" value="<?php echo $userid;?>"/>
<div class="tt">管理员[<?php echo $username;?>]会员面板管理</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="40">删除</th>
<th>排序</th>
<th>名称</th>
<th>地址</th>
</tr>
<?php foreach($dmenus as $k=>$v) {?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td><input name="right[<?php echo $v['adminid'];?>][delete]" type="checkbox" value="1"/></td>
<td><input name="right[<?php echo $v['adminid'];?>][listorder]" type="text" size="3" value="<?php echo $v['listorder'];?>"/></td>
<td><input name="right[<?php echo $v['adminid'];?>][title]" type="text" size="12" value="<?php echo $v['title'];?>"/> <?php echo dstyle('right['.$v['adminid'].'][style]', $v['style']);?></td>
<td><input name="right[<?php echo $v['adminid'];?>][url]" type="text" size="60" value="<?php echo $v['url'];?>"/></td>
</tr>
<?php }?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td class="f_red">新增</td>
<td><input name="right[0][listorder]" type="text" size="3" value=""/></td>
<td><input name="right[0][title]" type="text" size="12" value=""/> <?php echo dstyle('right[0][style]');?></td>
<td><input name="right[0][url]" type="text" size="60" value=""/>
</td>
</tr>
<tr>
<td> </td>
<td height="30" colspan="4"><input type="submit" name="submit" value=" 更 新 " class="btn"/></td>
</tr>
</table>
</form>

<?php if($user['level'] != 1) { ?>

<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="userid" value="<?php echo $userid;?>"/>
<div class="tt">管理员[<?php echo $username;?>]权限分配</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="40">删除</th>
<th>模块ID</th>
<th>文件(file)</th>
<th>动作(action)</th>
<th>分类ID(catid)</th>
</tr>
<?php foreach($drights as $k=>$v) {?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td><input name="right[<?php echo $v['adminid'];?>][delete]" type="checkbox" value="1"/></td>
<td><input name="right[<?php echo $v['adminid'];?>][moduleid]" type="text" size="3" value="<?php echo $v['moduleid'];?>"/></td>
<td><input name="right[<?php echo $v['adminid'];?>][file]" type="text" size="10" value="<?php echo $v['file'];?>"/></td>
<td><input name="right[<?php echo $v['adminid'];?>][action]" type="text" size="30" value="<?php echo $v['action'];?>"/></td>
<td><input name="right[<?php echo $v['adminid'];?>][catid]" type="text" size="45" value="<?php echo $v['catid'];?>"/>
</td>

</tr>
<?php }?>
<tr align="center">
<td class="f_red">新增</td>
<td id="moduleids">
<select name="right[0][moduleid]" size="2" style="height:200px;width:100px;" onchange="get_file(this.value);">
<option value="0">选择模块[单选]</option>
<?php foreach($MODULE as $k=>$v) { if($k>1) {?>
<option value="<?php echo $k;?>"><?php echo $v['name'];?>[<?php echo $k;?>]</option>
<?php }} ?>
</select>
</td>
<td id="files">
<select name="right[0][file]" size="2" style="height:200px;width:150px;" onchange="get_action(this.value);">
<option value="">选择文件[单选]</option>
</select>
</td>
<td id="actions">
<select name="right[0][action][]" size="2" multiple style="height:200px;width:150px;">
<option>选择动作[按Ctrl键多选]</option>
</select>
</td>
<td id="catids">
<select name="right[0][catid][]" size="2" multiple style="height:200px;width:300px;">
<option>选择分类多选[按Ctrl键多选]</option>
</select>
</td>
</td>
</tr>
<tr>
<td> </td>
<td height="30" colspan="4"><input type="submit" name="submit" value=" 更 新 " class="btn"/> 提示：动作和分类可按住Ctrl键多选</td>
</tr>
</table>
</form>
<script type="text/javascript">
var html_file = $('files').innerHTML;
var html_action = $('actions').innerHTML;
var html_catid = $('catids').innerHTML;
function get_file(mid) {
	if(mid) {
		makeRequest('file=<?php echo $file;?>&action=ajax&mid='+mid, '?', '_get_file');
	}
}
function _get_file() {
	if(xmlHttp.readyState==4 && xmlHttp.status==200) {
		if(xmlHttp.responseText) {
			var s = xmlHttp.responseText.split('|');
			$('files').innerHTML = s[0] != 0 ? s[0] : html_file;
			$('catids').innerHTML = s[1] != 0 ? s[1] : html_catid;
		}
	}
}
function get_action(fi, mid) {
	if(mid) {
		makeRequest('file=<?php echo $file;?>&action=ajax&mid='+mid+'&fi='+fi, '?', '_get_action');
	}
}
function _get_action() {    
	if(xmlHttp.readyState==4 && xmlHttp.status==200) {
		$('actions').innerHTML = xmlHttp.responseText != 0 ? xmlHttp.responseText : html_action;
	}
}
</script>
<?php } ?>
<script type="text/javascript">Menuon(2);</script>
<br/>
</body>
</html>