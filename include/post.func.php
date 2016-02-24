<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
function deditor($moduleid = 1, $textareaid = 'content', $toolbarset = 'Default', $width = 500, $height = 400) {
	$width = is_numeric($width) ? $width.'px' : $width;
	$height = is_numeric($height) ? $height.'px' : $width;
	$editor = '';
	$editor .= '<script type="text/javascript">var ModuleID = '.$moduleid.';';
	$editor .= 'var DTAdmin = '.(defined('DT_ADMIN') ? 1 : 0).';';
	$editor .= 'var EDPath = "'.DT_PATH.'editor/fckeditor/";</script>';
	$editor .= '<script type="text/javascript" src="'.DT_PATH.'editor/fckeditor/fckeditor.js"></script>';
	$editor .= '<script type="text/javascript">';
	$editor .= 'window.onload = function() {';
	$editor .= 'var sBasePath = "'.DT_PATH.'editor/fckeditor/";';
	$editor .= 'var oFCKeditor = new FCKeditor("'.$textareaid.'");';
	$editor .= 'oFCKeditor.Width = "'.$width.'";';
	$editor .= 'oFCKeditor.Height = "'.$height.'";';
	$editor .= 'oFCKeditor.BasePath = sBasePath;';
	$editor .= 'oFCKeditor.ToolbarSet = "'.$toolbarset.'";';
	$editor .= 'oFCKeditor.ReplaceTextarea();';
	$editor .= '}';
	$editor .= '</script>';
	echo $editor;
}

function dstyle($name, $value = '') {
	global $destoon_style_id;
	$style = $color = '';
	if(preg_match("/^#[0-9a-zA-Z]{6}$/", $value)) $color = $value;
	if(!$destoon_style_id) {
		$destoon_style_id = 1;
		$style .= '<script type="text/javascript" src="'.DT_PATH.'javascript/color.js"></script>';
	} else {
		$destoon_style_id++;
	}
	$style .= '<input type="hidden" name="'.$name.'" id="color_input_'.$destoon_style_id.'" value="'.$color.'"/><img src="'.SKIN_PATH.'image/color.gif" width="21" height="18" align="absmiddle" id="color_img_'.$destoon_style_id.'" style="cursor:pointer;background:'.$color.'" alt="选择颜色" title="选择颜色" onclick="color_show('.$destoon_style_id.', $(\'color_input_'.$destoon_style_id.'\').value, this);"/>';
	return $style;
}

function dcalendar($name, $value = '', $sep = '-') {
	global $destoon_calendar_id;
	$calendar = '';
	$id = str_replace(array('[', ']'), array('', ''), $name);
	if(!$destoon_calendar_id) {
		$destoon_calendar_id = 1;
		$calendar .= '<script type="text/javascript" src="'.DT_PATH.'javascript/calendar.js"></script>';
	}
	$calendar .= '<input type="text" name="'.$name.'" id="'.$id.'" value="'.$value.'" size="10" onfocus="calendar_show(\''.$id.'\', this, \''.$sep.'\');" readonly ondblclick="this.value=\'\';" title="双击鼠标清空"/> <img src="'.SKIN_PATH.'image/calendar.gif" align="absmiddle" onclick="calendar_show(\''.$id.'\', this, \''.$sep.'\');" style="cursor:pointer;"/>';
	return $calendar;
}

function dselect($sarray, $name, $title = '', $selected = 0, $extend = '', $key = 1, $ov = '', $abs = 0) {
	$select = '<select name="'.$name.'" '.$extend.'>';
	if($title) $select .= '<option value="'.$ov.'">'.$title.'</option>';
	foreach($sarray as $k=>$v) {
		if(!$v) continue;
		$_selected = ($abs ? ($key ? $k : $v) === $selected : ($key ? $k : $v) == $selected) ? ' selected=selected' : '';
		$select .= '<option value="'.($key ? $k : $v).'"'.$_selected.'>'.$v.'</option>';
	}	
	$select .= '</select>';
	return $select;
}

