<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="backup" value="1"/>
<div class="tt">DESTOON系统表[共<?php echo $dtotalsize;?>M]</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="25"><input type="checkbox" onclick="checkall(this.form);"/></th>
<th>表 名</th>
<th width="100">表注释</th>
<th width="80">记录数</th>
<th width="80">大小(M)</th>
<th width="80">ID自增</th>
<th width="150">最后更新时间</th>
<th width="75">操 作</th>
</tr>
<?php foreach($dtables as $k=>$v) {?>
<tr align="center" onmouseover="this.className='on';" onmouseout="this.className='';">
<td><input type="checkbox" name="tables[]" value="<?php echo $v['name'];?>" checked/></td>
<td align="left">&nbsp;<?php echo $v['name'];?></td>
<td><?php echo $v['note'];?></td>
<td class="px11"><?php echo $v['rows'];?></td>
<td class="px11"><?php echo $v['size'];?></td>
<td class="px11"><?php echo $v['auto'];?></td>
<td class="px11"><?php echo $v['updatetime'];?></td>
<td><a href="?file=<?php echo $file;?>&action=repair&tables=<?php echo $v['name'];?>">修复</a> | <a href="?file=<?php echo $file;?>&action=optimize&tables=<?php echo $v['name'];?>">优化</a></td>
</tr>
<?php }?>
</table>
<?php if($tables) {?>
<div class="tt">其他系统表[共<?php echo $totalsize;?>M]</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="25">-</th>
<th>表 名</th>
<th width="100">表注释</th>
<th width="80">记录数</th>
<th width="80">大小(M)</th>
<th width="80">ID自增</th>
<th width="150">最后更新时间</th>
<th width="75">操 作</th>
</tr>
<?php foreach($tables as $k=>$v) {?>
<tr align="center" onmouseover="this.className='on';" onmouseout="this.className='';">
<td><input type="checkbox" name="tables[]" value="<?php echo $v['name'];?>"/></td>
<td align="left">&nbsp;<?php echo $v['name'];?></td>
<td><?php echo $v['note'];?></td>
<td class="px11"><?php echo $v['rows'];?></td>
<td class="px11"><?php echo $v['size'];?></td>
<td class="px11"><?php echo $v['auto'];?></td>
<td class="px11"><?php echo $v['updatetime'];?></td>
<td><a href="?file=<?php echo $file;?>&action=repair&tables=<?php echo $v['name'];?>">修复</a> | <a href="?file=<?php echo $file;?>&action=optimize&tables=<?php echo $v['name'];?>">优化</a></td>
</tr>
<?php }?>
</table>
<?php } ?>
<div class="tt">备份选中表</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td width="120">&nbsp;&nbsp;&nbsp;分卷文件大小</td>
<td>&nbsp;<input type="text" name="sizelimit" value="2048" size="5"/> K</td>
</tr>
<tr>
<td>&nbsp;&nbsp;&nbsp;建表语句格式</td>
<td>&nbsp;<input type="radio" name="sqlcompat" value="" checked="checked"/> 默认 &nbsp; <input type="radio" name="sqlcompat" value="MYSQL40"/> MySQL 3.23/4.0.x &nbsp; <input type="radio" name="sqlcompat" value="MYSQL41"/> MySQL 4.1.x/5.x &nbsp;</td>
</tr>
<tr>
<td>&nbsp;&nbsp;&nbsp;强制字符集</td>
<td>&nbsp;<input type="radio" name="sqlcharset" value="" checked/> 默认 &nbsp; <input type="radio" name="sqlcharset" value="latin1"/> LATIN1 &nbsp; <input type="radio" name="sqlcharset" value="utf8"/> UTF-8</option></td>
</tr>
</table>
<div class="btns">
<input type="submit" name="submit" value=" 开始备份 " class="btn"/>&nbsp;
<input type="submit" value=" 修复表 " class="btn" onclick="this.form.action='?file=<?php echo $file;?>&action=repair';"/>&nbsp;
<input type="submit" value=" 优化表 " class="btn" onclick="this.form.action='?file=<?php echo $file;?>&action=optimize';"/>
</div>
</form>
<br/>
<script type="text/javascript">Menuon(0);</script>
</body>
</html>