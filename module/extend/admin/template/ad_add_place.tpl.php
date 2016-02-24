<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<div class="tt">添加广告位</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">广告位名称 <span class="f_red">*</span></td>
<td><input name="place[name]" id="name" type="text" size="30" /> <?php echo dstyle('place[style]');?> <span id="dname" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">广告位介绍</td>
<td><input name="place[introduce]" type="text" size="60" /></td>
</tr>
<tr>
<td class="tl">广告位类型 <span class="f_red">*</span></td>
<td>
<?php foreach($TYPE as $k=>$v) {
	if($k) echo '<input name="place[typeid]" type="radio" value="'.$k.'" '.($k == 1 ? 'checked' : '').' id="p'.$k.'" onclick="sh('.$k.');"/> <label for="p'.$k.'">'.$v.'&nbsp;</label>';
}
?>
</td>
</tr>
<tr id="wh" style="display:none">
<td class="tl">广告位大小 <span class="f_red">*</span></td>
<td class="f_gray"><input name="place[width]" id="width" type="text" size="5" /> X <input name="place[height]" id="height" type="text" size="5" /> [宽 X 高 px] <span id="dsize" class="f_red"></span>
</td>
</tr>
<tr id="md" style="display:none">
<td class="tl">所属模块 <span class="f_red">*</span></td>
<td><select name="place[moduleid]" id="moduleid">
<option value="0">请选择</option>
<option value="5"><?php echo $MODULE[5]['name'];?></option>
<option value="6"><?php echo $MODULE[6]['name'];?></option>
<option value="7"><?php echo $MODULE[7]['name'];?></option>
<option value="4"><?php echo $MODULE[4]['name'];?></option>
</select> <span id="dmoduleid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">广告位价格</td>
<td><input name="place[price]" type="text" size="5" /> 元/月 <span class="f_gray">[0或不填表示待议]</span></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value=" 重 置 " class="btn"/></div>
</form>
<script type="text/javascript">
function sh(id) {
	if(id == 5) {
		$('md').style.display = '';
		$('wh').style.display = 'none';
	} else if(id == 3 || id == 4 || id == 6) {
		$('md').style.display = 'none';
		$('wh').style.display = '';
	} else {
		$('md').style.display = 'none';
		$('wh').style.display = 'none';
	}
}
function check() {
	var l;
	var f;
	f = 'name';
	l = $(f).value.length;
	if(l < 1) {
		Dmsg('请填写广告位名称', f);
		return false;
	}
	if($('p3').checked || $('p4').checked || $('p6').checked) {
		if($('width').value.length < 2 || $('height').value.length < 2) {
			Dmsg('请填写广告位大小', 'size');
			return false;
		}
	}
	if($('p5').checked) {
		if($('moduleid').value == 0) {
			Dmsg('请选择所属模块', 'moduleid');
			return false;
		}
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(0);</script>
</body>
</html>