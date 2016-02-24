<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
show_menu($menus);
?>
<div class="tt">会员组修改</div>
<form method="post" action="?" onsubmit="return check();">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="action" value="<?php echo $action;?>"/>
<input type="hidden" name="groupid" value="<?php echo $groupid;?>"/>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">会员组名称 <span class="f_red">*</span></td>
<td><input type="text" size="20" name="groupname" id="groupname" value="<?php echo $groupname;?>"/> <span id="dgroupname" class="f_red"></span></td>
</tr>
<tr>
<td class="tl"><?php echo VIP;?>指数 <span class="f_red">*</span></td>
<td><input type="text" size="20" name="vip" id="vip" value="<?php echo $vip;?>"/> <span class="f_gray">免费会员请填0，收费会员请填1-9数字</span> <span id="dvip" class="f_red"></span></td>
</tr>
<tr>
<td class="tl">收费模式 <span class="f_red">*</span></td>
<td>
<input type="radio" name="setting[fee_mode]" value="1" <?php if($fee_mode) echo 'checked';?> onclick="Ds('mode_1');Dh('mode_0')"/> 包年&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[fee_mode]" value="0" <?php if(!$fee_mode) echo 'checked';?> onclick="Ds('mode_0');Dh('mode_1')"/> 扣费
</td>
</tr>

<tr id="mode_1" style="display:<?php echo $fee_mode ? '' : 'none';?>">
<td class="tl">收费设置 <span class="f_red">*</span></td>
<td><input type="text" size="20" name="setting[fee]" id="fee" value="<?php echo $fee;?>"/> 元/年 <span class="f_gray">免费会员请填0</span> <span id="dfee" class="f_red"></span></td>
</tr>

<tr id="mode_0" style="display:<?php echo $fee_mode ? 'none' : '';?>">
<td class="tl">享受折扣 <span class="f_red">*</span></td>
<td><input type="text" size="20" name="setting[discount]" id="discount" value="<?php echo $discount;?>"/> % 折扣仅限系统收费</td>
</tr>
</table>
<div class="tt c_p">会员权限</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">设置说明</td>
<td>数量限制填 <strong>0</strong> 则表示不限&nbsp;&nbsp;&nbsp;填 <strong>-1</strong> 表示禁止使用</td>
</tr>
<tr>
<td class="tl">会员网页编辑器</td>
<td>
<select name="setting[editor]">
<option value="0"<?php if($editor == 0) echo 'selected';?>>简洁版</option>
<option value="1"<?php if($editor == 1) echo 'selected';?>>全功能版</option>
</select>&nbsp;
<?php tips('全功能版允许会员编辑源代码和插入FLASH和视频文件<br/>为了防止被恶意利用，建议仅对受信任的会员组开启');?>
</td>
</tr>
<tr>
<td class="tl">发布信息需要审核</td>
<td>
<input type="radio" name="setting[check]" value="1" <?php if($check) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[check]" value="0" <?php if(!$check) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">发布信息启用验证码</td>
<td>
<input type="radio" name="setting[captcha]" value="1" <?php if($captcha) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha]" value="0" <?php if(!$captcha) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">发布信息启用验证问题</td>
<td>
<input type="radio" name="setting[question]" value="1" <?php if($question) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[question]" value="0" <?php if(!$question) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许申请提现</td>
<td>
<input type="radio" name="setting[cash]" value="1" <?php if($cash) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[cash]" value="0" <?php if(!$cash) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许使用客服中心功能</td>
<td>
<input type="radio" name="setting[ask]" value="1" <?php if($ask) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[ask]" value="0" <?php if(!$ask) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许使用商机订阅功能</td>
<td>
<input type="radio" name="setting[mail]" value="1" <?php if($mail) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[mail]" value="0" <?php if(!$mail) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许发送电子邮件</td>
<td>
<input type="radio" name="setting[sendmail]" value="1" <?php if($sendmail) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[sendmail]" value="0" <?php if(!$sendmail) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许站内付款</td>
<td>
<input type="radio" name="setting[trade_pay]" value="1" <?php if($trade_pay) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[trade_pay]" value="0" <?php if(!$trade_pay) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许下订单</td>
<td>
<input type="radio" name="setting[trade_buy]" value="1" <?php if($trade_buy) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[trade_buy]" value="0" <?php if(!$trade_buy) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许查看订单</td>
<td>
<input type="radio" name="setting[trade_sell]" value="1" <?php if($trade_sell) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[trade_sell]" value="0" <?php if(!$trade_sell) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">允许竞价排名</td>
<td>
<input type="radio" name="setting[spread]" value="1" <?php if($spread) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[spread]" value="0" <?php if(!$spread) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">收件箱数量限制</td>
<td>
<input type="text" name="setting[inbox_limit]" size="5" value="<?php echo $inbox_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">商友数量限制</td>
<td>
<input type="text" name="setting[friend_limit]" size="5" value="<?php echo $friend_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">商机收藏数量限制</td>
<td>
<input type="text" name="setting[favorite_limit]" size="5" value="<?php echo $favorite_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">每日可发站内信限制</td>
<td>
<input type="text" name="setting[message_limit]" size="5" value="<?php echo $message_limit;?>"/> <?php echo tips('询盘和报价为特殊的站内信，发送一次询盘或者报价会消耗一次站内信发送机会');?>
</td>
</tr>

