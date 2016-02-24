<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="tt">申请受理</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">申请人</td>
<td><a href="?moduleid=2&action=show&username=<?php echo $username;?>" title="查看详细"><strong><?php echo $username;?></strong></a> <a href="<?php echo $MODULE[3]['linkurl'];?>redirect.php?username=<?php echo $username;?>" target="_blank">[<?php echo $company;?>]</a></td>
</tr>
<tr>
<td class="tl">申请时间</td>
<td><?php echo $adddate;?></td>
</tr>
<tr>
<td class="tl">申请内容</td>
<td><?php echo $content;?></td>
</tr>
<?php if($status == 2) { ?>
<form method="post" action="?" id="dform">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<tr>
<td class="tl">受理状态</td>
<td>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<input type="hidden" name="vip[username]" value="<?php echo $username;?>"/>
<input type="radio" name="vip[status]" value="3" onclick="if(this.checked) $('pass').style.display='';"/> 通过
<input type="radio" name="vip[status]" value="2" checked/> 待审
<input type="radio" name="vip[status]" value="1" onclick="if(this.checked) $('pass').style.display='none';"/> 拒绝
</td>
</tr>
<tbody id="pass" style="display:none;">
<tr>
<td class="tl">会员组 <span class="f_red">*</span></td>
<td>
<?php foreach($GROUP as $g) {
	if($g['vip'] > 0) echo '<input type="radio" name="vip[groupid]" value="'.$g['groupid'].'"'.($g['groupid'] == 7 ? 'checked' : '').'/> '.$g['groupname'].'&nbsp;';
}
?>
</td>
</tr>
<tr>
<td class="tl">服务有效期 <span class="f_red">*</span></td>
<td><?php echo dcalendar('vip[fromtime]', $fromtime);?> 至 <?php echo dcalendar('vip[totime]', $totime);?></td>
</tr><tr>
<td class="tl">企业资料是否通过认证 <span class="f_red">*</span></td>
<td>
<input type="radio" name="vip[validated]" value="1"/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="vip[validated]" value="0" checked/> 否
</td>
</tr>
<tr>
<td class="tl">认证名称或机构</td>
<td><input type="text" name="vip[validator]" size="30"/></td>
</tr>
<tr>
<td class="tl">认证日期</td>
<td><?php echo dcalendar('vip[validtime]', $fromtime);?></td>
</tr>
</tbody>
<tr>
<td class="tl">受理备注</td>
<td><textarea name="vip[note]" rows="4" cols="60"><?php echo $note;?></textarea></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value=" 重 置 " class="btn"/></div>
</form>
<?php } else { ?>
<tr>
<td class="tl">受理状态</td>
<td><?php echo $status == 1 ? '已拒绝' : '已通过';?></td>
</tr>
<tr>
<td class="tl">受理人</td>
<td><?php echo $editor;?></td>
</tr>
<tr>
<td class="tl">受理时间</td>
<td><?php echo $editdate;?></td>
</tr>
<tr>
<td class="tl">备注</td>
<td><?php echo $note;?></td>
</tr>
</table>
<?php } ?>
<script type="text/javascript">Menuon(3);</script>
</body>
</html>