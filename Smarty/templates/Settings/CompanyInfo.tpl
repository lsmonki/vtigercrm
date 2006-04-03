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
	
	<form action="index.php" method="post" name="company">
    <input type="hidden" name="module" value="Settings">
    <input type="hidden" name="parenttab" value="Settings">
    <input type="hidden" name="action">
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
	<td rowspan="11" style="background-image: url({$ORGANIZATIONLOGOPATH}/{$ORGANIZATIONLOGONAME}); background-position: center; background-repeat: no-repeat;" bgcolor="#ffffff" width="25%">&nbsp;</td>
	<td colspan="2" class="genHeaderBig" width="75%">{$ORGANIZATIONNAME}<br><hr> 
	</td>
	</tr>
	<tr>

	<td align="right" width="25%"><b>{$MOD.LBL_ORGANIZATION_NAME} : </b></td>
	<td align="left" width="50%">{$ORGANIZATIONNAME}</td>
	</tr>
	<tr>
	<td align="right"><b>{$MOD.LBL_ORGANIZATION_ADDRESS} : </b></td>
	<td>{$ORGANIZATIONADDRESS}</td>
	</tr>
	<tr>

	<td align="right"><b>{$MOD.LBL_ORGANIZATION_CITY} : </b></td>
	<td>{$ORGANIZATIONCITY}</td>
	</tr>
	<tr>
	<td align="right"><b>{$MOD.LBL_ORGANIZATION_STATE} : </b></td>
	<td>{$ORGANIZATIONSTATE}</td>
	</tr>
	<tr>

	<td align="right"><b>{$MOD.LBL_ORGANIZATION_CODE}  : </b></td>
	<td>{$ORGANIZATIONCODE}</td>
	</tr>
	<tr>
	<td align="right"><b>{$MOD.LBL_ORGANIZATION_COUNTRY} : </b></td>
	<td>{$ORGANIZATIONCOUNTRY}</td>
	</tr>
	<tr>

	<td colspan="2" style="padding-bottom: 0px; padding-top: 0px;" width="75%"><hr> </td>
	</tr>
	<tr>
	<td colspan="2" style="padding: 0px;" width="75%">
	<table border="0" cellpadding="10" cellspacing="0" width="100%">
	<tbody><tr>
	<td nowrap align="right" width="25%"><b>{$MOD.LBL_ORGANIZATION_PHONE} : </b></td>
	<td width="25%">{$ORGANIZATIONPHONE}</td>

	<td nowrap align="right" width="25%"><b>{$MOD.LBL_ORGANIZATION_WEBSITE} : </b></td>
	<td width="25%"><a href="http://{$ORGANIZATIONWEBSITE}">{$ORGANIZATIONWEBSITE}</a></td>
	</tr>
	<tr>
	<td nowrap align="right"><b>{$MOD.LBL_ORGANIZATION_FAX} : </b></td>
	<td>{$ORGANIZATIONFAX}</td>
	<td nowrap align="right"><b>{$MOD.LBL_ORGANIZATION_LOGO} : </b></td>

	<td><a href="javascript:popitup('{$ORGANIZATIONLOGOPATH}/{$ORGANIZATIONLOGONAME}');">{$ORGANIZATIONLOGONAME}</a></td>
	</tr>
	</tbody></table></td>
	</tr>
	<tr>
	<td colspan="2" style="padding-top: 0px; padding-bottom: 0px;"><hr></td>
	</tr>
	<tr>
	<td	colspan="2" align="center">

	<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="classBtn" onclick="this.form.action.value='EditCompanyDetails'" type="submit" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}">&nbsp;&nbsp;
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
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
var win = null;
function popitup(url)
{
	newwindow=window.open(url,'name','height=300,width=450,left=550,top=20');

}
</SCRIPT>
{/literal}
