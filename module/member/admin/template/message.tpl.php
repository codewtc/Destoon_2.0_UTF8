<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form action="?">
<div class="tt">会员信件搜索</div>
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td>
&nbsp;<?php echo $fields_select;?>&nbsp;
<input type="text" size="30" name="kw" value="<?php echo $kw;?>" title="关键词"/>&nbsp;
<select name="typeid">
<option value="-1">类型</option>
<?php foreach($NAME as $k=>$v) { ?>
<option value="<?php echo $k;?>"<?php echo $k==$typeid ? ' selected' : '';?>><?php echo $v;?></option>
<?php } ?>
</select>&nbsp;
<select name="read">
<option value="-1">阅读</option>
<option value="1"<?php echo $read==1 ? ' selected' : '';?>>已读</option>
<option value="0"<?php echo $read==0 ? ' selected' : '';?>>未读</option>
</select>&nbsp;
<select name="send">
<option value="-1">转发</option>
<option value="1"<?php echo $send==1 ? ' selected' : '';?>>已发</option>
<option value="0"<?php echo $send==0 ? ' selected' : '';?>>未发</option>
</select>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="window.location='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>';"/>
</td>
</tr>
</table>
</form>
<form method="post">
<div class="tt">会员信件管理</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="25"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>类型</th>
<th>标题</th>
<th>收件人</th>
<th>发件人</th>
<th>发送时间</th>
<th>已读</th>
<th>已发</th>
<th>发送IP</th>
<th width="30">操作</th>
</tr>
<?php foreach($lists as $k=>$v) {?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>&typeid=<?php echo $v['typeid'];?>"><img src="<?php echo SKIN_PATH;?>member/message_<?php echo $v['typeid'];?>.gif" width="16" height="16" title="<?php echo $NAME[$v['typeid']];?>" alt=""/></a></td>
<td align="left"><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=show&itemid=<?php echo $v['itemid'];?>" title="<?php echo $v['title'];?>">&nbsp;<?php echo dsubstr($v['title'], 50, '...');?></a></td>
<td><a href="<?php echo userurl($v['touser']);?>" target="_blank"><?php echo $v['touser'];?></a></td>
<td><a href="<?php echo userurl($v['fromuser']);?>" target="_blank"><?php echo $v['fromuser'];?></a></td>
<td class="px11"><?php echo timetodate($v['addtime'], 6);?></td>
<td><?php echo $v['isread'] ? '是' : '否';?></td>
<td><?php echo $v['issend'] ? '是' : '否';?></td>
<td class="px11"><?php echo $v['ip'];?></td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&itemid=<?php echo $v['itemid'];?>" onclick="return _delete();"><img src="<?php echo IMG_PATH;?>delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value=" 删 除 " class="btn" onclick="if(confirm('确定要删除选中信件吗？此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete'}else{return false;}"/>
</div>
</form>
<div class="pages"><?php echo $pages;?></div>
<script type="text/javascript">Menuon(1);</script>
<br/>
</body>
</html>