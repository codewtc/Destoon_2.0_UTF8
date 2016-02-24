<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<script type="text/javascript">
var _del = 0;
</script>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="item" value="<?php echo $item;?>"/>
<div class="tt">关联链接</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="40">删除</th>
<th>排序</th>
<th>关键词</th>
<th>链接</th>
</tr>
<?php foreach($lists as $k=>$v) { ?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td><input name="post[<?php echo $v['itemid'];?>][delete]" type="checkbox" value="1" onclick="if(this.checked){_del++;}else{_del--;}"/></td>
<td><input name="post[<?php echo $v['itemid'];?>][listorder]" type="text" size="5" value="<?php echo $v['listorder'];?>"/></td>
<td><input name="post[<?php echo $v['itemid'];?>][title]" type="text" size="30" value="<?php echo $v['title'];?>"/></td>
<td><input name="post[<?php echo $v['itemid'];?>][url]" type="text" size="50" value="<?php echo $v['url'];?>"/></td>
</tr>
<?php } ?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td class="f_red">新增</td>
<td><input name="post[0][listorder]" type="text" size="5" value=""/></td>
<td><input name="post[0][title]" type="text" size="30" value=""/></td>
<td><input name="post[0][url]" type="text" size="50" value="http://"/></td>
</tr>
<tr>
<td> </td>
<td height="30" colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value=" 更 新 " onclick="if(_del && !confirm('提示:您选择删除'+_del+'个关联链接？确定要删除吗？')) return false;" class="btn"/></td>
</tr>
</table>
</form>
<div class="pages"><?php echo $pages;?></div>
<script type="text/javascript">Menuon(0);</script>
</body>
</html>