<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<script type="text/javascript">
var _del = 0;
</script>
<div class="tt">关键词搜索</div>
<form action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="status" value="<?php echo $status;?>"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td>&nbsp;
<select name="mid">
<option value="0">模块</option>
<?php 
foreach($MODULE as $v) {
	if(($v['moduleid'] > 0 && $v['moduleid'] < 4) || $v['islink']) continue;
	echo '<option value="'.$v['moduleid'].'"'.($mid == $v['moduleid'] ? ' selected' : '').'>'.$v['name'].'</option>';
} 
?>
</select>&nbsp;
<input type="text" size="30" name="kw" value="<?php echo $kw;?>" title="关键词"/>&nbsp;
<?php echo $order_select;?>&nbsp;
<input type="submit" value="搜 索" class="btn"/>&nbsp;
<input type="button" value="重 置" class="btn" onclick="window.location='?file=<?php echo $file;?>';"/>
</td>
</tr>
</table>
</form>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="status" value="<?php echo $status;?>"/>
<div class="tt">关键词管理</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="40">删除</th>
<th>模块</th>
<th>关键词</th>
<th>拼音</th>
<th>信息数量</th>
<th>总搜索量</th>
<th>本月搜索</th>
<th>本周搜索</th>
<th>今日搜索</th>
<th>状态</th>
</tr>
<?php foreach($lists as $k=>$v) { ?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td><input name="post[<?php echo $v['itemid'];?>][delete]" type="checkbox" value="1" onclick="if(this.checked){_del++;}else{_del--;}"/></td>
<td><a href="?file=<?php echo $file;?>&mid=<?php echo $v['moduleid'];?>&status=<?php echo $status;?>"><?php echo $MODULE[$v['moduleid']]['name'];?></a></td>
<td><input name="post[<?php echo $v['itemid'];?>][word]" type="text" size="20" value="<?php echo $v['word'];?>"/></td>
<td><input name="post[<?php echo $v['itemid'];?>][letter]" type="text" size="20" value="<?php echo $v['letter'];?>"/></td>
<td><?php echo $v['items'];?></td>
<td><input name="post[<?php echo $v['itemid'];?>][total_search]" type="text" size="5" value="<?php echo $v['total_search'];?>"/></td>
<td><input name="post[<?php echo $v['itemid'];?>][month_search]" type="text" size="5" value="<?php echo $v['month_search'];?>"/></td>
<td><input name="post[<?php echo $v['itemid'];?>][week_search]" type="text" size="5" value="<?php echo $v['week_search'];?>"/></td>
<td><input name="post[<?php echo $v['itemid'];?>][today_search]" type="text" size="5" value="<?php echo $v['today_search'];?>"/></td>
<td>
<input name="post[<?php echo $v['itemid'];?>][status]" type="radio" value="3"<?php echo $status==3 ? ' checked' : '';?>/> 启用
<input name="post[<?php echo $v['itemid'];?>][status]" type="radio" value="2"<?php echo $status==2 ? ' checked' : '';?>/> 待审
</td>

</tr>
<?php } ?>

<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td class="f_red">新增</td>
<td>
<select name="post[0][moduleid]">
<?php 
foreach($MODULE as $v) {
	if(($v['moduleid'] > 0 && $v['moduleid'] < 4) || $v['islink']) continue;
	echo '<option value="'.$v['moduleid'].'">'.$v['name'].'</option>';
} 
?>
</select>&nbsp;
</td>
<td><input name="post[0][word]" type="text" size="20" value="" onblur="get_letter(this.value);"/></td>
<td><input name="post[0][letter]" id="letter" type="text" size="20" value=""/></td>
<td><input name="post[0][items]" type="text" size="5" value="0"/></td>
<td><input name="post[0][total_search]" type="text" size="5" value="1"/></td>
<td><input name="post[0][month_search]" type="text" size="5" value="1"/></td>
<td><input name="post[0][week_search]" type="text" size="5" value="1"/></td>
<td><input name="post[0][today_search]" type="text" size="5" value="1"/></td>
<td>
<input name="post[0][statush]" type="radio" value="3" checked/> 启用
<input name="post[0][statush]" type="radio" value="2"/> 待审
</td>
</tr>

<tr>
<td> </td>
<td height="30" colspan="9">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value=" 更 新 " onclick="if(_del && !confirm('提示:您选择删除'+_del+'个关键词？确定要删除吗？')) return false;" class="btn"/></td>
</tr>
</table>
</form>
<div class="pages"><?php echo $pages;?></div>
<script type="text/javascript">
function get_letter(word) {
	makeRequest('file=<?php echo $file;?>&action=letter&word='+word, '?', '_get_letter');
}
function _get_letter() {
	if(xmlHttp.readyState==4 && xmlHttp.status==200) {
		if(xmlHttp.responseText) {
			if($('letter').value == '') $('letter').value = xmlHttp.responseText;
		}
	}
}
</script>
<script type="text/javascript">Menuon(<?php echo $status==3 ? '0' : '1';?>);</script>
</body>
</html>