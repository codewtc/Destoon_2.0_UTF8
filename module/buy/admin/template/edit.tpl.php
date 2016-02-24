<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<form method="post" action="?" id="dform" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="itemid" value="<?php echo $itemid;?>"/>
<input type="hidden" name="forward" value="<?php echo $forward;?>"/>
<div class="tt"><?php echo $tname;?></div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">产品图片</td>
<td>
	<input type="hidden" name="post[thumb]" id="thumb" value="<?php echo $thumb;?>"/>
	<input type="hidden" name="post[thumb1]" id="thumb1" value="<?php echo $thumb1;?>"/>
	<input type="hidden" name="post[thumb2]" id="thumb2" value="<?php echo $thumb2;?>"/>
	<table width="360">
	<tr align="center" height="120" class="c_p">
	<td width="120"><img src="<?php echo $thumb ? $thumb : SKIN_PATH.'image/waitpic.gif';?>" id="showthumb" title="预览图片" alt="" onclick="if(this.src.indexOf('waitpic.gif') == -1){_preview($('showthumb').src, 1);}else{Dalbum('',<?php echo $moduleid;?>,<?php echo $MOD['thumb_width'];?>,<?php echo $MOD['thumb_height'];?>, $('thumb').value, true);}"/></td>
	<td width="120"><img src="<?php echo $thumb1 ? $thumb1 : SKIN_PATH.'image/waitpic.gif';?>" id="showthumb1" title="预览图片" alt="" onclick="if(this.src.indexOf('waitpic.gif') == -1){_preview($('showthumb1').src, 1);}else{Dalbum(1,<?php echo $moduleid;?>,<?php echo $MOD['thumb_width'];?>,<?php echo $MOD['thumb_height'];?>, $('thumb1').value, true);}"/></td>
	<td width="120"><img src="<?php echo $thumb2 ? $thumb2 : SKIN_PATH.'image/waitpic.gif';?>" id="showthumb2" title="预览图片" alt="" onclick="if(this.src.indexOf('waitpic.gif') == -1){_preview($('showthumb2').src, 1);}else{Dalbum(2,<?php echo $moduleid;?>,<?php echo $MOD['thumb_width'];?>,<?php echo $MOD['thumb_height'];?>, $('thumb2').value, true);}"/></td>
	</tr>
	<tr align="center" height="25">
	<td><span onclick="Dalbum('',<?php echo $moduleid;?>,<?php echo $MOD['thumb_width'];?>,<?php echo $MOD['thumb_height'];?>, $('thumb').value, true);" class="jt">[上传]</span>&nbsp;<span onclick="delAlbum('', 'wait');" class="jt">[删除]</span></td>
	<td><span onclick="Dalbum(1,<?php echo $moduleid;?>,<?php echo $MOD['thumb_width'];?>,<?php echo $MOD['thumb_height'];?>, $('thumb1').value, true);" class="jt">[上传]</span>&nbsp;<span onclick="delAlbum(1, 'wait');" class="jt">[删除]</span></td>
	<td><span onclick="Dalbum(2,<?php echo $moduleid;?>,<?php echo $MOD['thumb_width'];?>,<?php echo $MOD['thumb_height'];?>, $('thumb2').value, true);" class="jt">[上传]</span>&nbsp;<span onclick="delAlbum(2, 'wait');" class="jt">[删除]</span></td>
	</tr>
	</table>
