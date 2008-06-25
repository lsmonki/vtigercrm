<!--*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->
<TABLE width="95%" border=0 cellPadding=0 cellSpacing=1 class="formOuterBorder">
   <tr>
	<td  colspan="5" class="formSecHeader">{$MOD.LBL_TICKET_CUMULATIVE_STATISTICS}</td>
   </tr>
   <tr>
	<td class="dataLabel" width="15%" noWrap><div align="left"><b> {$MOD.LBL_CASE_TOPIC}</div></b></td>
	<TD  class="dataLabel" width="15%" noWrap ><div align="left"><b>{$MOD.LBL_TICKET}</b></div></TD>
        <TD  class="dataLabel" width="20%" noWrap ><div align="left"><b>{$MOD.LBL_OPEN}</b></div></TD>
        <TD  class="dataLabel" width="20%" noWrap ><div align="left"><b>{$MOD.LBL_CLOSED}</b></div></TD>
        <TD  class="dataLabel" width="25%" noWrap ><div align="left"><b>{$MOD.LBL_TOTAL}</b></div></TD>
   </tr>
   <tr>
	<td class="dataLabel" width="10%" noWrap><div align="left">{$MOD.LBL_ALL}</div></td>
        <TD  class="dataLabel" width="10%" noWrap ><div align="left">{$MOD.LBL_ALL}</div></TD>
        <TD  width="25%" noWrap ><div align="left">{$ALLOPEN}</div></TD>
        <TD  width="25%" noWrap ><div align="left">{$ALLCLOSED}</div></TD>
        <TD  width="25%" noWrap ><div align="left">{$ALLTOTAL}</div></TD>
   </tr>
	{$PRIORITIES}
	{$CATEGORIES}
	{$USERS}
</TABLE>