function dcheckbox($sarray, $name, $checked = '', $extend = '', $key = 1, $except = '', $abs = 0) {
	$checked = $checked ? explode(',', $checked) : array();
	$except = $except ? explode(',', $except) : array();
	$checkbox = $sp = '';
	foreach($sarray as $k=>$v) {
		if(in_array($key ? $k : $v, $except)) continue;
		$sp = in_array($key ? $k : $v, $checked) ? ' checked ' : '';
		$checkbox .= '<input type="checkbox" name="'.$name.'" value="'.($key ? $k : $v).'"'.$sp.$extend.'> '.$v.'&nbsp;';
	}
	return $checkbox;
}

function type_select($item, $cache = 0, $name = 'typeid', $title = '', $typeid = 0, $extend = '', $all = '') {
	$TYPE = get_type($item, $cache);
	$select = '<select name="'.$name.'" '.$extend.'>';
	if($all) $select .= '<option value="-1"'.($typeid == -1 ? ' selected=selected' : '').'>'.$all.'</option>';
	if($title) $select .= '<option value="0"'.($typeid == 0 ? ' selected=selected' : '').'>'.$title.'</option>';
	foreach($TYPE as $k=>$v) {
		$select .= ' <option value="'.$k.'"'.($k == $typeid ? ' selected' : '').'> '.$v['typename'].'</option>';
	}
	$select .= '</select>';
	return $select;
}

function url_select($name, $ext = 'htm', $type = 'list', $urlid = 0, $extend = '') {
	include DT_ROOT."/include/url.inc.php";
	$select = '<select name="'.$name.'" '.$extend.'>';
	$types = count($urls[$ext][$type]);
	for($i = 0; $i < $types; $i++) {
		$select .= ' <option value="'.$i.'"'.($i == $urlid ? ' selected' : '').'>例 '.$urls[$ext][$type][$i]['example'].'</option>';
	}
	$select .= '</select>';
	return $select;
}

function tpl_select($file = 'index', $module = '', $name = 'template', $title = '', $template = '', $extend = '') {
	global $CFG;
    $tpldir = $module ? DT_ROOT."/template/".$CFG['template']."/".$module : DT_ROOT."/template/".$CFG['template'];
	@include $tpldir."/these.name.php";
	$select = '<select name="'.$name.'" '.$extend.'><option value="">'.$title.'</option>';
	$files = glob($tpldir."/*.htm");
	foreach($files as $tplfile)	{
		$tplfile = basename($tplfile);
		$tpl = str_replace('.htm', '', $tplfile);
		if(preg_match("/^".$file."-(.*)/i", $tpl) || !$file) {//$file == $tpl || 
			$selected = ($template && $tpl == $template) ? 'selected' : '';
            $templatename = (isset($names[$tpl]) && $names[$tpl]) ? $names[$tpl] : $tpl;
			$select .= '<option value="'.$tpl.'" '.$selected.'>'.$templatename.'</option>';
		}
	}
	$select .= '</select>';
	return $select;
}

function group_select($name = 'groupid', $title = '', $groupid = '', $extend = '') {
	global $GROUP;
	if(!$GROUP) $GROUP = cache_read('group.php');
	$select = '<select name="'.$name.'" '.$extend.'><option value="0">'.$title.'</option>';
	foreach($GROUP as $k=>$v) {
		$select .= '<option value="'.$k.'"'.($k == $groupid ? ' selected' : '').'>'.$v['groupname'].'</option>';
	}
	$select .= '</select>';
	return $select;
}

function group_checkbox($name = 'groupid', $checked = '', $except = '2,4') {
	global $GROUP;
	$GROUP or $GROUP = cache_read('group.php');
	$checked = $checked ? explode(',', $checked) : array();
	$except = $except ? explode(',', $except) : array();
	$str = $sp = '';
	$id = str_replace(array('[', ']'), array('', ''), $name);
	foreach($GROUP as $k=>$v) {
		if(in_array($k, $except)) continue;
		$sp = in_array($k, $checked) ? ' checked' : '';
		$str .= '<input type="checkbox" name="'.$name.'" value="'.$k.'"'.$sp.' id="'.$id.$k.'"/><label for="'.$id.$k.'"> '.$v['groupname'].'&nbsp; </label>';
	}
	return '<span id="group_'.$id.'">'.$str.'</span>&nbsp;<a href="javascript:check_box(\'group_'.$id.'\', true);">全选</a> / <a href="javascript:check_box(\'group_'.$id.'\', false);">全不选</a>';
}

