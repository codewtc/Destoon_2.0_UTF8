<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
function fields_update($post_fields, $table, $itemid, $keyname = 'itemid', $fd = array()) {
	global $FD, $db;
	if(!$table || !$itemid) return '';
	if($fd) $FD = $fd;
	$sql = '';
	foreach($FD as $k=>$v) {
		if(isset($post_fields[$v['name']])) {
			$mk = $v['name'];
			$mv = $post_fields[$v['name']];
			if($v['html'] == 'checkbox') $mv = implode(',', $post_fields[$v['name']]);
			$sql .= ",$mk='$mv'";
		}
	}
	$sql = substr($sql, 1);
	$db->query("UPDATE {$table} SET $sql WHERE `$keyname`=$itemid");
}

function fields_check($post_fields, $fd = array()) {
	global $FD;
	if($fd) $FD = $fd;
	$uploads = get_cookie('uploads');
	foreach($FD as $k=>$v) {
		$value = isset($post_fields[$v['name']]) ? $post_fields[$v['name']] : '';
		if(in_array($v['html'], array('thumb', 'file') && $uploads)) {
			$uploads = explode('|', $uploads);
			if(in_array($value, $uploads)) {				
				foreach($uploads as $sk => $sv) {
					if($sv == $value) unset($uploads[$sk]);
				}
			}
		}
		if(!$v['input_limit']) continue;
		if(!defined('DT_ADMIN') && !$v['front']) continue;
		if($v['input_limit'] == 'is_date') {
			if(!is_date($value)) message('请填写'.$v['title']);
		} else if($v['input_limit'] == 'is_email') {
			if(!is_email($value)) message('请填写正确的'.$v['title']);
		} else if(is_numeric($v['input_limit'])) {
			$length = char_count($value);
			if($length < $v['input_limit']) message($v['title'].'不能少于'.$v['input_limit'].'字符');
		} else {
			if(preg_match("/^([0-9]{1,})\-([0-9]{1,})$/", $v['input_limit'], $m)) {			
				$length = char_count($value);
				if($m[1] && $length < $m[1]) message($v['title'].'不能少于'.$m[1].'字符');
				if($m[2] && $length > $m[2]) message($v['title'].'不能多于'.$m[2].'字符');
			} else {
				if(!preg_match("/^".$v['input_limit']."$/", $value)) message($v['title'].'不符合填写规则');
			}
		}
	}
	if($uploads) set_cookie('uploads', implode('|', $uploads));
}

function fields_js($fd = array()) {
	global $FD;
	if($fd) $FD = $fd;
	$js = '';
	foreach($FD as $k=>$v) {
		if(!$v['input_limit']) continue;
		if(!defined('DT_ADMIN') && !$v['front']) continue;
		if($v['input_limit'] == 'is_date') {
			$js .= 'f = "post_fields'.$v['name'].'";l = $(f).value.length;';
			$js .= 'if(l != 10) {Dmsg("请填写'.$v['title'].'", f, 1);return false;}';
		} else if($v['input_limit'] == 'is_email') {
			$js .= 'f = "'.$v['name'].'";l = $(f).value.length;';
			$js .= 'if(l < 8) {Dmsg("请填写'.$v['title'].'", f);return false;}';
		} else if(is_numeric($v['input_limit'])) {
			$js .= 'f = "'.$v['name'].'";l = $(f).value.length;';
			$js .= 'if(l < '.$v['input_limit'].') {Dmsg("'.$v['title'].'不能少于'.$v['input_limit'].'字符", f);return false;}';
		} else {
			if(preg_match("/^([0-9]{1,})\-([0-9]{1,})$/", $v['input_limit'], $m)) {			
				$js .= 'f = "'.$v['name'].'";l = $(f).value.length;';
				if($m[1]) $js .= 'if(l < '.$m[1].') {Dmsg("'.$v['title'].'不能少于'.$m[1].'字符", f);return false;}';
				if($m[2]) $js .= 'if(l > '.$m[2].') {Dmsg("'.$v['title'].'不能大于'.$m[2].'字符", f);return false;}';
			} else {
				$js .= 'f = "'.$v['name'].'";l = $(f).value;';
				$js .= 'if(l.match(/^'.$v['input_limit'].'$/) == null) {Dmsg("'.$v['title'].'不符合填写规则", f);return false;}';
			}
		}
	}
	return $js;
}

