<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="tt">会员资料修改</div>
<form method="post" action="?" onsubmit="return Dcheck();" id="dform">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="userid" value="<?php echo $userid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">会员登录名</td>
<td><strong><?php echo $username;?></strong></td>
</tr>

<tr>
<td class="tl">通行证户名 <span class="f_red">*</span></td>
<td><input type="text" size="30" name="member[passport]" id="passport" value="<?php echo $passport;?>"/>&nbsp;<span id="dpassport" class="f_red"></span></td>
</tr>

<tr>
<td class="tl">会员组 <span class="f_red">*</span></td>
<td>
<?php echo group_select('member[groupid]', '会员组', $groupid, 'id="groupid"');?>&nbsp;<span id="dgroupid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">登录密码</td>
<td><input type="password" size="20" name="member[password]" id="password" onblur="validator('password');"/>&nbsp;<span id="dpassword" class="f_red"></span> <span class="f_gray">如不更改,请留空</span></td>
</tr>
<tr>
<td class="tl">重复输入密码</td>
<td><input type="password" size="20" name="member[cpassword]" id="cpassword"/>&nbsp;<span id="cpassword" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">支付密码</td>
<td><input type="password" size="20" name="member[payword]" id="payword" onblur="validator('payword');"/>&nbsp;<span id="dpayword" class="f_red"></span> <span class="f_gray">如不更改,请留空</span></td>
</tr>
<tr>
<td class="tl">重复支付密码</td>
<td><input type="password" size="20" name="member[cpayword]" id="cpassword"/>&nbsp;<span id="cpayword" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">Email <span class="f_red">*</span></td>
<td><input type="text" size="20" name="member[email]" id="email" value="<?php echo $email;?>" onblur="validator('email');"/>&nbsp;<span id="demail" class="f_red"></span> <span class="f_gray">[不公开]</span></td>
</tr>
<tr>
<td class="tl">真实姓名 <span class="f_red">*</span></td>
<td><input type="text" size="10" name="member[truename]" id="truename" value="<?php echo $truename;?>"/>&nbsp;<span id="dtruename" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">性别 <span class="f_red">*</span></td>
<td>
<input type="radio" name="member[gender]" value="1" <?php if($gender == 1) echo 'checked="checked"';?>/> 先生
<input type="radio" name="member[gender]" value="2" <?php if($gender == 2) echo 'checked="checked"';?>/> 女士
</td>
</tr>
<tr>
<td class="tl">部门</td>
<td><input type="text" size="20" name="member[department]" id="department" value="<?php echo $department;?>"/></td>
</tr>
<tr>
<td class="tl">职位</td>
<td><input type="text" size="20" name="member[career]" id="career" value="<?php echo $career;?>"/></td>
</tr>
<tr>
<td class="tl">手机号码</td>
<td><input type="text" size="20" name="member[mobile]" id="mobile" value="<?php echo $mobile;?>"/></td>
</tr>
<tr>
<td class="tl">MSN</td>
<td><input type="text" size="20" name="member[msn]" id="msn" value="<?php echo $msn;?>"/></td>
</tr>
<tr>
<td class="tl">QQ</td>
<td><input type="text" size="20" name="member[qq]" id="qq" value="<?php echo $qq;?>"/></td>
</tr>
<?php echo $MFD ? fields_html('<td class="tl">', '<td>', $user, $MFD) : '';?>
</table>
<div class="tt">公司资料</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">公司名称 <span class="f_red">*</span></td>
<td><input type="text" size="60" name="member[company]" id="company" value="<?php echo $company;?>" onblur="validator('company');"/>&nbsp;<span id="dcompany" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">公司类型 <span class="f_red">*</span></td>
<td><?php echo dselect($COM_TYPE, 'member[type]', '请选择', $type, 'id="type"', 0);?>&nbsp;<span id="dtype" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">形象图片</td>
<td><input name="member[thumb]" type="text" size="60" id="thumb" value="<?php echo $thumb;?>"/>&nbsp;&nbsp;<span onclick="Dthumb(<?php echo $moduleid;?>,<?php echo $MOD['thumb_width'];?>,<?php echo $MOD['thumb_height'];?>, $('thumb').value);" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="_preview($('thumb').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="$('thumb').value='';" class="jt">[删除]</span><br/>
<span class="f_gray">建议使用总经理照片、办公环境、LOGO等标志性图片，最佳大小为<?php echo $MOD['thumb_width'];?>px*<?php echo $MOD['thumb_height'];?>px，最佳格式为JPG</span></td>
</tr>
<tr>
<td class="tl">所在地区 <span class="f_red">*</span></td>
<td><?php echo ajax_area_select('member[areaid]', '请选择', $areaid);?>&nbsp;<span id="dareaid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">所属行业 <span class="f_red">*</span></td>
<td><?php echo ajax_category_select('member[catid]', '请选择', $catid, 4);?>&nbsp;<span id="dcatid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">主要经营范围 <span class="f_red">*</span></td>
<td><input type="text" size="80" name="member[business]" id="business" value="<?php echo $business;?>"/>&nbsp;<span id="dbusiness" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">经营模式</td>
<td>
<span id="com_mode"><?php echo dcheckbox($COM_MODE, 'member[mode][]', $mode, 'onclick="check_mode(this);"', 0);?></span> <span class="f_gray">(最多可选两种)</span></td>
</tr>
<tr>
<td class="tl">公司规模</td>
<td><?php echo dselect($COM_SIZE, 'member[size]', '请选择规模', $size, '', 0);?></td>
</tr>
<tr>
<td class="tl">注册资本</td>
<td><?php echo dselect($MONEY_UNIT, 'member[regunit]', '', $regunit, '', 0);?> <input type="text" size="6" name="member[capital]" id="capital" value="<?php echo $capital;?>"/> 万</td>
</tr>
<tr>
<td class="tl">公司成立年份 <span class="f_red">*</span></td>
<td><input type="text" size="15" name="member[regyear]" id="regyear" value="<?php echo $regyear;?>"/>&nbsp;<span id="dregyear" class="f_red"></span> <span class="f_gray">(年份，如：2004)</span></td>
</tr>
<tr>
<td class="tl">公司注册地 <span class="f_red">*</span></td>
<td><input type="text" size="20" name="member[regcity]" id="regcity" value="<?php echo $regcity;?>"/> &nbsp;<span id="dregcity" class="f_red"></span> <span class="f_gray">(省份/城市 例如 陕西/西安)</span></td>
</tr>

