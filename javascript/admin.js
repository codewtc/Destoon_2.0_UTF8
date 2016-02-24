/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
function mkDialog(u, c, t, w, s, p, px, py) {
	var w = w ? w : 300;
	var u = u ? u : '';
	var c = c ? c : (u ? '<iframe src="'+u+'" width="'+(w-25)+'" height="0" border="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" framespacing="0" frameborder="0" scrolling="no"></iframe>' : '');
	var t = t ? t : '系统提示';
	var s = s ? s : 0;
	var p = p ? p : 0;
	var px = px ? px : 0;
	var py = py ? py : 0;
	var body = document.documentElement || document.body;
	var cw = body.clientWidth;
	var ch = body.clientHeight;
	var bsw = body.scrollWidth;
	var bsh = body.scrollHeight;
	var bw = parseInt((bsw < cw) ? cw : bsw);
	var bh = parseInt((bsh < ch) ? ch : bsh);
	if(!s) {
		var Dmid = document.createElement("div");
		with(Dmid.style){zIndex = 998; position = 'absolute'; width = '100%'; height = bh+'px'; overflow = 'hidden'; top = 0; left = 0; border = "0px"; backgroundColor = '#EEEEEE';if(isIE){filter = " Alpha(Opacity=50)";}else{opacity = 50/100;}}
		Dmid.id = "Dmid";
		document.body.appendChild(Dmid);
	}
	var sl = px ? px : body.scrollLeft + parseInt((cw-w)/2);
	var st = py ? py : body.scrollTop + parseInt(ch/2) - 100;
	var Dtop = document.createElement("div");
	with(Dtop.style){zIndex = 999; position = 'absolute'; width = w+'px'; left = sl+'px'; top = st+'px'; if(isIE){filter = " Alpha(Opacity=0)";}else{opacity = 0;}}
	Dtop.id = 'Dtop';
	document.body.appendChild(Dtop);
	$('Dtop').innerHTML = '<div class="dbody"><div class="dhead" ondblclick="cDialog();" onmousedown="dragstart(\'Dtop\', event);"  onmouseup="dragstop(event);" onselectstart="return false;"><span onclick="cDialog();">'+sound('tip')+'&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;'+t+'</div><div class="dbox">'+c+'</div></div>';
	sDialog('Dtop', 100, '+');
}
function cDialog() {
	sDialog('Dtop', 100,  '-');
}
function sDialog(ID, v, t) {
	if(t == '+') {
		if(isIE) {$(ID).style.filter = 'Alpha(Opacity='+v+')';} else {$(ID).style.opacity = v/100;}
		if(v == 100) {
			Eh();
			return;
		}
		if(v+25 < 100) {window.setTimeout(function(){sDialog(ID, v+25, t);}, 1);} else {sDialog(ID, 100, t);}
	} else {
		try{
			$(ID).style.display = 'none';
			document.body.removeChild($('Dtop'));
			$('Dmid').style.display = 'none';
			document.body.removeChild($('Dmid'));
			Es();
		}
		catch(e){}
	}
}
function Dalert(c, w, s, t) {
	if(!c) return;
	var s = s ? s : 0;
	var w = w ? w : 350;
	var t = t ? t : 0;
	c = c + '<br style="margin-top:5px;"/><input type="button" class="btn" value=" 确 定 " onclick="cDialog();"/>';
	mkDialog('', c, '', w, s);
	if(t) window.setTimeout(function(){cDialog();}, t);
}
function Dconfirm(c, u, w, s) {
	if(!c) return;
	var s = s ? s : 0;
	var w = w ? w : 350;
	var d = u ? "window.location = '"+u+"'" : 'cDialog()';
	c = c +'<br style="margin-top:5px;"/><input type="button" class="btn" value=" 确 定 " onclick="'+d+'"/>&nbsp;&nbsp;<input type="button" class="btn" value=" 取 消 " onclick="cDialog();"/>';
	mkDialog('', c, '', w, s);
}
function Diframe(u, w, s, l, t) {
	var s = s ? s : 0;	
	var w = w ? w : 350;
	var l = l ? true : false;
	var c = '<iframe src="'+u+'" width="'+(w-25)+'" height=0" id="diframe" border="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" framespacing="0" frameborder="0" scrolling="no"></iframe><br/><input type="button" class="btn" value=" 确 定 " onclick="cDialog();"/>';
	if(l) c = '<div id="dload" style="line-height:22px;">Loading...</div>'+c;
	mkDialog('', c, t, w, s);
}
function Dtip(c, w, t) {
	if(!c) return;
	var w = w ? w : 350;
	var t = t ? t : 2000;
	mkDialog('', c, '', w);
	window.setTimeout(function(){cDialog();}, t);
}
function Dfile(m, o, i) {
	var c = '<iframe name="UploadFile" style="display: none" src=""></iframe>';
	c += '<form method="post" target="UploadFile" enctype="multipart/form-data" action="'+DTPath+'upload.php"><input type="hidden" name="moduleid" value="'+m+'"/><input type="hidden" name="from" value="file"/><input type="hidden" name="old" value="'+o+'"/><input type="hidden" name="fid" value="'+i+'"/><table cellpadding="3"><tr><td><input id="upfile" type="file" size="20" name="upfile"/></td></tr><td><input type="submit" class="btn" value="上 传" />&nbsp;&nbsp;<input type="button" class="btn" value="取 消" onclick="cDialog();"/></td></tr></table></form>';
	mkDialog('', c, '上传文件', 250);
}
function Dthumb(m, w, h, o, s, i) {
	var s = s ? 'none' : '';
	var i = i ? i : 'thumb';
	var c = '<iframe name="UploadThumb" style="display: none" src=""></iframe>';
	c += '<form method="post" target="UploadThumb" enctype="multipart/form-data" action="'+DTPath+'upload.php" onsubmit="return isImg(\'upthumb\');"><input type="hidden" name="moduleid" value="'+m+'"/><input type="hidden" name="from" value="thumb"/><input type="hidden" name="old" value="'+o+'"/><input type="hidden" name="fid" value="'+i+'"/><table cellpadding="3"><tr><td><input id="upthumb" type="file" size="20" name="upthumb"/></td></tr><tr style="display:'+s+'"><td>宽度 <input type="text" size="3" name="width" value="'+w+'"/> px &nbsp;&nbsp;&nbsp;高度 <input type="text" size="3" name="height" value="'+h+'"/> px </td></tr><tr><td><input type="submit" class="btn" value="上 传" />&nbsp;&nbsp;<input type="button" class="btn" value="取 消" onclick="cDialog();"/></td></tr></table></form>';
	mkDialog('', c, '上传图片', 250);
}
function Dalbum(fid, m, w, h, o, s) {
	var s = s ? 'none' : '';
	var c = '<iframe name="UploadAlbum" style="display: none" src=""></iframe>';
	c += '<form method="post" target="UploadAlbum" enctype="multipart/form-data" action="'+DTPath+'upload.php" onsubmit="return isImg(\'upalbum\');"><input type="hidden" name="fid" value="'+fid+'"/><input type="hidden" name="moduleid" value="'+m+'"/><input type="hidden" name="from" value="album"/><input type="hidden" name="old" value="'+o+'"/><table cellpadding="3"><tr><td><input id="upalbum" type="file" size="20" name="upalbum"/></td></tr><tr style="display:'+s+'"><td>宽度 <input type="text" size="3" name="width" value="'+w+'"/> px &nbsp;&nbsp;&nbsp;高度 <input type="text" size="3" name="height" value="'+h+'"/> px </td></tr><tr><td><input type="submit" class="btn" value="上 传" />&nbsp;&nbsp;<input type="button" class="btn" value="取 消" onclick="cDialog();"/></td></tr></table></form>';
	mkDialog('', c, '上传图片', 250);
}
function getAlbum(v, id) {
	$('thumb'+id).value = v;
	$('showthumb'+id).src = v;
}
function delAlbum(id, s) {
	$('thumb'+id).value = '';
	$('showthumb'+id).src = SKPath+'image/'+s+'pic.gif';
}
function isImg(ID) {
	var v = $(ID).value;
	if(v == '') {
		confirm('请选择文件');
		return false;
	}	
	var file_ext = ext(v);
	file_ext = file_ext.toLowerCase();
	var allow = "jpg|gif|png|jpeg";
	if(allow.indexOf(file_ext) == -1){
		confirm('仅允许'+allow+'图片格式');
		return false;
	}
	return true;
}
function check_box(f, t) {
	var t = t ? true : false;
	var box = $(f).getElementsByTagName('input');
	for(var i = 0; i < box.length; i++) {
		box[i].checked = t;
	}
}
function _user(name) {
	mkDialog('', '<iframe src="?moduleid=2&action=show&dialog=1&username='+name+'" width="750" height=350" border="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" framespacing="0" frameborder="0" scrolling="yes"></iframe>', '会员'+name+'资料', 775, 0, 0);
}
function _islink() {
	if($('islink').checked) {
		$('link').style.display = '';
		$('basic').style.display = 'none';
		$('linkurl').focus();
		if($('linkurl').value == '') $('linkurl').value = 'http://';
	} else {
		$('link').style.display = 'none';
		$('basic').style.display = '';
	}
}
function _preview(src, thumb) {
	var thumb = thumb ? true : false;
	if(src) {
		if(thumb) {
			var pos = src.lastIndexOf('.thumb.');
			if(pos != -1) src = src.substring(0, pos);
		}
		mkDialog('', '<img src="'+src+'" onload="$(\'Dtop\').style.width=(this.width+20)+\'px\';"/>', '图片预览');
	} else {
		Dtip('不可预览，图片地址为空');
	}
}
function _delete() {
	return confirm('确定要删除吗？此操作将不可撤销');
}
function _into(ID, str) {
	var o = $(ID);
	if(typeof document.selection != 'undefined') {
		o.focus();
		var r = document.selection.createRange();
		var ctr = o.createTextRange();
		var i;
		var s = o.value;
		var w = "www.destoon.com"; 
		r.text = w;
		i = o.value.indexOf(w);
		r.moveStart("character", -w.length);
		r.text = "";
		o.value = s.substr(0, i) + str + s.substr(i, s.length);
		ctr.collapse(true);
		ctr.moveStart("character", i + str.length);
		ctr.select();
	} else if(o.setSelectionRange) {
		var s = o.selectionStart;   
		var e =  o.selectionEnd;   
		var a = o.value.substring(0, s);   
		var b = o.value.substring(e);   
		o.value = a + str + b;
	} else {
		$(ID).value = $(ID).value + str;		
		o.focus();
	}
}
function Menuon(ID) {
	try{$('Tab'+ID).className='tab_on';}catch(e){}
}
var dgX = dgY = 0;       
var dgDiv;
function dragstart(id, e) {
	dgDiv = document.getElementById(id);    
	if(!e) e = window.event;
    dgX = e.clientX - parseInt(dgDiv.style.left);
    dgY = e.clientY - parseInt(dgDiv.style.top);
	document.onmousemove = dragmove;
}
function dragmove(e) { 
    if(!e) e = window.event;
    dgDiv.style.left = (e.clientX - dgX) + 'px';
    dgDiv.style.top = (e.clientY - dgY) + 'px';
}
function dragstop() {
	dgX =dgY =0;
	document.onmousemove = null;
}