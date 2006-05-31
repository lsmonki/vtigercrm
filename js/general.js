// JavaScript Document

function fnLoadValues(obj1,obj2){
	
	var tabName1 = document.getElementById(obj1);
	var tabName2 = document.getElementById(obj2);
	if(tabName1.className == "dvtUnSelectedCell")
		tabName1.className = "dvtSelectedCell";
	if(tabName2.className == "dvtSelectedCell")
		tabName2.className = "dvtUnSelectedCell";	
	if(obj1 == 'mi')
		window.location.href = "index.php?module=Faq&action=index";
	else if(obj1 == 'pi')
		window.location.href = "index.php";	
}

function fnDown(obj){
	var tagName = document.getElementById(obj);
	if(tagName.style.display == 'block')
		tagName.style.display = 'none';
	else
		tagName.style.display = 'block';
}

function fnShowDiv(obj){
	var tagName = document.getElementById(obj);
		tagName.style.visibility = 'visible';
}


function fnHideDiv(obj){
	var tagName = document.getElementById(obj);
		tagName.style.visibility = 'hidden';
}

function findPosX(obj) {
	var curleft = 0;
	if (document.getElementById || document.all) {
		while (obj.offsetParent) {
			curleft += obj.offsetLeft
			obj = obj.offsetParent;
		}
	} 
	else if (document.layers) {curleft += obj.x;}
	return curleft;
}

function findPosY(obj) {
	var curtop = 0;
	if (document.getElementById || document.all) {
		while (obj.offsetParent) {
			curtop += obj.offsetTop
			obj = obj.offsetParent;
		}
	}
	else if (document.layers) {curtop += obj.y;}
	return curtop;
}

function fnShow(obj){
	var tagName = document.getElementById('faqDetail');
	var leftSide = findPosX(obj);
	var topSide = findPosY(obj);
		topSide = topSide - 90;
		leftSide = leftSide - 200; 
		tagName.style.top = topSide + 'px';
		tagName.style.left = leftSide + 'px';
		tagName.style.visibility = 'visible';
}


