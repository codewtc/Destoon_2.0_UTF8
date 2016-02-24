<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="tt">日志搜索</div>
<form action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td>&nbsp;
<?php echo $fields_select;?>&nbsp;
<input type="text" size="30" name="kw" value="<?php echo $kw;?>" title="关键词"/>&nbsp;
<?php echo dcalendar('fromdate', $fromdate);?> 至 <?php echo dcalendar('todate', $todate);?>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="window.location='?file=<?php echo $file;?>';"/>
</td>
</tr>
</table>
</form>
<div class="tt">操作日志</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th>会员名</th>
<th>URL</th>
<th>IP</th>
<th>时间</th>
</tr>
<?php foreach($logs as $k=>$v) {?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td><a href="?file=<?php echo $file;?>&username=<?php echo $v['username'];?>"><?php echo $v['username'];?></a></td>
<td align="left"><?php echo $v['sqstring'];?></td>
<td><a href="?file=<?php echo $file;?>&ip=<?php echo $v['ip'];?>"><?php echo $v['ip'];?></a></td>
<td><?php echo $v['logtime'];?></td>
</tr>
<?php }?>
</table>
<div class="pages"><?php echo $pages;?></div>
<script type="text/javascript">Menuon(0);</script>
</body>
</html>