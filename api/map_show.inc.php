<?php 
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
/* 公司地图显示接口 */
isset($map_height) or $map_height = 300;
?>
<div style="height:<?php echo $map_height;?>px;margin:auto;overflow:hidden;" id="myMap"></div>
<script type="text/javascript" src="http://api.51ditu.com/js/maps.js"></script>
<script type="text/javascript">
window.onload = function() {
	var map=new LTMaps("myMap");
	map.addControl(new LTSmallMapControl());
	var point=new LTPoint(<?php echo $map;?>);
	map.centerAndZoom(point, 3);
	var marker = new LTMarker(point,new LTIcon('<?php echo SKIN_PATH;?>image/map_point.gif',[20,20],[12,12]));map.addOverLay(marker);
	var text = new LTMapText(marker);text.setLabel( "<div style=\"padding:3px;\"; title=\"<?php echo $COM['address'];?>\"><?php echo $COM['company'];?></div>" );
	map.addOverLay(text);
}
</script>