</td>
</tr>
<tr>
<td class="tl">信息类型 <span class="f_red">*</span></td>
<td>
<?php foreach($TYPE as $k=>$v) {?>
<input type="radio" name="post[typeid]" value="<?php echo $k;?>" <?php if($k==$typeid) echo 'checked';?> onclick="$('title').value='<?php echo $v;?>';" id="typeid_<?php echo $k;?>"/><label for="typeid_<?php echo $k;?>"> <?php echo $v;?></label>&nbsp;
<?php } ?>
</td>
</tr>
<tr>
<td class="tl">产品类别 <span class="f_red">*</span></td>
<td><?php echo ajax_category_select('post[catid]', '', $catid, $moduleid, 'size="2" style="height:120px;width:180px;"');?>
<br/><span id="dcatid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">信息标题 <span class="f_red">*</span></td>
<td><input name="post[title]" type="text" id="title" size="60" value="<?php echo $title;?>"/> <?php echo level_select('post[level]', '级别', $level);?> <?php echo dstyle('post[style]', $style);?> <br/><span id="dtitle" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">产品名称 <span class="f_red">*</span></td>
<td><input name="post[tag]" id="tag" type="text" size="30" value="<?php echo $tag;?>"/> <span id="dtag" class="f_red"></span></td>
</tr>
<?php echo $FD ? fields_html('<td class="tl">', '<td>', $item) : '';?>
<tr>
<td class="tl">详细说明 <span class="f_red">*</span></td>
<td><textarea name="post[content]" id="content" class="dsn"><?php echo $content;?></textarea>
<?php echo deditor($moduleid, 'content', 'Default', '95%', 350);?><br/>
<span id="dcontent" class="f_red"></span>
</td>
</tr>
<tr>
<td class="tl">过期时间 <span class="f_red">*</span></td>
<td><?php echo dcalendar('post[totime]', $totime);?> <span id="dposttotime" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">交易条件</td>
<td>
	<table width="100%">
	<tr>
	<td width="70">需求数量</td>
	<td><input name="post[amount]" type="text" size="20" value="<?php echo $amount;?>"/></td>
	</tr>
	<tr>
	<td>价格要求</td>
	<td><input name="post[price]" type="text" size="20" value="<?php echo $price;?>"/></td>
	</tr>
	<tr>
	<td>规格要求</td>
	<td><input name="post[standard]" type="text" size="20" value="<?php echo $standard;?>"/></td>
	</tr>
	<tr>
	<td>包装要求</td>
	<td><input name="post[pack]" type="text" size="20" value="<?php echo $pack;?>"/></td>
	</tr>
	</table>