function module_checkbox($name = 'moduleid', $checked = '', $except = '1,2,3,4') {
	global $MODULE;
	$checked = $checked ? explode(',', $checked) : array();
	$except = $except ? explode(',', $except) : array();
	$str = $sp = '';
	$id = str_replace(array('[', ']'), array('', ''), $name);
	foreach($MODULE as $k=>$v) {
		if(in_array($k, $except) || $v['islink']) continue;
		$sp = in_array($k, $checked) ? ' checked' : '';
		$str .= '<input type="checkbox" name="'.$name.'" value="'.$k.'"'.$sp.' id="'.$id.$k.'"/><label for="'.$id.$k.'"> '.$v['name'].'&nbsp; </label>';
	}
	return $str;
}

function module_select($name = 'moduleid', $title = '请选择', $moduleid = '', $extend = '', $except = '1,2,3,4') {
	global $MODULE;
	$except = $except ? explode(',', $except) : array();
	$select = '<select name="'.$name.'" '.$extend.'><option value="0">'.$title.'</option>';
	foreach($MODULE as $k=>$v) {
		if(in_array($k, $except) || $v['islink']) continue;
		$select .= '<option value="'.$k.'"'.($k == $moduleid ? ' selected' : '').'>'.$v['name'].'</option>';
	}
	$select .= '</select>';
	return $select;
}

function homepage_select($name, $title = '请选择', $groupid = 0, $itemid = 0, $extend = '') {
	global $db, $DT_PRE;
	$select = '<select name="'.$name.'" '.$extend.'><option value="0">'.$title.'</option>';
	$result = $db->query("SELECT * FROM {$DT_PRE}style ORDER BY listorder DESC,itemid DESC");
	while($r = $db->fetch_array($result)) {
		$select .= '<option value="'.$r['itemid'].'"'.($r['itemid'] == $itemid ? ' selected' : '').'>'.$r['title'].'</option>';
	}
	$select .= '</select>';
	return $select;
}

function product_select($name = 'pid', $title = '', $pid = 0, $extend = '') {
	global $PRODUCT;
	$PRODUCT or $PRODUCT = cache_read('product.php');
	$select = '<select name="'.$name.'" '.$extend.'>';
	if($title) $select .= '<option value="0">'.$title.'</option>';
	foreach($PRODUCT as $k=>$v) {
		$select .= '<option value="'.$k.'"'.($k == $pid ? ' selected' : '').'>'.$v['title'].'</option>';
	}
	$select .= '</select>';
	return $select;
}

function category_select($name = 'catid', $title = '', $catid = 0, $moduleid = 1, $extend = '') {
	$option = cache_read('cattree-'.$moduleid.'.php', '', true);
	if($option) {
		if($catid) $option = str_replace('value="'.$catid.'"', 'value="'.$catid.'" selected', $option);
		$select = '<select name="'.$name.'" '.$extend.' id="catid_1">';
		if($title) $select .= '<option value="0">'.$title.'</option>';
		$select .= $option ? $option : '</select>';
		return $select;
	} else {
		return ajax_category_select($name, $title, $catid, $moduleid, $extend);
	}
}

function get_category_select($title = '', $catid = 0, $moduleid = 1, $extend = '', $deep = 0, $cat_id = 1) {
	global $CATEGORY, $DCAT;
	$CATBAK = $CATEGORY ? $CATEGORY : array();
	if(isset($DCAT[$moduleid])) {
		$CATEGORY = $DCAT[$moduleid];
	} else {
		$CATEGORY = $DCAT[$moduleid] = cache_read('category-'.$moduleid.'.php');
	}
	$parents = array();
	$cid = $catid;
	if($catid && $CATEGORY[$catid]['child']) $parents[] = $catid;
	while($catid) {
		if($CATEGORY[$cid]['parentid']) {
			$parents[] = $cid = $CATEGORY[$cid]['parentid'];
		} else {
			break;
		}
	}
	$parents[] = 0;
	$parents = array_reverse($parents);
	$select = '';
	foreach($parents as $k=>$v) {
		if($deep && $deep <= $k) break;
		$select .= '<select onchange="load_category(this.value, '.$cat_id.');" '.$extend.'>';
		if($title) $select .= '<option value="0">'.$title.'</option>';
		foreach($CATEGORY as $c) {
			if($c['parentid'] == $v) {
				$selectid = isset($parents[$k+1]) ? $parents[$k+1] : $catid;
				$selected = $c['catid'] == $selectid ? ' selected' : '';
				$select .= '<option value="'.$c['catid'].'"'.$selected.'>'.$c['catname'].'</option>';
			}
		}
		$select .= '</select> ';
	}
	$CATEGORY = $CATBAK;
	return $select;
}

