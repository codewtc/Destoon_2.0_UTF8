<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="tt">模块管理</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="50">排序</th>
<th width="50">ID</th>
<th>名称</th>
<th width="70">类型</th>
<th width="70">导航</th>
<th width="120">模型</th>
<th width="100">安装日期</th>
<th width="100">管理</th>
<th width="50">状态</th>
</tr>
<?php foreach($modules as $k=>$v) {?>
<tr align="center" onmouseover="this.className='on';" onmouseout="this.className='';">
<td><?php echo $v['listorder'];?></td>
<td><?php echo $v['moduleid'];?></td>
<td><a href="<?php echo $v['linkurl'];?>" target="_blank"><?php echo set_style($v['name'], $v['style']);?></a></td>
<td><?php echo $v['islink'] ? '<span class="f_red">外链</span>' : '内置';?></td>
<td><?php echo $v['ismenu'] ? '是' : '<span class="f_red">否</span>';?></td>
<td title="<?php echo $v['module'];?>"><?php echo $v['modulename'];?></td>
<td><?php echo $v['installdate'];?></td>
<td><a href="?file=<?php echo $file;?>&action=edit&modid=<?php echo $v['moduleid'];?>"><img src="<?php echo IMG_PATH;?>edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;&nbsp;<a href="?file=<?php echo $file;?>&action=delete&modid=<?php echo $v['moduleid'];?>" onclick="return _delete();"><img src="<?php echo IMG_PATH;?>delete.png" width="16" height="16" title="删除" alt=""/></a>&nbsp;&nbsp;<a href="?file=<?php echo $file;?>&action=remkdir&modid=<?php echo $v['moduleid'];?>"><img src="<?php echo IMG_PATH;?>remkdir.png" width="16" height="16" title="重建目录" alt=""/></a>&nbsp;&nbsp;<a href="?file=setting&moduleid=<?php echo $v['moduleid'];?>"><img src="<?php echo IMG_PATH;?>set.png" width="16" height="16" title="设置" alt=""/></a></td>
<td>
<?php if($v['disabled']) {?>
<a href="?file=<?php echo $file;?>&action=disable&value=0&modid=<?php echo $v['moduleid'];?>"><img src="<?php echo IMG_PATH;?>stop.png" width="16" height="16" title="已禁用,点击启用" alt=""/></a>
<?php } else {?>
<a href="javascript:Dconfirm('确定要禁用[<?php echo $v['name'];?>]模块吗?', '?file=<?php echo $file;?>&action=disable&value=1&modid=<?php echo $v['moduleid'];?>');"><img src="<?php echo IMG_PATH;?>start.png" width="16" height="16" title="正常运行,点击禁用" alt=""/></a>
<?php } ?>
</td>
</tr>
<?php }?>
</table>
<div class="tt">系统可用模型</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th>模型</th>
<th width="70">可复制</th>
<th width="70">可卸载</th>
<th width="120">作者</th>
<th width="260">官方网站</th>
</tr>
<?php foreach($sysmodules as $k=>$v) {?>
<tr align="center">
<td align="left" title="位于./module/<?php echo $v['module'];?>/">&nbsp;<img src="<?php echo IMG_PATH;?>folder.gif" align="absmiddle"/> <?php echo $v['name'];?> (<?php echo $v['module'];?>)</td>
<td><?php echo $v['copy'] ? '<span class="f_red">是</span>' : '否'; ?></td>
<td><?php echo $v['uninstall'] ? '<span class="f_red">是</span>' : '否'; ?></td>
<td><?php echo $v['author'];?></td>
<td><a href="<?php echo 'http://'.$v['homepage'];?>" target="_blank"><?php echo $v['homepage'];?></a></td>
</tr>
<?php
}
?>
</table>
<script type="text/javascript">Menuon(1);</script>
</body>
</html>