<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form action="?">
<div class="tt">广告位搜索</div>
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td>
&nbsp;<?php echo $type_select;?>&nbsp;
<input type="text" size="50" name="kw" value="<?php echo $kw;?>" title="关键词"/>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="window.location='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>';"/>
</td>
</tr>
</table>
</form>
<form method="post">
<div class="tt">管理广告位</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="25"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th width="40">排序</th>
<th>编号</th>
<th>广告类型</th>
<th>广告位名称</th>
<th>规格(px)</th>
<th title="(元/月)">价格</th>
<th>广告数</th>
<th>调用代码</th>
<th>操作</th>
</tr>
<?php foreach($places as $k=>$v) {?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center" name="编辑:<?php echo $v['editor'];?>&#10;更新时间:<?php echo $v['editdate'];?>">
<td><input type="checkbox" name="pids[]" value="<?php echo $v['pid'];?>"/></td>
<td><input type="text" size="2" name="listorder[<?php echo $v['pid'];?>]" value="<?php echo $v['listorder'];?>"/></td>
<td>A<?php echo $v['pid'];?></td>
<td><a href="<?php echo $v['typeurl'];?>" target="_blank"><?php echo $v['typename'];?></td>
<td align="left" title="添加时间:<?php echo $v['adddate'];?>&#10;编辑:<?php echo $v['editor'];?>&#10;上次修改:<?php echo $v['editdate'];?>"><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=list&pid=<?php echo $v['pid'];?>"><?php echo $v['name'];?></td>
<td><?php echo $v['width'];?> x <?php echo $v['height'];?></td>
<td><?php echo $v['price'];?></td>
<td><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=list&pid=<?php echo $v['pid'];?>"><?php echo $v['ads'];?></a></td>
<td><input type="text" size="20" <?php if($v['typeid'] == 5) { ?>disabled value="系统自动调用"<?php } else { ?>value="{load('ad_<?php echo $v['pid'];?>.htm')}" onmouseover="this.select();"/><?php } ?></td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=add&pid=<?php echo $v['pid'];?>"><img src="<?php echo IMG_PATH;?>new.png" width="16" height="16" title="向此广告位添加广告" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=list&pid=<?php echo $v['pid'];?>"><img src="<?php echo IMG_PATH;?>child.png" width="16" height="16" title="此广告位广告列表" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=view&pid=<?php echo $v['pid'];?>" target="_blank"/><img src="<?php echo IMG_PATH;?>view.png" width="16" height="16" title="预览此广告位" alt=""></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit_place&pid=<?php echo $v['pid'];?>"><img src="<?php echo IMG_PATH;?>edit.png" width="16" height="16" title="修改此广告位" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete_place&pid=<?php echo $v['pid'];?>" onclick="return _delete();"><img src="<?php echo IMG_PATH;?>delete.png" width="16" height="16" title="删除此广告位" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value=" 更新排序 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=order_place';"/>&nbsp;
<input type="submit" value=" 删 除 " class="btn" onclick="if(confirm('确定要删除选中广告位吗？\n\n广告位下的所有广告也将被删除\n\n此操作不可撤销\n\n强烈建议不要删除系统自带的广告位')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete_place'}else{return false;}"/>&nbsp;&nbsp;&nbsp;
提示：系统会定期自动更新广告，如果需要立即看到效果，请点更新广告
</div>
</form>
<div class="pages"><?php echo $pages;?></div>
<br/>
<script type="text/javascript">Menuon(1);</script>
</body>
</html>