$(document).ready(function() {
	$("body").attr({
		ondragstart:'return false',
		onselectstart:'return false',
		oncontextmenu:'return false'
	}); 
}); 

/*right click disable**********************************************************/
/*document.onmousedown=disableclick;
status="Right Click Disabled";
Function disableclick(event)
{
  if(event.button==2)
   {
     alert(status);
     return false;    
   }
}
/*copy past  select disable**********************************************************/
/*function killCopy(e){
	return false
}
function reEnable(){
	return true
}
document.onselectstart=new Function ("return false")
if (window.sidebar){
	document.onmousedown=killCopy
	document.onclick=reEnable
}

/***********************************************************/