function fields_html($left, $right, $values = array(), $fd = array()) {
	extract($GLOBALS, EXTR_SKIP);
	if($fd) $FD = $fd;
	$html = '';
	foreach($FD as $k=>$v) {
		if(!$v['display']) continue;
		if(!defined('DT_ADMIN') && !$v['front']) continue;
		$value = '';
		if(isset($values[$v['name']])) {
			$value = $values[$v['name']];
		} else if($v['default_value']) {
			eval('$value = "'.$v['default_value'].'";');
		}
		if($v['html'] == 'hidden') {
			$html .= '<input type="hidden" name="post_fields['.$v['name'].']" id="'.$v['name'].'" value="'.$value.'" '.$v['addition'].'/>';
		} else {
			$html .= '<tr>'.$left;
			if($v['input_limit'] && !defined('DT_ADMIN')) $html .= '<span class="f_red">*</span> ';
			$html .= $v['title'];
			if($v['input_limit'] && defined('DT_ADMIN')) $html .= ' <span class="f_red">*</span>';
			$html .= '</td>';
			$html .= $right;
			switch($v['html']) {
				case 'text':
					$html .= '<input type="text" name="post_fields['.$v['name'].']" id="'.$v['name'].'" value="'.$value.'" '.$v['addition'].'/> <span class="f_red" id="d'.$v['name'].'"></span>';
				break;
				case 'textarea':
					$html .= '<textarea name="post_fields['.$v['name'].']" id="'.$v['name'].'" '.$v['addition'].'>'.$value.'</textarea> <span class="f_red" id="d'.$v['name'].'"></span>';
				break;
				case 'select':
					if($v['option_value']) {
						$html .= '<select name="post_fields['.$v['name'].']" id="'.$v['name'].'" '.$v['addition'].'><option value="">请选择</option>';
						$rows = explode("*", $v['option_value']);
						foreach($rows as $row) {
							if($row) {
								$cols = explode("|", trim($row));
								$html .= '<option value="'.$cols[0].'"'.($cols[0] == $value ? ' selected' : '').'>'.$cols[1].'</option>';
							}
						}
						$html .= '</select> <span class="f_red" id="d'.$v['name'].'"></span>';
					}
				break;
				case 'radio':
					if($v['option_value']) {
						$html .= '<span id="'.$v['name'].'">';
						$rows = explode("*", $v['option_value']);
						foreach($rows as $rw => $row) {
							if($row) {
								$cols = explode("|", trim($row));
								$html .= '<input type="radio" name="post_fields['.$v['name'].']" value="'.$cols[0].'" id="'.$v['name'].'_'.$rw.'"'.($cols[0] == $value ? ' checked' : '').'> '.$cols[1].'&nbsp;&nbsp;&nbsp;';
							}
						}
						$html .= '</span> <span class="f_red" id="d'.$v['name'].'"></span>';
					}
				break;
				case 'checkbox':
					if($v['option_value']) {
						$html .= '<span id="'.$v['name'].'">';
						$value = explode(',', $value);
						$rows = explode("*", $v['option_value']);
						foreach($rows as $rw => $row) {
							if($row) {
								$cols = explode("|", trim($row));
								$html .= '<input type="checkbox" name="post_fields['.$v['name'].'][]" value="'.$cols[0].'" id="'.$v['name'].'_'.$rw.'"'.(in_array($cols[0], $value) ? ' checked' : '').'> '.$cols[1].'&nbsp;&nbsp;&nbsp;';
							}
						}
						$html .= '</span> <span class="f_red" id="d'.$v['name'].'"></span>';
					}
				break;
				case 'date':
					$html .= dcalendar('post_fields['.$v['name'].']', $value);
					$html .= ' <span class="f_red" id="post_dfields'.$v['name'].'"></span>';
				break;
				case 'thumb':
					$html .= '<input name="post_fields['.$v['name'].']" type="text" size="60" id="'.$v['name'].'" value="'.$value.'" '.$v['addition'].'/>&nbsp;&nbsp;<span onclick="Dthumb('.$moduleid.','.$v['width'].','.$v['height'].', $(\''.$v['name'].'\').value,\''.(defined('DT_ADMIN') ? '' : '1').'\',\''.$v['name'].'\');" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="_preview($(\''.$v['name'].'\').value);" class="jt">[预览]</span>&nbsp;&nbsp;<span onclick="$(\''.$v['name'].'\').value=\'\';" class="jt">[删除]</span>';
					$html .= ' <span class="f_red" id="d'.$v['name'].'"></span>';
				break;
				case 'file':
					$html .= '<input name="post_fields['.$v['name'].']" type="text" size="60" id="'.$v['name'].'" value="'.$value.'" '.$v['addition'].'/>&nbsp;&nbsp;<span onclick="Dfile('.$moduleid.', $(\''.$v['name'].'\').value, \''.$v['name'].'\');" class="jt">[上传]</span>&nbsp;&nbsp;<span onclick="if($(\''.$v['name'].'\').value) window.open($(\''.$v['name'].'\').value);" class="jt">[预览]</span>';
					$html .= ' <span class="f_red" id="d'.$v['name'].'"></span>&nbsp;&nbsp;<span onclick="$(\''.$v['name'].'\').value=\'\';" class="jt">[删除]</span>';
					$html .= ' <span class="f_red" id="d'.$v['name'].'"></span>';
				break;
			}
			$html .= $v['note'];
			$html .= '</td></tr>';
		}
	}
	return $html;
}
?>