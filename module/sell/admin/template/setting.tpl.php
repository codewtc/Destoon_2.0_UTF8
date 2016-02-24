<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
$menus = array (
    array('基本设置'),
    array('SEO设置'),
    array('权限收费'),
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
<div class="tt">基本设置</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">模块首页模板</td>
<td><?php echo tpl_select('index', $module, 'setting[template]', '默认模板', $template);?></td>
</tr>
<tr>
<td class="tl">默认缩略图[宽X高]</td>
<td>
<input type="text" size="3" name="setting[thumb_width]" value="<?php echo $thumb_width;?>"/>
X
<input type="text" size="3" name="setting[thumb_height]" value="<?php echo $thumb_height;?>"/> px
</td>
</tr>
<tr>
<td class="tl">内容页图片最大宽度</td>
<td><input type="text" size="3" name="setting[max_width]" value="<?php echo $max_width;?>"/> px</td>
</tr>
<tr>
<td class="tl">列表每页默认信息条数</td>
<td><input type="text" size="3" name="setting[pagesize]" value="<?php echo $pagesize;?>"/> 条</td>
</tr>
<tr>
<td class="tl">自动截取内容至简介</td>
<td><input type="text" size="3" name="setting[introduce_length]" value="<?php echo $introduce_length;?>"/> 字符</td>
</tr>
<tr>
<td class="tl">信息排序方式</td>
<td>
<select name="setting[order]">
<option value="editdate desc,vip desc,edittime desc"<?php if($order == 'editdate desc,vip desc,edittime desc') echo ' selected';?>>更新日期,<?php echo VIP;?>级别,更新时间</option>
<option value="adddate desc,vip desc,addtime desc"<?php if($order == 'adddate desc,vip desc,addtime desc') echo ' selected';?>>添加日期,<?php echo VIP;?>级别,添加时间</option>
<option value="edittime desc"<?php if($order == 'edittime desc') echo ' selected';?>>更新时间</option>
<option value="addtime desc"<?php if($order == 'addtime desc') echo ' selected';?>>添加时间</option>
<option value="itemid desc"<?php if($order == 'itemid desc') echo ' selected';?>>信息ID</option>
</select>
</td>
</tr>
<tr>
<td class="tl"><?php echo $MOD['name'];?>类别</td>
<td><input type="text" size="80" name="setting[type]" value="<?php echo $type;?>"/><?php tips('不同类别用 | 分隔，设置好后，请勿频繁改动');?></td>
</tr>
<tr>
<td class="tl">询价产品信息</td>
<td><input type="text" size="80" name="setting[inquiry_type]" value="<?php echo $inquiry_type;?>"/></td>
</tr>
<tr>
<td class="tl">询价快速提问</td>
<td>
<textarea name="setting[inquiry_ask]" style="width:98%;height:50px;overflow:visible;"><?php echo $inquiry_ask;?></textarea>
<br/>不同问题用 | 分隔
</td>
</tr>
<tr>
<td class="tl">产品属性参数</td>
<td>
<input type="radio" name="setting[product_option]" value="1"  <?php if($product_option) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[product_option]" value="0"  <?php if(!$product_option) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">下载内容远程图片</td>
<td>
<input type="radio" name="setting[save_remotepic]" value="1"  <?php if($save_remotepic) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[save_remotepic]" value="0"  <?php if(!$save_remotepic) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">清除内容链接</td>
<td>
<input type="radio" name="setting[clear_link]" value="1"  <?php if($clear_link) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[clear_link]" value="0"  <?php if(!$clear_link) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">内容文本存储</td>
<td>
<input type="radio" name="setting[text_data]" value="1"  <?php if($text_data) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[text_data]" value="0"  <?php if(!$text_data) echo 'checked';?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">信息默认过期天数</td>
<td><input type="text" size="3" name="setting[over_days]" value="<?php echo $over_days;?>"/> 天
</td>
</tr>
<tr>
<td class="tl">信息最多可选过期天数</td>
<td><input type="text" size="3" name="setting[max_days]" value="<?php echo $max_days;?>"/> 天
</td>
</tr>
<tr>
<td class="tl">会员必须上传一张产品图</td>
<td>
<input type="radio" name="setting[upload_thumb]" value="1"  <?php if($upload_thumb) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[upload_thumb]" value="0"  <?php if(!$upload_thumb) echo 'checked';?>/> 关闭
<?php tips('此项可在一定程度提高信息质量，防范垃圾信息');?>
</td>
</tr>
</table>
</div>

<div id="Tabs1" style="display:none">
<div class="tt">SEO优化</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">模块首页Title(网页标题)</td>
<td><input name="setting[seo_title_index]" type="text" id="seo_title_index" value="<?php echo $seo_title_index;?>" style="width:98%;"><br/> 
常用变量：<?php echo seo_title('seo_title_index', array('modulename', 'sitename', 'sitetitle', 'page', 'delimiter'));?>
</td>
</tr>
<tr>
<td class="tl">Meta Keywords<br/>(网页关键词)</td>
<td><textarea name="setting[seo_keywords]" cols="60" rows="3" id="seo_keywords"><?php echo $seo_keywords;?></textarea></td>
</tr>
<tr>
<td class="tl">Meta Description<br/>(网页描述)</td>
<td><textarea name="setting[seo_description]" cols="60" rows="3" id="seo_description"><?php echo $seo_description;?></textarea></td>
</tr>
<tr>
<td class="tl">列表页Title(网页标题)</td>
<td><input name="setting[seo_title_list]" type="text" id="seo_title_list" value="<?php echo $seo_title_list;?>" style="width:98%;"><br/> 
<?php echo seo_title('seo_title_list', array('catname', 'cattitle', 'modulename', 'sitename', 'sitetitle', 'page', 'delimiter'));?>
</td>
</tr>
<tr>
<td class="tl">内容页Title(网页标题)</td>
<td><input name="setting[seo_title_show]" type="text" id="seo_title_show" value="<?php echo $seo_title_show;?>" style="width:98%;"><br/> 
<?php echo seo_title('seo_title_show', array('showtitle', 'catname', 'cattitle', 'modulename', 'sitename', 'sitetitle', 'delimiter'));?>
</td>
</tr>
<tr>
<td class="tl">搜索页Title(网页标题)</td>
<td><input name="setting[seo_title_search]" type="text" id="seo_title_search" value="<?php echo $seo_title_search;?>" style="width:98%;"><br/> 
<?php echo seo_title('seo_title_search', array('kw', 'areaname', 'catname', 'cattitle', 'modulename', 'sitename', 'sitetitle', 'page', 'delimiter'));?>
</td>
</tr>
<tr>
<td class="tl">列表页是否生成html</td>
<td>
<input type="radio" name="setting[list_html]" value="1"  <?php if($list_html){ ?>checked <?php } ?> onclick="$('list_html').style.display='';$('list_php').style.display='none';"/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[list_html]" value="0"  <?php if(!$list_html){ ?>checked <?php } ?> onclick="$('list_html').style.display='none';$('list_php').style.display='';"/> 否
</td>
</tr>
<tbody id="list_html" style="display:<?php echo $list_html ? '' : 'none'; ?>">
<tr>
<td class="tl">HTML列表页文件名前缀</td>
<td><input name="setting[htm_list_prefix]" type="text" id="htm_list_prefix" value="<?php echo $htm_list_prefix;?>" size="10"></td>
</tr>
<tr>
<td class="tl">HTML列表页地址规则</td>
<td><?php echo url_select('setting[htm_list_urlid]', 'htm', 'list', $htm_list_urlid);?><?php tips('提示:规则列表可在./include/url.inc.php文件里自定义');?></td>
</tr>
</tbody>
<tr id="list_php" style="display:<?php echo $list_html ? 'none' : ''; ?>">
<td class="tl">PHP列表页地址规则</td>
<td><?php echo url_select('setting[php_list_urlid]', 'php', 'list', $php_list_urlid);?></td>
</tr>
<tr>
<td class="tl">内容页是否生成html</td>
<td>
<input type="radio" name="setting[show_html]" value="1"  <?php if($show_html){ ?>checked <?php } ?> onclick="$('show_html').style.display='';$('show_php').style.display='none';"/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[show_html]" value="0"  <?php if(!$show_html){ ?>checked <?php } ?> onclick="$('show_html').style.display='none';$('show_php').style.display='';"/> 否
</td>
</tr>
<tbody id="show_html" style="display:<?php echo $show_html ? '' : 'none'; ?>">
<tr>
<td class="tl">HTML内容页文件名前缀</td>
<td><input name="setting[htm_item_prefix]" type="text" id="htm_item_prefix" value="<?php echo $htm_item_prefix;?>" size="10"></td>
</tr>
<tr>
<td class="tl">HTML内容页地址规则</td>
<td><?php echo url_select('setting[htm_item_urlid]', 'htm', 'item', $htm_item_urlid);?></td>
</tr>
</tbody>
<tr id="show_php" style="display:<?php echo $show_html ? 'none' : ''; ?>">
<td class="tl">PHP内容页地址规则</td>
<td><?php echo url_select('setting[php_item_urlid]', 'php', 'item', $php_item_urlid);?></td>
</tr>
<tr>
<td class="tl">更新信息地址</td>
<td>
<input type="radio" name="update_url" value="1"/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="update_url" value="0" checked/> 否 <?php tips('如果更改了地址规则或生成方式，则可能需要重新更新内容页地址和重新生成模块相关网页');?>
</td>
</tr>
</table>
</div>

<div id="Tabs2" style="display:none">
<div class="tt">权限收费</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">允许浏览模块首页</td>
<td><?php echo group_checkbox('setting[group_index][]', $group_index);?></td>
</tr>
<tr>
<td class="tl">允许浏览分类列表</td>
<td><?php echo group_checkbox('setting[group_list][]', $group_list);?></td>
</tr>
<tr>
<td class="tl">允许浏览信息内容</td>
<td><?php echo group_checkbox('setting[group_show][]', $group_show);?></td>
</tr>
<tr>
<td class="tl">允许浏览联系方式</td>
<td><?php echo group_checkbox('setting[group_contact][]', $group_contact);?></td>
</tr>
<tr>
<td class="tl">允许搜索信息</td>
<td><?php echo group_checkbox('setting[group_search][]', $group_search);?></td>
</tr>
<tr>
<td class="tl">允许对比信息</td>
<td><?php echo group_checkbox('setting[group_compare][]', $group_compare);?></td>
</tr>
<tr>
<td class="tl">询价启用验证码</td>
<td>
<input type="radio" name="setting[captcha_inquiry]" value="2"  <?php if($captcha_inquiry == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_inquiry]" value="1"  <?php if($captcha_inquiry == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_inquiry]" value="0"  <?php if($captcha_inquiry == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">询价启用验问题</td>
<td>
<input type="radio" name="setting[question_inquiry]" value="2"  <?php if($question_inquiry == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[question_inquiry]" value="1"  <?php if($question_inquiry == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[question_inquiry]" value="0"  <?php if($question_inquiry == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">审核发布信息</td>
<td>
<input type="radio" name="setting[check_add]" value="2"  <?php if($check_add == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[check_add]" value="1"  <?php if($check_add == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[check_add]" value="0"  <?php if($check_add == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">发布信息启用验证码</td>
<td>
<input type="radio" name="setting[captcha_add]" value="2"  <?php if($captcha_add == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_add]" value="1"  <?php if($captcha_add == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_add]" value="0"  <?php if($captcha_add == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">发布信息启用验问题</td>
<td>
<input type="radio" name="setting[question_add]" value="2"  <?php if($question_add == 2) echo 'checked';?>> 继承会员组设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[question_add]" value="1"  <?php if($question_add == 1) echo 'checked';?>> 全部启用&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[question_add]" value="0"  <?php if($question_add == 0) echo 'checked';?>> 全部关闭
</td>
</tr>
<tr>
<td class="tl">发布信息收费</td>
<td><input type="text" size="5" name="setting[fee_add]" value="<?php echo $fee_add;?>"/> 元/条</td>
</tr>
<tr>
<td class="tl">查看联系方式收费</td>
<td><input type="text" size="5" name="setting[fee_view]" value="<?php echo $fee_view;?>"/> 元/条</td>
</tr>
</table>
</div>

<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value=" 重 置 " class="btn"/></div>
</form>
<?php if($tab) { ?><script type="text/javascript">window.onload=function() {Tab(<?php echo $tab;?>);}</script><?php } ?>
</body>
</html>