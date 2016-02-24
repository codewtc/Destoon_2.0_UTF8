/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
var destoon_date, c_year, c_month, t_year, t_month, t_day, v_year, v_month, v_day, calendar_sep, calendar_id, calendar_interval, calendar_timeout;
var today = new Date();
t_year = today.getYear();
t_year = (t_year > 200) ? t_year : 1900 + t_year;
t_month = today.getMonth()+1;
t_day = today.getDate();
var calendar_htm = '';
calendar_htm += '<table width="100%" cellpadding="0" cellspacing="0" style="background:#2875B9;"><tr>';
calendar_htm += '<td style="color:#FFFFFF;-moz-user-select:none;" height="20" onselectstart="return false">&nbsp; <span onclick="calendar_prev_year();" onmousedown="calendar_setInterval(\'calendar_prev_year\');" onmouseup="calendar_clearInterval();" style="font-weight:bold;cursor:pointer;" title="前一年">&laquo;</span> <input type="text" maxlength="4" style="width:35px;border:none;color:#FFFFFF;text-align:center;background:#2875B9;padding:0 0 2px 0;" id="calendar_year" onblur="calendar_this_year();" ondblclick="this.value=\'\';" title="可直接填写年份，双击鼠标左键清空"/> <span onclick="calendar_next_year();" onmousedown="calendar_setInterval(\'calendar_next_year\');" onmouseup="calendar_clearInterval();" style="font-weight:bold;cursor:pointer;" title="后一年">&raquo;</span> 年 &nbsp;&nbsp; <span onclick="calendar_prev_month();" onmousedown="calendar_setInterval(\'calendar_prev_month\');" onmouseup="calendar_clearInterval();" style="font-weight:bold;cursor:pointer;" title="上一月">&laquo;</span> <input type="text" maxlength="2" style="width:16px;border:none;color:#FFFFFF;text-align:center;background:#2875B9;padding:0 0 2px 0;" id="calendar_month" onblur="calendar_this_month();" ondblclick="this.value=\'\';" title="可直接填写月份，双击鼠标左键清空"/> <span onclick="calendar_next_month();" onmousedown="calendar_setInterval(\'calendar_next_month\');" onmouseup="calendar_clearInterval();" style="font-weight:bold;cursor:pointer;" title="下一月">&raquo;</span> 月</td>';
calendar_htm += '<td align="right" style="color:#FFFFFF;font-weight:bold;cursor:pointer;" onclick="calendar_close();" title="Close">&#215;&nbsp;</td></tr></table>';
calendar_htm += '<div id="destoon_calendar_show" style="text-align:center;"></div>';
function get_days (year, month) {
	destoon_date = new Date(year, month, 1);
	destoon_date = new Date(destoon_date - (24*60*60*1000));
	return destoon_date.getDate();
}
function get_start (year, month) {
	destoon_date = new Date(year, month-1, 1);
	return destoon_date.getDay();
}
function calendar_setInterval(func) {
	calendar_timeout=setTimeout(function(){calendar_interval=setInterval(func+'()',200);},100);
}
function calendar_clearInterval () {
	clearTimeout(calendar_timeout);clearInterval(calendar_interval);
}
function calendar_this_year () {
	if($('calendar_year').value.match(/^(\d{4})$/)) {
		c_year = parseInt($('calendar_year').value);
		calendar_setup(c_year, c_month);
	} else {
		$('calendar_year').value = c_year;
	}
}
function calendar_next_year () {
	c_year = parseInt(c_year) + 1;
	calendar_setup(c_year, c_month);
}
function calendar_prev_year () {
	c_year = parseInt(c_year) - 1;
	calendar_setup(c_year, c_month);
}
function calendar_this_month () {
	if($('calendar_month').value.match(/^(\d{1,2})$/)) {
		c_month = parseInt($('calendar_month').value);
		calendar_setup(c_year, c_month);
	} else {
		$('calendar_month').value = c_month;
	}
}
function calendar_next_month () {
	if(c_month == 12) {
		c_year = parseInt(c_year) + 1;
		c_month = 1;
	} else {
		c_month = parseInt(c_month) + 1;
	}
	calendar_setup(c_year, c_month);
}
function calendar_prev_month () {
	if(c_month == 1) {
		c_year = parseInt(c_year) - 1;
		c_month = 12;
	} else {
		c_month = parseInt(c_month) - 1;
	}
	calendar_setup(c_year, c_month);
}
function calendar_setup(year, month) {
	if(year > 9999) year = 9999;
	if(year < 1970) year = 1970;
	if(month > 12) month = 12;
	if(month < 1) month = 1;
	c_year = year;
	c_month = month;
	var days = get_days(year, month);
	var start = get_start(year, month);
	var end = 7 - (days + start)%7;
	if(end == 7 ) end = 0;
	var calendar = '';
	var weeks = ['日','一','二','三','四','五','六'];
	var cells = new Array;
	var j = i = l = 0;
	$('calendar_year').value = year;
	$('calendar_month').value = month;
	if(start) for(i = 0; i < start; i++) { cells[j++] = 0; }
	for(i = 1; i<= days; i++) { cells[j++] = i; }
	if(end) for(i = 0; i < end; i++) { cells[j++] = 0; }
	calendar += '<table cellpadding="0" cellspacing="0" width="100%" title="Destoon Calendar Powered By Destoon.COM"><tr>';
	for(i = 0; i < 7; i++) {calendar += '<td width="26" height="24" bgcolor="#F1F1F1"><strong>'+(weeks[i])+'</strong></td>';}
	calendar += '</tr>';
	l = cells.length
	for(i = 0; i < l; i++) {
		if(i%7 == 0) calendar += '<tr height="24">';
		if(cells[i]) {
			calendar += '<td style="cursor:pointer;font-size:11px;border-top:#CCCCCC 1px solid;'+(i%7 == 6 ? '' : 'border-right:#CCCCCC 1px solid;')+'';
			if(year+'-'+month+'-'+cells[i] == v_year+'-'+v_month+'-'+v_day) {
				calendar += 'background:#FFFF00;"';
			} else if(year+'-'+month+'-'+cells[i] == t_year+'-'+t_month+'-'+t_day) {
				calendar += 'font-weight:bold;color:#FF0000;"';
			} else {
				calendar += 'background:#FFFFFF;" onmouseover="this.style.backgroundColor=\'#FFCC99\';" onmouseout="this.style.backgroundColor=\'#FFFFFF\';"';
			}
			calendar += 'title="'+year+'.'+month+'.'+cells[i]+'" onclick="calendar_select('+year+','+month+','+cells[i]+')"> '+cells[i]+' </td>';
		} else {
			calendar += '<td style="border-top:#CCCCCC 1px solid;'+(i%7 == 6 ? '' : 'border-right:#CCCCCC 1px solid;')+'">&nbsp;</td>';
		}
		if(i%7 == 6) calendar += '</tr>';
	}
	calendar += '</table>';
	$('destoon_calendar_show').innerHTML = calendar;
}
function calendar_show(id, obj, sep) {
	Eh();
	if($('destoon_calendar') == null) {
		var destoon_calendar_div = document.createElement("div");
		with(destoon_calendar_div.style) {
			zIndex = 9999;
			position = 'absolute';
			display = 'none';
			width = '196px';
			padding = '1px';
			top = 0;
			left = 0;
			border = '#A0A0A0 1px solid';
			backgroundColor = '#FFFFFF';
		}
		destoon_calendar_div.id = 'destoon_calendar';
		document.body.appendChild(destoon_calendar_div);
	}
	var aTag = obj;
	var leftpos = toppos = 0;
	do {
		aTag = aTag.offsetParent;
		leftpos	+= aTag.offsetLeft;
		toppos += aTag.offsetTop;
	} while(aTag.tagName != 'BODY');
	calendar_sep = sep;
	calendar_id = id;
	if($(id).value) {
		if(sep) {
			var arr = $(id).value.split(sep);
			c_year = v_year = arr[0];
			c_month = v_month = calendar_cutzero(arr[1]);
			v_day = calendar_cutzero(arr[2]);
		} else {
			c_year = v_year = $(id).value.substring(0, 4);
			c_month = v_month = calendar_cutzero($(id).value.substring(4, 6));
			v_day = calendar_cutzero($(id).value.substring(6, 8));
		}
	} else {
		c_year = t_year;
		c_month = t_month;
	}
	$('destoon_calendar').style.left = (obj.offsetLeft + leftpos) + 'px';
	$('destoon_calendar').style.top = (obj.offsetTop + toppos + 20) + 'px';
	$('destoon_calendar').innerHTML = calendar_htm;
	$('destoon_calendar').style.display = '';
	calendar_setup(c_year, c_month);

}
function calendar_select(year, month, day) {
	month = calendar_padzero(month);
	day = calendar_padzero(day);
	$(calendar_id).value = year+''+calendar_sep+month+calendar_sep+day;
	calendar_hide();
}
function calendar_padzero(num) {
	return (num	< 10)? '0' + num : num ;
}
function calendar_cutzero(num) {
	return num.substring(0, 1) == '0' ? num.substring(1, num.length) : num;
}
function calendar_hide() {
	$('destoon_calendar').style.display = 'none';
	Es();
}
function calendar_close() {
	calendar_hide();
}