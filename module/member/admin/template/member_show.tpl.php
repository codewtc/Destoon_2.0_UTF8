<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
if(!isset($dialog)) show_menu($menus);
?>
<div class="tt">会员资料</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">会员名</td>
<td>&nbsp;<?php echo $username;?></td>
<td class="tl">通行证名</td>
<td>&nbsp;<?php echo $passport;?></td>
</tr>
<tr>
<td class="tl">会员ID</td>
<td>&nbsp;<?php echo $userid;?></td>
<td class="tl">会员组</td>
<td>&nbsp;<?php echo $GROUP[$groupid]['groupname'];?></td>
</tr>

<tr>
<td class="tl">姓 名</td>
<td>&nbsp;<?php echo $truename;?></td>
<td class="tl">性 别</td>
<td>&nbsp;<?php echo $gender == 1 ? '先生' : '女士';?></td>
</tr>
<tr>
<td class="tl"><?php echo VIP;?>指数</td>
<td>&nbsp;<img src="<?php echo SKIN_PATH;?>image/vip_<?php echo $vip;?>.gif"/></td>
<td class="tl">登录次数</td>
<td>&nbsp;<?php echo $logintimes;?></td>
</tr>
<?php if($vip) { ?>
<tr>
<td class="tl">服务开始日期</td>
<td>&nbsp;<?php echo timetodate($fromtime, 3);?></td>
<td class="tl">服务结束日期</td>
<td>&nbsp;<?php echo timetodate($totime, 3);?></td>
</tr>
<?php } ?>
<tr>
<td class="tl">上次登录</td>
<td>&nbsp;<?php echo timetodate($logintime, 5);?></td>
<td class="tl">登录IP</td>
<td>&nbsp;<?php echo $loginip;?></td>
</tr>
<tr>
<td class="tl">注册时间</td>
<td>&nbsp;<?php echo timetodate($regtime, 5);?></td>
<td class="tl">注册IP</td>
<td>&nbsp;<?php echo $regip;?></td>
</tr>
<tr>
<td class="tl">资金余额</td>
<td>&nbsp;<?php echo $money;?></td>
<td class="tl">资金锁定</td>
<td>&nbsp;<?php echo $money_lock;?></td>
</tr>
</table>
<div class="tt">公司资料</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">公司主页</td>
<td colspan="3">&nbsp;<a href="<?php echo $linkurl;?>" target="_blank" style="color:red;"><?php echo $linkurl;?></a></td>
</tr>
<tr>
<td class="tl">公司名</td>
<td>&nbsp;<?php echo $company;?></td>
<td class="tl">公司类型</td>
<td>&nbsp;<?php echo $type;?></td>
</tr>
<tr>
<td class="tl">所属行业</td>
<td>&nbsp;<?php echo isset($CATEGORY[$catid]) ? $CATEGORY[$catid]['catname'] : '';?></td>
<td class="tl">所在地区</td>
<td>&nbsp;<?php echo isset($AREA[$areaid]) ? $AREA[$areaid]['areaname'] : '';?></td>
</tr>
<td class="tl">经营模式</td>
<td>&nbsp;<?php echo $mode;?></td>
<td class="tl">主营范围</td>
<td>&nbsp;<?php echo $business;?></td>
</tr>
<tr>
<td class="tl">注册资本</td>
<td>&nbsp;<?php echo $capital;?>万<?php echo $regunit;?></td>
<td class="tl">公司规模</td>
<td>&nbsp;<?php echo $size;?></td>
</tr>
<tr>
<td class="tl">成立年份</td>
<td>&nbsp;<?php echo $regyear;?></td>
<td class="tl">公司注册地</td>
<td>&nbsp;<?php echo $regcity;?></td>
</tr>
<tr>
<td class="tl">销售的产品 (提供的服务)</td>
<td>&nbsp;<?php echo $sell;?></td>
<td class="tl">采购的产品 (需要的服务)</td>
<td>&nbsp;<?php echo $buy;?></td>
</tr>
</table>

<div class="tt">联系方式</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">姓 名</td>
<td>&nbsp;<?php echo $truename;?></td>
<td class="tl">手 机</td>
<td>&nbsp;<?php echo $mobile;?></td>
</tr>
<tr>
<td class="tl">部 门</td>
<td>&nbsp;<?php echo $department;?></td>
<td class="tl">职 位</td>
<td>&nbsp;<?php echo $career;?></td>
</tr>
<tr>
<td class="tl">Email (不公开)</td>
<td>&nbsp;<?php echo $email;?></td>
<td class="tl">Email (公开)</td>
<td>&nbsp;<?php echo $mail;?></td>
</tr>
<tr>
<td class="tl">电 话</td>
<td>&nbsp;<?php echo $telephone;?></td>
<td class="tl">传 真</td>
<td>&nbsp;<?php echo $fax;?></td>
</tr>
<tr>
<td class="tl">MSN</td>
<td>&nbsp;<?php echo $msn;?></td>
<td class="tl">QQ</td>
<td>&nbsp;<?php echo $qq;?></td>
</tr>
<tr>
<td class="tl">网 址</td>
<td>&nbsp;<?php echo $homepage;?></td>
<td class="tl">邮 编</td>
<td>&nbsp;<?php echo $postcode;?></td>
</tr>
<tr>
<td class="tl">公司经营地址</td>
<td colspan="3">&nbsp;<?php echo $address;?></td>
</tr>
</table>
<div class="tt">其他信息</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">顶级域名</td>
<td>&nbsp;<?php echo $domain;?></td>
</tr>
<tr>
<td class="tl">Flash横幅</td>
<td>&nbsp;<?php echo $banner;?></td>
</tr>
<tr>
<td class="tl">ICP备案号</td>
<td>&nbsp;<?php echo $icp;?></td>
</tr>
<tr>
<td class="tl">客户端软件</td>
<td>&nbsp;<?php echo $agent;?></td>
</tr>
<tr>
<td class="tl">黑名单</td>
<td>&nbsp;<?php echo $black;?></td>
</tr>
<?php if(!isset($dialog)) { ?>
<tr>
<td class="tl"> </td>
<td colspan="3" height="30"><input type="button" value=" 修 改 " class="btn" onclick="window.location='?moduleid=<?php echo $moduleid;?>&action=edit&userid=<?php echo $userid;?>&forward=<?php echo urlencode($DT_URL);?>';"/>&nbsp;&nbsp;<input type="button" value=" 返 回 " class="btn" onclick="history.back(-1);"/></td>
</tr>
<?php } ?>
</table>
<br/>
<script type="text/javascript">Menuon(1);</script>
</body>
</html>