function ajax_category_select($name = 'catid', $title = '', $catid = 0, $moduleid = 1, $extend = '', $deep = 0) {
	global $cat_id;
	if($cat_id) {
		$cat_id++;
	} else {
		$cat_id = 1;
	}
	$catid = intval($catid);
	$deep = intval($deep);
	$select = '';
	$select .= '<input name="'.$name.'" id="catid_'.$cat_id.'" type="hidden" value="'.$catid.'"/>';
	$select .= '<span id="load_category_'.$cat_id.'">'.get_category_select($title, $catid, $moduleid, $extend, $deep, $cat_id).'</span>';
	$select .= '<script type="text/javascript">';
	if($cat_id == 1) $select .= 'var category_moduleid = new Array;';
	$select .= 'category_moduleid['.$cat_id.']="'.$moduleid.'";';
	if($cat_id == 1) $select .= 'var category_title = new Array;';
	$select .= 'category_title['.$cat_id.']=\''.$title.'\';';
	if($cat_id == 1) $select .= 'var category_extend = new Array;';
	$select .= 'category_extend['.$cat_id.']=\''.$extend.'\';';
	if($cat_id == 1) $select .= 'var category_catid = new Array;';
	$select .= 'category_catid['.$cat_id.']=\''.$catid.'\';';
	if($cat_id == 1) $select .= 'var category_deep = new Array;';
	$select .= 'category_deep['.$cat_id.']=\''.$deep.'\';';
	$select .= '</script>';
	if($cat_id == 1) $select .= '<script type="text/javascript" src="'.DT_PATH.'javascript/category.js"></script>';
	return $select;
}

function get_area_select($title = '', $areaid = 0, $extend = '', $deep = 0, $id = 1) {
	global $AREA;
	$AREA or $AREA = cache_read('area.php');
	$parents = array();
	$aid = $areaid;
	if($areaid && $AREA[$areaid]['child']) $parents[] = $areaid;
	while($areaid) {
		if($AREA[$aid]['parentid']) {
			$parents[] = $aid = $AREA[$aid]['parentid'];
		} else {
			break;
		}
	}
	$parents[] = 0;
	$parents = array_reverse($parents);
	$select = '';
	foreach($parents as $k=>$v) {
		if($deep && $deep <= $k) break;
		$select .= '<select onchange="load_area(this.value, '.$id.');" '.$extend.'>';
		if($title) $select .= '<option value="0">'.$title.'</option>';
		foreach($AREA as $a) {
			if($a['parentid'] == $v) {
				$selectid = isset($parents[$k+1]) ? $parents[$k+1] : $areaid;
				$selected = $a['areaid'] == $selectid ? ' selected' : '';
				$select .= '<option value="'.$a['areaid'].'"'.$selected.'>'.$a['areaname'].'</option>';
			}
		}
		$select .= '</select> ';
	}
	return $select;
}

function ajax_area_select($name = 'areaid', $title = '', $areaid = 0, $extend = '', $deep = 0) {
	global $area_id;
	if($area_id) {
		$area_id++;
	} else {
		$area_id = 1;
	}
	$areaid = intval($areaid);
	$deep = intval($deep);
	$select = '';
	$select .= '<input name="'.$name.'" id="areaid_'.$area_id.'" type="hidden" value="'.$areaid.'"/>';
	$select .= '<span id="load_area_'.$area_id.'">'.get_area_select($title, $areaid, $extend, $deep, $area_id).'</span>';
	$select .= '<script type="text/javascript">';
	if($area_id == 1) $select .= 'var area_title = new Array;';
	$select .= 'area_title['.$area_id.']=\''.$title.'\';';
	if($area_id == 1) $select .= 'var area_extend = new Array;';
	$select .= 'area_extend['.$area_id.']=\''.$extend.'\';';
	if($area_id == 1) $select .= 'var area_areaid = new Array;';
	$select .= 'area_areaid['.$area_id.']=\''.$areaid.'\';';
	if($area_id == 1) $select .= 'var area_deep = new Array;';
	$select .= 'area_deep['.$area_id.']=\''.$deep.'\';';
	$select .= '</script>';
	if($area_id == 1) $select .= '<script type="text/javascript" src="'.DT_PATH.'javascript/area.js"></script>';
	return $select;
}

