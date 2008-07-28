/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/

function chooseType(typeName)
{
	$('vtbusy_info').style.display="inline";
	$('stufftype_id').value=typeName;
	$('divHeader').innerHTML="<b>Add</b>"+" "+"<b>"+typeName+"</b>";
	if(typeName=='Module')
	{
		$('moduleNameRow').style.display="block";
		$('moduleFilterRow').style.display="block";
		$('modulePrimeRow').style.display="block";
		$('showrow').style.display="block";
		$('rssRow').style.display="none";
		$('dashNameRow').style.display="none";
		$('dashTypeRow').style.display="none";
		$('StuffTitleId').style.display="block";
	}
	else if(typeName=='DashBoard')
	{
		$('moduleNameRow').style.display="none";
		$('moduleFilterRow').style.display="none";
		$('modulePrimeRow').style.display="none";
		$('rssRow').style.display="none";
		$('showrow').style.display="none";
		$('dashNameRow').style.display="block";
		$('dashTypeRow').style.display="block";
		$('StuffTitleId').style.display="block";

		new Ajax.Request(
       			'index.php',
	               	{queue: {position: 'end', scope: 'command'},
               		method: 'post',
	                postBody:'module=Home&action=HomeAjax&file=HomestuffAjax&dash=dashboard',
			onComplete: function(response) 
			{
                       		var responseVal=response.responseText;
					$('selDashName').innerHTML=response.responseText;
					positionDivToCenter('PopupLay');
					show('PopupLay');
					$('vtbusy_info').style.display="none";
                        }
                       }
         	);
	}
	else if(typeName=='RSS')
	{
		$('moduleNameRow').style.display="none";
		$('moduleFilterRow').style.display="none";
		$('modulePrimeRow').style.display="none";
		$('showrow').style.display="block";
		$('rssRow').style.display="block";
		$('dashNameRow').style.display="none";
		$('dashTypeRow').style.display="none";
		$('StuffTitleId').style.display="block";
		$('vtbusy_info').style.display="none";
	}
	else if(typeName=='Default')
	{
		$('moduleNameRow').style.display="none";
		$('moduleFilterRow').style.display="none";
		$('modulePrimeRow').style.display="none";
		$('showrow').style.display="none";
		$('rssRow').style.display="none";
		$('dashNameRow').style.display="none";
		$('dashTypeRow').style.display="none";
		$('StuffTitleId').style.display="none";
	}
}
function setFilter(modName)
{
	var modval=modName.value;
	document.getElementById('savebtn').disabled = true;
	if(modval!="")
	{
		new Ajax.Request(
       		'index.php',
               	{queue: {position: 'end', scope: 'command'},
               		method: 'post',
                        postBody:'module=Home&action=HomeAjax&file=HomestuffAjax&modname='+modval,
			onComplete: function(response) 
			{
                       		var responseVal=response.responseText;
				$('selModFilter_id').innerHTML=response.responseText;
				setPrimaryFld(document.getElementById('selFilterid'));
			}
                       }
            	);
	}	
}
function setPrimaryFld(Primeval)
{
	primecvid=Primeval.value;
	var fldmodule = $('selmodule_id').options[$('selmodule_id').selectedIndex].value;
	new Ajax.Request(
        		'index.php',
                	{queue: {position: 'end', scope: 'command'},
                		method: 'post',
	                        postBody:'module=Home&action=HomeAjax&file=HomestuffAjax&primecvid='+primecvid+'&fieldmodname='+fldmodule,
				onComplete: function(response) 
				{
                        		var responseVal=response.responseText;
					$('selModPrime_id').innerHTML=response.responseText;
					$('selPrimeFldid').selectedIndex = 0;
					positionDivToCenter('PopupLay');
					show('PopupLay');
					$('vtbusy_info').style.display="none";
					document.getElementById('savebtn').disabled = false;
	                        }
                        }
               		);
}

