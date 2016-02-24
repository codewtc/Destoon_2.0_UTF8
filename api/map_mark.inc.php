<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
/* 公司地图标注接口 */
?>
<tr>
<td class="tl">公司地图标注</td>
<td class="tr">
<input type="hidden" name="setting[map]" id="map" value="<?php echo $map;?>"/>
<script type='text/javascript' src='http://api.51ditu.com/js/maps.js'></script>
<script type='text/javascript' src='http://api.51ditu.com/js/search.js'></script>
<script type='text/javascript' src='http://api.51ditu.com/js/ezmarker.js'></script>
<script type='text/javascript'>
var ez=new LTEZMarker('ez');
var point=new LTPoint(<?php echo $map ? $map : '11640969,3989945';?>);
ez.setSearch(true, '');//默认城市
ez.setDefaultView(point, <?php echo $map ? 3 : 10;?>);//显示比例
ez.setPlaceList(false);
LTEvent.addListener(ez,'mark',setMap);
function setMap(point,zoom){
var point=point.getLongitude().toString()+','+point.getLatitude().toString();
$('map').value=point;
}
</script>
&nbsp;&nbsp;
提示：打开地图后，请将地图放大至街道比例
</td>
</tr>