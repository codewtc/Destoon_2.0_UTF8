<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
define('DT_NONUSER', true);
define('DT_UPLOAD', true);
require 'common.inc.php';
$_userid or dalert('您还没有登录，没有权限上传文件');
require DT_ROOT.'/include/post.func.php';
$from = isset($from) ? trim($from) : '';
if(!$_FILES) exit;
$DT['uploaddir'] or $DT['uploaddir'] = 'Ym/d';
$uploaddir = 'file/upload/'.date($DT['uploaddir'], $DT_TIME).'/';
if(!is_dir(DT_ROOT.'/'.$uploaddir)) file_copy(DT_ROOT.'/file/index.html', DT_ROOT.'/'.$uploaddir.'index.html');
require DT_ROOT.'/include/upload.class.php';
$upload = new upload($_FILES, $uploaddir);
if($upload->uploadfile()) {
	if($upload->image) {
		require_once DT_ROOT.'/include/image.class.php';
		if($from == 'thumb' || $from == 'album') {
			if(strtolower($upload->ext) == 'gif' && (!function_exists('imagegif') || !function_exists('imagecreatefromgif'))) {
				unlink(DT_ROOT.'/'.$upload->saveto);
				dalert('抱歉！系统不支持GIF格式图片处理，请上传JPG或者PNG格式');
			}
		}
		if($from == 'thumb') {
			if($width && $height) {
				$image = new image(DT_ROOT.'/'.$upload->saveto);
				$image->thumb($width, $height);
			}
		} else if($from == 'album') {
			$saveto = $upload->saveto;
			$upload->saveto = $upload->saveto.'.thumb.'.$upload->ext;
			file_copy(DT_ROOT.'/'.$saveto, DT_ROOT.'/'.$upload->saveto);			
			$middle = $saveto.'.middle.'.$upload->ext;
			file_copy(DT_ROOT.'/'.$saveto, DT_ROOT.'/'.$middle);
			$image = new image(DT_ROOT.'/'.$saveto);
			if($DT['water_type'] == 1) {
				$image->watertext();
			} else {
				$image->waterimage();
			}
			$image = new image(DT_ROOT.'/'.$upload->saveto);
			$image->thumb($width, $height);	
			$image = new image(DT_ROOT.'/'.$middle);
			$image->thumb(200, 180);
		} else if($from == 'editor') {
			if($DT['water_type']) {
				$image = new image(DT_ROOT.'/'.$upload->saveto);
				if($DT['water_type'] == 1) {
					$image->watertext();
				} else {
					$image->waterimage();
				}
			}
		}
	}
	$saveto = linkurl($upload->saveto, 1);
	if($DT['ftp_remote'] && $DT['remote_url']) {
		require_once DT_ROOT.'/include/ftp.class.php';
		$ftp = new dftp($DT['ftp_host'], $DT['ftp_user'], $DT['ftp_pass'], $DT['ftp_port'], $DT['ftp_path'], $DT['ftp_pasv'], $DT['ftp_ssl']);
		if($ftp->connected) {
			$exp = explode("file/upload/", $saveto);
			if($ftp->dftp_put($upload->saveto, $exp[1])) {
				$saveto = $DT['remote_url'].$exp[1];
				@unlink(DT_ROOT.'/'.$upload->saveto);
				if(strpos($upload->saveto, '.thumb.') !== false) {
					$local = str_replace('.thumb.'.$upload->ext, '', $upload->saveto);
					$ftp->dftp_put($local, str_replace('.thumb.'.$upload->ext, '', $exp[1]));
					@unlink(DT_ROOT.'/'.$local);
					$local = str_replace('.thumb.'.$upload->ext, '.middle.'.$upload->ext, $upload->saveto);
					$ftp->dftp_put($local, str_replace('.thumb.'.$upload->ext, '.middle.'.$upload->ext, $exp[1]));
					@unlink(DT_ROOT.'/'.$local);
				}
			}
		}
	}
	$fid = isset($fid) ? $fid : '';
	if(isset($old) && $old) delete_upload($old, $_userid);
	$uploads = get_cookie('uploads');
	$uploads = $uploads ? $uploads.'|'.$saveto : $saveto;
	set_cookie('uploads', $uploads, $DT_TIME + 86400);
	if($from == 'thumb') {
		dalert('', '', 'try{parent.document.getElementById("d'.$fid.'").src="'.$saveto.'";}catch(e){}parent.document.getElementById("'.$fid.'").value="'.$saveto.'";window.parent.cDialog();');
	} else if($from == 'album') {
		dalert('', '', 'window.parent.getAlbum("'.$saveto.'", "'.$fid.'");window.parent.cDialog();');
	} else if($from == 'editor') {
		dalert('', '', 'window.parent.SetUrl("'.$saveto.'");window.parent.GetE("frmUpload").reset();');
	} else if($from == 'file') {
		dalert('', '', 'parent.document.getElementById("'.$fid.'").value="'.$saveto.'";window.parent.cDialog();');

	}
} else {
	dalert($upload->errmsg, '', '');
}
?>