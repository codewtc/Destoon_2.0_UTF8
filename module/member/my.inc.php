<?php 
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/module/'.$module.'/common.inc.php';
$head_title = $action == 'add' ? '发布信息' : '管理信息';
include template('my', $module);
?>