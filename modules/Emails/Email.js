/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

var gFolderid = 1;
var gselectedrowid = 0;
function DeleteEmail(id)
{
	if(confirm("Are you sure you want to delete ?"))
	{	
		getObj('search_text').value = '';
		gselectedrowid = 0;
		$("status").style.display="inline";
                new Ajax.Request(
                        'index.php',
                        {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                                method: 'post',
                                postBody: "module=Users&action=massdelete&return_module=Emails&folderid="+gFolderid+"&idlist="+id,
                                onComplete: function(response) {ldelim}
                                                $("status").style.display="none";
                                                $('EmailDetails').innerHTML = '<table valign="top" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td class="forwardBg"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td colspan="2">&nbsp;</td></tr></tbody></table></td></tr><tr><td style="padding-top: 10px;" bgcolor="#ffffff" height="300" valign="top"></td></tr></tbody></table>';
                                                $("subjectsetter").innerHTML='';
                                                $("email_con").innerHTML=response.responseText;
                                                execJS($('email_con'));
                                {rdelim}
                        {rdelim}
                );
	}
	else
	{
		return false;
	}
}
function Searchfn()
{
	gselectedrowid = 0;
	var osearch_field = document.getElementById('search_field');
	var search_field = osearch_field.options[osearch_field.options.selectedIndex].value;
	var search_text = document.getElementById('search_text').value;
	new Ajax.Request(
                'index.php',
                {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                        method: 'post',
                        postBody: "module=Emails&action=EmailsAjax&ajax=true&file=ListView&folderid="+gFolderid+"&search=true&search_field="+search_field+"&search_text="+search_text,
                        onComplete: function(response) {ldelim}
                                        $("status").style.display="none";
                                        $('EmailDetails').innerHTML = '<table valign="top" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td class="forwardBg"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td colspan="2">&nbsp;</td></tr></tbody></table></td></tr><tr><td style="padding-top: 10px;" bgcolor="#ffffff" height="300" valign="top"></td></tr></tbody></table>';
                                        $("subjectsetter").innerHTML='';
                                        $("email_con").innerHTML=response.responseText;
                                        execJS($('email_con'));
                        {rdelim}
                {rdelim}
        );
}

