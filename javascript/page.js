/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
function Print(id) {
	if(isIE) {
		window.print();
	} else {
		var id = id ? id : 'content';
		var w = window.open('','','');
		w.opener = null;
		w.document.write('<div style="width:630px;">'+$(id).innerHTML+'</div>');
		w.window.print();
	}
}
function addFav(title) {
	document.write('<a href="'+window.location.href+'" title="'+document.title+'" rel="sidebar" onclick="window.external.addFavorite(this.href, this.title);return false;">'+title+'</a>');
}
function fontZoom(z, id){
	var id = id ? id : 'content';
	var size = $(id).style.fontSize ? $(id).style.fontSize : '14px';
	var new_size = Number(size.replace('px', ''));
	new_size += z == '+' ? 1 : -1;
	if(new_size < 1) new_size = 1;
	$(id).style.fontSize=new_size+'px';
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
function Inner(id, s) {
	try {$(id).innerHTML = s; }catch(e){}
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
	$('imgshow').innerHTML = '<img src="'+s+'" onload="if(this.width<200){HAlbum();}else if(this.width>630){this.width=630;}"/>';
	Ds('imgshow');
}
function HAlbum() {
	Dh('imgshow');
}
function Dsearch() {
	if($('destoon_kw').value.length < 2 || $('destoon_kw').value == '请输入关键字') {
		$('destoon_kw').value = '';
		window.setTimeout(function(){$('destoon_kw').value = '请输入关键字';}, 500);
		return false;
	}
}
function setModule(name, url, id) {
	$('destoon_search').action = url+'search.php';
	$('destoon_search_m').href = url+'search.php';
	$('destoon_select').value = name;
	Dh('search_module');
	searchid = id;
	setKW();
}
function setKW() {
	makeRequest('action=keyword&mid='+searchid, AJPath, '_setKW');
}
function _setKW() {
	if(xmlHttp.readyState==4 && xmlHttp.status==200) {
		if(xmlHttp.responseText) $('search_word').innerHTML = '<strong>热门搜索：</strong> ' + xmlHttp.responseText;
	}
}
function setTip(word) {
	Dh('search_tips');
	$('destoon_kw').value = word;
	$('destoon_search').submit();
}
var tip_word = '';
function STip(word) {
	if(word.length < 2) {
		$('search_tips').innerHTML = '';
		Dh('search_tips');
		return;
	}
	if(word == tip_word) {
		return;
	} else {
		tip_word = word;
	}
	makeRequest('action=tipword&mid='+searchid+'&word='+word, AJPath, '_STip');
}
function _STip() {
	if(xmlHttp.readyState==4 && xmlHttp.status==200) {
		if(xmlHttp.responseText) {
			Ds('search_tips');
			$('search_tips').innerHTML = xmlHttp.responseText + '<label onclick="Dh(\'search_tips\');">关闭&nbsp;&nbsp;</label>';
		} else {
			$('search_tips').innerHTML = '';
			Dh('search_tips');
		}
	}
}
function SCTip(k) {
	var o = $('search_tips');
	if(o.style.display == 'none') {
		if(o.innerHTML != '') Ds('search_tips');
	} else {
		if(k == 13) {$('destoon_search').submit();return;}
		$('destoon_kw').blur();
		var d = o.getElementsByTagName('div');
		var l = d.length;
		var n, p;
		var c = w = -2;
		for(var i=0; i<l; i++) { if(d[i].className == 'search_t_div_2') c = i; }
		if(c == -2) {
			n = 0; p = l-1;
		} else if(c == 0) {
			n = 1; p = -1;
		} else if(c == l-1) {
			n = -1; p = l-2; 
		} else {
			n = c+1; p = c-1;
		}
		w = k == 38 ? p : n;
		if(c >= 0) d[c].className = 'search_t_div_1';
		if(w >= 0) d[w].className = 'search_t_div_2';
		if(w >= 0) {
			var r = d[w].innerHTML.split('>'); $('destoon_kw').value = r[2];
		} else {
			$('destoon_kw').value = tip_word;
		}
	}
}
function setFModule(name, url, id) {
	$('foot_search').action = url+'search.php';
	var ss = $('foot_search_m').getElementsByTagName('span');
	for(var i=0;i<ss.length;i++) {
		if(ss[i].id == 'foot_search_m_'+id) {
			ss[i].className = 'f_b';
			$('foot_search_s').value = '找'+ss[i].innerHTML;
		} else {
			ss[i].className = '';
		}	
	}
}
function Fsearch() {
	if($('foot_kw').value.length < 2 || $('foot_kw').value == '请输入关键字') {
		$('foot_kw').value = '';
		window.setTimeout(function(){$('foot_kw').value = '请输入关键字';}, 500);
		return false;
	}
}
function user_login() {
	if(!$('user_name').value.match(/^[a-z0-9@\.]{3,}$/)) {
		$('user_name').focus();
		return false;
	}
	if($('user_pass').value == 'password' || $('user_pass').value.length < 4) {
		$('user_pass').focus();
		return false;
	}
}
function show_comment(url, mid, itemid) {
	document.write('<iframe src="'+url+'comment.php?mid='+mid+'&itemid='+itemid+'" name="comment" id="comment" style="width:99%;height:0px;" scrolling="no" frameborder="0"></iframe>');
}
var sell_n = 0;
function sell_tip(o, i) {
	if(o.checked) {
		sell_n++;
		$('item_'+i).style.backgroundColor='#F1F6FC';
	} else {
		$('item_'+i).style.backgroundColor='#FFFFFF';
		sell_n--;
	}
	if(sell_n < 0) sell_n = 0;
	if(sell_n > 1) {
		var aTag = o;
		var leftpos = toppos = 0;
		do {
			aTag = aTag.offsetParent;
			leftpos	+= aTag.offsetLeft;
			toppos += aTag.offsetTop;
		} while(aTag.tagName != 'BODY');
		var X = o.offsetLeft + leftpos - 10;
		var Y = o.offsetTop + toppos - 70;
		$('sell_tip').style.left = X + 'px';
		$('sell_tip').style.top = Y + 'px';
		o.checked ? Ds('sell_tip') : Dh('sell_tip');
	} else {
		Dh('sell_tip');
	}
}
function img_tip(o, i) {
	if(i) {
		if(i.indexOf('nopic.gif') == -1 && i.indexOf('.thumb.') != -1) {
			var s = i.split('.thumb.');
			var aTag = o;
			var leftpos = toppos = 0;
			do {
				aTag = aTag.offsetParent;
				leftpos	+= aTag.offsetLeft;
				toppos += aTag.offsetTop;
			} while(aTag.tagName != 'BODY');
			var X = o.offsetLeft + leftpos + 90;
			var Y = o.offsetTop + toppos - 20;
			$('img_tip').style.left = X + 'px';
			$('img_tip').style.top = Y + 'px';
			Ds('img_tip');
			Inner('img_tip', '<img src="'+s[0]+'" onload="if(this.width<200) {Dh(\'img_tip\');}else if(this.width>300){this.width=300;}$(\'img_tip\').style.width=this.width+\'px\';"/>')
		}
	} else {
		Dh('img_tip');
	}
}