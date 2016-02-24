<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="tt">标签创建向导</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">所属模块</td>
<td><input type="text" name="setting[moduleid]" size="20" id="moduleid"/>
<select onchange="cat(this.value);">
<option value="">请选择</option>
<option value="$moduleid">变量</option>
<?php foreach($MODULE as $k=>$v) {
	if($k > 4) echo '<option value="'.$k.'">'.$v['name'].'</option>';
}
?>
</select>
<?php tips('模块的选择决定了标签里的链接的路径');?>
</td>
<td width="100">$moduleid</td>
</tr>
<tr>
<td class="tl">数据表 <span class="f_red">*</span></td>
<td><input type="text" name="setting[table]" size="20" id="table"/>
<span id="stable"><select onchange="$('table').value=this.value">
<option value="">选择表</option>
<?php echo $table_select;?>
</select></span>
<a href="javascript:$('stable').innerHTML=$('alltable').value;void(0);" class="t">[显示所有]</a>
<?php tips('数据表是调用数据的来源<br>系统允许调用同数据库其他表的数据');?>
<textarea style="display:none;" id="alltable">
<select onchange="$('table').value=this.value">
<option value="">选择表</option>
<?php echo $all_select;?>
</select>
</textarea>
</td>
<td>$table</td>
</tr>
<tr>
<td class="tl">调用条件</td>
<td><input type="text" name="setting[condition]" size="50" value="1" id="condition"/>
<select onchange="$('condition').value=this.value">
<option value="1">常用调用条件</option>
<option value="status=3">已发布的信息</option>
<option value="thumb!=''">有图的信息</option>
<option value="vip>0"><?php echo VIP;?>信息</option>
</select>
<?php tips('1表示不限条件<br>此项需要对MySQL语法有一定了解');?>
</td>
<td>$condition</td>
</tr>
<tr>
<td class="tl">调用数量 <span class="f_red">*</span></td>
<td><input type="text" name="setting[pagesize]" size="10" value="10" id="pagesize"/></td>
<td>$pagesize</td>
</tr>
<tr>
<td class="tl">排序方式</td>
<td><input type="text" name="setting[order]" size="30" id="order"/>
<select onchange="$('order').value=this.value">
<option value="">常用排序方式</option>
<option value="itemid desc">按信息ID排序</option>
<option value="edittime desc">按修改时间排序</option>
<option value="addtime desc">按添加时间排序</option>
<option value="vip desc">按VIP排序</option>
<option value="hits desc">按浏览次数排序</option>
<option value="rand() desc">按随机排序</option>
</select>
<?php tips('标签模板位与模板目录的tag目录里');?>
</td>
<td>$order</td>
</tr>
<tbody id="adv" style="display:none;">
<tr>
<td class="tl">所属分类</td>
<td><input type="text" name="setting[catid]" size="30" id="catid"/>
<span id="scatid"><select onchange="$('catid').value=this.value">
<option value="">不限分类</option>
<option value="$catid">变量</option>
</select></span>
<input type="checkbox" id="child"/> 包含子分类
</td>
<td>$catid</td>
</tr>
<tr>
<td class="tl">显示分页</td>
<td>
<input type="radio" name="setting[showpage]" value="1" id="showpage" /> 是
<input type="radio" name="setting[showpage]" value="0" id="hidepage" checked/> 否
</td>
<td>$showpage</td>
</tr>
<tr>
<td class="tl">缓存时间</td>
<td><input type="text" name="setting[expires]" size="10" id="expires"/>
<select onchange="$('expires').value=this.value">
<option value="">默认缓存</option>
<option value="-1">不缓存</option>
<option value="-2">SQL缓存</option>
<option value="">自定义时间(秒)</option>
</select>
</td>
<td>$expires</td>
</tr>
</tbody>
<tr>
<td class="tl">标签模板 <span class="f_red">*</span></td>
<td>
<?php echo tpl_select('', 'tag', 'setting[template]', '请选择', 'list', 'id="template"');?>&nbsp;
<a href="javascript:if($('template').value != 0){ window.open('?file=template&action=edit&dir=tag&fileid='+$('template').value);}else{alert('请选择模板');$('template').focus();}void(0);" class="t">[修改]</a>
<a href="?file=template&action=add&dir=tag" class="t">[新建]</a>
</td>
<td>$template</td>
</tr>
<tr>
<td class="tl">

