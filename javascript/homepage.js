/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
function $(ID){return document.getElementById(ID);}
function ext(v) {
	return v.substring(v.lastIndexOf('.')+1, v.length);
}
function Album(id, s) {
	for(var i=0;i<3;i++) {
		$('t_'+i).className = i==id ? 'ab_on' : 'ab_im';
	}
	$('abm').innerHTML = '<img src="'+s+'" onload="if(this.width>200){this.width=200;}" onclick="window.open(this.src.substring(0, this.src.length-8-ext(this.src).length));" onmouseover="SAlbum(this.src);" onmouseout="HAlbum();"/>';
}
function SAlbum(s) {
	if(s.indexOf('nopic200.gif') != -1) return;
	s = s.substring(0, s.length-8-ext(s).length);
	$('imgshow').innerHTML = '<img src="'+s+'" onload="if(this.width<200){HAlbum();}else if(this.width>400){this.width=400;}"/>';
	$('imgshow').style.display = '';
}
function HAlbum() {
	$('imgshow').style.display = 'none';
}
function check_kw() {
	if($('kw').value == '输入关键词' || $('kw').value.length<2) {
		alert('请输入关键词');
		$('kw').focus();
		return false;
	}
}
function show_date() {
	var dt_day = '';
	var dt_month = '';
	var dt_weekday = '';
	var dt_cnweek = ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'];
	dt_today=new Date();
	dt_weekday=dt_today.getDay();
	dt_month=dt_today.getMonth()+1;
	dt_day= dt_today.getDate();
	document.write('今天是'+dt_month+'月'+dt_day+'日 '+dt_cnweek[dt_weekday]+' ');
}
function ImgZoom(Id, m){
	var m = m ? m : 550;
	var w = Id.width;
	if(w < m){
		return;
	} else {
		var h = Id.height;
		Id.title = '点击打开原图';
		Id.onclick = function (e) {window.open(Id.src);}
		Id.height = parseInt(h*m/w);
		Id.width = m;
	}
}