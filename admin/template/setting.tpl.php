<?php
defined('IN_DESTOON') or exit('Access Denied');
include tpl('header');
$menus = array (
    array('基本设置'),
    array('SEO优化'),
    array('服务器优化'),
    array('安全中心'),
    array('图片处理'),
    array('邮件发送'),
    array('页面细节'),
);
show_menu($menus);
?>
<form method="post" action="?">
<input type="hidden" name="file" value="<?php echo $file;?>"/>
<input type="hidden" name="tab" id="tab" value="<?php echo $tab;?>"/>
<div id="Tabs0" style="display:">
<div class="tt">基本设置</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">网站名称</td>
<td><input name="setting[sitename]" type="text" value="<?php echo $sitename;?>" size="40"/></td>
</tr>
<tr>
<td class="tl">网站地址</td>
<td><input name="config[url]" type="text" value="<?php echo $url;?>" size="40"/><?php tips('请添写完整URL地址,例如http://www.destoon.com/<br/>注意以 / 结尾');?></td>
</tr>
<tr>
<td class="tl">绝对地址</td>
<td>
<input type="radio" name="config[absurl]" value="1"  <?php if($absurl){ ?>checked <?php } ?>/> 启用
<input type="radio" name="config[absurl]" value="0"  <?php if(!$absurl){ ?>checked <?php } ?>/> 关闭<?php tips('如有任一模块绑定二级域名时必须启用绝对地址');?>
</td>
</tr>
<tr>
<td class="tl">版权信息</td>
<td><textarea name="setting[copyright]" id="copyright" style="width:500px;height:50px;"><?php echo $copyright;?></textarea><br/>支持HTML语法，常用代码：版权&copy; &amp;copy; 空格 &amp;nbsp; 换行  &lt;br/&gt;
</td> 
</tr>
<tr>
<td class="tl">客服电话</td>
<td><input name="setting[telephone]" type="text" value="<?php echo $telephone;?>" size="20"/></td>
</tr>
<tr>
<td class="tl">ICP备案序号</td>
<td><input name="setting[icpno]" type="text" value="<?php echo $icpno;?>" size="20"/></td>
</tr>

<tr>
<td class="tl">网站默认风格</td>
<td>
<?php
$select = '';
$dirs = glob(DT_ROOT.'/skin/*');
if(is_array($dirs))	{
	foreach($dirs as $v) {
		if(is_file($v)) continue;
		$v = basename($v);
		$selected = ($CFG['skin'] && $v == $CFG['skin']) ? 'selected' : '';
		$select .= "<option value='".$v."' ".$selected.">".$v."</option>";
	}
}
$select = '<select name="config[skin]">'.$select.'</select>';
echo $select;
tips('位于./skin/目录,一个目录即为一套风格');
?>
</td> 
</tr>
<tr>
<td class="tl">网站默认模板</td>
<td>
<?php
$select = '';
$dirs = glob(DT_ROOT.'/template/*');
if(is_array($dirs))	{
	foreach($dirs as $v) {
		if(is_file($v)) continue;
		$v = basename($v);
		$selected = ($CFG['template'] && $v == $CFG['template']) ? 'selected' : '';
		$select .= "<option value='".$v."' ".$selected.">".$v."</option>";
	}
}
$select = '<select name="config[template]">'.$select.'</select>';
echo $select;
tips('位于./template/目录,一个目录即为一套模板');
?>
</td> 
</tr>
<tr>
<td class="tl">网站首页默认模板</td>
<td><?php echo tpl_select('index', '', 'setting[template]', '默认模板', $template);?></td> 
</tr>

