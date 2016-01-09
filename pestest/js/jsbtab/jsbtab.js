/***********************************************
* Tab Menu- By Alf Magne Kalleland www.dhtmlgoodies.com
* Script featured and available at Dynamic Drive: http://www.dynamicdrive.com/
* This notice must stay intact for use
***********************************************/
		
	var topMenuSpacer = 15; // Horizontal space(pixels) between the main menu items	
	var activateSubOnClick = false; // if true-> Show sub menu items on click, if false, show submenu items onmouseover
	var leftAlignSubItems = false; 	// left align sub items t
	var activeMenuItem = false;	// Don't change this option. It should initially be false
	var activeTabIndex = 0;	// Index of initial active tab	(0 = first tab) - If the value below is set to true, it will override this one.
	var rememberActiveTabByCookie = true;	// Set it to true if you want to be able to save active tab as cookie
	
	/*
	These cookie functions are downloaded from 
	http://www.mach5.com/support/analyzer/manual/html/General/CookiesJavaScript.htm
	*/	
	function Get_Cookie(name) { 
	   var start = document.cookie.indexOf(name+"="); 
	   var len = start+name.length+1; 
	   if ((!start) && (name != document.cookie.substring(0,name.length))) return null; 
	   if (start == -1) return null; 
	   var end = document.cookie.indexOf(";",len); 
	   if (end == -1) end = document.cookie.length; 
	   return unescape(document.cookie.substring(len,end)); 
	} 
	// This function has been slightly modified
	function Set_Cookie(name,value,expires,path,domain,secure) { 
		expires = expires * 60*60*24*1000;
		var today = new Date();
		var expires_date = new Date( today.getTime() + (expires) );
	    var cookieString = name + "=" +escape(value) + 
	       ( (expires) ? ";expires=" + expires_date.toGMTString() : "") + 
	       ( (path) ? ";path=" + path : "") + 
	       ( (domain) ? ";domain=" + domain : "") + 
	       ( (secure) ? ";secure" : ""); 
	    document.cookie = cookieString; 
	}	
	
	function showHide()
	{
		if(activeMenuItem){
			activeMenuItem.className = 'inactiveMenuItem'; 	
			var theId = activeMenuItem.id.replace(/[^\d]/g,'');
			document.getElementById('submenu_'+theId).style.display='none';
		}

		
		activeMenuItem = this;		
		this.className = 'activeMenuItem';
		var theId = this.id.replace(/[^\d]/g,'');
		document.getElementById('submenu_'+theId).style.display='block';
		
		if(rememberActiveTabByCookie){			
			Set_Cookie('jsbtab_fixed_22_01_2010',(theId-1),100,'/','','');
		}
	}
	
	function initMenu()
	{
		var mainMenuObj = document.getElementById('mainMenu');
		var subMenuObj = document.getElementById('submenu'); //DD added line
		mainMenuObj.style.visibility=subMenuObj.style.visibility="visible" //DD added line
		var menuItems = mainMenuObj.getElementsByTagName('A');
		if(document.all){
			mainMenuObj.style.visibility = 'hidden';
			document.getElementById('submenu').style.visibility='hidden';
		}		
		if(rememberActiveTabByCookie){
			var cookieValue = Get_Cookie('jsbtab_fixed_22_01_2010') + '';			
			cookieValue = cookieValue.replace(/[^\d]/g,'');
			if(cookieValue.length>0 && cookieValue<menuItems.length){
				activeTabIndex = cookieValue/1;
			}			
		}
		
		var currentLeftPos = 15;
		for(var no=0;no<menuItems.length;no++){			
			//if(activateSubOnClick)menuItems[no].onclick = showHide; else 
			menuItems[no].onmouseover = showHide;
			menuItems[no].id = 'mainMenuItem' + (no+1);
			menuItems[no].style.left = currentLeftPos + 'px';
			currentLeftPos = currentLeftPos + menuItems[no].offsetWidth + topMenuSpacer; 
			if(no==activeTabIndex){
				menuItems[no].className='activeMenuItem';
				activeMenuItem = menuItems[no];
			}else menuItems[no].className='inactiveMenuItem';
			if(!document.all)menuItems[no].style.bottom = '-1px';
			
		}		
		
		var mainMenuLinks = mainMenuObj.getElementsByTagName('A');
		
		var subCounter = 1;
		var parentWidth = mainMenuObj.offsetWidth;
		while(document.getElementById('submenu_' + subCounter)){
			var subItem = document.getElementById('submenu_' + subCounter);

			
			if(leftAlignSubItems){
				// No action
			}else{							
				var leftPos = mainMenuLinks[subCounter-1].offsetLeft;
				document.getElementById('submenu_'+subCounter).style.paddingLeft =  leftPos + 'px';
				subItem.style.position ='absolute';
				if(subItem.offsetWidth > parentWidth){
					leftPos = leftPos - Math.max(0,subItem.offsetWidth-parentWidth); 	
				}
				subItem.style.paddingLeft =  leftPos + 'px';
				subItem.style.position ='static';
					
				
			}
			if(subCounter==(activeTabIndex+1)){
				subItem.style.display='block';
			}else{
				subItem.style.display='none';
			}
			
			subCounter++;
		}
		if(document.all){
			mainMenuObj.style.visibility = 'visible';
			document.getElementById('submenu').style.visibility='visible';
		}		
		document.getElementById('submenu').style.display='block';
	}
	//window.onload = initMenu;	