function level_select($name, $title = '', $level = 0, $extend = '') {
	$select = '<select name="'.$name.'" '.$extend.'>';
	if($title) $select .= '<option value="0">'.$title.'</option>';
	for($i = 1; $i < 10; $i++) {
		$select .= '<option value="'.$i.'"'.($i == $level ? ' selected' : '').'>'.$i.' 级</option>';
	}
	$select .= '</select>';
	return $select;
}

function is_email($email) {
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

function is_gbk($string) {
	return preg_match("/^([\s\S]*?)([\x81-\xfe][\x40-\xfe])([\s\S]*?)/", $string);
}

function is_date($date, $sep = '-') {
	if(empty($date)) return false;
	if(strlen($date) > 10)  return false;
	list($year, $month, $day) = explode($sep, $date);
	return checkdate($month, $day, $year);
}

function is_image($file) {
	return preg_match("/^(jpg|jpeg|gif|png)$/i", file_ext($file));
}

function is_user($username) {
	global $db, $DT_PRE;
	$r = $db->get_one("SELECT username FROM {$DT_PRE}member WHERE username='$username'");
	return $r ? true : false;
}

function is_password($username, $password) {
	global $db, $DT_PRE;
	if(strlen($password) < 6) return false;
	$r = $db->get_one("SELECT password FROM {$DT_PRE}member WHERE username='$username'");
	if(!$r) return false;
	return $r['password'] == (is_md5($password) ? md5($password) : md5(md5($password)));
}

function is_payword($username, $payword) {
	global $db, $DT_PRE;
	if(strlen($payword) < 6) return false;
	$r = $db->get_one("SELECT payword,password FROM {$DT_PRE}member WHERE username='$username'");
	if(!$r) return false;
	$r['payword'] = $r['payword'] ? $r['payword'] : $r['password'];
	return $r['payword'] == (is_md5($payword) ? md5($payword) : md5(md5($payword)));
}

function is_md5($password) {
	return preg_match("/^[a-z0-9]{32}$/", $password);
}

function gb2py($text, $exp = '') {
	global $CFG;
	if(!$text) return '';
	if(strtolower($CFG['charset']) != 'gbk') $text = convert($text, $CFG['charset'], 'gbk');
	$data = array();
	$tmp = @file(DT_ROOT.'/file/table/gb-pinyin.table');
	if(!$tmp) return '';
	$tmps = count($tmp);
	for($i = 0; $i < $tmps; $i++) {
		$tmp1 = explode("	", $tmp[$i]);
		$data[$i]=array($tmp1[0], $tmp1[1]);
	}
	$r = array();
	$k = 0;
	$textlen = strlen($text);
	for($i = 0; $i < $textlen; $i++) {
		$p = ord(substr($text, $i, 1));		
		if($p > 160) {
			$q = ord(substr($text, ++$i, 1));
			$p = $p*256+$q-65536;
		}
        if($p > 0 && $p < 160) {
            $r[$k] = chr($p);
        } elseif($p< -20319 || $p > -10247) {
            $r[$k] = '';
        } else {
            for($j = $tmps-1; $j >= 0; $j--) {
                if($data[$j][1]<=$p) break;
            }
            $r[$k] = $data[$j][0];
        }
		$k++;
	}
	return implode($exp, $r);
}

function match_userid($file) {
	$file = basename($file);
	if(preg_match("/\-([0-9]{2}+)\-([0-9]{1,}+)\./", $file, $m)) {
		return $m[2];
	} else {
		return 0;
	}
}

function clear_link($content) {
	$content = preg_replace("/<a[^>]*>/i", "", $content);
	return preg_replace("/<\/a>/i", "", $content); 
}

function save_remote($content, $ext = 'jpg|jpeg|gif|png') {
	global $DT, $DT_TIME, $_userid;
	if(!$_userid || !$content) return $content;
	if(!preg_match_all("/src=([\"|']?)([^ \"'>]+\.($ext))\\1/i", $content, $matches)) return $content;
	require_once DT_ROOT.'/include/image.class.php';
	$dftp = false;
	if($DT['ftp_remote'] && $DT['remote_url']) {
		require_once DT_ROOT.'/include/ftp.class.php';
		$ftp = new dftp($DT['ftp_host'], $DT['ftp_user'], $DT['ftp_pass'], $DT['ftp_port'], $DT['ftp_path'], $DT['ftp_pasv'], $DT['ftp_ssl']);
		$dftp = $ftp->connected;
	}
	$urls = $oldpath = $newpath = array();
	$DT['uploaddir'] or $DT['uploaddir'] = 'Ym/d';
	foreach($matches[2] as $k=>$url) {
		if(in_array($url, $urls)) continue;
		$urls[$url] = $url;		
		if(strpos($url, '://') === false || match_userid($url) == $_userid) continue;
		$filedir = 'file/upload/'.date($DT['uploaddir'], $DT_TIME).'/';
		$filepath = DT_PATH.$filedir;
		$fileroot = DT_ROOT.'/'.$filedir;
		$file_ext = file_ext($url);
		$filename = date('H-i-s', $DT_TIME).'-'.rand(10, 99).'-'.$_userid.'.'.$file_ext;
		$newfile = $fileroot.$filename;
		if(file_copy($url, $newfile)) {
			if(is_image($newfile) && $DT['water_type']) {
				$image = new image($newfile);
				if($DT['water_type'] == 1) {
					$image->watertext();
				} else {
					$image->waterimage();
				}
			}
			$oldpath[] = $url;
			$newurl = linkurl($filepath.$filename, 1);
			if($dftp) {
				$exp = explode("file/upload/", $newurl);
				if($ftp->dftp_put($filedir.$filename, $exp[1])) {
					$newurl = $DT['remote_url'].$exp[1];
					@unlink($newfile);
				}
			}
			$newpath[] = $newurl;
		}
	}
	unset($matches);
	return str_replace($oldpath, $newpath, $content);
}

function save_thumb($content, $no, $width = 120, $height = 90) {
	global $DT, $DT_TIME, $_userid;
	if(!$_userid || !$content) return '';
	$ext = 'jpg|jpeg|gif|png';
	if(!preg_match_all("/src=([\"|']?)([^ \"'>]+\.($ext))\\1/i", $content, $matches)) return '';
	require_once DT_ROOT.'/include/image.class.php';
	$dftp = false;
	if($DT['ftp_remote'] && $DT['remote_url']) {
		require_once DT_ROOT.'/include/ftp.class.php';
		$ftp = new dftp($DT['ftp_host'], $DT['ftp_user'], $DT['ftp_pass'], $DT['ftp_port'], $DT['ftp_path'], $DT['ftp_pasv'], $DT['ftp_ssl']);
		$dftp = $ftp->connected;
	}
	$urls = $oldpath = $newpath = array();
	$DT['uploaddir'] or $DT['uploaddir'] = 'Ym/d';
	foreach($matches[2] as $k=>$url) {
		if($k == $no - 1) {
			$filedir = 'file/upload/'.date($DT['uploaddir'], $DT_TIME).'/';
			$filepath = DT_PATH.$filedir;
			$fileroot = DT_ROOT.'/'.$filedir;
			$file_ext = file_ext($url);
			$filename = date('H-i-s', $DT_TIME).'-'.rand(10, 99).'-'.$_userid.'.'.$file_ext;
			$newfile = $fileroot.$filename;
			if(file_copy($url, $newfile)) {
				if(is_image($newfile) && $DT['water_type']) {
					$image = new image($newfile);
					$image->thumb($width, $height);
				}
				$newurl = linkurl($filepath.$filename, 1);
				if($dftp) {
					$exp = explode("file/upload/", $newurl);
					if($ftp->dftp_put($filedir.$filename, $exp[1])) {
						$newurl = $DT['remote_url'].$exp[1];
						@unlink($newfile);
					}
				}
				return $newurl;
			}
		}
	}
	unset($matches);
	return '';
}

function delete_local($content, $userid, $ext = 'jpg|jpeg|gif|png|swf') {
	if(preg_match_all("/src=([\"|']?)([^ \"'>]+\.($ext))\\1/i", $content, $matches)) {
		foreach($matches[2] as $url) {
			delete_upload($url, $userid);
		}
		unset($matches);
	}
}

function delete_diff($new, $old, $ext = 'jpg|jpeg|gif|png|swf') {
	global $_userid;
	$new = stripslashes($new);
	$diff_urls = $new_urls = $old_urls = array();
	if(preg_match_all("/src=([\"|']?)([^ \"'>]+\.($ext))\\1/i", $old, $matches)) {
		foreach($matches[2] as $url) {
			$old_urls[] = $url;
		}
	} else {
		return;
	}
	if(preg_match_all("/src=([\"|']?)([^ \"'>]+\.($ext))\\1/i", $new, $matches)) {
		foreach($matches[2] as $url) {
			$new_urls[] = $url;
		}
	}
	foreach($old_urls as $url) {
		in_array($url, $new_urls) or $diff_urls[] = $url;
	}
	if(!$diff_urls) return;
	foreach($diff_urls as $url) {
		delete_upload($url, $_userid);
	}
	unset($new, $old, $matches, $url, $diff_urls, $new_urls, $old_urls);
}

function delete_upload($file, $userid) {
	global $CFG, $DT, $DT_TIME, $ftp;
	if(!defined('DT_ADMIN') && (!$userid || $userid != match_userid($file))) return false;
	if(strpos($file, 'file/upload') === false) {//Remote
		if($DT['ftp_remote'] && $DT['remote_url']) {
			if(strpos($file, $DT['remote_url']) !== false) {
				if(!is_object($ftp)) {
					require_once DT_ROOT.'/include/ftp.class.php';
					$ftp = new dftp($DT['ftp_host'], $DT['ftp_user'], $DT['ftp_pass'], $DT['ftp_port'], $DT['ftp_path'], $DT['ftp_pasv'], $DT['ftp_ssl']);
				}
				$file = str_replace($DT['remote_url'], '', $file);
				$ftp->dftp_delete($file);
				if(strpos($file, '.thumb.') !== false) {
					$ext = file_ext($file);
					$file = str_replace('.thumb.'.$ext, '', $file);
					$ftp->dftp_delete($file);
					$file = str_replace('.thumb.'.$ext, '.middle.'.$ext, $file);
					$ftp->dftp_delete($file);
				}
				return true;
			}
		}
	} else {//Local
		$exp = explode("file/upload/", $file);
		$file = DT_ROOT.'/file/upload/'.$exp[1];
		if(is_file($file)) {
			unlink($file);
			if(strpos($file, '.thumb.') !== false) {
				$ext = file_ext($file);
				@unlink(str_replace('.thumb.'.$ext, '', $file));
				@unlink(str_replace('.thumb.'.$ext, '.middle.'.$ext, $file));
			}
		}
		return true;
	}
	return false;
}

function clear_upload($content = '') {
	global $CFG, $_userid;
	$uploads = get_cookie('uploads');
	if($uploads && $content) {
		$uploads = explode('|', $uploads);
		foreach($uploads as $file) {
			if(strpos($content, $file) === false) delete_upload($file, $_userid);
		}
		set_cookie('uploads', '');
	}
}

function char_count($str, $strip = true) {
	global $CFG;
	$charset = strtolower($CFG['charset']);
	if($strip) $str = strip_tags($str);
	$str_len = strlen($str);
	$count = 0;
	for($i = 0; $i < $str_len; $i++) {
		$t = ord($str[$i]);
		if($t > 127) {
			if($charset == 'utf-8') {
				if(194 <= $t && $t <= 223) {
					$i += 1;
				} else if(240 <= $t && $t <= 247) {
					$i += 3;
				} else if(248 <= $t && $t <= 251) {
					$i += 4;
				} else if($t == 252 || $t == 253) {
					$i += 5;
				} else {
					$i += 2;
				}
			} else {
				$i++;
			}
		}
		$count++;
	}
	return $count;
}
?>