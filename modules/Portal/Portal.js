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
	$("status").style.display="inline";
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody:'module=Portal&action=PortalAjax&file=Popup&record='+id,
			onComplete: function(response) {
				$("status").style.display="none";
				$('editportal_cont').innerHTML = response.responseText;
			}
		}
	);
}

function fetchContents(mode)
{
	$("status").style.display="inline";
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody:'action=PortalAjax&mode=ajax&module=Portal&file=ListView&datamode='+mode,
                        onComplete: function(response) {
                                $("status").style.display="none";
                                $('portalcont').innerHTML = response.responseText;
                        }
                }
        );
}
function DeleteSite(id)
{
	if(confirm("Are you sure you want to delete ?"))
	{
		$("status").style.display="inline";
		new Ajax.Request(
          	      'index.php',
                	{queue: {position: 'end', scope: 'command'},
                        	method: 'post',
	                        postBody:'action=PortalAjax&mode=ajax&file=Delete&module=Portal&record='+id,
        	                onComplete: function(response) {
                	                $("status").style.display="none";
                        	        $('portalcont').innerHTML = response.responseText;
                        	}
                	}
        	);
	}
}
function SaveSite(id)
{
	if ($('portalurl').value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0) {
		alert('Site Url cannot be empty')
		return false;
	}
	if ($('portalname').value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0) {
		alert('Site Name cannot be empty')
		return false;
	}
	Effect.Puff('orgLay');	
	$("status").style.display="inline";
	var portalurl = document.getElementById('portalurl').value;
	var portalname = document.getElementById('portalname').value;
        new Ajax.Request(
        	'index.php',
                {queue: {position: 'end', scope: 'command'},
                	method: 'post',
                        postBody:'action=PortalAjax&mode=ajax&file=Save&module=Portal&portalname='+portalname+'&portalurl='+portalurl+'&record='+id,
                        onComplete: function(response) {
                        		$("status").style.display="none";
                                        $('portalcont').innerHTML = response.responseText;
                        }
                }
	);
}
function setSite(oUrllist)
{
	var url = oUrllist.options[oUrllist.options.selectedIndex].value;
	document.getElementById('locatesite').src = url;
}

