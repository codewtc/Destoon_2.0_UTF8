<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="tt">流水搜索</div>
<form action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td>
&nbsp;
<?php echo $fields_select;?>
&nbsp;
<input type="text" size="10" name="kw" value="<?php echo $kw;?>"/>
&nbsp;
<select name="type">
<option value="0">类型</option>
<option value="1" <?php if($type == 1) echo 'selected';?>>收入</option>
<option value="2" <?php if($type == 2) echo 'selected';?>>支出</option>
</select>
&nbsp;
<select name="bank">
<option value="">支付方式</option>
<?php
foreach($BANKS as $k=>$v) {
	echo '<option value="'.$v.'" '.($bank == $v ? 'selected' : '').'>'.$v.'</option>';
}
?>
</select>
&nbsp;
<?php echo dcalendar('fromtime', $fromtime);?> 至 <?php echo dcalendar('totime', $totime);?>
&nbsp;
<?php echo $order_select;?>
&nbsp;
<input type="submit" value="搜 索" class="btn"/>
<input type="button" value="重 置" class="btn" onclick="window.location='?moduleid=<?php echo $moduleid;?>&file=<?php echo $file;?>&action=<?php echo $action;?>';"/>
</td>
</tr>
</table>
</form>
<div class="tt">流水记录</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th>流水号</th>
<th>收入</th>
<th>支出</th>
<th>会员名称</th>
<th>支付平台</th>
<th width="110">发生时间</th>
<th>操作人</th>
<th width="100">事由</th>
<th width="100">备注</th>
</tr>
<?php foreach($records as $k=>$v) {?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td><?php echo $v['itemid'];?></td>
<td class="f_blue"><?php if($v['amount'] > 0) echo $v['amount'];?></td>
<td class="f_red"><?php if($v['amount'] < 0) echo $v['amount'];?></td>
<td><a href="<?php echo $MODULE[3]['linkurl'];?>redirect.php?username=<?php echo $v['username'];?>" target="_blank"><?php echo $v['username'];?></a></td>
<td><?php echo $v['bank'];?></td>
<td class="px11"><?php echo $v['addtime'];?></td>
<td><?php echo $v['editor'];?></td>
<td title="<?php echo $v['reason'];?>"><input type="text" size="10" value="<?php echo $v['reason'];?>"/></td>
<td title="<?php echo $v['note'];?>"><input type="text" size="10" value="<?php echo $v['note'];?>"/></td>
</tr>
<?php }?>
<tr align="center">
<td><strong>小计</strong></td>
<td class="f_blue"><?php echo $income;?></td>
<td class="f_red"><?php echo $expense;?></td>
<td colspan="7">&nbsp;</td>
</tr>
</table>
<div class="pages"><?php echo $pages;?></div>
<div class="tt">导出记录</div>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="export"/>
<input type="hidden" name="tables[]" value="<?php echo $DT_PRE;?>finance_record"/>
<input type="hidden" name="sqlcompat" value=""/>
<input type="hidden" name="sqlcharset" value=""/>
<input type="hidden" name="submit" value="1"/>
<input type="hidden" name="backup" value="1"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td>
&nbsp;
<?php echo $fields_select;?>
&nbsp;
<input type="text" size="10" name="kw" value="<?php echo $kw;?>"/>
&nbsp;
<select name="type">
<option value="0">类型</option>
<option value="1">收入</option>
<option value="2">支出</option>
</select>
&nbsp;
<select name="bank">
<option value="">支付方式</option>
<?php
foreach($BANKS as $k=>$v) {
	$v = trim($v);
	echo '<option value="'.$v.'" '.($bank == $v ? 'selected' : '').'>'.$v.'</option>';
}
?>
</select>
&nbsp;
<?php echo dcalendar('dfromtime', $fromtime);?> 至 <?php echo dcalendar('dtotime', $totime);?>
&nbsp;
<?php echo $order_select;?>
&nbsp;
<input type="submit" value="导出CSV" class="btn"/>
<input type="submit" value="备份数据" class="btn" onclick="this.form.action='?file=database';"/>
</td>
</tr>
</table>
</form>
<script type="text/javascript">Menuon(1);</script>
<br/>
</body>
</html>