<tr>
<td class="tl">主要经营地点 <span class="f_red">*</span></td>
<td><input type="text" size="40" name="member[address]" id="address" value="<?php echo $address;?>"/>&nbsp;<span id="daddress" class="f_red"></span> <span class="f_gray">(请填写业务部门工作地点)</span></td>
</tr>

<tr>
<td class="tl">邮政编码</td>
<td><input type="text" size="8" name="member[postcode]" id="postcode" value="<?php echo $postcode;?>"/></td>
</tr>

<tr>
<td class="tl">公司电话 <span class="f_red">*</span></td>
<td><input type="text" size="20" name="member[telephone]" id="telephone" value="<?php echo $telephone;?>"/>&nbsp;<span id="dtelephone" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">公司传真</td>
<td><input type="text" size="20" name="member[fax]" id="fax" value="<?php echo $fax;?>"/></td>
</tr><tr>
<td class="tl">公司Email</td>
<td><input type="text" size="20" name="member[mail]" id="mail" value="<?php echo $mail;?>"/> <span class="f_gray">[公开]</span></td>
</tr>
<tr>
<td class="tl">公司网址</td>
<td><input type="text" size="30" name="member[homepage]" id="homepage" value="<?php echo $homepage;?>"/></td>
</tr>
<tr>
<td class="tl">销售的产品(提供的服务)</td>
<td><input type="text" size="50" name="member[sell]" id="sell" value="<?php echo $sell;?>"/> <span class="f_gray">多个产品或服务请用'|'号隔开</span></td>
</tr>
<tr>
<td class="tl">采购的产品(需要的服务)</td>
<td><input type="text" size="50" name="member[buy]" id="buy" value="<?php echo $buy;?>"/> <span class="f_gray">多个产品或服务请用'|'号隔开</span></td>
</tr>
<tr>
<td class="tl">公司介绍 <span class="f_red">*</span></td>
<td><textarea name="member[introduce]" id="introduce" class="dsn"><?php echo $introduce;?></textarea>
<?php echo deditor($moduleid, 'introduce', 'Default', '92%', 300);?></td>
</tr>
<?php echo $CFD ? fields_html('<td class="tl">', '<td>', $user, $CFD) : '';?>
<tr>
<td class="tl">绑定域名 </td>
<td><input type="text" size="30" name="member[domain]" id="domain" value="<?php echo $domain;?>"/><?php tips('例如 www.destoon.com 不带http<br/>同时需要会员将此域名IP指向本站服务器');?></td>
</tr>
<tr>
<td class="tl">域名ICP备案号 </td>
<td><input type="text" size="30" name="member[icp]" id="icp" value="<?php echo $icp;?>"/></td>
</tr>
<tr>
<td class="tl">Flash横幅 </td>
<td class="f_gray"><input type="text" size="60" name="member[banner]" id="flash" value="<?php echo $banner;?>"/>&nbsp;&nbsp;<span onclick="Dfile(<?php echo $moduleid;?>, $('flash').value, 'flash');" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="if($('flash').value) window.open($('flash').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="$('flash').value='';" class="jt">[删除]</span> <span id="dflash" class="f_red"></span><?php tips('出于安全原因，系统禁止会员直接上传Flash横幅<br/>管理员可以从这里替会员上传<br/>上传后将显示在会员主页横幅图片位置');?></td>
</tr>
</table>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">会员资料是否完整</td>
<td>
<input type="radio" name="member[edittime]" value="1"<?php echo $edittime ? ' checked' : '';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="member[edittime]" value="0"<?php echo $edittime ? '' : ' checked';?>/> 否&nbsp;&nbsp;
<span class="f_gray">如果选择是，系统将不再提示会员完善资料</span>
</td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn">&nbsp;&nbsp;<input type="button" value=" 返 回 " class="btn" onclick="history.back(-1);"/></div>
</form>
<?php load('clear.js'); ?>
<script type="text/javascript">
var vid = '';
function validator(id) {
	if(!$(id).value) return false;
	vid = id;
	makeRequest('moduleid=<?php echo $moduleid;?>&action=member&job='+id+'&value='+$(id).value+'&userid=<?php echo $userid;?>', AJPath, 'dvalidator')
}
function dvalidator() {    
	if(xmlHttp.readyState==4 && xmlHttp.status==200) {
		$('d'+vid).innerHTML = xmlHttp.responseText ? xmlHttp.responseText : '';
	}
}
function check_mode(c) {
	var mode_num = 0;
	var e = $('com_mode').getElementsByTagName('input');	
	for(var i=0; i<e.length; i++) {
		if(e[i].checked) mode_num++;
	}
	if(mode_num > 2) {
		confirm('最多可选两种经营模式');
		c.checked = false;
	}
}
function Dcheck() {
	if($('groupid').value == 0) {
		Dmsg('请选择会员组', 'groupid');
		return false;
	}
	if($('password').value != '') {
		if($('cpassword').value == '') {
			Dmsg('请重复输入密码', 'cpassword');
			return false;
		}
		if($('password').value != $('cpassword').value) {
			Dmsg('两次输入的密码不一致', 'password');
			return false;
		}
	}
	if($('passport').value == '') {
		Dmsg('请填写通行证', 'passport');
		return false;
	}
	if($('email').value == '') {
		Dmsg('请填写电子邮箱', 'email');
		return false;
	}
	if($('truename').value == '') {
		Dmsg('请填写真实姓名', 'truename');
		return false;
	}
	<?php echo $MFD ? fields_js($MFD) : '';?>
	<?php if($groupid > 5) { ?>
	<?php echo $CFD ? fields_js($CFD) : '';?>
	if($('company').value == '') {
		Dmsg('请填写公司名称', 'company');
		return false;
	}
	if($('type').value == '') {
		Dmsg('请选择公司类型', 'type');
		return false;
	}
	if($('areaid_1').value == 0) {
		Dmsg('请选择公司所在地', 'areaid');
		return false;
	}
	if($('catid_1').value == 0) {
		Dmsg('请选择公司所属行业', 'catid');
		return false;
	}	
	if($('business').value.length < 10) {
		Tab(2);
		Dmsg('请填写主要经营范围', 'business');
		return false;
	}
	if($('regyear').value == '') {
		Dmsg('请填写公司成立年份', 'regyear');
		return false;
	}
	if($('regcity').value == '') {
		Dmsg('请填写公司注册地', 'regcity');
		return false;
	}
	if($('address').value == '') {
		Dmsg('请填写业务部门工作地点', 'address');
		return false;
	}
	if($('telephone').value == '') {
		Dmsg('请填写公司电话', 'telephone');
		return false;
	}
	<?php } ?>
	return true;
}
</script>
<script type="text/javascript">Menuon(1);</script>
</body>
</html>