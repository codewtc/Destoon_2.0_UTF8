<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$head_title = '您访问的公司尚未注册本站会员';
include template('guest', 'message');
?>