function showEditrow(sid,stype)
{
		$('editRowmodrss_'+sid).className="show_tab";
}
function cancelEntries(editRow)
{
	$(editRow).className="hide_tab";
}
/*function cancelEditDash(editdash,editdash1)
{
	$(editdash).className = 'hide_tab';
	$(editdash1).className = 'hide_tab';
}*/
function saveEntries(selMaxName)
{
	sidarr=selMaxName.split("_");
	sid=sidarr[1];
	$('refresh_'+sid).innerHTML=$('vtbusy_homeinfo').innerHTML;
	cancelEntries('editRowmodrss_'+sid)
	showmax=$(selMaxName).value;
	new Ajax.Request(
        	'index.php',
               	{queue: {position: 'end', scope: 'command'},
               		method: 'post',
	                       postBody:'module=Home&action=HomeAjax&file=HomestuffAjax&showmaxval='+showmax+'&sid='+sid,
			onComplete: function(response) 
			{
                       		var responseVal=response.responseText;
				eval(response.responseText);
				$('refresh_'+sid).innerHTML='';
	                }
                       }
         	);
}
function saveEditDash(dashRowId)
{
	$('refresh_'+dashRowId).innerHTML=$('vtbusy_homeinfo').innerHTML;
	cancelEntries('editRowmodrss_'+dashRowId);
	var dashVal='';
	var iter=0;
	for(iter=0;iter<3;iter++)
	{
		if($('dashradio_'+[iter]).checked)
			dashVal=$('dashradio_'+[iter]).value;
	}
	did=dashRowId;
	new Ajax.Request(
        	'index.php',
               	{queue: {position: 'end', scope: 'command'},
               		method: 'post',
	                       postBody:'module=Home&action=HomeAjax&file=HomestuffAjax&dashVal='+dashVal+'&did='+did,
			onComplete: function(response) 
			{
                       		var responseVal=response.responseText;
				eval(response.responseText);
				$('refresh_'+did).innerHTML='';
	                }
                       }
         	);
}
function DelStuff(sid)
{
	if(confirm("Are you sure you want to delete?"))
	{
		new Ajax.Request(
        	'index.php',
               	{queue: {position: 'end', scope: 'command'},
               		method: 'post',
	                       postBody:'module=Home&action=HomeAjax&file=HomestuffAjax&homestuffid='+sid,
			onComplete: function(response) 
			{
				var responseVal=response.responseText;
				if(response.responseText.indexOf('SUCCESS') > -1)
				{
					var delchild = $('stuff_'+sid);
					odeletedChild = $('MainMatrix').removeChild(delchild);
					$('seqSettings').innerHTML= '<table cellpadding="10" cellspacing="0" border="0" width="100%" class="vtResultPop small"><tr><td align="center">Stuff deleted sucessfully.</td></tr></table>';
					LocateObj($('seqSettings'))
					Effect.Appear('seqSettings');
					setTimeout(hideSeqSettings,3000);
				}else
				{
					alert("Error while deleting.Please try again.")
				}
	                }
                       }
         	);
	}
}


function loadAddedDiv(stuffid,stufftype)
{
	gstuffId = stuffid;
	new Ajax.Request(
        	   'index.php',
		   {queue: {position: 'end', scope: 'command'},
                    method: 'post',
		    postBody:'module=Home&action=HomeAjax&file=NewBlock&stuffid='+stuffid+'&stufftype='+stufftype,
		    onComplete: function(response) 
		    {
			var responseVal=response.responseText;
			$('MainMatrix').innerHTML = response.responseText + $('MainMatrix').innerHTML;
			positionDivInAccord('stuff_'+gstuffId,'');
			initHomePage();
			loadStuff(stuffid,stufftype);
		    }
                   }
               	);
}

