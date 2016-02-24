<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="dfileid" value="<?php echo $fileid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<div class="tt">风格修改</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td width="80">&nbsp;风格路径</td>
<td><?php echo $skin_path.$fileid;?>.css</td>
</tr>
<tr>
<td>&nbsp;文件名</td>
<td><input type="text" size="20" name="fileid" value="<?php echo $fileid;?>"/>.css 不支持中文</td>
</tr>
<tr>
<td colspan="2">
<textarea name="content" style="width:98%;height:300px;font-family:Fixedsys,verdana;overflow:visible;"><?php echo $content;?></textarea>
</td>
</tr>
<tr>
<td colspan="2"><input type="checkbox" name="backup" value="1"/> 保存时创建一个备份&nbsp;&nbsp;<input type="submit" name="submit" value=" 保 存 " class="btn"/>&nbsp;&nbsp;<input type="button" value=" 返 回 " class="btn" onclick="history.back(-1);"/></td>
</tr>
</table>
</form>
<br/>
<script type="text/javascript">Menuon(1);</script>
</body>
</html>