</td>
<td>
<input type="button" value=" 下一步 " class="btn" onclick="mk_tag();"/>
<input type="button" value=" 高级选项 " class="btn" onclick="show_adv();"/>
</td>
<td> </td>
</table>
<form method="post" action="?" target="destoon_tag" onsubmit="return check();">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="preview"/>
<input type="hidden" id="tag_expires" name="tag_expires"/>
<div class="tt">标签代码</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">自定义CSS</td>
<td><textarea name="tag_css" id="tag_css"  style="width:98%;height:40px;font-family:Fixedsys,verdana;overflow:visible;color:green;"></textarea> 
</td>
</tr>
<tr>
<td class="tl">HTML开始标签</td>
<td><input type="text" name="tag_html_s" id="tag_html_s" size="30" value="" style="font-family:Fixedsys,verdana;"/></td>
</tr>
<tr>
<td class="tl">标签代码 <span class="f_red">*</span></td>
<td><textarea name="tag_code" id="tag_code"  style="width:98%;height:40px;font-family:Fixedsys,verdana;overflow:visible;color:blue;"></textarea> 
</td>
</tr>
<tr>
<td class="tl">JS调用</td>
<td><textarea name="tag_js" id="tag_js"  style="width:98%;height:40px;font-family:Fixedsys,verdana;overflow:visible;color:#800000;"></textarea> 
</td>
</tr>
<tr>
<td class="tl">HTML结束标签</td>
<td><input type="text" name="tag_html_e" id="tag_html_e" size="10" value="" style="font-family:Fixedsys,verdana;"/></td>
</tr>
<tr>
<td class="tl"></td>
<td>
<input type="submit" name="submit" value=" 预览标签 " class="btn"/>
<input type="button" value=" 复制标签 " class="btn" onclick="copy_tag();"/>
<input type="button" value=" 清空标签 " class="btn" onclick="$('tag_code').value='';"/>
<input type="button" value=" 清空CSS" class="btn" onclick="$('tag_css').value='';"/>
<input type="button" value=" 清空HTML " class="btn" onclick="$('tag_html_s').value='';$('tag_html_e').value='';"/>
</td>
</tr>
</table>
</form>
<div class="tt">调用手册</div>
<table cellpadding="2" cellspacing="1" class="tb" style="line-height:200%;">
<tr>
<td class="tl">前言</td>
<td>
标签调用理论上需要网站管理人员有一定的HTML+CSS知识，并对PHP+MySQL有初步的了解。<br/>
<strong>调用过程</strong>实际是按照<span style="color:#006699;">调用条件</span>从<span style="color:#006699;">数据表</span>读取<span style="color:#006699;">调用数量</span>条数据，并依<span style="color:#006699;">排序方式</span>排序，最终通过<span style="color:#006699;">标签模板</span>的布局输出数据。<br/>
本页内容有限，仅为概述，更多方法技巧请关注官方教程及论坛。<a href="http://www.destoon.com" target="_blank">http://www.destoon.com</a><br/>
</td>
</tr>
<tr>
<td class="tl">函数原型</td>
<td>
<strong>tag</strong>($parameter, $expires = 0)<br/>
$parameter 表示传递给tag函数的字符串，系统自动将其转换为多个变量<br/>
例如传递 table=destoon&pagesize=10，系统相当于得到$table = 'destoon'；$pagesize = 10；两个变量<br/>
$expires 表示缓存过期时间<br/>
<span style="color:blue;">>0</span>  缓存$expires秒；<span style="color:blue;">0</span> - 系统默认时间；<span style="color:blue;">-1</span> - 不缓存；<span style="color:blue;">-2</span> - 缓存SQL；一般情况保持默认即可。<br/>
</td>
</tr>
<tr>
<td class="tl">变量</td>
<td>
<strong>$tags</strong><br/>
以数组类型保存标签调用的数据，可通过loop语法遍历显示。<br/>
<strong>$pages</strong><br/>
保存数据分页代码，仅在调用了分页时有效。<br/>
<strong>$path</strong><br/>
模块路径。<br/>
</td>
</tr>
<tr>
<td class="tl">常用字段</td>
<td>
<strong>title</strong> 标题； <strong>linkurl</strong> 链接； <strong>catid</strong> 分类ID； <strong>introduce</strong> 简介； <strong>addtime</strong> 添加时间；
</td>
</tr>
<tr>
<td class="tl">常用函数</td>
<td>
<strong>dsubstr</strong>($string, $length, $suffix = '')<br/>
将字符串$string截取为$length长,尾部追加$suffix(例如..)<br/>
<strong>set_style</strong>($string, $style = '', $tag = 'span')<br/>
将字符串$string置于$tagHTML标签中并设置style为$style<br/>
<strong>linkurl</strong>($linkurl, $absurl = 0)<br/>
将相对路径$linkurl修补为绝对路径(防止链接错误)<br/>
<strong>date</strong>($format, $timestamp)<br/>
将时间戳$timestamp转化为$format(例如Y-m-d)格式<br/>
</td>
</tr>
<tr>
<td class="tl">标签模板</td>
<td>
模板保存于./template/<?php echo $CFG['template'];?>/tag/目录；<br/>
建议不要删除或者修改自带的模板，推荐在自带模板基础上新建模板并应用。<br/>
</td>
</tr>
</table>
</div>
<script type="text/javascript">
function mk_tag() {
	var tag = js = '';
	var adv = $('adv').style.display=='none' ? false : true;
	if($('moduleid').value != '') tag += '&moduleid='+$('moduleid').value;
	if($('table').value == '') {
		alert('请填写数据表');
		$('table').focus();
		return;
	} else {
		tag += '&table='+$('table').value;
	}
	if(adv && $('catid').value != '') tag += '&catid='+$('catid').value;
	if(adv && $('catid').value != '' && $('child').checked) tag += '&child=1';
	if($('condition').value != '' && $('condition').value != '1') tag += '&condition='+$('condition').value;
	if($('pagesize').value == '') {
		alert('请填写调用数量');
		$('pagesize').focus();
		return;
	} else {
		tag += '&pagesize='+$('pagesize').value;
	}
	if(adv && $('showpage').checked) tag += '&showpage=1&page=$page';
	if($('order').value != '') tag += '&order='+$('order').value;
	if($('template').value == 0) {
		alert('请选择标签模板');
		$('template').focus();
		return;
	} else {
		tag += '&template='+$('template').value;
	}
	tag = tag.substr(1);
	var rp = false;
	for(var i=0;i<tag.length;i++) {
		if(tag.charAt(i) == '$') {
			js += '{$'
			rp = true;
		} else if(rp && tag.charAt(i) == '&') {
			js += '}&';
			rp = false;
		} else {
			js += tag.charAt(i);
		}
	}
	js = '<script type="text/javascript" src="<?php echo DT_PATH;?>js.php?'+js;
	tag = '<!--{tag("'+tag+'"';
	if(adv && $('expires').value != '') {
		tag += ', '+$('expires').value;
		js += '&tag_expires='+$('expires').value;
	}
	js = js+'"><\/script>';
	tag = tag+')}-->';
	$('tag_code').value = tag;
	$('tag_js').value = js;
}
function copy_tag() {
	if(!$('tag_code').value) return;
	$('tag_code').select();
	if(isIE) {
		clipboardData.setData('text', $('tag_code').value);
	} else {
		prompt('Press Ctrl+C Copy to Clipboard', $('tag_code').value);
	}
}
function check() {
	if($('adv').style.display!='none' && $('expires').value != '') $('tag_expires').value = $('expires').value
	if($('tag_code').value == '') {
		if(confirm('标签代码尚未生成，现在生成吗？')) mk_tag();
		return false;
	}
}
var catid_o = $('scatid').innerHTML;
var catid_n = ''
function cat(m) {
	$('moduleid').value = m;
	if($('adv').style.display!='none') $('catid').value = '';
	if(!m) return false;
	makeRequest('file=<?php echo $file;?>&action=cat&mid='+m, '?', 'dcat')
}
function dcat() {    
	if(xmlHttp.readyState==4 && xmlHttp.status==200) {
		if(xmlHttp.responseText) {
			catid_n = xmlHttp.responseText;
			if($('adv').style.display!='none') $('scatid').innerHTML = catid_n;
		}
	}
}
function show_adv() {
	if($('adv').style.display=='none') {
		$('adv').style.display='';
		$('scatid').innerHTML = catid_n ? catid_n : catid_o;
	} else {
		$('scatid').innerHTML = catid_o;
		$('catid').value = $('expires').value = '';
		$('showpage').checked = $('child').checked = false;
		$('hidepage').checked = true;
		$('adv').style.display='none';
	}
}
</script>
<script type="text/javascript">Menuon(1);</script>
</body>
</html>