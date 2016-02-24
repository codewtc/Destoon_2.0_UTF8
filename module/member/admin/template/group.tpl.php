<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="tt">会员组管理</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="120">ID</th>
<th>会员组</th>
<th width="120">类型</th>
<th width="120"><?php echo VIP;?>指数</th>
<th width="150">操作</th>
</tr>
<?php foreach($groups as $k=>$v) {?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td>&nbsp;<?php echo $v['groupid'];?></td>
<td><?php echo $v['groupname'];?></td>
<td>&nbsp;<?php echo $v['type'];?></td>
<td>&nbsp;<?php echo $v['vip'];?></td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=edit&groupid=<?php echo $v['groupid'];?>"><img src="<?php echo IMG_PATH;?>edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<?php if($v['groupid'] > 7) { ?>
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&groupid=<?php echo $v['groupid'];?>"  onclick="return _delete();"><img src="<?php echo IMG_PATH;?>delete.png" width="16" height="16" title="删除" alt=""/></a>
<?php } else {?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?php } ?>
</td>
</tr>
<?php }?>
</table>
<script type="text/javascript">Menuon(1);</script>
<br/>
</body>
</html>