/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
var _sbt = false;
var _frm = _frm ? _frm : 'dform';
try {
	if(document.attachEvent) {
		$(_frm).attachEvent("onsubmit", sbt);
	} else {
		$(_frm).addEventListener("submit", sbt, false);
	}
} catch(e) {}
function sbt() {
	_sbt = true;
}
window.onunload=function() {
	if(!_sbt) makeRequest('action=clear', AJPath);
}