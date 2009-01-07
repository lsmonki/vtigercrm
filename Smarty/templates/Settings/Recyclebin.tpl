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
<br>
<script language="JavaScript" type="text/javascript" src="include/js/search.js"></script>
<script language="JavaScript" type="text/javascript" src="modules/Recyclebin/Recyclebin.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="{'showPanelTopLeft.gif'|@vtiger_imageurl:$THEME}"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
	<div align=center>
	
			{include file='SetMenu.tpl'}
			<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
				<tr>
					<td width=50 rowspan=2 valign=top><img src="{'settingsTrash.gif'|@vtiger_imageurl:$THEME}" width="48" height="48" border=0></td>
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=Recyclebin&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> > {$MOD.LBL_RECYCLEBIN} </b><div id="an_busy" style="display:none;float:left;position:relative;"><img src="{'vtbusy.gif'|@vtiger_imageurl:$THEME}" align="right"></div></td>
				</tr>
				<tr>
					<td valign=top class="small">{$MOD.LBL_RECYCLEBIN_DESCRIPTION} </td>
				</tr>
			</table>
				
			<br>


{*<!-- Contents -->*}

<table border=0  cellspacing=0 cellpadding=0 width=98% align=center>

     <tr>
	<td valign="top" width=100% style="padding:10px;">
<form name="basicSearch" action="index.php" onsubmit="return false;">
<div id="searchAcc" style="z-index:1;display:block;position:relative;">
<table width="80%" cellpadding="5" cellspacing="0"  class="searchUIBasic small" align="center" border=0>
<tr>
<td class="searchUIName small" nowrap align="left">
<span class="moduleName">{$APP.LBL_SEARCH}</span><br>		</td>
<td class="small" nowrap align=right><b>{$APP.LBL_SEARCH_FOR}</b></td>
<td class="small"><input type="text"  class="txtBox" style="width:120px" name="search_text"></td>
<td class="small" nowrap><b>{$APP.LBL_IN}</b>&nbsp;</td>
<td class="small" nowrap>
<div id="basicsearchcolumns_real">
<select name="search_field" id="bas_searchfield" class="txtBox" style="width:150px">
{html_options  options=$SEARCHLISTHEADER }
</select>
</div>
<input type="hidden" name="searchtype" value="BasicSearch">
<input type="hidden" name="module" value="{$SELECTED_MODULE}">
<input type="hidden" name="parenttab" value="{$CATEGORY}">
<input type="hidden" name="action" value="index">
<input type="hidden" name="query" value="true">
<input type="hidden" name="search_cnt">
</td>
<td class="small" nowrap colspan=2>
<input name="submit" type="button" class="crmbutton small create" onClick="callRBSearch('Basic');" value=" {$APP.LBL_SEARCH_NOW_BUTTON} ">&nbsp;

</td>
</tr>
<tr>
<td colspan="7" align="center" class="small">
<table border=0 cellspacing=0 cellpadding=0 width=100%>
<tr>
{$ALPHABETICAL}
</tr>
</table>
</td>
</tr>
</table>
</div>
</form>
     </td>
</tr>
<tr><td>

	  <div id="modules_datas" class="small" style="width:100%;position:relative;">
			{include file="Recyclebin/RecyclebinContents.tpl"}
	</div>
</tr></td>


</div>
</td>
</tr>
</table>
</td>
</tr>
</table>

	</td>
        <td valign="top"><img src="{'showPanelTopRight.gif'|@vtiger_imageurl:$THEME}"></td>
   </tr>
</tbody>
</table>

