<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="add"/>
<div class="tt">管理员管理</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th>用户名</th>
<th>姓名</th>
<th>管理级别</th>
<th>上次登录</th>
<th>登录IP</th>
<th>登录次数</th>
<th>管理</th>
<th width="40">撤销</th>
</tr>
<?php foreach($members as $k=>$v) {?>
<tr <?php if($v['level'] == 1) { ?>class="on"<?php } else { ?>onmouseover="this.className='on';" onmouseout="this.className='';"<?php }  ?> align="center">
<td><?php echo $v['username'];?></td>
<td title="改变此管理员管理级别"><?php echo $v['truename'];?></td>
<td><a href="javascript:Dconfirm('确定要改变此管理员管理级别吗？', '?file=<?php echo $file;?>&action=move&username=<?php echo $v['username'];?>');"><?php echo $v['adminname'];?></a></td>
<td><?php echo $v['logintime'];?></td>
<td><?php echo $v['loginip'];?></td>
<td><?php echo $v['logintimes'];?></td>
<td><a href="?file=<?php echo $file;?>&action=right&userid=<?php echo $v['userid'];?>">分配权限 / 管理面板</a>
</td>
<td><a href="?file=<?php echo $file;?>&action=delete&username=<?php echo $v['username'];?>" onclick="return _delete();"><img src="<?php echo IMG_PATH;?>delete.png" width="16" height="16" title="撤销" alt=""/></a></td>
</tr>
<?php }?>
</table>
<div class="pages"><?php echo $pages;?></div>
<script type="text/javascript">Menuon(1);</script>
<br/>
</body>
</html>