<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br/>
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_CONFIGURATION} > {$MOD.LBL_COMPANY_INFO}</b></span>
<hr noshade="noshade" size="1" />
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">
	
	<form action="index.php?module=Settings&action=add2db" method="post" name="index" enctype="multipart/form-data">
 	<input type="hidden" name="return_module" value="Settings">
 	<input type="hidden" name="parenttab" value="Settings">
    <input type="hidden" name="return_action" value="OrganizationConfig">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
	<tbody><tr>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="6" width="7"><img src="{$IMAGE_PATH}top_left.jpg" align="top"></td>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif; height: 6px;" bgcolor="#ebebeb"></td>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="6" width="8"><img src="{$IMAGE_PATH}top_right.jpg" align="top" height="6" width="8"></td>
	</tr>
	<tr>

	<td bgcolor="#ebebeb" width="7"></td>
	<td style="padding-left: 10px; padding-top: 10px; vertical-align: top;" bgcolor="#ececec">
	<table border="0" cellpadding="10" cellspacing="0" width="100%">
	<tbody><tr>
	<td rowspan="11" style="background-image: url(include/images/noimage.gif); background-position: center; background-repeat: no-repeat;" bgcolor="#ffffff" width="25%">&nbsp;</td>
	<td colspan="2" class="genHeaderBig" width="75%">{$ORGANIZATIONNAME}
	{$ERRORFLAG}
	<br><hr> </td>
	</tr>
	<tr>

	<td align="right" width="25%"><font color="red">*</font><b>{$MOD.LBL_ORGANIZATION_NAME} : </b></td>
	<td align="left" width="50%"><input name="organization_name" class="txtBox" value="{$ORGANIZATIONNAME}" type="text">
	<input type="hidden" name="org_name" value="{$ORGANIZATIONNAME}">
	</td>
	</tr>
	<tr>
	<td align="right"><b>{$MOD.LBL_ORGANIZATION_ADDRESS} : </b></td>
	<td><input name="organization_address" class="txtBox" value="{$ORGANIZATIONADDRESS}" type="text"></td>
	</tr>
	<tr>

	<td align="right"><b>{$MOD.LBL_ORGANIZATION_CITY} : </b></td>
	<td><input name="organization_city" class="txtBox" value="{$ORGANIZATIONCITY}" type="text"></td>
	</tr>
	<tr>
	<td align="right"><b>{$MOD.LBL_ORGANIZATION_STATE} : </b></td>
	<td><input name="organization_state" class="txtBox" value="{$ORGANIZATIONSTATE}" type="text"></td>
	</tr>
	<tr>

	<td align="right"><b>{$MOD.LBL_ORGANIZATION_CODE}  : </b></td>
	<td><input name="organization_code" class="txtBox" value="{$ORGANIZATIONCODE}" type="text"></td>
	</tr>
	<tr>
	<td align="right"><b>{$MOD.LBL_ORGANIZATION_COUNTRY} : </b></td>
	<td><input name="organization_country" class="txtBox" value="{$ORGANIZATIONCOUNTRY}" type="text"></td>
	</tr>
	<tr>

	<td colspan="2" style="padding-bottom: 0px; padding-top: 0px;" width="75%"><hr> </td>
	</tr>
	<tr>
	<td colspan="2" style="padding: 0px;" width="75%">
	<table border="0" cellpadding="10" cellspacing="0" width="100%">
	<tbody><tr>
	<td nowrap align="right" width="25%"><b>{$MOD.LBL_ORGANIZATION_PHONE} : </b></td>
	<td width="25%"><input name="organization_phone" class="txtBox" value="{$ORGANIZATIONPHONE}" type="text"></td>

	<td nowrap align="right" width="25%"><b>{$MOD.LBL_ORGANIZATION_WEBSITE} : </b></td>
	<td width="25%"><input name="organization_website" class="txtBox" value="{$ORGANIZATIONWEBSITE}" type="text"></td>
	</tr>
	<tr>
	<td nowrap align="right"><b>{$MOD.LBL_ORGANIZATION_FAX} : </b></td>
	<td><input name="organization_fax" class="txtBox" value="{$ORGANIZATIONFAX}" type="text"></td>
	<td nowrap align="right"><b>{$MOD.LBL_ORGANIZATION_LOGO} : </b></td>

	<td><INPUT TYPE="HIDDEN" NAME="MAX_FILE_SIZE" VALUE="800000">
	    <INPUT TYPE="HIDDEN" NAME="PREV_FILE" VALUE="{$ORGANIZATIONLOGONAME}">
	    <input type="file" class="txtBox" name="binFile" value="{$ORGANIZATIONLOGONAME}">[{$ORGANIZATIONLOGONAME}]
	</td>
	</tr>
	</tbody></table></td>
	</tr>
	<tr>
	<td colspan="2" style="padding-top: 0px; padding-bottom: 0px;"><hr></td>
	</tr>
	<tr>
	<td	colspan="2" align="center">
	<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="classBtn" type="submit" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}" onclick="return verify_data(form,'{$MOD.LBL_ORGANIZATION_NAME}');" >
    <input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="classBtn" onclick="window.history.back()" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}"></td>
	</td>
	</tr>
	</tbody></table>
	</td>
	<td bgcolor="#ebebeb" width="8"></td>
	</tr>
	<tr>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="8" width="7"><img src="{$IMAGE_PATH}bottom_left.jpg" align="bottom"></td>
	<td style="font-size: 1px;" bgcolor="#ececec" height="8"></td>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="8" width="8"><img src="{$IMAGE_PATH}bottom_right.jpg" align="bottom"></td>
	</tr>
	</tbody></table>
	</form>

</td>
<td width="1%" style="border-right:1px dotted #CCCCCC;">&nbsp;</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
	{include file='SettingsSubMenu.tpl'}
{literal}
<script>
function verify_data(form,company_name)
{
	if (form.organization_name.value == "" )
	{
		alert(company_name +" cannot be none");
		return false

	}
	else if (form.organization_name.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0)
	{
		alert(company_name +" cannot be empty");
		form.organization_name.focus();
		return false;
	}
	else
	{
		return true;
	}
}
</script>
{/literal}
