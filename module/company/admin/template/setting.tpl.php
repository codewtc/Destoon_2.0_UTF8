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
<td class="tl">列表每页默认信息条数</td>
<td><input type="text" size="3" name="setting[pagesize]" value="<?php echo $pagesize;?>"/> 条</td>
</tr>
<tr>
<td class="tl">信息排序方式</td>
<td>
<select name="setting[order]">
<option value="vip desc"<?php if($order == 'vip desc') echo ' selected';?>><?php echo VIP;?>级别</option>
<option value="userid desc"<?php if($order == 'userid desc') echo ' selected';?>>会员ID</option>
</select>
</td>
</tr>
<tr>
<td class="tl">公司主页显示评论</td>
<td>
<input type="radio" name="setting[comment]" value="1"  <?php if($comment){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[comment]" value="0"  <?php if(!$comment){ ?>checked <?php } ?>/> 关闭 </td>
</tr>

<tr>
<td class="tl"><?php echo VIP;?>指数计算规则</td>
<td>
	<table cellpadding="3" cellspacing="1" width="400" bgcolor="#E5E5E5" style="margin:5px;">
	<tr align="center">
	<td>项目</td>
	<td>值</td>
	<td>最大值</td>
	</tr>
	<tr align="center">
	<td>会员组<?php echo VIP;?>指数</td>
	<td>相等</td>
	<td><input type="text" size="2" name="setting[vip_maxgroupvip]" value="<?php echo $vip_maxgroupvip;?>"/></td>
	</tr>
	<tr align="center">
	<td>企业资料认证</td>
	<td><input type="text" size="2" name="setting[vip_cominfo]" value="<?php echo $vip_cominfo;?>"/></td>
	<td><?php echo $vip_cominfo;?></td>
	</tr>
	<tr align="center">
	<td>VIP年份（单位：值/年）</td>
	<td><input type="text" size="2" name="setting[vip_year]" value="<?php echo $vip_year;?>"/></td>
	<td><input type="text" size="2" name="setting[vip_maxyear]" value="<?php echo $vip_maxyear;?>"/></td>
	</tr>
	<tr align="center">
	<td>5张以上资质证书</td>
	<td><input type="text" size="2" name="setting[vip_credit]" value="<?php echo $vip_credit;?>"/></td>
	<td><?php echo $vip_credit;?></td>
	</tr>
	</table>
	<span class="f_gray">&nbsp;&nbsp;所有数值均为整数。<?php echo VIP;?>指数满分10分，故最大值之和应等于10</span>
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
<td class="tl">允许搜索信息</td>
<td><?php echo group_checkbox('setting[group_search][]', $group_search);?></td>
</tr>

<tr>
<td class="tl">允许查看公司主页联系方式</td>
<td><?php echo group_checkbox('setting[group_contact][]', $group_contact);?></td>
</tr>

<tr>
<td class="tl">允许查看公司主页采购列表</td>
<td><?php echo group_checkbox('setting[group_buy][]', $group_buy);?></td>
</tr>

<tr>
<td class="tl">允许在公司主页留言</td>
<td><?php echo group_checkbox('setting[group_message][]', $group_message);?></td>
</tr>

<tr>
<td class="tl">允许在公司主页询盘</td>
<td><?php echo group_checkbox('setting[group_inquiry][]', $group_inquiry);?></td>
</tr>

<tr>
<td class="tl">允许在公司主页报价</td>
<td><?php echo group_checkbox('setting[group_price][]', $group_price);?></td>
</tr>

</table>
</div>

<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value=" 重 置 " class="btn"/></div>
</form>
<?php if($tab) { ?><script type="text/javascript">window.onload=function() {Tab(<?php echo $tab;?>);}</script><?php } ?>
</body>
</html>