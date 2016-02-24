<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
$menus = array (
    array('基本设置'),
    array('公司相关'),
    array('会员整合'),
    array('支付配置'),
    array('支付接口'),
    array('模板管理', '?file=template&dir='.$module),
    array('定义字段', '?file=fields&tb='.$table),
);
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="tab" id="tab" value="<?php echo $tab;?>"/>
<div id="Tabs0" style="display:">
<div class="tt">注册设置</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">允许新用户注册</td>
<td>
<input type="radio" name="setting[enable_register]" value="1"  <?php if($enable_register) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[enable_register]" value="0"  <?php if(!$enable_register) echo 'checked';?>/> 否
</td>
</tr>
<tr>
<td class="tl">IP注册间隔限制(小时)</td>
<td>
<input type="text" size="3" name="setting[iptimeout]" value="<?php echo $iptimeout;?>"/><?php tips('同一IP在本时间间隔内将只能注册一个帐号，填0为不限制');?>
</td>
</tr>
<tr>
<td class="tl">用户名长度</td>
<td>
<input type="text" size="3" name="setting[minusername]" value="<?php echo $minusername;?>"/>
至
<input type="text" size="3" name="setting[maxusername]" value="<?php echo $maxusername;?>"/>
字符<?php tips('建议设置为4-20个字符之间');?>
</td>
</tr>
<tr>
<td class="tl">用户密码长度</td>
<td>
<input type="text" size="3" name="setting[minpassword]" value="<?php echo $minpassword;?>"/>
至
<input type="text" size="3" name="setting[maxpassword]" value="<?php echo $maxpassword;?>"/>
字符<?php tips('过短的密码不利于用户的帐户安全<br/>建议设置为6-20个字符之间，不要超过31位');?>
</td>
</tr>
<tr>
<td class="tl">用户名保留关键字</td>
<td><textarea name="setting[banusername]" style="width:96%;height:50px;overflow:visible;"><?php echo $banusername;?></textarea><?php tips('含有保留的关键字的用户名将被禁止使用，以免引起歧义<br/>多个保留关键字请用|隔开');?>
</td>
</tr>
<tr>
<td class="tl">新用户注册验证</td>
<td>
<input type="radio" name="setting[checkuser]" value="0"  <?php if(!$checkuser) echo 'checked';?>> 无需验证
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[checkuser]" value="1"  <?php if($checkuser==1) echo 'checked';?>> 人工审核&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[checkuser]" value="2"  <?php if($checkuser==2) echo 'checked';?>> 邮件验证
</td>
</tr>
<tr>
<td class="tl">发送欢迎信息</td>
<td>
<input type="radio" name="setting[welcome]" value="0"  <?php if(!$welcome) echo 'checked';?>/> 不发送
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[welcome]" value="1"  <?php if($welcome==1) echo 'checked';?>/> 站内短信&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[welcome]" value="2"  <?php if($welcome==2) echo 'checked';?>/> 电子邮件&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[welcome]" value="3"  <?php if($welcome==3) echo 'checked';?>/> 站内短信和电子邮件
</td>
</tr>
<tr>
<td class="tl">客户端屏蔽</td>
<td><textarea name="setting[banagent]" style="width:96%;height:50px;overflow:visible;"><?php echo $banagent;?></textarea><?php tips('群发软件可以伪造IP，但是部分软件发送的客户端信息相同<br/>例如某群发软件的客户端信息全部包含 .NET CLR 1.0.3705<br/>可在此直接屏蔽含有此类特征码的客户端注册<br/>多个特征码请用 | 分割<br/>用户注册的客户端信息已记录，请在会员资料里查看和分析<br/>用户登录日志里也记录了客户端信息，请注意分析');?>
</td>
</tr>
<tr>
<td class="tl">站内短信同时最多发送至</td>
<td>
<input type="text" size="3" name="setting[maxtouser]" value="<?php echo $maxtouser;?>"/> 位会员<?php tips('最小填1，例如填5则表示，同一信件一次最多可以同时发送给5位会员');?>
</td>
</tr>
<tr>
<td class="tl">发送站内短信启用验证码</td>
<td>
<input type="radio" name="setting[captcha_sendmessage]" value="2"  <?php if($captcha_sendmessage == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_sendmessage]" value="1"  <?php if($captcha_sendmessage == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_sendmessage]" value="0"  <?php if($captcha_sendmessage == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">登录失败次数限制</td>
<td><input type="text" size="3" name="setting[login_times]" value="<?php echo $login_times;?>"/> 次登录失败后锁定登录 <input type="text" size="3" name="setting[lock_hour]" value="<?php echo $lock_hour;?>"/> 小时
</td>
</tr>
<tr>
<td class="tl">验证邮件有效期</td>
<td>
<input type="text" size="3" name="setting[auth_days]" value="<?php echo $auth_days;?>"/> 天<?php tips('验证信链接超过有效期天数将失效 填0为不限制');?>
</td>
</tr>
<tr>
<td class="tl">商务中心显示所有菜单</td>
<td>
<input type="radio" name="setting[show_menu]" value="1"  <?php if($show_menu) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[show_menu]" value="0"  <?php if(!$show_menu) echo 'checked';?>/> 否<?php tips('选择否 则隐藏无权限访问的菜单');?>
</td>
</tr>
<tr>
<td class="tl">用户注册启用验证码</td>
<td>
<input type="radio" name="setting[captcha_register]" value="1"  <?php if($captcha_register) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_register]" value="0"  <?php if(!$captcha_register) echo 'checked';?>/> 否
</td>
</tr>
<tr>
<td class="tl">用户注册启用验证问题</td>
<td>
<input type="radio" name="setting[question_register]" value="1"  <?php if($question_register) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[question_register]" value="0"  <?php if(!$question_register) echo 'checked';?>/> 否
</td>
</tr>
<tr>
<td class="tl">用户登录启用验证码</td>
<td>
<input type="radio" name="setting[captcha_login]" value="1"  <?php if($captcha_login) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_login]" value="0"  <?php if(!$captcha_login) echo 'checked';?>/> 否
</td>
</tr>
<tr>
<td class="tl">用户登录默认记住会员</td>
<td>
<input type="radio" name="setting[login_remember]" value="1"  <?php if($login_remember) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[login_remember]" value="0"  <?php if(!$login_remember) echo 'checked';?>/> 否
</td>
</tr>
<tr>
<td class="tl">用户登录默认进入商务室</td>
<td>
<input type="radio" name="setting[login_goto]" value="1"  <?php if($login_goto) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[login_goto]" value="0"  <?php if(!$login_goto) echo 'checked';?>/> 否
</td>
</tr>
</table>
</div>

