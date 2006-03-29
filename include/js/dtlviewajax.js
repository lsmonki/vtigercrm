/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
var globaldtlviewspanid = "";
var globaleditareaspanid = ""; 
var globaltxtboxid = "";
var itsonview=false;

function showHide(showId, hideId)
{
	show(showId);
	hide(hideId);
}

function hndCancel(valuespanid,textareapanid,fieldlabel)
{

  showHide(valuespanid,textareapanid);
  itsonview=false;
  return false;
}

function hndMouseOver(uitype,fieldLabel)
{
      var mouseArea="";
      mouseArea="mouseArea_"+ fieldLabel;
      
      if(itsonview)
      {
            return;
      }
      
      show("crmspanid");
      globaldtlviewspanid= "dtlview_"+ fieldLabel;//valuespanid;
      globaleditareaspanid="editarea_"+ fieldLabel;//textareapanid;
      globaltxtboxid="txtbox_"+ fieldLabel;//textboxpanid;
      divObj = getObj('crmspanid'); 
      crmy = findPosY(getObj(mouseArea));
      crmx = findPosX(getObj(mouseArea));
      if(document.all)
      {
          divObj.onclick=handleEdit;
      }
      else
      {
          divObj.setAttribute('onclick','handleEdit();');
      }
      divObj.style.left=(crmx+getObj(mouseArea).offsetWidth -divObj.offsetWidth)+"px";
      divObj.style.top=crmy+"px";
}

function handleEdit()
{
     show(globaleditareaspanid) ;
     hide(globaldtlviewspanid);
     getObj(globaltxtboxid).focus();
     hide('crmspanid');
     itsonview=true;
     return false;
}

function dtlViewAjaxResponse(response)
{
     var item = response.responseText;
     if(item.indexOf(":#:FAILURE")>-1)
     {
          alert("Error while Editing");
     }
     else if(item.indexOf(":#:SUCCESS")>-1)
     {
          hide("vtbusy_info");
     }
}

function trim(str)
{
	return(str.replace(/\s+$/,''));
}

var genUiType = "";
var genFldValue = "";

function dtlViewAjaxSave(fieldLabel,module,uitype,tableName,fieldName,crmId)
{
     var dtlView = "dtlview_"+ fieldLabel;
     var editArea = "editarea_"+ fieldLabel;
     var txtBox= "txtbox_"+ fieldLabel;
     var popupTxt= "popuptxt_"+ fieldLabel;      

     var tagValue = trim(document.getElementById(txtBox).value);

     var data = "module=" + module + "&action=" + module + "Ajax&recordid=" + crmId ;
     data = data + "&fldName=" + fieldName + "&fieldValue=" + escape(tagValue) + "&ajxaction=DETAILVIEW";
     show("vtbusy_info");
     
     var ajaxObj = new Ajax(dtlViewAjaxResponse);
     ajaxObj.process("index.php?",data);
     if(uitype == '13')
     {
          getObj(dtlView).innerHTML = "<a href=\"mailto:"+ tagValue+"\" target=\"_blank\">"+tagValue+"&nbsp;</a>";
     }
     else if(uitype == '17')
     {
          getObj(dtlView).innerHTML = "<a href=\"http://"+ tagValue+"\" target=\"_blank\">"+tagValue+"&nbsp;</a>";
     }
     else if(getObj(popupTxt))
     {
	var popObj = getObj(popupTxt);
	if(uitype == '50' || uitype == '73' || uitype == '51')
	{
		getObj(dtlView).innerHTML = "<a href=\"index.php?module=Accounts&action=DetailView&record="+tagValue+"\">"+popObj.value+"&nbsp;</a>";
	}
	else if(uitype == '57')
	{
		getObj(dtlView).innerHTML = "<a href=\"index.php?module=Contacts&action=DetailView&record="+tagValue+"\">"+popObj.value+"&nbsp;</a>";
	}
	else if(uitype == '59')
	{
		getObj(dtlView).innerHTML = "<a href=\"index.php?module=Products&action=DetailView&record="+tagValue+"\">"+popObj.value+"&nbsp;</a>";
	}
	else if(uitype == '75' || uitype == '81' )
	{
		getObj(dtlView).innerHTML = "<a href=\"index.php?module=Vendors&action=DetailView&record="+tagValue+"\">"+popObj.value+"&nbsp;</a>";

	}
	else if(uitype == '76')
	{
		getObj(dtlView).innerHTML = "<a href=\"index.php?module=Potentials&action=DetailView&record="+tagValue+"\">"+popObj.value+"&nbsp;</a>";
	}
	else if(uitype == '78')
	{
		getObj(dtlView).innerHTML = "<a href=\"index.php?module=Quotes&action=DetailView&record="+tagValue+"\">"+popObj.value+"&nbsp;</a>";
	}
	else if(uitype == '80')
	{
		getObj(dtlView).innerHTML = "<a href=\"index.php?module=SalesOrder&action=DetailView&record="+tagValue+"\">"+popObj.value+"&nbsp;</a>";
	}
	else
	{
		getObj(dtlView).innerHTML = popObj.value;
	}
     }
     else
     {
          getObj(dtlView).innerHTML = tagValue;
     }
     showHide(dtlView,editArea);  //show,hide
     itsonview=false;
}


function dtlViewAjaxTagResponse(response)
{
     var item = response.responseText;
	 getObj('tagfields').innerHTML = item;
     hide("vtbusy_info");
}

function SaveTag(txtBox,crmId,module)
{
	var tagValue = document.getElementById(txtBox).value;
	document.getElementById(txtBox).value ='';
    var data = "module=" + module + "&action=" + module + "Ajax&recordid=" + crmId + "&ajxaction=SAVETAG&tagfields=" +tagValue;
    
	var ajaxObj = new Ajax(dtlViewAjaxTagResponse);
    ajaxObj.process("index.php?",data);
   	show("vtbusy_info");
}
