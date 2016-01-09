// JavaScript Document
//------------------------------------------------
function popupResize(x,y)
{	
	if (document.all) {
		window.resizeTo(x,y);
	}
	else if (document.layers||document.getElementById) {
		window.innerWidth = x;
		window.innerHeight = y;
	}		
}
function showpopup(url,w,h)
{
	showDialog(url, w, h);
}
function showDialog(url, width, height)
{
//	return showWindow(url, false,false, true, true, false, false, false, true, true, width, height, 0, 0);
	//return showWindow(url, true, width, height, 1);
	window.open(url, 'POPUP', 'width='+width+', height='+height+', marginwidth=0, marginheight=0, resizable=1, scrollbars=1, status=1');
}
/*function showWindow(url, isStatus,isMenubar, isResizeable, isScrollbars, isToolbar, isLocation, isFullscreen, isTitlebar, isCentered, width, height, top, left)
{
	if (isCentered)
	{
		top = (screen.height - height)/2;
		left = (screen.width - width) / 2;
	}
	
	var newwindow = open(url, '_blank', 'status=' + (isStatus ? 'yes' : 'no') + ','
	+ 'menubar=' + (isMenubar ? 'yes' : 'no') + ','								 
	+ 'resizable=' + (isResizeable ? 'yes' : 'no') + ','
	+ 'scrollbars=' + (isScrollbars ? 'yes' : 'no') + ','
	+ 'toolbar=' + (isToolbar ? 'yes' : 'no') + ','
	+ 'location=' + (isLocation ? 'yes' : 'no') + ','
	+ 'fullscreen=' + (isFullscreen ? 'yes' : 'no') + ','
	+ 'titlebar=' + (isTitlebar ? 'yes' : 'no') + ','
	+ 'height=' + height + ',' + 'width=' + width + ','
	+ 'left=' + left + ',' + 'top=' + left);
	newwindow.focus();
}
*/
function showWindow( page, isCentered, w, h, sb)
{
	var sw = screen.width, sh = screen.height;
  	var ulx = ((sw-w)/2), uly = ((sh-h)/2);
  	var sbt = (sb==1) ? 'yes' : 'no';

  	var paramz = 'toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=no,scrollbars='+sbt+',width='+w+',height='+h+'';
  	var oSubWin = window.open("","SubWin",paramz);
  	oSubWin.focus();
	if (isCentered)
	{
		oSubWin.moveTo(ulx, uly);
	}  
  	oSubWin.location.replace(page);  
}	
function close_popup()
{
	window.opener.location.reload();
	window.close();
}
function close_refresh() 
{
    window.opener.location.href = window.opener.location.href;
    window.close();
}
