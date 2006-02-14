// JavaScript Document
//Layer Function

if (document.all) var browser_ie=true
else if (document.layers) var browser_nn4=true
else if (document.layers || (!document.all && document.getElementById)) var browser_nn6=true

function getObj(n,d) {
  var p,i,x; 
  if(!d)d=document;
  if((p=n.indexOf("?"))>0&&parent.frames.length) {d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all)x=d.all[n];
  for(i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++)  x=getObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n);
  return x;
}
	

function findPosX(obj) {
	var curleft = 0;
	if (document.getElementById || document.all) {
		while (obj.offsetParent) { curleft += obj.offsetLeft; obj = obj.offsetParent;}
	} 
	else if (document.layers) { curleft += obj.x; }
	return curleft;
}


function findPosY(obj) {
	var curtop = 0;
	if (document.getElementById || document.all) {
		while (obj.offsetParent) { curtop += obj.offsetTop; obj = obj.offsetParent; }
	}
	else if (document.layers) {curtop += obj.y;}
	return curtop;
}

function openPopUp(winInst,currObj,baseURL,winName,width,height,features) {
	var left=parseInt(findPosX(currObj))
	var top=parseInt(findPosY(currObj))
	
	if (window.navigator.appName!="Opera") top+=parseInt(currObj.offsetHeight)
	else top+=(parseInt(currObj.offsetHeight)*2)+10
	if (browser_ie)	{
		top+=window.screenTop-document.body.scrollTop
		left-=document.body.scrollLeft
		if (top+height+30>window.screen.height) 
			top=findPosY(currObj)+window.screenTop-height-30 
		if (left+width>window.screen.width) 
			left=findPosX(currObj)+window.screenLeft-width
	} else if (browser_nn4 || browser_nn6) {
		top+=(scrY-pgeY)
		left+=(scrX-pgeX)
		if (top+height+30>window.screen.height) 
			top=findPosY(currObj)+(scrY-pgeY)-height-30
		if (left+width>window.screen.width) 
			left=findPosX(currObj)+(scrX-pgeX)-width
	}
	
	features="width="+width+",height="+height+",top="+top+",left="+left+";"+features
	eval(winInst+'=window.open("'+baseURL+'","'+winName+'","'+features+'")')
}

var scrX=0,scrY=0,pgeX=0,pgeY=0;

if (browser_nn4 || browser_nn6) {
	document.addEventListener("click",popUpListener,true)
}

function popUpListener(ev) {
	if (browser_nn4 || browser_nn6) {
		scrX=ev.screenX
		scrY=ev.screenY
		pgeX=ev.pageX
		pgeY=ev.pageY
	}
}


ScrollEffect = function(){ };
ScrollEffect.lengthcount=0;
ScrollEffect.closelimit=0;
ScrollEffect.limit=0;

ScrollEffect1 = function(){ };
ScrollEffect1.lengthcount=0;
ScrollEffect1.closelimit=0;
ScrollEffect1.limit=0;

ScrollEffect2 = function(){ };
ScrollEffect2.lengthcount=0;
ScrollEffect2.closelimit=0;
ScrollEffect2.limit=0;

ScrollEffect3 = function(){ };
ScrollEffect3.lengthcount=0;
ScrollEffect3.closelimit=0;
ScrollEffect3.limit=0;

function just(){
	ig=getObj("top");
	if(ScrollEffect.lengthcount > ScrollEffect.closelimit ){closet();return;}
	ig.style.display="block";
	ig.style.height=ScrollEffect.lengthcount+'px';
	ScrollEffect.lengthcount=ScrollEffect.lengthcount+10;
	if(ScrollEffect.lengthcount < ScrollEffect.limit){setTimeout("just()",25);}
	else{ getObj("user").style.display="block";return;}
}

function closet(){
	ig=getObj("top");
	getObj("user").style.display="none";
	ScrollEffect.lengthcount=ScrollEffect.lengthcount-10;
	ig.style.height=ScrollEffect.lengthcount+'px';
	if(ScrollEffect.lengthcount<20){ig.style.display="none";return;}
	else{setTimeout("closet()", 25);}
}

function just1(){
	ig=getObj("top2");
	if(ScrollEffect1.lengthcount > ScrollEffect1.closelimit1 ){closet1();return;}
	ig.style.display="block";
	ig.style.height=ScrollEffect1.lengthcount+'px';
	ScrollEffect1.lengthcount=ScrollEffect1.lengthcount+10;
	if(ScrollEffect1.lengthcount < ScrollEffect1.limit1){setTimeout("just1()",25);}
	else{ getObj("studio").style.display="block";return;}
}

function closet1(){
	ig=getObj("top2");
	getObj("studio").style.display="none";
	ScrollEffect1.lengthcount=ScrollEffect1.lengthcount-10;
	ig.style.height=ScrollEffect1.lengthcount+'px';
	if(ScrollEffect1.lengthcount<20){ig.style.display="none";return;}
	else{setTimeout("closet1()", 25);}
}

function just2(){
	ig=getObj("top3");
	if(ScrollEffect2.lengthcount > ScrollEffect2.closelimit2 ){closet2();return;}
	ig.style.display="block";
	ig.style.height=ScrollEffect2.lengthcount+'px';
	ScrollEffect2.lengthcount=ScrollEffect2.lengthcount+10;
	if(ScrollEffect2.lengthcount < ScrollEffect2.limit2){setTimeout("just2()",25);}
	else{ getObj("comm").style.display="block";return;}
}

function closet2(){
	ig=getObj("top3");
	getObj("comm").style.display="none";
	ScrollEffect2.lengthcount=ScrollEffect2.lengthcount-10;
	ig.style.height=ScrollEffect2.lengthcount+'px';
	if(ScrollEffect2.lengthcount<20){ig.style.display="none";return;}
	else{setTimeout("closet2()", 25);}
}

function just3(){
	ig=getObj("top4");
	if(ScrollEffect3.lengthcount > ScrollEffect3.closelimit3 ){closet3();return;}
	ig.style.display="block";
	ig.style.height=ScrollEffect3.lengthcount+'px';
	ScrollEffect3.lengthcount=ScrollEffect3.lengthcount+10;
	if(ScrollEffect3.lengthcount < ScrollEffect3.limit3){setTimeout("just3()",25);}
	else{ getObj("config").style.display="block";return;}
}

function closet3(){
	ig=getObj("top4");
	getObj("config").style.display="none";
	ScrollEffect3.lengthcount=ScrollEffect3.lengthcount-10;
	ig.style.height=ScrollEffect3.lengthcount+'px';
	if(ScrollEffect3.lengthcount<20){ig.style.display="none";return;}
	else{setTimeout("closet3()", 25);}
}
