<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form action="?">
<div class="tt">公司搜索</div>
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td>&nbsp;
<?php echo $fields_select;?>&nbsp;
<input type="text" size="50" name="kw" value="<?php echo $kw;?>" title="关键词"/>&nbsp;
<?php echo $status_select;?>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="window.location='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>';"/>
</td>
</tr>
</table>
</form>
<form method="post">
<div class="tt">受理申请</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="25"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>公司名称</th>
<th>会员</th>
<th>申请时间</th>
<th>受理状态</th>
<th>受理人</th>
<th>受理时间</th>
<th>管理</th>
</tr>
<?php foreach($companys as $k=>$v) {?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td><input type="checkbox" name="itemid[]" value="<?php echo $v['itemid'];?>"/></td>
<td align="left"><a href="<?php echo $MODULE[3]['linkurl'];?>redirect.php?username=<?php echo $v['username'];?>" target="_blank"><?php echo $v['company'];?></a></td>
<td><a href="?moduleid=2&action=show&username=<?php echo $v['username'];?>"><?php echo $v['username'];?></a>
</td>
<td><?php echo $v['adddate'];?></td>
<td><?php echo $v['status'];?></td>
<td><?php echo $v['editor'];?></td>
<td><?php echo $v['editdate'];?></td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=show&itemid=<?php echo $v['itemid'];?>"><img src="<?php echo IMG_PATH;?>edit.png" width="16" height="16" title="受理" alt=""/></a>
<a href="?moduleid=2&action=show&username=<?php echo $v['username'];?>"><img src="<?php echo IMG_PATH;?>user.png" width="16" height="16" title="会员[<?php echo $v['username'];?>]详细资料" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value=" 删除申请 " class="btn" onclick="if(confirm('注意:此操作仅删除申请记录，并不撤销<?php echo VIP;?>会员\n\n建议保留此记录，以便查询及防止会员重复申请\n\n确定要删除选中申请吗？')){this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=move'}else{return false;}"/>
</div>
</form>
<div class="pages"><?php echo $pages;?></div>
<br/>
<script type="text/javascript">Menuon(3);</script>
</body>
</html>