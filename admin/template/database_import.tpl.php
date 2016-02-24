<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="delete"/>
<div class="tt">DESTOON备份文件</div>
<table cellpadding="2" cellspacing="1" class="tb" title="系统提示信息:列表中，背景颜色相同的文件为同一备份序列，只需恢复卷号为1的备份文件，系统会自动依次恢复本序列备份文件">
<tr>
<th width="50"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>文件</th>
<th width="100">文件大小(M)</th>
<th width="150">备份时间</th>
<th width="50">卷号</th>
<th width="100">操作</th>
</tr>
<?php foreach($dsqls as $k=>$v) {?>
<tr align="center"<?php if($v['class']) echo ' class="on"';?>>
<td><input type="checkbox" name="filenames[]" value="<?php echo $v['filename'];?>"></td>
<td align="left">&nbsp;<a href="<?php DT_PATH;?>file/backup/<?php echo $v['filename'];?>" title="点鼠标右键另存为保存此文件" target="_blank"><?php echo $v['filename'];?></a></td>
<td><?php echo $v['filesize'];?></td>
<td title="修改时间:<?php echo $v['mtime'];?>"><?php echo $v['btime'];?></td>
<td><?php echo $v['number'];?></td>
<td>
<a href="?file=<?php echo $file;?>&action=<?php echo $action;?>&filepre=<?php echo $v['pre'];?>&import=1" onclick="return confirm('确定要导入此系列文件吗？现有数据将被覆盖，此操作将不可恢复');"><img src="<?php echo IMG_PATH;?>import.png" width="16" height="16" title="导入本系列备份文件" alt=""/></a>&nbsp;&nbsp;<a href="?file=<?php echo $file;?>&action=download&filename=<?php echo $v['filename'];?>"><img src="<?php echo IMG_PATH;?>save.png" width="16" height="16" title="下载" alt=""/></a>&nbsp;&nbsp;<a href="?file=<?php echo $file;?>&action=delete&filenames=<?php echo $v['filename'];?>" onclick="return _delete();"><img src="<?php echo IMG_PATH;?>delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
<?php if($sqls) {?>
<div class="tt">其他SQL文件</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="50">-</th>
<th>文件</th>
<th width="100">文件大小(M)</th>
<th width="150">修改时间</th>
<th width="50">卷号</th>
<th width="100">操作</th>
</tr>
<?php foreach($sqls as $k=>$v) {?>
<tr align="center" onmouseover="this.className='on';" onmouseout="this.className='';">
<td><input type="checkbox" name="filenames[]" value="<?php echo $v['filename'];?>"></td>
<td align="left">&nbsp;<a href="<?php DT_PATH;?>file/backup/<?php echo $v['filename'];?>" title="点鼠标右键另存为保存此文件" target="_blank"><?php echo $v['filename'];?></a></td>
<td><?php echo $v['filesize'];?></td>
<td><?php echo $v['mtime'];?></td>
<td> -- </td>
<td><a href="?file=<?php echo $file;?>&action=<?php echo $action;?>&filename=<?php echo $v['filename'];?>&import=1"><img src="<?php echo IMG_PATH;?>import.png" width="16" height="16" title="导入SQL文件" alt=""/></a>&nbsp;&nbsp;<a href="?file=<?php echo $file;?>&action=download&filename=<?php echo $v['filename'];?>"><img src="<?php echo IMG_PATH;?>save.png" width="16" height="16" title="下载" alt=""/></a>&nbsp;&nbsp;<a href="?file=<?php echo $file;?>&action=delete&filenames=<?php echo $v['filename'];?>" onclick="return _delete();"><img src="<?php echo IMG_PATH;?>delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
<?php } ?>
<table cellpadding="2" cellspacing="1" width="100%" bgcolor="#F1F2F3">
<tr>
<td height="30" width="200">&nbsp;&nbsp;
<input type="submit" name="submit" value="删除文件" class="btn" onclick="return confirm('确定要删除所选文件吗？此操作将不可恢复');"/></td>
</form>
<form method="post" action="?" enctype="multipart/form-data">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="upload"/>
<td title="上传成功后文件将自动在文件列表中显示" align="right">
上传SQL文件
<input name="uploadfile" type="file" size="25"/>
<input type="hidden" name="MAX_FILE_SIZE" value="4096000"/>
<input type="submit" name="submit" value=" 上 传 " class="btn"/>&nbsp;
</td>
</form>
</tr>
</table>
<form method="post" action="?" onsubmit="return check();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="runsql"/>
<div class="tt">执行SQL</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td>&nbsp;&nbsp;<textarea name="sql" id="sql" style="width:98%;height:100px;overflow:visible;"></textarea></td>
</tr>
<tr>
<td>
&nbsp;&nbsp;<input type="submit" name="submit" value=" 执 行 " class="btn"/> <span id="dsql" class="f_red"></span></td>
</tr>
</table>
</form>
<script type="text/javascript">
function check() {
	if($('sql').value == '') {
		Dmsg('SQL语句不能为空', 'sql');
		return false;
	}
	return confirm('确定要执行此语句吗？此操作将不可恢复');
}
</script>
<script type="text/javascript">Menuon(1);</script>
</body>
</html>