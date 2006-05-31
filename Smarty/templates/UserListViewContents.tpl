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
<table width="100%" border=0>
					<tr>
					<td width="75%" style="border-right:1px dashed #CCCCCC;padding:5px;">
							<table width="100%" border="0">
								<tr>
									<td align="left" style="padding:5px;">{$RECORD_COUNTS}</td>
									{$NAVIGATION}
								</tr>
							</table>
							<table width="100%" border="0" cellpadding="5" cellspacing="1" class="small" style="background-color: rgb(204, 204, 204);">
                          <tbody>
                          	<tr>
							{foreach item=header from=$LIST_HEADER}
                              <td class="lvtCol">{$header}</td>
							{/foreach}	
                            </tr>
							{section name=entries loop=$LIST_ENTRIES}
             <tr  class="lvtColData" onmouseover="this.className='lvtColDataHover'" onmouseout="this.className='lvtColData'" bgcolor="white">
             {foreach item=listvalues from=$LIST_ENTRIES[entries]}
				  <td >{$listvalues}</td>
             {/foreach}
			 </tr>
		{/section}	
        </tbody>
        </table>
	</td>
	<td width="25%" class="padTab" align="center" valign="top">
		<div id="chPhoto" style="display:block;width:80%;">
			<table width="100%"   cellspacing="0" cellpadding="5" class="small">
				<tr><td align="left" colspan="2" style="border-bottom:1px dotted #CCCCCC;">
					<b>{$CMOD.LBL_STATISTICS}</b></td>
				</tr>
				<tr><td align="right"><b>{$CMOD.LBL_TOTAL}</b></td>
					<td  align="left">{$USER_COUNT.user} {$CMOD.LBL_USERS}</td>	
				</tr>	
				<tr><td  align="right"><b>{$CMOD.LBL_ADMIN} {$CMOD.LBL_COLON}</b></td>
					<td  align="left">{$USER_COUNT.admin} {$CMOD.LBL_USERS}</td>	
				</tr>	
				<tr><td  align="right"><b>{$CMOD.LBL_OTHERS}</b></td>
					<td  align="left">{$USER_COUNT.nonadmin} {$CMOD.LBL_USERS}</td>
				</tr>	
			</table>
	    </div>
	</td>
	</tr>
</table>

