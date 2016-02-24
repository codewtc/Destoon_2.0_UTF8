<?php
$moduleid = 2;
require '../common.inc.php';
require DT_ROOT.'/module/'.$module.'/common.inc.php';
if($MOD['passport'] != 'uc') exit('Ucenter Client Is Disabled');

define("UC_DBHOST", $MOD['uc_dbhost']) ;
define("UC_DBUSER", $MOD['uc_dbuser']) ;
define("UC_DBPW", $MOD['uc_dbpwd']) ;
define("UC_DBNAME", $MOD['uc_dbname']) ;
define("UC_DBPRE", $MOD['uc_dbpre']) ;
define("UC_KEY", $MOD['uc_key']) ;
define('UC_APPID', $MOD['uc_appid']) ;
define("UC_API", $MOD['uc_api']) ;
define("UC_IP", $MOD['uc_ip']) ;
define("UC_DBTABLEPRE", $MOD['uc_dbpre']);
define("UC_CONNECT", $MOD['uc_mysql'] ? 'mysql' : '');
define('API_RETURN_SUCCEED', 1);
define('UC_DBCHARSET', $MOD['uc_charset']); 
define('API_UPDATECREDIT', 0);
define('API_GETCREDITSETTINGS', 0);
define('API_UPDATECREDITSETTINGS', 0);
require_once DT_ROOT.'/api/ucenter/client.php';

parse_str(uc_authcode($code, 'DECODE', UC_KEY), $uc_arr) ;

if($DT_TIME - intval($uc_arr['time']) > 3600) exit('Authracation Has Expiried');
if(empty($uc_arr)) exit('Invalid Request');
$action = $uc_arr['action'];
switch($action) {
	case 'test':
		exit('1');
	break;
	case 'synlogin':
		$username = $uc_arr['username'];
		$user = $db->get_one("SELECT userid,password,username,passport,groupid,message FROM {$DT_PRE}member WHERE passport='$username' limit 0,1");
		if(!$user || $user['groupid'] == 2 || $user['groupid'] == 4) exit('-1');
		$cookietime = $DT_TIME + ($cookietime ? $cookietime : 86400);
		$destoon_auth = dcrypt($user['userid']."\t".$user['username']."\t".$user['groupid']."\t".$user['password'], 0, md5($CFG['authkey'].$_SERVER['HTTP_USER_AGENT']));
		ob_clean() ;
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		set_cookie('auth', $destoon_auth, $cookietime);
		$this->db->query("UPDATE {$DT_PRE}member SET loginip='$DT_IP',logintime=$DT_TIME,logintimes=logintimes+1 WHERE userid='$userid'");
		exit('1');
	break;
	case 'synlogout':
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		set_cookie('auth', '');
		exit('1');
	break;
	case 'deleteuser':
		$passport = $uc_arr['username'];/* 禁止访问 不直接删除 */
		$db->query("UPDATE {$DT_PRE}member SET groupid=2 WHERE passport='$passport' AND groupid!=1");
		exit('1');
	break;
	case 'updatepw':
		exit('1');//BUG $uc_arr['password'] is empty?
		if($uc_arr['password'] && $uc_arr['username']) {
			$password = preg_match('/^\w{32}$/', $uc_arr['password']) ? $uc_arr['password'] : md5($uc_arr['password']);
			$db->query("UPDATE {$DT_PRE}member SET password='$password' WHERE passport='$uc_arr[username]'");
			exit('1');
		}
		exit('0');
	break;
	case 'updateapps':
		exit('1');
	break;
	default:
		exit('-1');
	break;
}
?>