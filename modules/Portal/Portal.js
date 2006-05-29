/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/


function fetchAddSite(id)
{
	show('status');
	var ajaxObj = new VtigerAjax(ajaxeditSiteResp);
	url ='module=Portal&action=PortalAjax&file=Popup&record='+id;
	ajaxObj.process("index.php?",url);
}
function ajaxeditSiteResp(response)
{
	hide('status');
	document.getElementById('editportal_cont').innerHTML = response.responseText;

}
function fetchContents(mode)
{
	show('status');
	if(mode == 'data')
	{
		getObj('datatab').className = 'SiteSel';
		getObj('managetab').className = 'SiteUnSel';
	}
	else
	{
		getObj('datatab').className = 'SiteUnSel';
		getObj('managetab').className = 'SiteSel';
	}
	var ajaxObj = new VtigerAjax(ajaxfetchContentsResp);
	url ='action=PortalAjax&mode=ajax&module=Portal&file=ListView&datamode='+mode;
	ajaxObj.process("index.php?",url);
}
function ajaxfetchContentsResp(response)
{
	hide('status');
	document.getElementById('portalcont').innerHTML = response.responseText;
}
function DeleteSite(id)
{
	if(confirm("Are you sure you want to delete ?"))
	{
		show('status');
		var ajaxObj = new VtigerAjax(ajaxfetchContentsResp);
		url ='action=PortalAjax&mode=ajax&file=Delete&module=Portal&record='+id;
		ajaxObj.process("index.php?",url);
	}
}
function SaveSite(id)
{
	var ajaxObj = new VtigerAjax(ajaxfetchContentsResp);
	if (document.getElementById('portalurl').value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0) {
		alert('Site Url cannot be empty')
		return false;
	}
	if (document.getElementById('portalname').value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0) {
		alert('Site Name cannot be empty')
		return false;
	}
	Effect.Puff('orgLay');	
	show('status');
	var portalurl = document.getElementById('portalurl').value;
	var portalname = document.getElementById('portalname').value;
	url ='action=PortalAjax&mode=ajax&file=Save&module=Portal&portalname='+portalname+'&portalurl='+portalurl+'&record='+id;
	ajaxObj.process("index.php?",url);
}
function setSite(oUrllist)
{
	var url = oUrllist.options[oUrllist.options.selectedIndex].value;
	document.getElementById('locatesite').src = url;
}