<tr>
<td class="tl">每日询盘次数限制</td>
<td>
<input type="text" name="setting[inquiry_limit]" size="5" value="<?php echo $inquiry_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">每日报价次数限制</td>
<td>
<input type="text" name="setting[price_limit]" size="5" value="<?php echo $price_limit;?>"/>
</td>
</tr>


<tr>
<td class="tl">自定义分类限制</td>
<td>
<input type="text" name="setting[type_limit]" size="5" value="<?php echo $type_limit;?>"/>
</td>
</tr>

</table>

<div class="tt">公司主页</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">拥有公司主页</td>
<td>
<input type="radio" name="setting[homepage]" value="1" <?php if($homepage) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[homepage]" value="0" <?php if(!$homepage) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">默认公司模板</td>
<td>
<?php echo homepage_select('setting[styleid]', '请选择', $groupid, $styleid);?>
</td>
</tr>
<tr>
<td class="tl">允许自定义主页设置</td>
<td>
<input type="radio" name="setting[home]" value="1" <?php if($home) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[home]" value="0" <?php if(!$home) echo 'checked';?>> 否
</td>
</tr>
<tr>
<td class="tl">允许选择模板</td>
<td>
<input type="radio" name="setting[style]" value="1" <?php if($style) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[style]" value="0" <?php if(!$style) echo 'checked';?>> 否
</td>
</tr>

<tr>
<td class="tl">公司新闻数量限制</td>
<td>
<input type="text" name="setting[news_limit]" size="5" value="<?php echo $news_limit;?>"/>
</td>
</tr>
<tr>
<td class="tl">荣誉资质数量限制</td>
<td>
<input type="text" name="setting[credit_limit]" size="5" value="<?php echo $credit_limit;?>"/>
</td>
</tr>
<tr>
<td class="tl">友情链接数量限制</td>
<td>
<input type="text" name="setting[link_limit]" size="5" value="<?php echo $link_limit;?>"/>
</td>
</tr>
</table>

<div class="tt">信息发布</div>
<table cellpadding="2" cellspacing="1" class="tb">

<tr>
<td class="tl">允许发布信息的模块</td>
<td>
<?php
	$moduleids = explode(',', $moduleids);
	foreach($MODULE as $m) {
		if($m['moduleid'] > 4 && is_file(DT_ROOT.'/module/'.$m['module'].'/my.inc.php')) {
			if($m['moduleid'] == 9) {
				echo '<input type="checkbox" name="setting[moduleids][]" value="9" '.(in_array(9, $moduleids) ? 'checked' : '').'/> 招聘&nbsp;&nbsp;';
				echo '<input type="checkbox" name="setting[moduleids][]" value="-9" '.(in_array(-9, $moduleids) ? 'checked' : '').'/> 简历&nbsp;&nbsp;';
			} else {
				echo '<input type="checkbox" name="setting[moduleids][]" value="'.$m['moduleid'].'" '.(in_array($m['moduleid'], $moduleids) ? 'checked' : '').'/> '.$m['name'].'&nbsp;&nbsp;';
			}
		}
	}
?>
</td>
</tr>

<tr>
<td class="tl">允许复制信息</td>
<td>
<input type="radio" name="setting[copy]" value="1" <?php if($copy) echo 'checked';?>> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[copy]" value="0" <?php if(!$copy) echo 'checked';?>> 否 

复制信息可显著提高发布信息效率
</td>
</tr>

<tr>
<td class="tl">发布信息时间间隔</td>
<td>
<input type="text" name="setting[add_limit]" size="5" value="<?php echo $add_limit;?>"/>
&nbsp;&nbsp;单位： 秒&nbsp;&nbsp;填 0 表示不限制&nbsp;&nbsp;填正数表示发布两次发布时间间隔
</td>
</tr>