/*
function loadMainDiv()
{
	var stuffarray=new Array();
	new Ajax.Request(
        	   'index.php',
		   {queue: {position: 'end', scope: 'command'},
                    method: 'post',
		    postBody:'module=Home&action=HomeAjax&file=MainHomeBlock',
		    onComplete: function(response) 
		    {
			$('MainMatrix').innerHTML=response.responseText;
			stuffarray=getElementsById(document,"SCRIPT","loadstuffscript");
			for(var m=0;m<stuffarray.length;m++)
			{
				eval(stuffarray[m].innerHTML);
			}

			
		    }
                   }
               	);
}
*/
function loadStuff(stuffid,stufftype)
{
	$('refresh_'+stuffid).innerHTML=$('vtbusy_homeinfo').innerHTML;
	new Ajax.Request(
        	   'index.php',
		   {queue: {position: 'end', scope: 'command'},
                    method: 'post',
		    postBody:'module=Home&action=HomeAjax&file=HomeBlock&homestuffid='+stuffid+'&blockstufftype='+stufftype,
		    onComplete: function(response) 
		    {
			var responseVal=response.responseText;
			$('stuffcont_'+stuffid).innerHTML=response.responseText;
			if(stufftype=="Module")
				$('a_'+stuffid).href = "index.php?module="+$('more_'+stuffid).value+"&action=ListView&viewname="+$('cvid_'+stuffid).value;	
			if(stufftype=="Default" && typeof($('a_'+stuffid)) != 'undefined')
			{
				if($('more_'+stuffid).value != '')
				{
					$('a_'+stuffid).style.display = 'block';
					$('a_'+stuffid).href = "index.php?module="+$('more_'+stuffid).value+"&action=index";
				}
				else
					$('a_'+stuffid).style.display = 'none';
			}
			if(stufftype=="RSS")
				$('a_'+stuffid).href = $('more_'+stuffid).value;
			if(stufftype=="DashBoard")
				$('a_'+stuffid).href = "index.php?module=Dashboard&action=index&type="+$('more_'+stuffid).value;	
			$('refresh_'+stuffid).innerHTML='';	
		    }
                   }
               	);
}
function frmValidate()
	{
		if(trim($('stufftitle_id').value)=="")
		{
			alert("Please enter Window Title");
			$('stufftitle_id').focus();
			return false;
		}
		if($('stufftype_id').value=="RSS")
		{
				
			if($('txtRss_id').value=="")
			{
				alert("Please enter RSS URL");
				$('txtRss_id').focus();
				return false;
			}
		}
		if($('stufftype_id').value=="Module")
		{
			var selLen;
			var fieldval=new Array();
			var cnt=0;
			selVal=document.Homestuff.PrimeFld;
			for(k=0;k<selVal.options.length;k++)
			{
				if(selVal.options[k].selected)
				{
					fieldval[cnt]=selVal.options[k].value;
					cnt= cnt+1;
				}
			}
			if(cnt>2)
			{
				alert("Please select only two fields");
				selVal.focus();
				return false;
			}
			else
			{
				document.Homestuff.fldname.value=fieldval;
			}
		}
		var stufftype=$('stufftype_id').value;
		var stufftitle=$('stufftitle_id').value;
		$('stufftitle_id').value = '';
		var selFiltername='';
		var fldname='';
		var selmodule='';
		var maxentries='';
		var txtRss='';
		var seldashbd='';
		var seldashtype='';
		var seldeftype='';
		if(stufftype=="Module")
		{
			selFiltername =document.Homestuff.selFiltername[document.Homestuff.selFiltername.selectedIndex].value;
			fldname = fieldval;
			selmodule =$('selmodule_id').value;
			maxentries =$('maxentryid').value;
		}
		else if(stufftype=="RSS")
		{
			txtRss=$('txtRss_id').value;
			maxentries =$('maxentryid').value;
		}
		else if(stufftype=="DashBoard")
		{
			seldashbd=$('seldashbd_id').value;
			seldashtype=$('seldashtype_id').value;
		}
		else if(stufftype=="Default")
			seldeftype=document.Homestuff.seldeftype[document.Homestuff.seldeftype.selectedIndex].value;
		var url="stufftype="+stufftype+"&stufftitle="+stufftitle+"&selmodule="+selmodule+"&maxentries="+maxentries+"&selFiltername="+selFiltername+"&fldname="+encodeURIComponent(fldname)+"&txtRss="+txtRss+"&seldashbd="+seldashbd+"&seldashtype="+seldashtype+"&seldeftype="+seldeftype;
		var stuffarr=new Array();
		$('vtbusy_info').style.display="inline";	
		new Ajax.Request(
	           'index.php',
		   {queue: {position: 'end', scope: 'command'},
            		method: 'post',
			postBody:'module=Home&action=HomeAjax&file=Homestuff&'+url,
		        onComplete: function(response) 
	        	{
                 		var responseVal=response.responseText;
				if(!response.responseText)
				{
					alert("Unable to add homestuff! Please try again");
					$('vtbusy_info').style.display="none";
					$('stufftitle_id').value='';
					$('txtRss_id').value='';
					return false;
				}
				else
				{
					hide('PopupLay');
					$('vtbusy_info').style.display="none";
					$('stufftitle_id').value='';
					$('txtRss_id').value='';
					eval(response.responseText);
				}
		        }
        	    }
        	);
}
/*function showHideDiv(getSid,getStype)
{
	if(getStype=="Module" || getStype=="RSS")
		$('editRow_'+getSid).className="hide_tab";
	$('maincont_row_'+getSid).className=($('maincont_row_'+getSid).className=='show_tab')?"hide_tab":"show_tab";	
	$('stuff_'+getSid).style.height= ($('stuff_'+getSid).style.height!=$('headerrow_'+getSid).style.height)?$('headerrow_'+getSid).style.height:"280px";	
}*/
function HideDefault(sid,stype)
{
		new Ajax.Request(
	           'index.php',
		   {queue: {position: 'end', scope: 'command'},
            		method: 'post',
			postBody:'module=Home&action=HomeAjax&file=HomestuffAjax&stuffid='+sid+"&act=hide",
		        onComplete: function(response) 
	        	{
				var responseVal=response.responseText;
				if(response.responseText.indexOf('SUCCESS') > -1)
				{
					var delchild = $('stuff_'+sid);
					odeletedChild = $('MainMatrix').removeChild(delchild);
					$('seqSettings').innerHTML= '<table cellpadding="10" cellspacing="0" border="0" width="100%" class="vtResultPop small"><tr><td align="center">Stuff hidden.You can restore it from your preferences.</td></tr></table>';
					LocateObj($('seqSettings'))
					Effect.Appear('seqSettings');
					setTimeout(hideSeqSettings,3000);
				}else
				{
					alert("Error while hiding.Please try again.");
				}
		        }
        	    }
        	);
}

