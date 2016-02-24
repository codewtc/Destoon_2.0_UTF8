<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="tt"><?php if($parentid) echo $CATEGORY[$parentid]['catname'];?>分类管理</div>
<form method="post">
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="25"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>排序</th>
<th>ID</th>
<th>分类名</th>
<th>分类目录</th>
<th>索引</th>
<th>级别</th>
<th>子分类</th>
<th width="120">操作</th>
</tr>
<?php foreach($DTCAT as $k=>$v) {?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td><input type="checkbox" name="catids[]" value="<?php echo $v['catid'];?>"/></td>
<td><input name="category[<?php echo $v['catid'];?>][listorder]" type="text" size="5" value="<?php echo $v['listorder'];?>"/></td>
<td>&nbsp;<a href="<?php echo $MODULE[$mid]['linkurl'].$v['linkurl'];?>" target="_blank"><?php echo $v['catid'];?></a>&nbsp;</td>
<td>
<input name="category[<?php echo $v['catid'];?>][catname]" type="text" value="<?php echo $v['catname'];?>" style="width:120px;color:<?php echo $v['style'];?>"/>
<?php echo dstyle('category['.$v['catid'].'][style]', $v['style']);?>
</td>
<td>
<input name="category[<?php echo $v['catid'];?>][catdir]" type="text" value="<?php echo $v['catdir'];?>" size="15"/>
</td>
<td>
<input name="category[<?php echo $v['catid'];?>][letter]" type="text" value="<?php echo $v['letter'];?>" size="1"/>
</td>
<td>
<input name="category[<?php echo $v['catid'];?>][level]" type="text" value="<?php echo $v['level'];?>" size="1"/>
</td>
<td>&nbsp;<a href="?file=<?php echo $file;?>&mid=<?php echo $mid;?>&parentid=<?php echo $v['catid'];?>"><?php echo $v['childs'];?></a></td>
<td>
<a href="?file=<?php echo $file;?>&mid=<?php echo $mid;?>&parentid=<?php echo $v['catid'];?>"><img src="<?php echo IMG_PATH;?>child.png" width="16" height="16" title="管理子分类，当前有<?php echo $v['childs'];?>个子分类" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=add&mid=<?php echo $mid;?>&parentid=<?php echo $v['catid'];?>"><img src="<?php echo IMG_PATH;?>new.png" width="16" height="16" title="添加子分类" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=edit&mid=<?php echo $mid;?>&catid=<?php echo $v['catid'];?>"><img src="<?php echo IMG_PATH;?>edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?file=<?php echo $file;?>&action=delete&mid=<?php echo $mid;?>&catid=<?php echo $v['catid'];?>&parentid=<?php echo $parentid;?>" onclick="return _delete();"><img src="<?php echo IMG_PATH;?>delete.png" width="16" height="16" title="删除" alt=""/></a></td>
</tr>
<?php }?>
</table>
<div class="btns">
<span class="f_r">
分类总数:<strong class="f_red"><?php echo count($CATEGORY);?></strong>&nbsp;&nbsp;
当前目录:<strong class="f_blue"><?php echo count($DTCAT);?></strong>&nbsp;&nbsp;
</span>
<input type="submit" name="submit" value=" 更新分类 " class="btn" onclick="this.form.action='?mid=<?php echo $mid;?>&file=<?php echo $file;?>&parentid=<?php echo $parentid;?>&action=update'"/>&nbsp;
<input type="submit" value=" 删除选中 " class="btn" onclick="if(confirm('确定要删除选中分类吗？此操作将不可撤销')){this.form.action='?mid=<?php echo $mid;?>&file=<?php echo $file;?>&parentid=<?php echo $parentid;?>&action=delete'}else{return false;}"/>
<?php if($parentid) {?>&nbsp;
<input type="button" value=" 上级分类 " class="btn" onclick="window.location='?file=<?php echo $file;?>&mid=<?php echo $mid;?>&parentid=<?php echo $CATEGORY[$parentid]['parentid'];?>';"/>
<?php }?>
</div>
</form>
<form method="post" action="?">
<div class="tt">快捷操作</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr align="center">
<td>
<div style="float:left;padding:10px;">
<?php echo category_select('cid', '分类结构', $parentid, $mid, 'size="2" style="width:200px;height:130px;" id="cid"');?></div>
<div style="float:left;padding:10px;">
	<table>
	<tr>
	<td><input type="submit" value=" 管理分类 " class="btn" onclick="this.form.action='?mid=<?php echo $mid;?>&file=<?php echo $file;?>&parentid='+$('cid').value;"/></td>
	</tr>
	<tr>
	<td><input type="submit" value=" 添加分类 " class="btn" onclick="this.form.action='?mid=<?php echo $mid;?>&file=<?php echo $file;?>&action=add&parentid='+$('cid').value;"/></td>
	</tr>
	<tr>
	<td><input type="submit" value=" 修改分类 " class="btn" onclick="this.form.action='?mid=<?php echo $mid;?>&file=<?php echo $file;?>&action=edit&catid='+$('cid').value;"/></td>
	</tr>
	<tr>
	<td><input type="submit" value=" 删除分类 " class="btn" onclick="if(confirm('确定要删除选中分类吗？此操作将不可撤销')){this.form.action='?mid=<?php echo $mid;?>&file=<?php echo $file;?>&action=delete&catid='+$('cid').value;}else{return false;}"/></td>
	</tr>
	</table>
</div>
</td>
</tr>
</table>
</div>
</form>
<br/>
<br/>
<br/>
<script type="text/javascript">Menuon(1);</script>
</body>
</html>