<tr>
<td class="tl">URL Rewrite</td>
<td>
<input type="radio" name="setting[rewrite]" value="1"  <?php if($rewrite){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[rewrite]" value="0"  <?php if(!$rewrite){ ?>checked <?php } ?>/> 关闭 <?php tips('请确认服务器已做过规则配置，否则请勿开启<br/>ReWrite规则见帮助文档<br/>请点击下面的地址，如果可以正常显示，说明规则配置成功<br/><a href=index-htm-url-rule.html target=_blank>index-htm-url-rule.html</a>');?>
</td>
</tr>

<tr>
<td class="tl">VIP会员名称</td>
<td><input name="config[com_vip]" type="text" value="<?php echo $com_vip;?>" size="10"/></td>
</tr>

<tr>
<td class="tl">公司主页绑定二级域名</td>
<td><input name="config[com_domain]" type="text" value="<?php echo $com_domain;?>" size="30"/> <?php tips('例如可填写 .destoon.com 同时需要将域名泛解析 *.destoon.com 指向服务器IP，并且在服务器端绑定泛域名至 网站根目录 或者 网站根目录/company 目录');?></td>
</tr>

<tr>
<td class="tl">泛解析绑定目录</td>
<td>
<select name="config[com_dir]">
<option value="0"<?php echo $com_dir == 0 ? ' selected' : '';?>>根目录</option>
<option value="1"<?php echo $com_dir == 1 ? ' selected' : '';?>>company目录</option>
</select>&nbsp;
<?php tips('如果服务器支持，推荐绑定至company目录<br/>如果绑定根目录，请不要设置网站首页生成html');?>
</td>
</tr>

<tr>
<td class="tl">会员顶级域名ReWrite</td>
<td>
<input type="radio" name="config[com_rewrite]" value="1"  <?php if($com_rewrite){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;
<input type="radio" name="config[com_rewrite]" value="0"  <?php if(!$com_rewrite){ ?>checked <?php } ?>/> 关闭 <?php tips('部分服务器可能无法开启会员绑定的顶级域名Rewrite，如果无法开启，可在此关闭，以免出现打不开页面的情况，此项仅针对会员顶级域名，不影响其他页面Rewrite');?>
</td>
</tr>

<tr>
<td class="tl">网站状态</td>
<td>
<input type="radio" name="setting[close]" value="0"  <?php if(!$close){ ?>checked <?php } ?> onclick="Dh('dclose');"/> 开启&nbsp;&nbsp;
<input type="radio" name="setting[close]" value="1"  <?php if($close){ ?>checked <?php } ?> onclick="Ds('dclose');"/> 关闭
</td>
</tr>
<tr id="dclose" style="display:<?php if(!$close) echo 'none';?>">
<td class="tl">关闭原因</td>
<td><textarea name="setting[close_reason]" id="close_reason" style="width:500px;height:50px;overflow:visible;"><?php echo $close_reason;?></textarea><br/>支持HTML语法，网站关闭不影响后台管理
</td> 
</tr>

</table>
</div>

<div id="Tabs1" style="display:none">
<div class="tt">SEO优化</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">标题分隔符</td>
<td><input name="setting[seo_delimiter]" type="text" value="<?php echo $seo_delimiter;?>" size="10"/></td>
</tr>
<tr>
<td class="tl">Title(网站标题)</td>
<td><input name="setting[seo_title]" type="text" value="<?php echo $seo_title;?>" size="61"><?php tips('针对搜索引擎设置的网页标题');?></td>
</tr>
<tr>
<td class="tl">Meta Keywords<br/>(网页关键词)</td>
<td><textarea name="setting[seo_keywords]" cols="60" rows="3"><?php echo $seo_keywords;?></textarea><?php tips('针对搜索引擎设置的关键词');?></td>
</tr>
<tr>
<td class="tl">Meta Description<br/>(网页描述)</td>
<td><textarea name="setting[seo_description]" cols="60" rows="3"><?php echo $seo_description;?></textarea><?php tips('针对搜索引擎设置的网页描述');?></td>
</tr>
<tr>
<td class="tl">目录首页文件名</td>
<td><input name="setting[index]" type="text" value="<?php echo $index;?>" size="8"/>
</td>
</tr>
<tr>
<td class="tl">生成文件扩展名</td>
<td>
<select name="setting[file_ext]">
<option value="html"<?php if($file_ext == 'html') echo ' selected';?>>.html</option>
<option value="htm"<?php if($file_ext == 'htm') echo ' selected';?>>.htm</option>
<option value="shtm"<?php if($file_ext == 'shtm') echo ' selected';?>>.shtm</option>
<option value="shtml"<?php if($file_ext == 'shtml') echo ' selected';?>>.shtml</option>
</select>
</td>
</tr>
<tr>
<td class="tl">网站首页是否生成html</td>
<td>
<input type="radio" name="setting[index_html]" value="1"  <?php if($index_html){ ?>checked <?php } ?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[index_html]" value="0"  <?php if(!$index_html){ ?>checked <?php } ?>/> 否
</td>
</tr>
</table>
</div>

<div id="Tabs2" style="display:none">
<div class="tt">服务器优化</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">首页自动更新频率</td>
<td>
<input name="setting[task_index]" type="text" value="<?php echo $task_index;?>" size="5"/> 秒 <?php tips('仅对生成的静态网页有效');?>
</td>
</tr>
<tr>
<td class="tl">列表页自动更新频率</td>
<td>
<input name="setting[task_list]" type="text" value="<?php echo $task_list;?>" size="5"/> 秒 <?php tips('仅对生成的静态网页有效');?>
</td>
</tr>
<tr>
<td class="tl">内容页自动更新频率</td>
<td>
<input name="setting[task_item]" type="text" value="<?php echo $task_item;?>" size="5"/> 秒 <?php tips('仅对生成的静态网页有效');?>
</td>
</tr>
<tr>
<td class="tl">TAG(标签)缓存更新周期</td>
<td><input type="text" name="config[tag_expires]" value="<?php echo $tag_expires;?>" size="5"/> 秒<?php tips('此项可明显减轻标签数据调用对服务器的压力');?></td>
</tr>
<tr>
<td class="tl">SQL查询缓存更新周期</td>
<td><input type="text" name="config[db_expires]" value="<?php echo $db_expires;?>" size="5"/> 秒<?php tips('此项可明显减轻搜索等大量耗费资源的操作对服务器的压力');?></td>
</tr>
<tr>
<td class="tl">PHP页面缓存</td>
<td><input type="radio" name="config[cache_page]" value="1"  <?php if($cache_page){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="config[cache_page]" value="0"  <?php if(!$cache_page){ ?>checked <?php } ?>/> 关闭 <?php tips('一般情况无需开启，SQL和TAG缓存会缓冲掉大部分的服务器压力。如果网站不生成静态页面且服务器负载超标，请考虑开启');?></td>
</tr>
<tr>
<td class="tl">PHP缓存更新周期</td>
<td><input type="text" name="config[page_expires]" value="<?php echo $page_expires;?>" size="5"/> 秒
</td>
</tr>
<tr title="将页面内容以gzip压缩后传输，可以加快传输速度，需PHP 4.0.4以上且支持Zlib模块才能使用">
<td class="tl">页面Gzip压缩</td>
<td>
<input type="radio" name="setting[gzip_enable]" value="1" <?php if($gzip_enable){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[gzip_enable]" value="0" <?php if(!$gzip_enable){ ?>checked <?php } ?>/> 关闭 <?php tips(function_exists('ob_gzhandler') ? '当前服务器支持Gzip，建议开启' : '当前服务器不支持Gzip，请关闭');?>
</td>
</tr>
<tr>
<td class="tl">模板缓存自动更新</td>
<td>
<input type="radio" name="config[template_refresh]" value="1" <?php if($template_refresh){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="config[template_refresh]" value="0" <?php if(!$template_refresh){ ?>checked <?php } ?>/> 关闭 <?php tips('如果网站模板无需修改，建议您关闭此功能');?></td>
</tr>
<tr>
<td class="tl">去除模板换行标记</td>
<td>
<input type="radio" name="config[template_trim]" value="1" <?php if($template_trim){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="config[template_trim]" value="0" <?php if(!$template_trim){ ?>checked <?php } ?>/> 关闭 <?php tips('去除换行等多余标记，在一定程度上可以压缩网页体积');?></td>
</tr>
<tr>
<td class="tl">智能搜索提示</td>
<td>
<input type="radio" name="setting[search_tips]" value="1" <?php if($search_tips){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[search_tips]" value="0" <?php if(!$search_tips){ ?>checked <?php } ?>/> 关闭</td>
</tr>
<tr>
<td class="tl">搜索关键词自动记录</td>
<td>
<input type="radio" name="setting[search_kw]" value="1" <?php if($search_kw){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[search_kw]" value="0" <?php if(!$search_kw){ ?>checked <?php } ?>/> 关闭</td>
</tr>
<tr>
<td class="tl">人工审核关键词记录</td>
<td>
<input type="radio" name="setting[search_check_kw]" value="1" <?php if($search_check_kw){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[search_check_kw]" value="0" <?php if(!$search_check_kw){ ?>checked <?php } ?>/> 关闭</td>
</tr>
<tr>
<td class="tl">搜索关键词长度限制</td>
<td><input type="text" size="3" name="setting[min_kw]" value="<?php echo $min_kw;?>"/>
至
<input type="text" size="3" name="setting[max_kw]" value="<?php echo $max_kw;?>"/>
字符<?php tips('一个汉字的长度为2个字符，建议设置为3-30个字符之间');?></td>
</tr>
<tr>
<td class="tl">两次搜索时间间隔</td>
<td><input type="text" size="3" name="setting[search_limit]" value="<?php echo $search_limit;?>"/> 秒<?php tips('填0为不限制');?></td>
</tr>
<tr>
<td class="tl">列表每页默认信息条数</td>
<td><input name="setting[pagesize]" type="text" value="<?php echo $pagesize;?>" size="5"/> 条</td>
</tr>
<tr>
<td class="tl">远程FTP文件上传</td>
<td>
<input type="radio" name="setting[ftp_remote]" value="1"  <?php if($ftp_remote){ ?>checked <?php } ?> onclick="Ds('ftp');"/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[ftp_remote]" value="0"  <?php if(!$ftp_remote){ ?>checked <?php } ?> onclick="Dh('ftp');"/> 关闭<?php tips('开启远程文件上传后，所有上传文件将被FTP移动到远程服务器上，可以极大的缓解主站流量压力');?></td>
</tr>
<tbody id="ftp" style="display:<?php echo $ftp_remote ? '' : 'none';?>">
<?php if(!extension_loaded("ftp")){ ?>
<tr>
<td class="tl">系统提示</td>
<td class="f_red">当前PHP环境不支持FTP功能</td>
</tr>
<?php }?>
<tr> 
<td class="tl">FTP主机</td>
<td><input name="setting[ftp_host]" id="ftp_host" type="text" size="30" value="<?php echo $ftp_host;?>"/><?php tips('可以是 FTP 服务器的 IP 地址或域名');?></td>
</tr>
<tr> 
<td class="tl">FTP端口</td>
<td><input name="setting[ftp_port]" id="ftp_port" type="text" size="30" value="<?php echo $ftp_port;?>"/><?php tips('默认为 21');?></td>
</tr>
<tr> 
<td class="tl">FTP帐号</td>
<td><input name="setting[ftp_user]" id="ftp_user" type="text" size="30" value="<?php echo $ftp_user;?>"/><?php tips('该帐号必需具有以下权限：读取文件、写入文件、删除文件、创建目录、子目录继承');?></td>
</tr>
<tr> 
<td class="tl">FTP密码<br></td>
<td><input name="setting[ftp_pass]" id="ftp_pass" type="password" size="30" value="<?php echo $ftp_pass;?>"/></td>
</tr>
<tr>
<td class="tl">SSL连接</td>
<td>
<input type="radio" name="setting[ftp_ssl]" value="1"  <?php if($ftp_ssl){ ?>checked <?php } ?> id="ftp_ssl"/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[ftp_ssl]" value="0"  <?php if(!$ftp_ssl){ ?>checked <?php } ?>/> 否<?php tips('FTP 服务器必需开启了 SSL 才可以启用');?></td>
</tr>
<tr>
<td class="tl">被动模式(PASV)连接</td>
<td>
<input type="radio" name="setting[ftp_pasv]" value="1"  <?php if($ftp_pasv){ ?>checked <?php } ?> id="ftp_pasv"/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[ftp_pasv]" value="0"  <?php if(!$ftp_pasv){ ?>checked <?php } ?>/> 否<?php tips('一般情况下非被动模式即可，如果存在上传失败问题，可尝试打开此设置');?>
</td>
</tr>
<tr> 
<td class="tl">远程存储目录</td>
<td><input name="setting[ftp_path]" id="ftp_path" type="text" size="60" value="<?php echo $ftp_path;?>"/><?php tips('例如 /wwwroot/img/ 或者 /httpdocs/img/<br/>具体以实际情况为准');?></td>
</tr>
<tr>
<td class="tl">远程访问URL</td>
<td><input name="setting[remote_url]" type="text" value="<?php echo $remote_url;?>" size="60"/><?php tips('例如 http://pic.destoon.com/，注意以 / 结尾');?></td>
</tr>
<tr> 
<td class="tl">测试FTP连接</td>
<td><input name="testftp" type="button" class="btn" value="点击测试" onclick="TestFTP();"></td>
</tr>
</table>
<script type="text/javascript">
function TestFTP() {
	var fssl = $('ftp_ssl').checked ? 1 : 0;
	var fpasv = $('ftp_pasv').checked ? 1 : 0;
	var url = '?file=setting&action=ftp&ftp_host='+$('ftp_host').value+'&ftp_port='+$('ftp_port').value+'&ftp_user='+$('ftp_user').value+'&ftp_pass='+$('ftp_pass').value+'&ftp_path='+$('ftp_path').value+'&ftp_ssl='+fssl+'&ftp_pasv='+fpasv;
	Diframe(url, 0, 0 , 1);
}
</script>
</div>

<div id="Tabs3" style="display:none">
<div class="tt">安全中心</div>
<table cellpadding="2" cellspacing="1" class="tb">
<?php if(strpos(get_env('self'), '/admin.php') !== false) { ?>
<tr>
<td class="tl">网站后台地址</td>
<td class="f_red">当前后台管理文件为默认的admin.php(位于网站根目录),建议您修改此文件名,通过访问新文件名来管理网站</td>
</tr>
<?php } ?>
<tr>
<td class="tl">后台登录验证码 </td>
<td>
<input type="radio" name="setting[captcha_admin]" value="1"  <?php if($captcha_admin){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[captcha_admin]" value="0"  <?php if(!$captcha_admin){ ?>checked <?php } ?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">加密传输密码</td>
<td>
<input type="radio" name="setting[md5_pass]" value="1"  <?php if($md5_pass){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[md5_pass]" value="0"  <?php if(!$md5_pass){ ?>checked <?php } ?>/> 关闭
</td>
</tr>
<tr>
<td class="tl">后台操作日志 </td>
<td>
<input type="radio" name="setting[admin_log]" value="0" <?php if(!$admin_log){ ?>checked <?php } ?>/> 关闭&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[admin_log]" value="1" <?php if($admin_log == 1){ ?>checked <?php } ?>/> 部分开启<?php tips('仅记录添加、修改、删除、设置等关键操作');?>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[admin_log]" value="2" <?php if($admin_log == 2){ ?>checked <?php } ?>/> 完全开启<?php tips('记录后台所有操作');?>
</td>
</tr>
<tr>
<td class="tl">会员登录日志 </td>
<td>
<input type="radio" name="setting[login_log]" value="0" <?php if(!$login_log){ ?>checked <?php } ?>/> 关闭&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[login_log]" value="1" <?php if($login_log == 1){ ?>checked <?php } ?>/> 后台开启<?php tips('仅记录网站后台登录日志');?>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[login_log]" value="2" <?php if($login_log == 2){ ?>checked <?php } ?>/> 完全开启<?php tips('记录所有登录日志');?>
</td>
</tr>
<tr>
<td class="tl">同一帐号同时异地登录</td>
<td>
<input type="radio" name="setting[ip_login]" value="0"  <?php if(!$ip_login){ ?>checked <?php } ?>/> 允许&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[ip_login]" value="1"  <?php if($ip_login == 1){ ?>checked <?php } ?>/> 仅限前台<?php tips('仅限前台允许同一帐号同时异地登录<br/>后台将不允许同一帐号同时异地登录');?>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[ip_login]" value="2"  <?php if($ip_login == 2){ ?>checked <?php } ?>/> 完全禁止<?php tips('完全禁止同一帐号同时异地登录');?>
</td>
</tr>
<tr>
<td class="tl">允许上传的文件类型</td>
<td><input name="setting[uploadtype]" type="text" value="<?php echo $uploadtype;?>" size="60"/> <?php tips('用|号隔开文件后缀');?></td>
</tr>
<tr>
<td class="tl">允许上传大小限制</td>
<td><input name="setting[uploadsize]" type="text" value="<?php echo $uploadsize;?>" size="10"/> Kb (1024Kb=1M)</td>
</tr>
<tr>
<td class="tl">文件保存目录</td>
<td>
<select name="setting[uploaddir]">
<option value="Ym/d" <?php if($uploaddir == 'Ym/d') echo 'selected';?>>年月/日</option>
<option value="Ym/d/H" <?php if($uploaddir == 'Ym/d/H') echo 'selected';?>>年月/日/时</option>
<option value="Ym/d/H/i" <?php if($uploaddir == 'Ym/d/H/i') echo 'selected';?>>年月/日/时/分</option>
<option value="Ym/d/H/i/s" <?php if($uploaddir == 'Ym/d/H/i/s') echo 'selected';?>>年月/日/时/分/秒</option>
</select>
 <?php tips('上传文件保存于 file/upload 目录');?>
</td>
</tr>
<tr>
<td class="tl">网站安全密钥</td>
<td><input name="config[authkey]" type="text" value="<?php echo $authkey;?>" size="30"/><?php tips('可用英文字母、数字、特殊符号，建议定期修改');?></td>
</tr>


<tr>
<td class="tl">验证数据来源</td>
<td>
<input type="radio" name="setting[check_referer]" value="1"  <?php if($check_referer){ ?>checked <?php } ?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[check_referer]" value="0"  <?php if(!$check_referer){ ?>checked <?php } ?>/> 关闭
</td>
</tr>

<tr>
<td class="tl">信任的域名</td>
<td><input name="setting[safe_domain]" type="text" value="<?php echo $safe_domain;?>" size="60"/><?php tips('不填写则默认为当前域名<br/>多个域名请用|分开 例如destoon.com|destoon.cn');?></td>
</tr>

<tr>
<td class="tl">Cookie作用域</td>
<td><input name="config[cookie_domain]" type="text" value="<?php echo $cookie_domain;?>" size="20"/><?php tips('例如要保证顶级域名destoon.com所有二级域名均可正常登录注销，则填写.destoon.com(注意顶级域名前加.)');?></td>
</tr>
<tr>
<td class="tl">Cookie作用路径</td>
<td><input name="config[cookie_path]" type="text" value="<?php echo $cookie_path;?>" size="10"/></td>
</tr>
<tr>
<td class="tl">用户注册文件名</td>
<td><input name="setting[file_register]" type="text" value="<?php echo $file_register;?>" size="20"/><input name="old_file_register" type="hidden" value="<?php echo $file_register;?>"/> <a href="<?php echo $MODULE[2]['linkurl'];?><?php echo $file_register;?>" target="_blank">访问</a><?php tips('请保证对应文件可写，提交后系统会尝试修改，如果系统修改失败，请通过ftp修改<br/>文件名建议使用数字和字母，文件保存于 member/ 目录');?></td>
</tr>
<tr>
<td class="tl">用户登录文件名</td>
<td><input name="setting[file_login]" type="text" value="<?php echo $file_login;?>" size="20"/><input name="old_file_login" type="hidden" value="<?php echo $file_login;?>"/> <a href="<?php echo $MODULE[2]['linkurl'];?><?php echo $file_login;?>" target="_blank">访问</a></td>
</tr>
<tr>
<td class="tl">用户发布信息文件名</td>
<td><input name="setting[file_my]" type="text" value="<?php echo $file_my;?>" size="20"/><input name="old_file_my" type="hidden" value="<?php echo $file_my;?>"/> <a href="<?php echo $MODULE[2]['linkurl'];?><?php echo $file_my;?>" target="_blank">访问</a></td>
</tr>
</table>
</div>

<div id="Tabs4" style="display:none">
<div class="tt">图片处理</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">水印类型</td>
<td>
<input type="radio" name="setting[water_type]" value="0"  <?php if($water_type==0){ ?>checked <?php } ?> onclick="wt(0);"> 禁用&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[water_type]" value="1"  <?php if($water_type==1){ ?>checked <?php } ?> onclick="wt(1);"> 文字水印&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[water_type]" value="2"  <?php if($water_type==2){ ?>checked <?php } ?> onclick="wt(2);"> 图片水印
</td>
</tr>
<tbody id="w_txt" style="display:;">
<tr>
<td class="tl">水印文字</td>
<td><input name="setting[water_text]" type="text" id="water_text" value="<?php echo $water_text;?>" size="30" style="color:<?php echo $water_fontcolor;?>;font-size:<?php echo $water_fontsize;?>px;"></td>
</tr>
<tr>
<td class="tl">文字字体</td>
<td><input name="setting[water_font]" type="text" value="<?php echo $water_font;?>" size="30"> <?php if($water_font && !is_file(DT_ROOT."/file/".$water_font)){ ?><span class="f_red">字体不存在,请上传字体到./file/目录</span><?php } ?></td>
</tr>
<tr>
<td class="tl">文字大小</td>
<td><input name="setting[water_fontsize]" type="text" value="<?php echo $water_fontsize;?>" size="8" style="font-size:<?php echo $water_fontsize;?>px;" onblur="this.style.fontSize=this.value+'px';$('water_text').style.fontSize=this.value+'px';"> px</td>
</tr>
<tr>
<td class="tl">文字颜色</td>
<td><input name="setting[water_fontcolor]" type="text" value="<?php echo $water_fontcolor;?>" size="8" style="color:<?php echo $water_fontcolor;?>" onblur="this.style.color=this.value;$('water_text').style.color=this.value;"></td>
</tr>
</tbody>
<tbody id="w_img" style="display:;">
<tr>
<td class="tl">水印图片</td>
<td><input name="setting[water_image]" type="text" value="<?php echo $water_image;?>" size="40">
</td>
</tr>
<tr>
<td class="tl">水印透明度</td>
<td><input name="setting[water_transition]" type="text" value="<?php echo $water_transition;?>" size="5"><?php tips('如果水印图为gif格式，请设置范围为 1~100 的整数,数值越小水印图片越透明。PNG 类型水印本身具有真彩透明效果，无须此设置');?></td>
</tr>
<tr>
<td class="tl">JPEG 水印质量</td>
<td><input name="setting[water_jpeg_quality]" type="text" value="<?php echo $water_jpeg_quality;?>" size="5"><?php tips('范围为 0~100 的整数,数值越大结果图片效果越好,但尺寸也越大');?></td>
</tr>
</tbody>
<tbody id="w_pos" style="display:;">
<tr>
<td class="tl">水印图片或文字边距</td>
<td><input name="setting[water_margin]" type="text" value="<?php echo $water_margin;?>" size="5"> px <?php tips('水印图片或文字在原图的边距');?>
</td>
</tr>
<tr>
<td class="tl">图片处理条件</td>
<td><input name="setting[water_min_wh]" type="text" value="<?php echo $water_min_wh;?>" size="5"> px <?php tips('图片宽度或者高度小于此值将不做水印处理');?>
</td>
</tr>
<tr>
<td class="tl">水印位置</td>
<td>
	<table cellspacing="1" cellpadding="5" width="150" bgcolor="#DDDDDD">
	<tr align="center" bgcolor="#F1F2F3">
	<td onmouseover="this.style.backgroundColor='#FEB685'" onmouseout="this.style.backgroundColor='#F1F2F3'"> <input type="radio" name="setting[water_pos]" value="1" <?php if($water_pos==1){ ?>checked <?php } ?>/> </td>
	<td onmouseover="this.style.backgroundColor='#FEB685'" onmouseout="this.style.backgroundColor='#F1F2F3'"> <input type="radio" name="setting[water_pos]" value="2" <?php if($water_pos==2){ ?>checked <?php } ?>/></td>
	<td onmouseover="this.style.backgroundColor='#FEB685'" onmouseout="this.style.backgroundColor='#F1F2F3'"> <input type="radio" name="setting[water_pos]" value="3" <?php if($water_pos==3){ ?>checked <?php } ?>/> </td>
	</tr>

	<tr align="center"  bgcolor="#F1F2F3">
	<td onmouseover="this.style.backgroundColor='#FEB685'" onmouseout="this.style.backgroundColor='#F1F2F3'"> <input type="radio" name="setting[water_pos]" value="4" <?php if($water_pos==4){ ?>checked <?php } ?>/> </td>
	<td onmouseover="this.style.backgroundColor='#FEB685'" onmouseout="this.style.backgroundColor='#F1F2F3'"> <input type="radio" name="setting[water_pos]" value="5" <?php if($water_pos==5){ ?>checked <?php } ?>/> </td>
	<td onmouseover="this.style.backgroundColor='#FEB685'" onmouseout="this.style.backgroundColor='#F1F2F3'"> <input type="radio" name="setting[water_pos]" value="6" <?php if($water_pos==6){ ?>checked <?php } ?>/> </td>
	</tr>

	<tr align="center" bgcolor="#F1F2F3">
	<td onmouseover="this.style.backgroundColor='#FEB685'" onmouseout="this.style.backgroundColor='#F1F2F3'"> <input type="radio" name="setting[water_pos]" value="7" <?php if($water_pos==7){ ?>checked <?php } ?>/> </td>
	<td onmouseover="this.style.backgroundColor='#FEB685'" onmouseout="this.style.backgroundColor='#F1F2F3'"> <input type="radio" name="setting[water_pos]" value="8" <?php if($water_pos==8){ ?>checked <?php } ?>/> </td>
	<td onmouseover="this.style.backgroundColor='#FEB685'" onmouseout="this.style.backgroundColor='#F1F2F3'"> <input type="radio" name="setting[water_pos]" value="9" <?php if($water_pos==9){ ?>checked <?php } ?>/> </td>
	</tr>
	<tr align="center" bgcolor="#F1F2F3">
	<td onmouseover="this.style.backgroundColor='#FEB685'" onmouseout="this.style.backgroundColor='#F1F2F3'" colspan="3">随机 <input type="radio" name="setting[water_pos]" value="0" <?php if($water_pos==0){ ?>checked <?php } ?>/></td>
	</tr>
	</table>
</tr>
</tbody>
</table>
</div>

<div id="Tabs5" style="display:none">
<div class="tt">邮件发送</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">发送方式</td>
<td>
<input type="radio" name="setting[mail_type]" value="close" <?php if($mail_type=="close"){ ?>checked <?php } ?> id="mailtype_close"/> <label for="mailtype_close">关闭邮件发送</label><br/>
<input type="radio" name="setting[mail_type]" value="smtp" <?php if($mail_type=="smtp"){ ?>checked <?php } ?> onclick="Ds('dsmtp');Ds('demail');$('l_rn').checked=true;" id="mailtype_smtp"/> <label for="mailtype_smtp">通过SMTP SOCKET 连接 SMTP 服务器发送(支持ESMTP验证)</label><br/>
<input type="radio" name="setting[mail_type]" value="mail"  <?php if($mail_type=="mail"){ ?>checked <?php } ?> onclick="Dh('dsmtp');Dh('demail');$('l_n').checked=true;" id="mailtype_mail"/> <label for="mailtype_mail">通过PHP mail 函数发送(通常为Unix/Linux 主机)</label><br/>
<input type="radio" name="setting[mail_type]" value="psmtp"  <?php if($mail_type=="psmtp"){ ?>checked <?php } ?> onclick="Ds('dsmtp');Dh('demail');$('l_rn').checked=true;" id="mailtype_psmtp"/> <label for="mailtype_psmtp">通过PHP mail 函数SMTP发送(通常为WIN主机)</label>
</td>
</tr>
<tr>
<td class="tl">邮件头的分隔符</td>
<td><input type="radio" name="setting[mail_delimiter]" value="1" <?php if($mail_delimiter==1){ ?>checked <?php } ?> id="l_rn"/> <label for="l_rn">使用 CRLF 作为分隔符(通常为Windows主机)</label><br/>
<input type="radio" name="setting[mail_delimiter]" value="2" <?php if($mail_delimiter==2){ ?>checked <?php } ?> id="l_n"/> <label for="l_n">使用 LF 作为分隔符(通常为Unix/Linux主机)</label><br/>
<input type="radio" name="setting[mail_delimiter]" value="3" <?php if($mail_delimiter==3){ ?>checked <?php } ?> id="l_r"/> <label for="l_r">使用 CR 作为分隔符(通常为Mac主机)</label>
</td>
</tr>
<tbody id="dsmtp" style="display:<?php if($mail_type == "mail") echo 'none';?>">
<tr> 
<td class="tl">SMTP服务器</td>
<td><input name="setting[smtp_host]" id="smtp_host" type="text" size="40" value="<?php echo $smtp_host;?>"/><?php tips('SMTP服务器,例如smtp.xxx.com<br/>提示:目前大部分新申请的免费邮箱并不支持smtp发信');?></td>
</tr>
<tr> 
<td class="tl">SMTP端口</td>
<td><input name="setting[smtp_port]" id="smtp_port" type="text" size="5" value="<?php echo $smtp_port;?>"/></td>
</tr>
</tbody>
<tbody id="demail" style="display:<?php if($mail_type != "smtp") echo 'none';?>">
<tr> 
<td class="tl">SMTP服务器是否验证</td>
<td>
<input type="radio" name="setting[smtp_auth]" value="1"  <?php if($smtp_auth==1){ ?>checked <?php } ?> id="smtp_auth" onclick="Ds('dsmtp_user');Ds('dsmtp_pass');"/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[smtp_auth]" value="0" <?php if($smtp_auth==0){ ?>checked <?php } ?> onclick="Dh('dsmtp_user');Dh('dsmtp_pass');"/> 否
</tr>
<tr id="dsmtp_user" style="display:<?php if(!$smtp_auth) echo 'none';?>">
<td class="tl">邮箱帐号</td>
<td><input name="setting[smtp_user]" id="smtp_user" type="text" size="40" value="<?php echo $smtp_user;?>"/><?php tips('SMTP服务器的用户帐号,一般为邮件地址');?></td>
</tr>
<tr id="dsmtp_pass" style="display:<?php if(!$smtp_auth) echo 'none';?>"> 
<td class="tl">邮箱密码</td>
<td ><input name="setting[smtp_pass]" id="smtp_pass" type="password" size="40" value="<?php echo $smtp_pass;?>"/></td>
</tr>
</tbody>
<tr> 
<td class="tl">邮件签名</td>
<td><textarea name="setting[mail_sign]" id="mail_sign" cols="60" rows="4"><?php echo $mail_sign;?></textarea><?php tips('支持HTML语法');?></td>
</tr>
<tr> 
<td class="tl">发件人邮箱</td>
<td><input name="setting[mail_sender]" id="mail_sender" type="text" size="40" value="<?php echo $mail_sender;?>"/><?php tips('系统发送的信件将以此邮箱名义发送');?></td>
</tr>
<tr> 
<td class="tl">发件人名称</td>
<td><input name="setting[mail_name]" id="mail_name" type="text" size="40" value="<?php echo $mail_name;?>"/><?php tips('系统发送的信件将显示此名称，不填则显示网站名');?></td>
</tr>
<tr> 
<td class="tl">测试收件人</td>
<td><input name="testemail" type="text" id="testemail" value="" size="30"/> <input type="button" class="btn" value="测试发送" onclick="TestMail();"/><?php tips('请在左侧输入一个接收测试邮件的邮件地址');?></td>
</tr>
<tr> 
<td class="tl">自动转发未读站内信</td>
<td>
<input type="radio" name="setting[message_email]" value="1"  <?php if($message_email) echo 'checked';?>/> 开启&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[message_email]" value="0"  <?php if(!$message_email) echo 'checked';?>/> 关闭
</td>
</tr>
<tr> 
<td class="tl">自动转发会员组</td>
<td><?php echo group_checkbox('setting[message_group][]', $message_group, '1,2,3,4');?></td>
</tr>
<tr> 
<td class="tl">未读时间限制</td>
<td><input name="setting[message_time]" type="text" size="5" value="<?php echo $message_time;?>"/> 分钟
<?php tips('未读时间超过此时间时开始转发');?></td>
</tr>
<tr> 
<td class="tl">转发信件类型</td>
<td>
<?php
$NAME = array('普通', '询价', '报价', '留言', '信使');
$message_type = explode(',', $message_type);
foreach($NAME as $k=>$v) {
	$checked = in_array($k, $message_type) ? ' checked' : '';
	echo '<input type="checkbox" name="setting[message_type][]" value="'.$k.'"'.$checked.'/> '.$v.'&nbsp;';
}
?>
</td>
</tr>
</table>
</div>

<div id="Tabs6" style="display:none">
<div class="tt">页面细节</div>
<table cellpadding="2" cellspacing="1" class="tb">
<tr>
<td class="tl">按行业浏览每行分类数</td>
<td><input type="text" name="setting[page_subcat]" value="<?php echo $page_subcat;?>" size="5"/></td>
</tr>

<tr>
<td class="tl">首页行情速递数量</td>
<td><input type="text" name="setting[page_price]" value="<?php echo $page_price;?>" size="5"/></td>
</tr>


<tr>
<td class="tl">首页供求信息数量</td>
<td><input type="text" name="setting[page_trade]" value="<?php echo $page_trade;?>" size="5"/></td>
</tr>

<tr>
<td class="tl">首页产品信息数量</td>
<td><input type="text" name="setting[page_product]" value="<?php echo $page_product;?>" size="5"/></td>
</tr>

<tr>
<td class="tl">优先显示推荐产品</td>
<td><input type="radio" name="setting[page_product_elite]" value="1"  <?php if($page_product_elite){ ?>checked <?php } ?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[page_product_elite]" value="0"  <?php if(!$page_product_elite){ ?>checked <?php } ?>/> 否</td>
</tr>
<tr>
<td class="tl">首页显示行业类别</td>
<td><input type="radio" name="setting[page_catalog]" value="1"  <?php if($page_catalog){ ?>checked <?php } ?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[page_catalog]" value="0"  <?php if(!$page_catalog){ ?>checked <?php } ?>/> 否</td>
</tr>

<tr>
<td class="tl">首页显示字母索引</td>
<td><input type="radio" name="setting[page_letter]" value="1"  <?php if($page_letter){ ?>checked <?php } ?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[page_letter]" value="0"  <?php if(!$page_letter){ ?>checked <?php } ?>/> 否</td>
</tr>

<tr>
<td class="tl">首页显示会员登录</td>
<td><input type="radio" name="setting[page_login]" value="1"  <?php if($page_login){ ?>checked <?php } ?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[page_login]" value="0"  <?php if(!$page_login){ ?>checked <?php } ?>/> 否</td>
</tr>


<tr>
<td class="tl">首页最新资讯数量</td>
<td><input type="text" name="setting[page_news]" value="<?php echo $page_news;?>" size="5"/></td>
</tr>

<tr>
<td class="tl">首页显示头条资讯</td>
<td><input type="radio" name="setting[page_newsh]" value="1"  <?php if($page_newsh){ ?>checked <?php } ?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[page_newsh]" value="0"  <?php if(!$page_newsh){ ?>checked <?php } ?>/> 否</td>
</tr>

<tr>
<td class="tl">首页显示推荐资讯</td>
<td><input type="radio" name="setting[page_newsr]" value="1"  <?php if($page_newsr){ ?>checked <?php } ?>/> 是&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="setting[page_newsr]" value="0"  <?php if(!$page_newsr){ ?>checked <?php } ?>/> 否</td>
</tr>

<tr>
<td class="tl">首页企业数量</td>
<td><input type="text" name="setting[page_com]" value="<?php echo $page_com;?>" size="5"/></td>
</tr>

<tr>
<td class="tl">首页行情报价数量</td>
<td><input type="text" name="setting[page_quote]" value="<?php echo $page_quote;?>" size="5"/></td>
</tr>

<tr>
<td class="tl">首页招商代理数量</td>
<td><input type="text" name="setting[page_info]" value="<?php echo $page_info;?>" size="5"/></td>
</tr>

<tr>
<td class="tl">首页行业展会数量</td>
<td><input type="text" name="setting[page_exhibit]" value="<?php echo $page_exhibit;?>" size="5"/></td>
</tr>
<tr>
<td class="tl">首页推荐展会数量</td>
<td><input type="text" name="setting[page_exhibit_elite]" value="<?php echo $page_exhibit_elite;?>" size="5"/></td>
</tr>

<tr>
<td class="tl">首页招聘求职数量</td>
<td><input type="text" name="setting[page_job]" value="<?php echo $page_job;?>" size="5"/></td>
</tr>

<tr>
<td class="tl">首页投票调查数量</td>
<td><input type="text" name="setting[page_vote]" value="<?php echo $page_vote;?>" size="5"/></td>
</tr>
<tr>
<td class="tl">首页图片链接数量</td>
<td><input type="text" name="setting[page_logo]" value="<?php echo $page_logo;?>" size="5"/></td>
</tr>
<tr>
<td class="tl">首页文字链接数量</td>
<td><input type="text" name="setting[page_text]" value="<?php echo $page_text;?>" size="5"/></td>
</tr>
</table>
</div>
<script type="text/javascript">
function TestMail() {
	if($('testemail').value == '') {
		Dtip('请先输入一个接收测试邮件的邮件地址');
		$('testemail').focus();
		return false;
	}
	var url = '?file=setting&action=mail';
	var mail_type = '';
	if($('mailtype_close').checked) mail_type = 'close';
	if($('mailtype_mail').checked) mail_type = 'mail';
	if($('mailtype_smtp').checked) mail_type = 'smtp';
	if($('mailtype_psmtp').checked) mail_type = 'psmtp';
	var mail_delimiter = $('l_rn').checked ? 1 : ($('l_n').checked ? 2 : 3);
	var smtp_auth = $('smtp_auth').checked ? 1 : 0;
	url += '&mail_type='+mail_type+'&mail_delimiter='+mail_delimiter+'&mail_name='+$('mail_name').value+'&smtp_host='+$('smtp_host').value+'&smtp_auth='+smtp_auth+'&smtp_user='+$('smtp_user').value+'&smtp_pass='+$('smtp_pass').value+'&smtp_port='+$('smtp_port').value+'&mail_sender='+$('mail_sender').value+'&testemail='+$('testemail').value;
	//window.open(url);
	Diframe(url, 0, 0, 1);
}
</script>

<div class="sbt"><input type="submit" name="submit" value=" 确 定 " class="btn">&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value=" 重 置 " class="btn"></div>
</form>
<script type="text/javascript">
function wt(ID) {
	if(ID == 1) {
		$('w_txt').style.display = '';
		$('w_img').style.display = 'none';
		$('w_pos').style.display = '';
	} else if(ID == 2) {
		$('w_txt').style.display = 'none';
		$('w_img').style.display = '';
		$('w_pos').style.display = '';
	} else {
		$('w_txt').style.display = 'none';
		$('w_img').style.display = 'none';
		$('w_pos').style.display = 'none';
	}
}
wt(<?php echo $water_type;?>);
</script>
<?php if($tab) { ?><script type="text/javascript">window.onload=function() {Tab(<?php echo $tab;?>);}</script><?php } ?>
</body>
</html>