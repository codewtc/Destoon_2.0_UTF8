<?php 
defined('IN_DESTOON') or exit('Access Denied');
check_referer() or exit;
//$auth = $auth ? urldecode($auth) : '';
if($auth) {
	$string = dcrypt($auth, true);
	if(preg_match("/^[a-z0-9\.\-_@]+$/i", $string)) {
		header ("content-type:image/png");
		$imageX = strlen($string)*7;
		$imageY = 18;
		$im = @imagecreate($imageX, $imageY) or exit();
		imagecolorallocate($im, 255, 255, 255);
		$color = imagecolorallocate ($im, 0, 0, 0);
		imagestring($im, 3, 0, 4, $string, $color);
		/*
		$num = mt_rand(30, 60);
		for($i = 0; $i < $num; $i++) {
			imagesetpixel($im, mt_rand(0, $imageX), mt_rand(0, $imageY), $color);
		}
		*/
		imagepng($im);
		imagedestroy($im);
	}
}
?>