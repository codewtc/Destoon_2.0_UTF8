/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
var DMURL = document.location.protocol+'//'+location.hostname+(location.port ? ':'+location.port : '')+'/';
var AJPath = (DTPath.indexOf('://') == -1 ? DTPath : (DTPath.indexOf(DMURL) == -1 ? DMURL : DTPath))+'ajax.php';
if(isIE) try{document.execCommand("BackgroundImageCache", false, true);}catch(e){}
var xmlHttp;
var Try = {
  these: function() {
    var returnValue;
    for (var i = 0; i < arguments.length; i++) {
      var lambda = arguments[i];
      try {
        returnValue = lambda();
        break;
      } catch (e) {}
    }
    return returnValue;
  }
}
function makeRequest(queryString, php, func, method) {
	xmlHttp = Try.these(
      function() {return new XMLHttpRequest()},
      function() {return new ActiveXObject('Msxml2.XMLHTTP')},
      function() {return new ActiveXObject('Microsoft.XMLHTTP')}
    );	
	method = (typeof method == 'undefined') ? 'post' : 'get';
	if(func) xmlHttp.onreadystatechange = eval(func);
	xmlHttp.open(method, php, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.send(method == 'post' ? queryString : null);
}
function $() {
  var elements = new Array();
  for (var i = 0; i < arguments.length; i++) {
    var element = arguments[i];
    if (typeof element == 'string') element = document.getElementById(element);
    if (arguments.length == 1) return element;
    elements.push(element);
  }
  return elements;
}
var tID=0;
function Tab(ID) {
	var tTab=$('Tab'+tID);
	var tTabs=$('Tabs'+tID);
	var Tab=$('Tab'+ID);
	var Tabs=$('Tabs'+ID);
	if(ID!=tID)	{
		tTab.className='tab';
		Tab.className='tab_on';
		tTabs.style.display='none';
		Tabs.style.display='';
		tID=ID;
		try{$('tab').value=ID;}catch(e){}
	}
}
function checkall(form) {
	form = $(form);
	for(var i = 0; i < form.elements.length; i++) {
		var e = form.elements[i];
		if(e.type != 'checkbox') continue;
		e.checked = e.checked ? false : true;
	}
}
function select_op(id, val) {
	var o = $(id);
	for(var i=0; i<o.options.length; i++) {
		if(o.options[i].value == val) {o.options[i].selected=true;break;}
	}
}
function Dmsg(str, id, s, t) {
	var t = t ? t : 5000;
	var s = s ? true : false;
	try{if(s){window.scrollTo(0,0);}$('d'+id).innerHTML = '<img src="'+SKPath+'image/check_error.gif" width="12" height="12" align="absmiddle"/> '+str+sound('tip');$(id).focus();}catch(e){}
	window.setTimeout(function(){$('d'+id).innerHTML = '';}, t);
}
function confirmURI(message, forward) {
	if(confirm(message)) window.location = forward;
}
function Go(url) {
	window.location = url;
}
function showmsg(msg, t) {
	var t = t ? t : 5000;
	var s = msg.indexOf('删除') != -1 ? 'delete' : 'ok';
	try{$('msgbox').style.display = '';$('msgbox').innerHTML = msg+sound(s);window.setTimeout('closemsg();', t);}catch(e){}
}
function closemsg() {
	try{$('msgbox').innerHTML = '';$('msgbox').style.display = 'none';}catch(e){}
}
function sound(file) {
	return '<div style="float:left;"><embed src="'+DTPath+'file/flash/'+file+'.swf" quality="high" type="application/x-shockwave-flash" height="0" width="0" hidden="true"/></div>';
}
function Ds(ID) {
	$(ID).style.display = '';
}
function Dh(ID) {
	$(ID).style.display = 'none';
}
function Eh(tag) {
	var tag = tag ? tag : 'select';
	if(isIE) {
		var arVersion = navigator.appVersion.split("MSIE");
		var IEversion = parseFloat(arVersion[1]);		
		if(IEversion >= 7 || IEversion < 5) return;
		var ss = document.body.getElementsByTagName(tag);					
		for(var i=0;i<ss.length;i++) {
			ss[i].style.visibility = 'hidden';
		}
	}
}
function Es(tag) {
	var tag = tag ? tag : 'select';
	if(isIE) {
		var arVersion = navigator.appVersion.split("MSIE");
		var IEversion = parseFloat(arVersion[1]);		
		if(IEversion >= 7 || IEversion < 5) return;
		var ss = document.body.getElementsByTagName(tag);					
		for(var i=0;i<ss.length;i++) {
			ss[i].style.visibility = 'visible';
		}
	}
}
function FCKLen(ID) {
	var ID = ID ? ID : 'content';
	var oEditor = FCKeditorAPI.GetInstance(ID);
	var oDOM = oEditor.EditorDocument;
	var iLength ;
	if (document.all) {
		iLength = oDOM.body.innerText.length;
	} else {
		var r = oDOM.createRange() ;
		r.selectNodeContents(oDOM.body);
		iLength = r.toString().length;
	}
	return iLength;
}
function Tb(id, t, p, c) {
	for(var i=1; i<=t; i++) {
		if(id == i) {
			$(p+'_t_'+i).className = c+'_2';
			$(p+'_c_'+i).style.display = '';
		} else {
			$(p+'_t_'+i).className = c+'_1';
			$(p+'_c_'+i).style.display = 'none';
		}
	}
}
function is_captcha(v) {
	return v.match(/^[a-z0-9A-Z]{4,}$/);
}
function ext(v) {
	return v.substring(v.lastIndexOf('.')+1, v.length);
}
function set_cookie(name, value, day) {
	var expire = "";
	var day_value = 365;
	if(day != null) day_value=day;
	expire = new Date((new Date()).getTime() + day_value * 86400000);
	expire = "; expires=" + expire.toGMTString();
	document.cookie = CKPrex + name + "=" + value + ((CKPath == "") ? "" : ("; path=" + CKPath)) + ((CKDomain =="") ? "" : ("; domain=" + CKDomain)) + expire; 

}
function get_cookie(name) {
	var value = '';
	var search = CKPrex + name + "=";
	if(document.cookie.length > 0) {
		offset = document.cookie.indexOf(search);
		if (offset != -1) {	
			offset += search.length;
			end = document.cookie.indexOf(";", offset);
			if (end == -1) end = document.cookie.length;
			value = unescape(document.cookie.substring(offset, end))
		}
	}
	return value;
}
function del_cookie(name) {
	var expire = "";
	expire = new Date((new Date()).getTime() - 1 );
	expire = "; expires=" + expire.toGMTString();
	document.cookie = CKPrex + name + "=" + escape("") +";path=/"+ expire;
}
document.onkeydown = function(e) {
	var k = typeof e == 'undefined' ? event.keyCode : e.keyCode;
	if(k == 37) {
		try{if($('destoon_previous').href)window.location=$('destoon_previous').href;}catch(e){}
	} else if(k == 39) {
		try{if($('destoon_next').href)window.location=$('destoon_next').href;}catch(e){}
	} else if(k == 38 || k == 40 || k == 13) {
		try{if($('search_tips').style.display != 'none' || $('search_tips').innerHTML != ''){SCTip(k);return false;}}catch(e){}
	}
}