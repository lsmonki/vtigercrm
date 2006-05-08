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
<table width="100%" border="0" cellpadding="0" cellspacing="0">
 <tr>
			   <td>&nbsp;</td>
			   <td class="forwardBg">
			   		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="75%">
						  <input type="button" name="Qualify2" value=" Qualify " class="classWebBtn" />&nbsp;
						  <input type="button" name="reply" value=" Reply " class="classWebBtn" />&nbsp;
						  <input type="button" name="forward" value=" Forward " class="classWebBtn" />&nbsp;
						  <input type="button" name="download" value=" Download Attachments " class="classWebBtn" onclick="fnvshobj(this,'reportLay');"  onmouseout="fninvsh('reportLay')" />
						</td>
						<td width="25%" align="right"><input type="button" name="Button" value=" Delete "  class="classWebBtn" onClick="DeleteEmail('{$ID}')"/></td>
					  </tr>
					</table>
				</td>
			   </tr>
			 <tr>
			   <td>&nbsp;</td>
			   <td height="300" bgcolor="#FFFFFF" valign="top" style="padding-top:10px;">
				{foreach item=row from=$BLOCKS}	
{foreach item=elements key=title from=$row}	
{if $title eq 'Subject'}
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td width="20%" align="right"><b>From :</b></td><td width="2%">&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td align="right">CC :</td><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td align="right"><b>Subject  :</b></td><td>&nbsp;</td><td>{$BLOCKS.3.Subject.value}</td></tr>
	<tr><td align="right" style="border-bottom:1px solid #666666;" colspan="3">&nbsp;</td></tr>
</table>
{elseif $title eq 'Description'}
<div>
{$BLOCKS.4.Description.value}
</div>
{/if}
{/foreach}
{/foreach}
			   </td>
			   </tr>

</table>