<div id="Tabs1" style="display:none;">
<div class="tt">公司相关</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">公司类型</td>
<td><textarea name="setting[com_type]" style="width:98%;height:50px;overflow:visible;"><?php echo $com_type;?></textarea></td>
</tr>
<tr>
<td class="tl">公司规模</td>
<td><textarea name="setting[com_size]" style="width:98%;height:50px;overflow:visible;"><?php echo $com_size;?></textarea></td>
</tr>
<tr>
<td class="tl">经营模式</td>
<td><textarea name="setting[com_mode]" style="width:98%;height:50px;overflow:visible;"><?php echo $com_mode;?></textarea></td>
</tr>
<tr>
<td class="tl">公司注册资本货币类型</td>
<td><textarea name="setting[money_unit]" style="width:98%;height:50px;overflow:visible;"><?php echo $money_unit;?></textarea></td>
</tr>
<tr>
<td class="tl"></td>
<td class="f_red">以上设置请用 | 分隔类型，结尾不需要 |</td>
</tr>
<tr>
<td class="tl">默认形象图[宽X高]</td>
<td>
<input type="text" size="3" name="setting[thumb_width]" value="<?php echo $thumb_width;?>"/>
X
<input type="text" size="3" name="setting[thumb_height]" value="<?php echo $thumb_height;?>"/> px
</td>
</tr>
<tr>
<td class="tl">截取公司介绍至简介</td>
<td>默认截取 <input type="text" size="3" name="setting[introduce_length]" value="<?php echo $introduce_length;?>"/> 字符
</td>
</tr>
<tr>
<td class="tl">公司新闻需审核</td>
<td>
<input type="radio" name="setting[news_check]" value="2"  <?php if($news_check == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[news_check]" value="1"  <?php if($news_check == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[news_check]" value="0"  <?php if($news_check == 0) echo 'checked';?>> 全部关闭

</td>
</tr>
<tr>
<td class="tl">荣誉资质需审核</td>
<td>
<input type="radio" name="setting[credit_check]" value="2"  <?php if($credit_check == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[credit_check]" value="1"  <?php if($credit_check == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[credit_check]" value="0"  <?php if($credit_check == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">友情链接需审核</td>
<td>
<input type="radio" name="setting[link_check]" value="2"  <?php if($link_check == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[link_check]" value="1"  <?php if($link_check == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[link_check]" value="0"  <?php if($link_check == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
</table>
</div>
<div id="Tabs2" style="display:none">
<div class="tt">会员整合</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">启用会员整合</td>
<td>
<input type="radio" name="setting[passport]" value="0"  <?php if(!$passport) echo 'checked';?> onclick="Dh('p_s');Dh('u_c');"/> 否
<input type="radio" name="setting[passport]" value="phpwind" <?php if($passport == 'phpwind') echo 'checked';?> onclick="Ds('p_s');Dh('u_c');"/> PHPWind
<input type="radio" name="setting[passport]" value="discuz" <?php if($passport == 'discuz') echo 'checked';?> onclick="Ds('p_s');Dh('u_c');"/> Discuz!
<input type="radio" name="setting[passport]" value="uc" <?php if($passport == 'uc') echo 'checked';?> onclick="Dh('p_s');Ds('u_c');"/> Ucenter
</td>
</tr>
<tbody id="p_s" style="display:<?php echo $passport && $passport != 'uc' ? '' : 'none';?>">
<tr>
<td class="tl">整合程序字符编码</td>
<td>
<select name="setting[passport_charset]">
<option value="gbk"<?php if($passport_charset == 'gbk') echo ' selected';?>>GBK/GB2312</option>
<option value="utf-8"<?php if($passport_charset == 'utf-8') echo ' selected';?>>UTF-8</option>
</select>
</td>
</tr>
<tr>
<td class="tl">整合程序地址</td>
<td><input name="setting[passport_url]" type="text" size="50" value="<?php echo $passport_url;?>"/><?php tips('整合程序接口地址 例如:http://bbs.destoon.com 结尾不要带斜线');?></td>
</tr>
<tr>
<td class="tl">整合密钥</td>
<td><input name="setting[passport_key]" type="text" size="30" value="<?php echo $passport_key;?>"/></td>
</tr>
</tbody>
<tbody id="u_c" style="display:<?php echo $passport && $passport == 'uc' ? '' : 'none';?>">
<tr>
<td class="tl">API 地址</td>
<td><input name="setting[uc_api]" type="text" size="50" value="<?php echo $uc_api;?>"/><?php tips('整合程序接口地址 例如:http://bbs.destoon.com 结尾不要带斜线');?></td>
</tr>
<tr>
<td class="tl">主机IP</td>
<td><input name="setting[uc_ip]" type="text" size="50" value="<?php echo $uc_ip;?>"/><?php tips('一般不用填写,遇到无法同步时,请填写Ucenter主机的IP地址');?></td>
</tr>
<tr>
<td class="tl">整合方式</td>
<td>
<input type="radio" name="setting[uc_mysql]" value="1" <?php if($uc_mysql) echo 'checked';?> onclick="Ds('u_c_m');"/> MySQL
<input type="radio" name="setting[uc_mysql]" value="0" <?php if(!$uc_mysql) echo 'checked';?> onclick="Dh('u_c_m');"/> 远程连接
</td>
</tr>
<tr id="u_c_m" style="display:<?php echo $uc_mysql ? '' : 'none';?>">
<td colspan="2" style="padding:10px;">
	<table cellpadding="2" cellspacing="1" class="tb">
	<tr>
	<td class="tl">数据库主机名</td>
	<td><input name="setting[uc_dbhost]" type="text" size="30" value="<?php echo $uc_dbhost;?>"/></td>
	</tr>
	<tr>
	<td class="tl">数据库用户名</td>
	<td><input name="setting[uc_dbuser]" type="text" size="30" value="<?php echo $uc_dbuser;?>"/></td>
	</tr>
	<tr>
	<td class="tl">数据库密码</td>
	<td><input name="setting[uc_dbpwd]" type="password" size="30" value="<?php echo $uc_dbpwd;?>"/></td>
	</tr>
	<tr>
	<td class="tl">数据库名</td>
	<td><input name="setting[uc_dbname]" type="text" size="30" value="<?php echo $uc_dbname;?>"/></td>
	</tr>
	<tr>
	<td class="tl">数据表前缀</td>
	<td><input name="setting[uc_dbpre]" type="text" size="30" value="<?php echo $uc_dbpre;?>"/></td>
	</tr>
	<tr>
	<td class="tl">数据库字符集</td>
	<td>
	<select name="setting[uc_charset]">
	<option value="gbk"<?php if($uc_charset == 'gbk') echo ' selected';?>>GBK/GB2312</option>
	<option value="utf-8"<?php if($uc_charset == 'utf-8') echo ' selected';?>>UTF-8</option>
	</select>
	</td>
	</tr>
	</table>
</td>
</tr>
<tr>
<td class="tl">应用ID(APP ID)</td>
<td><input name="setting[uc_appid]" type="text" size="30" value="<?php echo $uc_appid;?>"/></td>
</tr>
<tr>
<td class="tl">通信密钥</td>
<td><input name="setting[uc_key]" type="text" size="30" value="<?php echo $uc_key;?>"/></td>
</tr>
</tbody>
</table>
</div>
<div id="Tabs3" style="display:none">
<div class="tt">支付配置</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">启用会员在线充值</td>
<td>
<input type="radio" name="setting[pay_online]" value="1"  <?php if($pay_online) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[pay_online]" value="0"  <?php if(!$pay_online) echo 'checked';?>/> 否
</td>
</tr>
<tr>
<td class="tl">线下付款方式网页地址</td>
<td><input type="text" size="60" name="setting[pay_url]" value="<?php echo $pay_url;?>"/><?php tips('如果未启用会员在线充值，则系统自动调转至此地址查看普通付款方式。建议用插件的单网页功能建立');?></td>
</tr>
<tr>
<td class="tl">开启会员提现</td>
<td>
<input type="radio" name="setting[cash_enable]" value="1"  <?php if($cash_enable) echo 'checked';?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[cash_enable]" value="0"  <?php if(!$cash_enable) echo 'checked';?>/> 否
</td>
</tr>
<tr>
<td class="tl">提现方式</td>
<td><textarea name="setting[cash_banks]" style="width:90%;height:50px;overflow:visible;"><?php echo $cash_banks;?></textarea><?php tips('不同方式请用 | 分隔');?></td>
</tr>
<tr>
<td class="tl">24小时提现次数</td>
<td><input type="text" size="5" name="setting[cash_times]" value="<?php echo $cash_times;?>"/> 0为不限</td>
</tr>
<tr>
<td class="tl">单次提现最小金额</td>
<td><input type="text" size="5" name="setting[cash_min]" value="<?php echo $cash_min;?>"/> 0为不限</td>
</tr>
<tr>
<td class="tl">单次提现最大金额</td>
<td><input type="text" size="5" name="setting[cash_max]" value="<?php echo $cash_max;?>"/> 0为不限</td>
</tr>
<tr>
<td class="tl">提现费率</td>
<td><input type="text" size="2" name="setting[cash_fee]" value="<?php echo $cash_fee;?>"/> %</td>
</tr>
<tr>
<td class="tl">费率最小值</td>
<td><input type="text" size="5" name="setting[cash_fee_min]" value="<?php echo $cash_fee_min;?>"/> 0为不限</td>
</tr>
<tr>
<td class="tl">费率封顶值</td>
<td><input type="text" size="5" name="setting[cash_fee_max]" value="<?php echo $cash_fee_max;?>"/> 0为不限</td>
</tr>
<tr>
<td class="tl">买家默认确认收货时间</td>
<td><input type="text" size="2" name="setting[trade_day]" value="<?php echo $trade_day;?>"/> 天<?php tips('买家在此时间内未确认收货或申请仲裁，则系统自动付款给卖家，交易成功');?></td>
</tr>
<tr>
<td class="tl">常用支付方式</td>
<td><textarea name="setting[pay_banks]" style="width:90%;height:50px;overflow:visible;"><?php echo $pay_banks;?></textarea><?php tips('手动添加资金流水时需选择');?></td>
</tr>
<tr>
<td class="tl">常用物流方式</td>
<td><textarea name="setting[send_types]" style="width:90%;height:50px;overflow:visible;"><?php echo $send_types;?></textarea></td>
</tr>
</table>
</div>
<div id="Tabs4" style="display:none">
<div class="tt">支付接口</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl"><a href="<?php echo $MODULE[3]['linkurl'];?>redirect.php?url=www.chinabank.com.cn" target="_blank" class="t"><strong>网银在线 ChinaBank</strong></a></td>
<td>
<input type="radio" name="pay[chinabank][enable]" value="1"  <?php if($chinabank['enable']) echo 'checked';?> onclick="$('chinabank').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="pay[chinabank][enable]" value="0"  <?php if(!$chinabank['enable']) echo 'checked';?> onclick="$('chinabank').style.display='none';"/> 禁用
</td>
</tr>
<tbody style="display:<?php echo $chinabank['enable'] ? '' : 'none';?>" id="chinabank">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="pay[chinabank][name]" value="<?php echo $chinabank['name'];?>"/></td>
</tr>
<tr>
<td class="tl">商户编号</td>
<td><input type="text" size="30" name="pay[chinabank][partnerid]" value="<?php echo $chinabank['partnerid'];?>"/></td>
</tr>
<tr>
<td class="tl">支付密钥</td>
<td><input type="password" size="30" name="pay[chinabank][keycode]" value="<?php echo $chinabank['keycode'];?>"/></td>
</tr>
<tr>
<td class="tl">扣除手续费</td>
<td><input type="text" size="2" name="pay[chinabank][percent]" value="<?php echo $chinabank['percent'];?>"/> %</td>
</tr>
</tbody>
<tr>
<td class="tl"><a href="<?php echo $MODULE[3]['linkurl'];?>redirect.php?url=www.alipay.com" target="_blank" class="t"><strong>支付宝 Alipay</strong></a></td>
<td>
<input type="radio" name="pay[alipay][enable]" value="1"  <?php if($alipay['enable']) echo 'checked';?> onclick="$('alipay').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="pay[alipay][enable]" value="0"  <?php if(!$alipay['enable']) echo 'checked';?> onclick="$('alipay').style.display='none';"/> 禁用
</td>
</tr>
<tbody style="display:<?php echo $alipay['enable'] ? '' : 'none';?>" id="alipay">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="pay[alipay][name]" value="<?php echo $alipay['name'];?>"/></td>
</tr>
<tr>
<td class="tl">支付宝帐号</td>
<td><input type="text" size="30" name="pay[alipay][email]" value="<?php echo $alipay['email'];?>"/></td>
</tr>
<tr>
<td class="tl">合作者身份（partnerID）</td>
<td><input type="text" size="30" name="pay[alipay][partnerid]" value="<?php echo $alipay['partnerid'];?>"/></td>
</tr>
<tr>
<td class="tl">交易安全校验码（key）</td>
<td><input type="password" size="30" name="pay[alipay][keycode]" value="<?php echo $alipay['keycode'];?>"/></td>
</tr>
<tr>
<td class="tl">接口类型</td>
<td>
<select name="pay[alipay][service]">
<option value="create_direct_pay_by_user" <?php if($alipay['service'] == 'create_direct_pay_by_user') echo 'selected';?>>快速付款（即时到账接口）</option>
<option value="trade_create_by_buyer" <?php if($alipay['service'] == 'trade_create_by_buyer') echo 'selected';?>>标准实物双接口（标准双接口）</option>
<option value="create_partner_trade_by_buyer" <?php if($alipay['service'] == 'create_partner_trade_by_buyer') echo 'selected';?>>纯担保交易接口（担保接口）</option>
</select>
</td>
</tr>
<tr>
<td class="tl">接收服务器通知文件名</td>
<td><input type="type" size="30" name="pay[alipay][notify]" value="<?php echo $alipay['notify'];?>"/> <?php tips('默认为alipay.php 保存于 api/ 目录<br/>建议你修改此文件名，然后在此填写新文件名，以防受到骚扰');?></td>
</tr>
<tr>
<td class="tl">扣除手续费</td>
<td><input type="text" size="2" name="pay[alipay][percent]" value="<?php echo $alipay['percent'];?>"/> %</td>
</tr>
</tbody>
<tr>
<td class="tl"><a href="<?php echo $MODULE[3]['linkurl'];?>redirect.php?url=www.tenpay.com" target="_blank" class="t"><strong>财付通 TenPay</strong></a></td>
<td>
<input type="radio" name="pay[tenpay][enable]" value="1"  <?php if($tenpay['enable']) echo 'checked';?> onclick="$('tenpay').style.display='';"/> 启用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="pay[tenpay][enable]" value="0"  <?php if(!$tenpay['enable']) echo 'checked';?> onclick="$('tenpay').style.display='none';"/> 禁用
</td>
</tr>
<tbody style="display:<?php echo $tenpay['enable'] ? '' : 'none';?>" id="tenpay">
<tr>
<td class="tl">显示名称</td>
<td><input type="text" size="30" name="pay[tenpay][name]" value="<?php echo $tenpay['name'];?>"/></td>
</tr>
<tr>
<td class="tl">商户编号</td>
<td><input type="text" size="30" name="pay[tenpay][partnerid]" value="<?php echo $tenpay['partnerid'];?>"/></td>
</tr>
<tr>
<td class="tl">支付密钥</td>
<td><input type="password" size="30" name="pay[tenpay][keycode]" value="<?php echo $tenpay['keycode'];?>"/></td>
</tr>
<tr>
<td class="tl">扣除手续费</td>
<td><input type="text" size="2" name="pay[tenpay][percent]" value="<?php echo $tenpay['percent'];?>"/> %</td>
</tr>
</tbody>
</table>
</div>
<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value=" 重 置 " class="btn"/></div>
</form>
<?php if($tab) { ?><script type="text/javascript">window.onload=function() {Tab(<?php echo $tab;?>);}</script><?php } ?>
</body>
</html>