{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
<link rel="stylesheet" type="text/css" href="{$THEME_PATH}style.css">
<script language="javascript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
<body class="small" marginwidth=0 marginheight=0 leftmargin=0 topmargin=0 bottommargin=0 rigthmargin=0>

<form action="index.php" method="post" id="form">
<input type='hidden' name='module' value='Users'>
<input type='hidden' id='userid' name='userid' value='{$USERID}'>

<table  width="100%" border="0" cellspacing="0" cellpadding="0" class="small">
	<tr>
		<td background="{$IMAGE_PATH}popupHdr.jpg" height="70" style="padding-left:20px;">
		<span style="color:#FFFFFF;font:Arial, Helvetica, sans-serif;font-size:18px;font-weight:bold;">
		{$MOD.LBL_AUDIT_TRAIL}
		</span> 
		</td>
	</tr>
	<tr>
		<td>
			<div id="AuditTrailContents">
				{include file="ShowAuditTrailContents.tpl"}
			</div>
		</td>
	</tr>
</table>
</form>
</body>
{literal}
<script>
function getListViewEntries_js(module,url)
{
	var userid = document.getElementById('userid').value;
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Users&action=UsersAjax&file=ShowAuditTrail&ajax=true&'+url+'&userid='+userid,
                        onComplete: function(response) {
                                $("AuditTrailContents").innerHTML= response.responseText;
                        }
                }
        );
}
</script>
{/literal}