<tr>
<td class="tl">24小时发布信息数量</td>
<td>
<input type="text" name="setting[day_limit]" size="5" value="<?php echo $day_limit;?>"/>
&nbsp;&nbsp;填 0 表示不限制&nbsp;&nbsp;填正数表示24小时内在单模块发布信息数量限制
</td>
</tr>

<tr>
<td class="tl">刷新信息时间间隔</td>
<td>
<input type="text" name="setting[refresh_limit]" size="5" value="<?php echo $refresh_limit;?>"/>
&nbsp;&nbsp;单位： 秒&nbsp;&nbsp;填 -1 表示不允许刷新&nbsp;&nbsp;填 0 表示不限制时间间隔&nbsp;&nbsp;填正数表示限制两次刷新时间
</td>
</tr>

<tr>
<td class="tl">允许修改信息时间</td>
<td>
<input type="text" name="setting[edit_limit]" size="5" value="<?php echo $edit_limit;?>"/>
&nbsp;&nbsp;单位： 天&nbsp;&nbsp;填 -1 表示不允许修改&nbsp;&nbsp;填 0 表示不限制时间修改&nbsp;&nbsp;填正数表示发布时间超出后不可修改
</td>
</tr>

<tr>
<td class="tl">供应信息数量限制</td>
<td>
<input type="text" name="setting[sell_limit]" size="5" value="<?php echo $sell_limit;?>"/>
&nbsp;&nbsp;填 -1 表示禁止发布 填 0 表示不限制数量 填正数表示限制数量，下同
</td>
</tr>

<tr>
<td class="tl">免费供应信息数量限制</td>
<td>
<input type="text" name="setting[sell_free_limit]" size="5" value="<?php echo $sell_free_limit;?>"/>
&nbsp;&nbsp;填 -1 表示不收费 请填 0 表示无免费 填正数表示可免费发布条数 此项仅针对扣费模式会员，下同
</td>
</tr>

<tr>
<td class="tl">求购信息数量限制</td>
<td>
<input type="text" name="setting[buy_limit]" size="5" value="<?php echo $buy_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">免费求购信息数量限制</td>
<td>
<input type="text" name="setting[buy_free_limit]" size="5" value="<?php echo $buy_free_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">展会信息数量限制</td>
<td>
<input type="text" name="setting[exhibit_limit]" size="5" value="<?php echo $exhibit_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">免费展会信息数量限制</td>
<td>
<input type="text" name="setting[exhibit_free_limit]" size="5" value="<?php echo $exhibit_free_limit;?>"/>
</td>
</tr>


<tr>
<td class="tl">招聘信息数量限制</td>
<td>
<input type="text" name="setting[job_limit]" size="5" value="<?php echo $job_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">免费招聘信息数量限制</td>
<td>
<input type="text" name="setting[job_free_limit]" size="5" value="<?php echo $job_free_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">简历数量限制</td>
<td>
<input type="text" name="setting[resume_limit]" size="5" value="<?php echo $resume_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">免费简历数量限制</td>
<td>
<input type="text" name="setting[resume_free_limit]" size="5" value="<?php echo $resume_free_limit;?>"/>
</td>
</tr>


<tr>
<td class="tl">文章数量限制</td>
<td>
<input type="text" name="setting[article_limit]" size="5" value="<?php echo $article_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">免费文章数量限制</td>
<td>
<input type="text" name="setting[article_free_limit]" size="5" value="<?php echo $article_free_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">信息数量限制</td>
<td>
<input type="text" name="setting[info_limit]" size="5" value="<?php echo $info_limit;?>"/>
</td>
</tr>

<tr>
<td class="tl">免费信息数量限制</td>
<td>
<input type="text" name="setting[info_free_limit]" size="5" value="<?php echo $info_free_limit;?>"/>
</td>
</tr>

</table>

<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn">&nbsp;&nbsp;&nbsp;&nbsp;</div>
</form>
<script type="text/javascript">
function check() {
	var l;
	var f;
	f = 'groupname';
	l = $(f).value.length;
	if(l < 2) {
		Dmsg('请填写会员组名称', f);
		return false;
	}
	f = 'vip';
	if($(f).value == '') {
		Dmsg('请填写<?php echo VIP;?>指数', f);
		return false;
	}
	f = 'fee';
	if($(f).value == '') {
		Dmsg('请填写收费设置', f);
		return false;
	}
	return true;
}
</script>
<script type="text/javascript">Menuon(<?php echo $menuid;?>);</script>
</body>
</html>