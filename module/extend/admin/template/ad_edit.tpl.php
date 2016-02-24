<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="aid" value="<?php echo $aid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<input type="hidden" name="pid" value="<?php echo $p['pid'];?>"/>
<input type="hidden" name="ad[pid]" value="<?php echo $p['pid'];?>"/>
<input type="hidden" name="ad[typeid]" value="<?php echo $p['typeid'];?>"/>
<input type="hidden" name="ad[key_moduleid]" value="<?php echo $p['moduleid'];?>"/>
<div class="tt">修改广告</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">广告位</td>
<td class="f_gray">&nbsp;<?php echo $p['name'];?></td>
</tr>
<tr>
<td class="tl">广告名称 <span class="f_red">*</span></td>
<td><input name="ad[title]" id="title" type="text" size="30" value="<?php echo $title;?>"/> <span id="dtitle" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">广告介绍</td>
<td><input name="ad[introduce]" type="text" size="60" value="<?php echo $introduce;?>"/></td>
</tr>
<tr>
<td class="tl">广告类型</td>
<td class="f_gray">&nbsp;<?php echo $TYPE[$p['typeid']];?></td>
</tr>
<tbody id="t1" style="display:none;">
<tr>
<td class="tl">广告代码 <span class="f_red">*</span></td>
<td><textarea name="ad[code]" id="code" style="width:98%;height:50px;overflow:visible;font-family:Fixedsys,verdana;"><?php echo $code;?></textarea><br/><span id="dcode" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">上传文件</td>
<td class="f_gray"><input type="text" size="60" id="upload" onmouseover="this.select();"/>&nbsp;&nbsp;<span onclick="Dfile(<?php echo $moduleid;?>, $('upload').value, 'upload');" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="if($('upload').value) window.open($('upload').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="$('upload').value='';" class="jt">[删除]</span><?php tips('从这里上传文件后，把地址复制到代码里即可使用');?></td>
</tr>
</tbody>
<tbody id="t2" style="display:none;">
<tr>
<td class="tl">链接文字 <span class="f_red">*</span></td>
<td class="f_gray"><input type="text" size="30" name="ad[text_name]" id="text_name" value="<?php echo $text_name;?>"/> [支持HTML语法] <span id="dtext_name" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">链接地址 <span class="f_red">*</span></td>
<td><input type="text" size="30" name="ad[text_url]" id="text_url" value="<?php echo $text_url;?>"/> <span id="dtext_url" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">Title提示</td>
<td><input type="text" size="30" name="ad[text_title]" value="<?php echo $text_title;?>"/></td>
</tr>
</tbody>
<tbody id="t3" style="display:none;">
<tr>
<td class="tl">图片地址 <span class="f_red">*</span></td>
<td class="f_gray"><input type="text" size="60" name="ad[image_src]" id="thumb" value="<?php echo $image_src;?>"/>&nbsp;&nbsp;<span onclick="Dthumb(<?php echo $moduleid;?>,<?php echo $p['width'];?>,<?php echo $p['height'];?>, $('thumb').value);" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="_preview($('thumb').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="$('thumb').value='';" class="jt">[删除]</span> <span id="dthumb" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">链接地址</td>
<td><input type="text" size="30" name="ad[image_url]" value="<?php echo $image_url;?>" id="image_url"/> <span id="dimage_url" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">Alt提示</td>
<td><input type="text" size="30" name="ad[image_alt]" value="<?php echo $image_alt;?>"/></td>
</tr>
</tbody>
<tbody id="t4" style="display:none;">
<tr>
<td class="tl">Flash地址 <span class="f_red">*</span></td>
<td class="f_gray"><input type="text" size="60" name="ad[flash_src]" id="flash" value="<?php echo $flash_src;?>"/>&nbsp;&nbsp;<span onclick="Dfile(<?php echo $moduleid;?>, $('flash').value, 'flash');" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="if($('flash').value) window.open($('flash').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="$('flash').value='';" class="jt">[删除]</span> <span id="dflash" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">链接地址</td>
<td><input type="text" size="30" name="ad[flash_url]" value="<?php echo $flash_url;?>"/></td>
</tr>
</tbody>
<tbody id="t5" style="display:none;">
<tr>
<td class="tl">所属模块</td>
<td class="f_gray">&nbsp;<?php echo $MODULE[$p['moduleid']]['name'];?><?php tips('如果行业与关键字未设置，则参与'.$MODULE[$p['moduleid']]['name'].'首页列表排名');?>
</td>
</tr>
<tr>
<td class="tl">所属行业</td>
<td><?php echo ajax_category_select('ad[key_catid]', '请选择', $key_catid, $p['moduleid']);?><?php tips('如果选择，则参与行业列表排名');?></td>
</tr>
<tr>
<td class="tl">关键字</td>
<td><input type="text" size="30" name="ad[key_word]" value="<?php echo $key_word;?>"/><?php tips('如果填写，则参与搜索结果排名<br/>请勿过长，建议控制10个汉字内');?></td>
</tr>
<tr>
<td class="tl">信息ID <span class="f_red">*</span></td>
<td><input type="text" size="30" name="ad[key_id]" id="key_id" value="<?php echo $key_id;?>"/><?php tips('多个信息ID用空格分开，例如“20 25 55 66 99”');?> <span id="dkey_id" class="f_red"></span></td>
</tr>
</tbody>
<tbody id="t6" style="display:none;">
<tr>
<td colspan="2" style="padding:10px;">
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<th width="60">排序</th>
<th>链接地址</th>
<th>图片地址</th>
<th>上传</th>
</tr>
<?php
$slide = array();
$code = explode("\n", trim($code));
foreach($code as $k=>$c) {
	$c = explode("|", $c);
	$slide[$k]['order'] = $c[0];
	$slide[$k]['url'] = $c[1];
	$slide[$k]['thumb'] = $c[2];
}
$t = $k + 1;
for($k = $t; $k < $t + 2; $k++) {
	$slide[$k]['order'] = $slide[$k]['url'] = $slide[$k]['thumb'] = '';
}
foreach($slide as $k=>$v) {
?>
<tr onmouseover="this.className='on';" onmouseout="this.className='';" align="center">
<td><input name="slide[<?php echo $k;?>][order]" type="text" size="2" value="<?php echo $v['order'];?>"/></td>
<td align="left">&nbsp;&nbsp;<input name="slide[<?php echo $k;?>][url]" type="text" size="20" value="<?php echo $v['url'];?>" style="width:250px;"/></td>
<td align="left">&nbsp;&nbsp;<input name="slide[<?php echo $k;?>][thumb]" type="text" size="20" value="<?php echo $v['thumb'];?>" style="width:250px;" id="thumb_<?php echo $k;?>"/></td>
<td><span onclick="Dthumb(<?php echo $moduleid;?>,<?php echo $p['width'];?>,<?php echo $p['height'];?>, $('thumb_<?php echo $k;?>').value, '', 'thumb_<?php echo $k;?>');" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="_preview($('thumb_<?php echo $k;?>').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="$('thumb_<?php echo $k;?>').value='';" class="jt">[删除]</span></td>
</tr>
<?php } ?>
</table>
&nbsp;<span class="f_gray">备注：最少需要设置两张幻灯图片 （默认Flash播放器仅支持JPG格式）</span>
</td>
</tr>
</tbody>
<tr>
<td class="tl">投放时段 <span class="f_red">*</span></td>
<td><?php echo dcalendar('ad[fromtime]', $fromtime);?> 至 <?php echo dcalendar('ad[totime]', $totime);?> <span id="dtime" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">会员名</td>
<td><input name="ad[username]" type="text" size="10" value="<?php echo $username;?>"/></td>
</tr>
<tr>
<td class="tl">备注</td>
<td><input name="ad[note]" type="text" size="60" value="<?php echo $note;?>"/></td>
</tr>
<tr style="display:<?php if($p['typeid']<2) echo 'none';?>">
<td class="tl">点击统计</td>
<td>
<input type="radio" name="ad[stat]" value="1" <?php if($stat) echo 'checked';?>/> 开启
<input type="radio" name="ad[stat]" value="0" <?php if(!$stat) echo 'checked';?>/> 关闭
</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value=" 重 置 " class="btn"/></div>
</form>
<?php load('clear.js'); ?>
<script type="text/javascript">
$('t'+<?php echo $p['typeid'];?>).style.display='';
function check() {
	var l;
	var f;
	f = 'title';
	l = $(f).value.length;
	if(l < 1) {
		Dmsg('请填写广告名称', f);
		return false;
	}
	if($('adfromtime').value.length != 10 || $('adtotime').value.length != 10) {
		Dmsg('请填写投放时段', 'time');
		return false;
	}
	if($('t1').style.display != 'none') {
		f = 'code';
		l = $(f).value.length;
		if(l < 5) {
			Dmsg('请填写广告代码', f);
			return false;
		}
	} else if($('t2').style.display != 'none') {
		f = 'text_name';
		l = $(f).value.length;
		if(l < 2) {
			Dmsg('请填写链接文字', f);
			return false;
		}
		f = 'text_url';
		l = $(f).value.length;
		if(l < 12) {
			Dmsg('请填写链接地址', f);
			return false;
		}
	} else if($('t3').style.display != 'none') {
		f = 'thumb';
		l = $(f).value.length;
		if(l < 2) {
			Dmsg('请填写图片地址', f);
			return false;
		}
	} else if($('t4').style.display != 'none') {
		f = 'flash';
		l = $(f).value.length;
		if(l < 5) {
			Dmsg('请填写Flash地址', f);
			return false;
		}
	} else if($('t5').style.display != 'none') {
		f = 'key_id';
		l = $(f).value.length;
		if(l < 1) {
			Dmsg('请填写信息ID', f);
			return false;
		}
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(2);</script>
</body>
</html>