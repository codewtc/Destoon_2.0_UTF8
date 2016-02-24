<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form action="?">
<div class="tt">会员搜索</div>
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td>&nbsp;
<?php echo $fields_select;?>&nbsp;
<input type="text" size="30" name="kw" value="<?php echo $kw;?>" title="关键词"/>&nbsp;
<?php echo $group_select;?>&nbsp;
<?php echo $gender_select;?>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="window.location='?moduleid=<?php echo $moduleid;?>&action=<?php echo $action;?>';"/>
</td>
</tr>
</table>
</form>
<form method="post">
<div class="tt">会员管理</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="25"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>会员ID</th>
<th>会员名称</th>
<th>公司</th>
<th>性别</th>
<th>会员组</th>
<th>注册时间</th>
<th>最后登录</th>
<th>登录次数</th>
<th width="50">操作</th>
</tr>
<?php foreach($members as $k=>$v) {?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td><input type="checkbox" name="userid[]" value="<?php echo $v['userid'];?>"/></td>
<td class="px11"><?php echo $v['userid'];?></td>
<td align="left"><a href="?moduleid=<?php echo $moduleid;?>&action=show&userid=<?php echo $v['userid'];?>" title="<?php echo $v['truename'];?>"><?php echo $v['username'];?></a></td>
<td align="left"><a href="<?php echo $MODULE[3]['linkurl'];?>redirect.php?username=<?php echo $v['username'];?>" target="_blank"><?php echo $v['company'];?></a></td>
<td><?php echo $v['gender'] == 1 ? '先生' : '女士';?></td>
<td><a href="?moduleid=<?php echo $moduleid;?>&groupid=<?php echo $v['groupid'];?>"><?php echo $GROUP[$v['groupid']]['groupname'];?></a></td>
<td class="px11"><?php echo $v['regdate'];?></td>
<td class="px11"><?php echo $v['logindate'];?></td>
<td class="px11"><?php echo $v['logintimes'];?></td>
<td>
<a href="?moduleid=<?php echo $moduleid;?>&action=edit&userid=<?php echo $v['userid'];?>"><img src="<?php echo IMG_PATH;?>edit.png" width="16" height="16" title="修改" alt=""/></a>&nbsp;
<a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=delete&userid=<?php echo $v['userid'];?>" onclick="return _delete();"><img src="<?php echo IMG_PATH;?>delete.png" width="16" height="16" title="删除" alt=""/></a>
</td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value=" 删除会员 " class="btn" onclick="if(confirm('确定要删除选中会员吗？系统将删除选中用户所有信息，此操作将不可撤销')){this.form.action='?moduleid=<?php echo $moduleid;?>&action=delete'}else{return false;}"/>&nbsp;
<input type="submit" value=" 禁止访问 " class="btn" onclick="if(confirm('确定要禁止选中会员访问吗？')){this.form.action='?moduleid=<?php echo $moduleid;?>&action=move&groupids=2'}else{return false;}"/>&nbsp;
<input type="submit" value=" 设置<?php echo VIP;?> " class="btn" onclick="this.form.action='?moduleid=4&file=vip&action=add';"/>&nbsp;
<input type="submit" value=" 移动至 " class="btn" onclick="if($('mgroupid').value==0){alert('请选择会员组');$('mgroupid').focus();return false;}if(confirm('确定要改变所选会员的会员组吗？\n\n请注意：\n\n1、移动会员至管理员组并不等于添加管理员，添加管理员请进入会员管理->管理员管理\n\n2、移动会员至<?php echo VIP;?>会员组并不等于添加<?php echo VIP;?>会员，添加<?php echo VIP;?>会员请进入公司管理-><?php echo VIP;?>管理')){this.form.action='?moduleid=<?php echo $moduleid;?>&action=move'}else{return false;}"/> <?php echo group_select('groupid', '会员组', 0, 'id="mgroupid"');?> 
</div>
</form>
<div class="pages"><?php echo $pages;?></div>
<div class="tt">会员登录</div>
<form method="post" action="?"  target="_top">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="login"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td>&nbsp;会员名： <input type="text" name="username" size="30"/> &nbsp; <input type="submit" name="submit" value=" 登 录 " class="btn"/>&nbsp;&nbsp;<span class="f_gray">可直接登录会员商务中心帮助会员管理，但同时后台登录将被注销</span>
</td>
</tr>
</table>
</form>
<div class="tt">IP解锁</div>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="unlock"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td>&nbsp;IP地址： <input type="text" name="ip" size="30"/> &nbsp; <input type="submit" name="submit" value=" 解 锁 " class="btn"/>&nbsp;&nbsp;<span class="f_gray">可解除因登录失败次数过多而被锁定登录的IP</span>
</td>
</tr>
</table>
</form>
<br/><br/><br/>
<script type="text/javascript">Menuon(1);</script>
</body>
</html>