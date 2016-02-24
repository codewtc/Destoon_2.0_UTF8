<?php 
defined('IN_DESTOON') or exit('Access Denied');
login();
require DT_ROOT.'/module/'.$module.'/common.inc.php';
require DT_ROOT.'/include/post.func.php';
isset($item) or message();
$names = array('friend'=>'商友', 'favorite'=>'收藏', 'product'=>'产品');
isset($names[$item]) or message();
require DT_ROOT.'/include/type.class.php';
$do = new dtype;
$do->item = $item.'-'.$_userid;

if($submit) {
	if($MG['type_limit'] && count($type) > $MG['type_limit']) dalert('最多可添加 '.$MG['type_limit'].' 个分类', 'goback');
	$do->update($type);
	dmsg('更新成功', '?item='.$item);
} else {
	$head_title = $names[$item].'分类管理';
	$types = $do->get_list();
	foreach($types as $k=>$v) {
		$types[$k]['style_select'] = dstyle('type['.$v['typeid'].'][style]', $v['style']);
	}
	$new_style = dstyle('type[0][style]');
	include template('type', $module);
}
?>