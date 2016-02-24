<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form action="?">
<div class="tt"><?php echo $MOD['name'];?>搜索</div>
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td>&nbsp;
<?php echo $fields_select;?>&nbsp;
<input type="text" size="40" name="kw" value="<?php echo $kw;?>" title="关键词"/>&nbsp;
<select name="vip">
<option value=""><?php echo VIP;?>级别</option>
<?php 
for($i = 0; $i < 11; $i++) {
	echo '<option value="'.$i.'"'.($i == $vip ? ' selected' : '').'>'.$i.' 级</option>';
}
?>
</select>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="window.location='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>';"/>
</td>
</tr>
</table>
</form>
<form method="post">
<div class="tt"><?php echo $MOD['name'];?>管理</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="25"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>ID</th>
<th><?php echo $MOD['name'];?>名称</th>
<th>主营行业</th>
<th>注册城市</th>
<th>注册年份</th>
<th>注册资本</th>
<th><?php echo VIP;?>指数</th>
<th>会员</th>
</tr>
<?php foreach($companys as $k=>$v) {?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center" title="<?php echo $MOD['name'];?>类型:<?php echo $v['type'];?>&#10;<?php echo $MOD['name'];?>规模:<?php echo $v['size'];?>">
<td><input type="checkbox" name="userid[]" value="<?php echo $v['userid'];?>"/></td>
<td><?php echo $v['userid'];?></td>
<td align="left"><a href="<?php echo $v['linkurl'];?>" target="_blank"><?php echo $v['company'];?></a><?php if($v['vip']) { ?> <img src="<?php echo SKIN_PATH;?>image/vip.gif" align="absmiddle"/><?php } ?></td>
<td><a href="?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&catid=<?php echo $v['catid'];?>"><?php echo $CATEGORY[$v['catid']]['catname'];?></a></td>
<td><?php echo $v['regcity'];?></td>
<td><?php echo $v['regyear'];?></td>
<td><?php echo $v['capital'] ? $v['capital'].'万'.$v['regunit'] : '未填';?></td>
<td><img src="<?php echo SKIN_PATH;?>image/vip_<?php echo $v['vip'];?>.gif"/></td>
<td><a href="?moduleid=2&action=edit&userid=<?php echo $v['userid'];?>"><img src="<?php echo IMG_PATH;?>edit.png" width="16" height="16" title="修改会员[<?php echo $v['username'];?>]资料" alt=""/></a> <a href="?moduleid=2&action=show&userid=<?php echo $v['userid'];?>"><img src="<?php echo IMG_PATH;?>user.png" width="16" height="16" title="会员[<?php echo $v['username'];?>]详细资料" alt=""/></a></td>
</tr>
<?php }?>
</table>
<div class="btns">
<input type="submit" value=" 移动<?php echo $MOD['name'];?> " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=move';"/>&nbsp;
<input type="submit" value=" 更新公司 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=update';"/>&nbsp;
<input type="submit" value=" 生成分类 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=html&action=list';" title="生成该模块所有分类"/>&nbsp
<input type="submit" value=" 更新所有 " class="btn" onclick="this.form.action='?moduleid=<?php echo $moduleid;?>&file=html&action=show&update=1';" title="更新所有<?php echo $MOD['name'];?>"/>
</div>
</form>
<div class="pages"><?php echo $pages;?></div>
<br/>
<script type="text/javascript">Menuon(0);</script>
</body>
</html>