function fnRemoveWindow(){
	var tagName = document.getElementById('addEventDropDown').style.display= 'none';
}

function fnShowWindow(){
		var tagName = document.getElementById('addEventDropDown').style.display= 'block';
}
function positionDivToCenter(targetDiv)
{
	//Gets the browser's viewport dimension
	getViewPortDimension();
	//Gets the Target DIV's width & height in pixels using parseInt function
	divWidth =(parseInt(document.getElementById(targetDiv).style.width))/2;
	//divHeight=(parseInt(document.getElementById(targetDiv).style.height))/2;
	//calculate horizontal and vertical locations relative to Viewport's dimensions
	mx = parseInt(XX/2)-parseInt(divWidth);
	//my = parseInt(YY/2)-parseInt(divHeight);
	//Prepare the DIV and show in the center of the screen.
	document.getElementById(targetDiv).style.left=mx+"px";
	document.getElementById(targetDiv).style.top="150px";
}

function getViewPortDimension()
{
if(!document.all)
	{
  	XX = self.innerWidth;
	YY = self.innerHeight;
	}
	else if(document.all)
	{
	XX = document.documentElement.clientWidth;
	YY = document.documentElement.clientHeight;
  
	}
}
function positionDivInAccord(targetDiv,stufftitle)
{
	mainX = parseInt(document.getElementById("MainMatrix").style.width);
	if(stufftitle != "Home Page Dashboard")
		dx = mainX * 31 / 100;
	else
		dx = mainX * 64 / 100;
	document.getElementById(targetDiv).style.width=dx + "%";
}
function hideSeqSettings()
{
	Effect.Fade('seqSettings');
}
function getElementsById(placeslides, tagName, id) {
       var allElements = getElementsByTagName(placeslides,tagName);
       var elemColl = new Array();
       for (var i = 0; i< allElements.length; i++) {
               if (allElements[i].id == id)  {
                       elemColl[elemColl.length] = allElements[i];
               }
       }
       return elemColl;
} 
function getElementsByTagName(ele, tagName) 
{
       var tagEles = [];
       var childNodes = ele.childNodes;
       for (var k=0;k<childNodes.length;k++) 
	{
               var childNode = childNodes[k];
               if (childNode.nodeName == tagName) 
	       {
                       tagEles.push(childNode);
               }
               		temp = getElementsByTagName(childNode, tagName);
       		        for (var g=0;g<temp.length;g++) {
                       var ele = temp[g];
                       tagEles.push(ele);
               }
       }
       	return tagEles;
} 

function fetch_homeDB(stuffid,stufftype)
{
	$('refresh_'+stuffid).innerHTML=$('vtbusy_homeinfo').innerHTML;
	new Ajax.Request(
	'index.php',
	{queue: {position: 'end', scope: 'command'},
	method: 'post',
	postBody: 'module=Dashboard&action=DashboardAjax&file=HomepageDB',
	onComplete: function(response)
	{
	$('stuffcont_'+stuffid).style.display = 'none';
	$('stuffcont_'+stuffid).innerHTML=response.responseText;
	$('refresh_'+stuffid).innerHTML='';
	Effect.Appear('stuffcont_'+stuffid);
	}
	}
	);
}
