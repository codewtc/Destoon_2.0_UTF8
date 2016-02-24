<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form action="?">
<div class="tt">[<?php echo $p['name'];?>] 广告搜索</div>
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="pid" value="<?php echo $pid;?>"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td>
&nbsp;<?php echo $fields_select;?>&nbsp;
<input type="text" size="50" name="kw" value="<?php echo $kw;?>" title="关键词"/>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="window.location='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&pid=<?php echo $pid;?>';"/>
</td>
</tr>
</table>
</form>
<form method="post">
<div class="tt">[<?php echo $p['name'];?>] 广告列表</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="25"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>ID</th>
<th>广告名称</th>
<th>点击</th>
<th>开始时间<?php tips('如果两个广告时间设置冲突，系统将以开始时间为依据，优先显示开始时间晚的广告');?></th>
<th>结束时间</th>
<th>状态</th>
<th width="80">操作</th>
</tr>
<?php foreach($ads as $k=>$v) {?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td><input type="checkbox" name="aids[]" value="<?php echo $v['aid'];?>"/></td>
<td><?php echo $v['aid'];?></td>
<td align="left" title="编辑:<?php echo $v['editor'];?>&#10;添加时间:<?php echo $v['adddate'];?>&#10;更新时间:<?php echo $v['editdate'];?>">&nbsp;<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=view&aid=<?php echo $v['aid'];?>" target="_blank"/><?php echo $v['title'];?></a></td>
<td><?php echo $v['hits'];?></td>
<td><?php echo $v['fromdate'];?></td>
<td><?php echo $v['todate'];?></td>
<td><?php echo $v['status'];?></td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=view&aid=<?php echo $v['aid'];?>" target="_blank"/><img src="<?php echo IMG_PATH;?>view.png" width="16" height="16" title="预览此广告" alt=""></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&aid=<?php echo $v['aid'];?>&pid=<?php echo $v['pid'];?>"><img src="<?php echo IMG_PATH;?>edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&aid=<?php echo $v['aid'];?>&pid=<?php echo $v['pid'];?>" onclick="return _delete();"><img src="<?php echo IMG_PATH;?>delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value=" 删 除 " class="btn" onclick="if(confirm('确定要删除选中广告吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&pid=<?php echo $pid;?>'}else{return false;}"/>&nbsp;
<input type="button" value=" 添加广告 " class="btn" onclick="window.location='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=add&pid=<?php echo $pid;?>';"/>
</div>
</form>
<div class="pages"><?php echo $pages;?></div>
<br/>
<script type="text/javascript">Menuon(2);</script>
</body>
</html>