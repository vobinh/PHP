// JavaScript Document
function validate_greater_than(field1,field2,alerttxt)
	{
		var i = parseFloat(field2.value);
		with (field1)
		{
			f1 = parseFloat(value);
			if (f1 > i )
			{
				alert(alerttxt);return false;
			}
			else
			{ 
				return true;
			}
		}
	}
	
	function validate_required(field,alerttxt)
	{
		var ret = true;
		var val = field.value;
		val = val.replace(/^\s+|\s+$/g,"");//trim
		if(eval(val.length) == 0) 
		{  
		   alert(alerttxt);
		   ret=false; 
		}//if 
		return ret;
	}
	
	function validate_smaller_than_zero(field1,alerttxt)
	{
		with (field1)
		{
			f1 = parseFloat(value);
			if (f1 < 0 )
			{
				alert(alerttxt);return false;
			}
			else
			{ 
				return true;
			}
		}
	}
	
	function validate_numeric(field,alerttxt)
	{
		var ret = true;
		var strRegExp = "[^0-9\.]";
		var charpos = field.value.search(strRegExp);
		
		if (field.value.length >0 && charpos >=0)
		{
			alert(alerttxt);
			ret = false;
		}
		return ret;
	}
	
	function validate_numeric_interger(field,alerttxt)
	{
		var ret = true;
		var strRegExp = "[^0-9]";
		var charpos = field.value.search(strRegExp);
		
		if (field.value.length >0 && charpos >=0)
		{
			alert(alerttxt);
			ret = false;
		}
		return ret;
	}
	
	function validateEmail(email)
	{
		var splitted = email.match("^(.+)@(.+)$");
		if(splitted == null) return false;
		if(splitted[1] != null )
		{
		  var regexp_user=/^\"?[\w-_\.]*\"?$/;
		  if(splitted[1].match(regexp_user) == null) return false;
		}
		if(splitted[2] != null)
		{
		  var regexp_domain=/^[\w-\.]*\.[A-Za-z]{2,4}$/;
		  if(splitted[2].match(regexp_domain) == null) 
		  {
			var regexp_ip =/^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
			if(splitted[2].match(regexp_ip) == null) return false;
		  }// if
		  return true;
		}
	return false;
	}
	
	function validate_email(field,alerttxt)
	{
		var ret = true;
		 if(field.value.length > 0 && !validateEmail(field.value)) 
		 { 
		   alert(alerttxt);
			ret = false;
		 }//if 
	return ret;
	}