// JavaScript Document
function checkedAll(checked) {
	
	if(checked == true) checked = false;
	else checked = true;
	
	if (checked == false){checked = true}else{checked = false}
	for (var i = 0; i < document.getElementById('frmlist').elements.length; i++) {
		document.getElementById('frmlist').elements[i].checked = checked;
	}
}
function submit_form(frm,action)
{
	frm.action = action;
	frm.submit();
}
function sm_form(frm,action)
{
	frm.action = action;
	frm.submit();
}
function sm_frm(frm,action,strcf){
 
	fchk = 1;
	strdo = document.getElementById('sel_action').value;
	
	if(strdo == 'delete')
	{
		if(confirm(strcf) == false){
		  fchk = 0;
		}  
	}
	
	if(fchk == 1){
	  frm.action = action;
	  frm.submit();
	}
  
}
function confirmDelete(msg,url) {
	if (confirm(msg)) {
		document.location = url;
	}
}
function set_default_focus(id_ele)
{
	document.getElementById(id_ele).focus();
}
//FORMAT NUMBER FLOAT
//EXAMPLE : <input name="dollar" size="5" onkeypress="return isNumberFloat(this, event)">
function isNumberFloat(myfield, e) {
	var key;
	var keychar;
	
	if (window.event) {
		key = window.event.keyCode;
	} else if (e) {
		key = e.which;
	} else return true;
	
	//GET KEY CHAR
	keychar = String.fromCharCode(key);
	
	if((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27)) {
		return true;
	} else if(((myfield.value).indexOf('.') > -1)) {// KIEM TRA CO DAU "."
		if((("0123456789").indexOf(keychar) > -1)) return true;
		else return false;
	//KIEM TRA PHAI LA SO KHONG
	} else if ((("0123456789.").indexOf(keychar) > -1)) return true; else return false;
}
//FOTMAT NUMBER INT
//EXAMPLE : <input type="text" onkeypress="return isNumberInt(event)" />
function isNumberInt(evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;
	return true;
}
//FOTMAT NUMBER ONLY
//EXAMPLE : <input type="text" onkeypress="return numbersonly(this,event)" />
function numbersonly(myfield, e) {
	var key;
	var keychar;
	
	if (window.event) {
		key = window.event.keyCode;
	} else if (e) {
		key = e.which;
	} else return true;
	
	//GET KEY CHAR
	keychar = String.fromCharCode(key);
	
	if((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27)) {
		return true;
	} else if(((myfield.value).indexOf('.') > -1)) {// KIEM TRA CO DAU "."
		if((("0123456789").indexOf(keychar) > -1)) return true;
		else return false;
	//KIEM TRA PHAI LA SO KHONG
	} else if ((("0123456789.").indexOf(keychar) > -1)) return true; else return false;
}
//CHECK USERNAME
//EXAMPLE : <input type="text" onkeypress="return isUsername(this,event)" />
function isUsername(myfield, e) {
	var key;
	var keychar;
	
	if (window.event) {
		key = window.event.keyCode;
	} else if (e) {
		key = e.which;
	} else return true;
	
	//GET KEY CHAR
	keychar = String.fromCharCode(key);

	var re = new RegExp(/[a-zA-Z0-9]/);
	if(keychar.match(re)) {
		return true;
	}else {
		return false;
	}
}
//CHECK EMAIL
function valid_email(email) {
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(!filter.test(email)) return false;
	return true;
}
//NUMBER FORMAT
function number_format(number, decimals, dec_point, thousands_sep) {
    // Formats a number with grouped thousands  
    // 
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/number_format
    // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     bugfix by: Michael White (http://getsprink.com)
    // +     bugfix by: Benjamin Lupton
    // +     bugfix by: Allan Jensen (http://www.winternet.no)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +     bugfix by: Howard Yeend
    // +    revised by: Luke Smith (http://lucassmith.name)
    // +     bugfix by: Diogo Resende
    // +     bugfix by: Rival
    // +      input by: Kheang Hok Chin (http://www.distantia.ca/)
    // +   improved by: davook
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Jay Klehr
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Amir Habibi (http://www.residence-mixte.com/)
    // +     bugfix by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Theriault
    // +      input by: Amirouche
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: number_format(1234.56);
    // *     returns 1: '1,235'
    // *     example 2: number_format(1234.56, 2, ',', ' ');
    // *     returns 2: '1 234,56'
    // *     example 3: number_format(1234.5678, 2, '.', '');
    // *     returns 3: '1234.57'
    // *     example 4: number_format(67, 2, ',', '.');
    // *     returns 4: '67,00'
    // *     example 5: number_format(1000);
    // *     returns 5: '1,000'
    // *     example 6: number_format(67.311, 2);
    // *     returns 6: '67.31'
    // *     example 7: number_format(1000.55, 1);
    // *     returns 7: '1,000.6'
    // *     example 8: number_format(67000, 5, ',', '.');
    // *     returns 8: '67.000,00000'
    // *     example 9: number_format(0.9, 0);
    // *     returns 9: '1'
    // *    example 10: number_format('1.20', 2);
    // *    returns 10: '1.20'
    // *    example 11: number_format('1.20', 4);
    // *    returns 11: '1.2000'
    // *    example 12: number_format('1.2000', 3);
    // *    returns 12: '1.200'
    // *    example 13: number_format('1 000,50', 2, '.', ' ');
    // *    returns 13: '100 050.00'
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
//Alt & Title of Images
(function($){

    $.fn.imgAlt = function(options) {
        var settings = {
            _alt : 'alt',
            _title : 'title'
        }
		
        function build(self) {
            self.find('img').each(function() {
                $(this).attr('alt', settings._alt);
                $(this).attr('title', settings._title);
            });
        }
        
        return this.each(function() {
            if (options) { 
                $.extend(settings, options);
            }
            var self = $(this);
			
            build(self);
        });

    };
})(jQuery);