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
<!-- BEGIN: main -->
<div style="background-color:#FFFFFF;padding:10px;height:394px;overflow:auto;">
<table class="small" border="0" cellpadding="5" cellspacing="0" width="100%">
<tbody><tr>
<td colspan="3" class="genHeaderSmall">{$MOD.LBL_MY_BOOKMARKS} <hr></td>

</tr>
<tr>
<td colspan="3"><input name="bookmark" value=" {$MOD.LBL_NEW_BOOKMARK} " class="classBtn" onclick="fnvshobj(this,'editportal_cont');fetchAddSite('');" type="button"></td>
</tr>
<tr>
<td class="detailedViewHeader" width="5%"><b>{$MOD.LBL_SNO}</b></td>
<td class="detailedViewHeader" width="75%"><b>{$MOD.LBL_BOOKMARK_NAME_URL}</b></td>

<td class="detailedViewHeader" width="20%"><b>{$MOD.LBL_TOOLS}</b></td>
</tr>

{foreach name=portallists item=portaldetails key=sno from=$PORTALS}
<tr>  <td class="dvtCellInfo" style="border-right: 1px solid rgb(204, 204, 204);" align="left">{$smarty.foreach.portallists.iteration}</td>
<td class="dvtCellInfo" style="border-right: 1px solid rgb(204, 204, 204);" align="left">
<b>{$portaldetails.portalname}</b><br>
<span class="big">{$portaldetails.portalurl}</span>
</td>
<td class="dvtCellInfo" align="left">
<a href="javascript:;" onclick="fnvshobj(this,'editportal_cont');fetchAddSite('{$portaldetails.portalid}');" class="webMnu">{$APP.LBL_EDIT}</a>&nbsp;|&nbsp;
<a href="javascript:;" onclick="DeleteSite('{$portaldetails.portalid}');"class="webMnu">{$APP.LBL_MASS_DELETE}</a>
</td>
</tr>
{/foreach}
<tr>
<td colspan="3">&nbsp;</td>
</tr>
</tbody></table>
</div>