</td>
</tr>
<tr>
<td class="tl">会员信息</td>
<td>
<input type="radio" name="ismember" value="1" <?php if($username) echo 'checked';?> onclick="Dh('d_guest');Ds('d_member');$('username').value='<?php echo $username;?>';" id="ismember_1"/><label for="ismember_1"> 是</label>&nbsp;&nbsp;&nbsp;
<input type="radio" name="ismember" value="0" <?php if(!$username) echo 'checked';?> onclick="Dh('d_member');Ds('d_guest');$('username').value='';" id="ismember_0"/><label for="ismember_0"> 否</label>
</td>
</tr>
<tbody id="d_member" style="display:<?php echo $username ? '' : 'none';?>">
<tr>
<td class="tl">会员名 <span class="f_red">*</span></td>
<td><input name="post[username]" type="text"  size="20" value="<?php echo $username;?>" id="username"/> <span id="dusername" class="f_red"></span></td>
</tr>
</tbody>
<tbody id="d_guest" style="display:<?php echo $username ? 'none' : '';?>">
<tr>
<td class="tl">公司名称 <span class="f_red">*</span></td>
<td class="tr"><input name="post[company]" type="text" id="company" size="50" value="<?php echo $company;?>" /> 个人请填 姓名(个人) 例如：张三(个人)<br/><span id="dcompany" class="f_red"></span> </td>
</tr>
<tr>
<td class="tl">所在地区 <span class="f_red">*</span></td>
<td class="tr"><?php echo ajax_area_select('post[areaid]', '请选择', $areaid);?> <span id="dareaid" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">联系人 <span class="f_red">*</span></td>
<td class="tr"><input name="post[truename]" type="text" id="truename" size="20" value="<?php echo $truename;?>" /> <span id="dtruename" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">联系手机 <span class="f_red">*</span></td>
<td class="tr"><input name="post[mobile]" id="mobile" type="text" size="30" value="<?php echo $mobile;?>"/> <span id="dmobile" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">电子邮件</td>
<td class="tr"><input name="post[email]" id="email" type="text" size="30" value="<?php echo $email;?>" /> <span id="demail" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">联系电话</td>
<td class="tr"><input name="post[telephone]" id="telephone" type="text" size="30" value="<?php echo $telephone;?>"/> <span id="dtelephone" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">联系地址</td>
<td class="tr"><input name="post[address]" type="text" size="60" value="<?php echo $address;?>"/></td>
</tr>
<tr>
<td class="tl">联系MSN</td>
<td class="tr"><input name="post[msn]" type="text" size="30" value="<?php echo $msn;?>"/></td>
</tr>
<tr>
<td class="tl">联系QQ</td>
<td class="tr"><input name="post[qq]" type="text" size="30" value="<?php echo $qq;?>"/></td>
</tr>
</tbody>
<tr>
<td class="tl">信息状态</td>
<td>
<input type="radio" name="post[status]" value="3" <?php if($status == 3) echo 'checked';?>/> 通过
<input type="radio" name="post[status]" value="2" <?php if($status == 2) echo 'checked';?>/> 待审
<input type="radio" name="post[status]" value="1" <?php if($status == 1) echo 'checked';?> onclick="if(this.checked) $('note').style.display='';"/> 拒绝
<input type="radio" name="post[status]" value="4" <?php if($status == 4) echo 'checked';?>/> 过期
<input type="radio" name="post[status]" value="0" <?php if($status == 0) echo 'checked';?>/> 删除
</td>
</tr>
<tr id="note" style="display:<?php echo $status==1 ? '' : 'none';?>">
<td class="tl">拒绝理由 <span class="f_red">*</span></td>
<td><input name="post[note]" type="text"  size="40" value="<?php echo $note;?>"/></td>
</tr>
<tr>
<td class="tl">添加时间</td>
<td><input type="text" size="22" name="post[addtime]" value="<?php echo $addtime;?>"/></td>
</tr>
<tr>
<td class="tl">内容收费</td>
<td><input name="post[fee]" type="text" size="5" value="<?php echo $fee;?>"/><?php tips('不填或填0表示继承模块设置价格，-1表示不收费<br/>大于0的数字表示具体收费价格');?>
</td>
</tr>
<tr>
<td class="tl">内容模板</td>
<td><?php echo tpl_select('show', $module, 'post[template]', '默认模板', $template);?></td>
</tr>
</table>
<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value=" 重 置 " class="btn"/></div>
</form>
<?php load('clear.js'); ?>
<script type="text/javascript">
function check() {
	var l;
	var f;
	f = 'catid_1';
	if($(f).value == 0) {
		Dmsg('请选择所属行业', 'catid', 1);
		return false;
	}
	f = 'title';
	l = $(f).value.length;
	if(l < 2) {
		Dmsg('标题最少2字，当前已输入'+l+'字', f);
		return false;
	}
	f = 'tag';
	l = $(f).value.length;
	if(l < 2) {
		Dmsg('产品关键字最少2字，当前已输入'+l+'字', f);
		return false;
	}
	f = 'content';
	l = FCKLen();
	if(l < 5) {
		Dmsg('详细说明最少5字，当前已输入'+l+'字', f);
		return false;
	}
	f = 'posttotime';
	l = $(f).value.length;
	if(l != 10) {
		Dmsg('请选择信息过期时间', f);
		return false;
	}

	if($(f).value.replace(/-/g, '') > <?php echo $maxdate;?>) {
		$(f).value = '<?php echo $maxtime;?>';
		Dmsg('过期时间最晚可选择至<?php echo $maxtime;?>', f);
		return false;
	}

	if($('ismember_1').checked) {
		f = 'username';
		l = $(f).value.length;
		if(l < 2) {
			Dmsg('请填写会员名', f);
			return false;
		}
	} else {
		f = 'company';
		l = $(f).value.length;
		if(l < 2) {
			Dmsg('请填写公司名称', f);
			return false;
		}
		if($('areaid_1').value == 0) {
			Dmsg('请选择所在地区', 'areaid', 1);
			return false;
		}
		f = 'truename';
		l = $(f).value.length;
		if(l < 2) {
			Dmsg('请填写联系人', f);
			return false;
		}
		f = 'mobile';
		l = $(f).value.length;
		if(l < 7) {
			Dmsg('请填写手机', f);
			return false;
		}
	}
	<?php echo $FD ? fields_js() : '';?>
	return true;
}
</script>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
</body>
</html>