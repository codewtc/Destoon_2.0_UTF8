<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
$menus = array (
    array('模块设置'),
    array('模板管理', '?file=template&dir='.$module),
);
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="moduleid" value="<?php echo $moduleid;?>"/>
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="tab" id="tab" value="<?php echo $tab;?>"/>
<div id="Tabs0" style="display:">
<div class="tt">排名推广</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr> 
<td class="tl">排名推广绑定域名</td>
<td><input name="setting[spread_domain]"  type="text" size="30" value="<?php echo $spread_domain;?>"/><?php tips('例如 http://spread.destoon.com/<br/>请将此域名绑定至网站spread目录');?></td>
</tr>
<tr> 
<td class="tl">供应排名起价</td>
<td><input name="setting[spread_sell_price]"  type="text" size="5" value="<?php echo $spread_sell_price;?>"/></td>
</tr>
<tr> 
<td class="tl">求购排名起价</td>
<td><input name="setting[spread_buy_price]"  type="text" size="5" value="<?php echo $spread_buy_price;?>"/></td>
</tr>
<tr>
<td class="tl">公司排名起价</td>
<td><input name="setting[spread_company_price]"  type="text" size="5" value="<?php echo $spread_company_price;?>"/></td>
</tr>
<tr>
<td class="tl">加价幅度</td>
<td><input name="setting[spread_step]"  type="text" size="5" value="<?php echo $spread_step;?>"/><?php tips('如果设置了加价幅度，则出价必须是起价加加价幅度的倍数');?></td>
</tr>
<tr>
<td class="tl">最多可购买月数</td>
<td><input name="setting[spread_month]"  type="text" size="5" value="<?php echo $spread_month;?>"/><?php tips('以月为单位 最少为1个月');?></td>
</tr>
<tr>
<td class="tl">同一月单词最多购买次数</td>
<td><input name="setting[spread_max]"  type="text" size="5" value="<?php echo $spread_max;?>"/></td>
</tr>
<tr>
<td class="tl">购买排名需要审核</td>
<td>
<input type="radio" name="setting[spread_check]" value="1"  <?php if($spread_check) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[spread_check]" value="0"  <?php if(!$spread_check) echo 'checked';?>/> 关闭
</td>
</tr>
</table>
<div class="tt">广告设置</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">广告功能</td>
<td>
<input type="radio" name="setting[ad_enable]" value="1"  <?php if($ad_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[ad_enable]" value="0"  <?php if(!$ad_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">广告绑定域名</td>
<td><input name="setting[ad_domain]"  type="text" size="30" value="<?php echo $ad_domain;?>"/><?php tips('例如 http://ad.destoon.com/<br/>请将此域名绑定至网站ad目录');?></td>
</tr>
<tr>
<td class="tl">广告位预览</td>
<td>
<input type="radio" name="setting[ad_view]" value="1"  <?php if($ad_view) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[ad_view]" value="0"  <?php if(!$ad_view) echo 'checked';?>/> 关闭
</td>
</tr>
</table>
<div class="tt">公告设置</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">公告功能</td>
<td>
<input type="radio" name="setting[announce_enable]" value="1"  <?php if($announce_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[announce_enable]" value="0"  <?php if(!$announce_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">公告绑定域名</td>
<td><input name="setting[announce_domain]"  type="text" size="30" value="<?php echo $announce_domain;?>"/><?php tips('例如 http://announce.destoon.com/<br/>请将此域名绑定至网站announce目录');?></td>
</tr>
<tr>
<td class="tl">公告是否生成HTML</td>
<td>
<input type="radio" name="setting[announce_html]" value="1"  <?php if($announce_html) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[announce_html]" value="0"  <?php if(!$announce_html) echo 'checked';?>/> 关闭
</td>
</tr>
</table>
<div class="tt">友情链接</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">友情链接功能</td>
<td>
<input type="radio" name="setting[link_enable]" value="1"  <?php if($link_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[link_enable]" value="0"  <?php if(!$link_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">友情链接绑定域名</td>
<td><input name="setting[link_domain]"  type="text" size="30" value="<?php echo $link_domain;?>"/><?php tips('例如 http://link.destoon.com/<br/>请将此域名绑定至网站link目录');?></td>
</tr>
<tr>
<td class="tl">友情链接在线申请</td>
<td>
<input type="radio" name="setting[link_reg]" value="1"  <?php if($link_reg) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[link_reg]" value="0"  <?php if(!$link_reg) echo 'checked';?>/> 关闭
</td>
</tr>
</table>
<div class="tt">投票设置</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">投票功能</td>
<td>
<input type="radio" name="setting[vote_enable]" value="1"  <?php if($vote_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[vote_enable]" value="0"  <?php if(!$vote_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">投票绑定域名</td>
<td><input name="setting[vote_domain]"  type="text" size="30" value="<?php echo $vote_domain;?>"/><?php tips('例如 http://vote.destoon.com/<br/>请将此域名绑定至网站vote目录');?></td>
</tr>
<tr>
<td class="tl">允许参与投票的会员组</td>
<td><?php echo group_checkbox('setting[vote_group][]', $vote_group);?></td>
</tr>
</table>
<div class="tt">留言设置</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">留言功能</td>
<td>
<input type="radio" name="setting[guestbook_enable]" value="1"  <?php if($guestbook_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[guestbook_enable]" value="0"  <?php if(!$guestbook_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">留言绑定域名</td>
<td><input name="setting[guestbook_domain]"  type="text" size="30" value="<?php echo $guestbook_domain;?>"/><?php tips('例如 http://guestbook.destoon.com/<br/>请将此域名绑定至网站guestbook目录');?></td>
</tr>
</table>
<div class="tt">WAP设置</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">WAP功能</td>
<td>
<input type="radio" name="setting[wap_enable]" value="1"  <?php if($wap_enable) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[wap_enable]" value="0"  <?php if(!$wap_enable) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">WAP绑定域名</td>
<td><input name="setting[wap_domain]"  type="text" size="30" value="<?php echo $wap_domain;?>"/><?php tips('例如 http://wap.destoon.com/<br/>请将此域名绑定至网站wap目录');?></td>
</tr>
<tr>
<td class="tl">WAP字符集</td>
<td>
<input type="radio" name="setting[wap_charset]" value="utf-8"  <?php if($wap_charset == 'utf-8'){ ?>checked <?php } ?>/> UTF-8
<input type="radio" name="setting[wap_charset]" value="unicode"  <?php if($wap_charset == 'unicode'){ ?>checked <?php } ?>/> UNICODE<?php tips('表达同样内容的前提下，UTF-8 编码尺寸较小，但遇有乱码等情况可能导致页面无法浏览；UNICODE 编码尺寸大很多，但对乱码等有良好的容错性。默认为 UNICODE 编码');?>
</td>
</tr>
<tr> 
<td class="tl">WAP列表页显示信息数</td>
<td><input name="setting[wap_pagesize]"  type="text" size="10" value="<?php echo $wap_pagesize;?>"/></td>
</tr>
<tr> 
<td class="tl">WAP内容页最大长度</td>
<td><input name="setting[wap_maxlength]"  type="text" size="10" value="<?php echo $wap_maxlength;?>"/></td>
</tr>
</table>
<div class="tt">RSS设置</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">RSS功能</td>
<td>
<input type="radio" name="setting[feed_enable]"  value="2" <?php if($feed_enable==2){ ?>checked <?php } ?>/> 完全开启
<input type="radio" name="setting[feed_enable]"  value="1" <?php if($feed_enable==1){ ?>checked <?php } ?>/> 部分开启
<input type="radio" name="setting[feed_enable]" value="0"  <?php if(!$feed_enable){ ?>checked <?php } ?>/> 关闭<?php tips('选择完全开启将允许用户自定义条件订阅<br/>选择部分开启仅支持按模型订阅');?>
</td>
</tr>
<tr> 
<td class="tl">RSS绑定域名</td>
<td><input name="setting[feed_domain]"  type="text" size="30" value="<?php echo $feed_domain;?>"/><?php tips('例如 http://feed.destoon.com/<br/>请将此域名绑定至网站feed目录');?></td>
</tr>
<tr> 
<td class="tl">RSS输出数量</td>
<td><input name="setting[feed_pagesize]"  type="text" size="10" value="<?php echo $feed_pagesize;?>"/></td>
</tr>
</table>
<a name="comment"></a>
<div class="tt">评论设置</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">允许评论的模块</td>
<td><?php echo module_checkbox('setting[comment_module][]', $comment_module, '1,2,3');?></td>
</tr>
<tr>
<td class="tl">允许评论的会员组</td>
<td><?php echo group_checkbox('setting[comment_group][]', $comment_group);?></td>
</tr>
<tr>
<td class="tl">允许支持反对的会员组</td>
<td><?php echo group_checkbox('setting[comment_vote_group][]', $comment_group);?></td>
</tr>
<tr>
<td class="tl">审核评论</td>
<td>
<input type="radio" name="setting[comment_check]" value="2"  <?php if($comment_check == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[comment_check]" value="1"  <?php if($comment_check == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[comment_check]" value="0"  <?php if($comment_check == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">发布评论启用验证码</td>
<td>
<input type="radio" name="setting[comment_captcha_add]" value="2"  <?php if($comment_captcha_add == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[comment_captcha_add]" value="1"  <?php if($comment_captcha_add == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[comment_captcha_add]" value="0"  <?php if($comment_captcha_add == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">信息发布者删除评论</td>
<td><?php echo module_checkbox('setting[comment_user_del][]', $comment_user_del, '1,2,3');?></td>
</tr>
<tr>
<td class="tl">管理员前台删除评论</td>
<td>
<input type="radio" name="setting[comment_admin_del]" value="1"  <?php if($comment_admin_del == 1) echo 'checked';?>> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[comment_admin_del]" value="0"  <?php if($comment_admin_del == 0) echo 'checked';?>> 关闭
</td>
</tr>
<tr>
<td class="tl">评论支持反对</td>
<td>
<input type="radio" name="setting[comment_vote]" value="1"  <?php if($comment_vote) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[comment_vote]" value="0"  <?php if(!$comment_vote) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">评论内容字数限制</td>
<td>&nbsp;
<input type="text" size="5" name="setting[comment_min]" value="<?php echo $comment_min;?>"/> 至
<input type="text" size="5" name="setting[comment_max]" value="<?php echo $comment_max;?>"/> 字节
</td>
</tr>
<tr>
<td class="tl">两次评论时间间隔</td>
<td>&nbsp;
<input type="text" size="5" name="setting[comment_time]" value="<?php echo $comment_time;?>"/> 秒
</td>
</tr>
<tr>
<td class="tl">每页显示评论个数</td>
<td>&nbsp;
<input type="text" size="5" name="setting[comment_pagesize]" value="<?php echo $comment_pagesize;?>"/> 条
</td>
</tr>
<tr>
<td class="tl">单会员或IP每日限评</td>
<td>&nbsp;
<input type="text" size="5" name="setting[comment_limit]" value="<?php echo $comment_limit;?>"/> 次
</td>
</tr>
</table>
</div>
<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value=" 重 置 " class="btn"/></div>
</form>
<?php if($tab) { ?><script type="text/javascript">window.onload=function() {Tab(<?php echo $tab;?>);}</script><?php } ?>
</body>
</html>