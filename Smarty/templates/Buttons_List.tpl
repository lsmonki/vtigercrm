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
<script type="text/javascript" src="modules/{$MODULE}/{$MODULE}.js"></script>

<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>
<tr><td style="height:2px"></td></tr>
<tr>
	{assign var="action" value="ListView"}
	{if $MODULE eq 'Recyclebin'}
		{assign var="action" value="index"}
	{/if}	
	{assign var="MODULELABEL" value=$MODULE}
	{if $APP[$MODULE]}
		{assign var="MODULELABEL" value=$APP[$MODULE]}
	{/if}	
	<td style="padding-left:10px;padding-right:50px" class="moduleName" nowrap>{$APP.$CATEGORY} > <a class="hdrLink" href="index.php?action={$action}&module={$MODULE}&parenttab={$CATEGORY}">{$MODULELABEL}</a></td>
	<td width=100% nowrap>
	
		<table border="0" cellspacing="0" cellpadding="0" >
		<tr>
		<td class="sep1" style="width:1px;"></td>
		<td class=small >
			<!-- Add and Search -->
			<table border=0 cellspacing=0 cellpadding=0>
			<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					{if $CHECK.EditView eq 'yes' && $MODULE neq 'Emails' && $MODULE neq 'Webmails'}
			        		{if $MODULE eq 'Calendar' || $MODULE eq 'Recyclebin'}
		                      	        	<td style="padding-right:0px;padding-left:10px;"><img src="{'btnL3Add-Faded.gif'|@vtiger_imageurl:$THEME}" border=0></td>
                	   			 {else}
	                        		       	<td style="padding-right:0px;padding-left:10px;"><a href="index.php?module={$MODULE}&action=EditView&return_action=DetailView&parenttab={$CATEGORY}"><img src="{$IMAGE_PATH}btnL3Add.gif" alt="{$APP.LBL_CREATE_BUTTON_LABEL} {$APP.$SINGLE_MOD}..." title="{$APP.LBL_CREATE_BUTTON_LABEL} {$APP.$SINGLE_MOD}..." border=0></a></td>
			                       	{/if}
					{else}
						<td style="padding-right:0px;padding-left:10px;"><img src="{'btnL3Add-Faded.gif'|@vtiger_imageurl:$THEME}" border=0></td>	
					{/if}
									
					{if $CHECK.index eq 'yes' && $MODULE neq 'Emails' && $MODULE neq 'Webmails'}
						 <td style="padding-right:10px"><a href="javascript:;" onClick="moveMe('searchAcc');searchshowhide('searchAcc','advSearch');mergehide('mergeDup')" ><img src="{$IMAGE_PATH}btnL3Search.gif" alt="{$APP.LBL_SEARCH_ALT}{$APP.$MODULE}..." title="{$APP.LBL_SEARCH_TITLE}{$APP.$MODULE}..." border=0></a></a></td>
					{else}
						<td style="padding-right:10px"><img src="{'btnL3Search-Faded.gif'|@vtiger_imageurl:$THEME}" border=0></td>
					{/if}
					
				</tr>
				</table>
			</td>
			</tr>
			</table>
		</td>
		<td style="width:20px;">&nbsp;</td>
		<td class="small">
			<!-- Calendar Clock Calculator and Chat -->
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
						{if $CALENDAR_DISPLAY eq 'true'} 
 		                                                {if $CATEGORY eq 'Settings' || $CATEGORY eq 'Tools' || $CATEGORY eq 'Analytics'} 
 		                                                        {if $CHECK.Calendar eq 'yes'} 
 		                                                                <td style="padding-right:0px;padding-left:10px;"><a href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal("parenttab=My Home Page");'><img src="{$IMAGE_PATH}btnL3Calendar.gif" alt="{$APP.LBL_CALENDAR_ALT}" title="{$APP.LBL_CALENDAR_TITLE}" border=0></a></a></td> 
 		                                                        {else} 
 		                                                                <td style="padding-right:0px;padding-left:10px;"><img src="{'btnL3Calendar-Faded.gif'|@vtiger_imageurl:$THEME}"></td> 
 		                                                        {/if} 
						{else}
						{if $CHECK.Calendar eq 'yes'} 
 		                                                                <td style="padding-right:0px;padding-left:10px;"><a href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal("parenttab={$CATEGORY}");'><img src="{$IMAGE_PATH}btnL3Calendar.gif" alt="{$APP.LBL_CALENDAR_ALT}" title="{$APP.LBL_CALENDAR_TITLE}" border=0></a></a></td> 
 		                                                        {else} 
 		                                                                <td style="padding-right:0px;padding-left:10px;"><img src="{'btnL3Calendar-Faded.gif'|@vtiger_imageurl:$THEME}"></td> 
 		                                                        {/if} 
						{/if}
					{/if}
					{if $WORLD_CLOCK_DISPLAY eq 'true'} 
 		                                                <td style="padding-right:0px"><a href="javascript:;"><img src="{$IMAGE_PATH}btnL3Clock.gif" alt="{$APP.LBL_CLOCK_ALT}" title="{$APP.LBL_CLOCK_TITLE}" border=0 onClick="fnvshobj(this,'wclock');"></a></a></td> 
 		                                        {/if} 
 		                                        {if $CALCULATOR_DISPLAY eq 'true'} 
 		                                                <td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calc.gif" alt="{$APP.LBL_CALCULATOR_ALT}" title="{$APP.LBL_CALCULATOR_TITLE}" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();"></a></td> 
 		                                        {/if} 
 		                                        {if $CHAT_DISPLAY eq 'true'} 
 		                                                <td style="padding-right:10px"><a href="javascript:;" onClick='return window.open("index.php?module=Home&action=vtchat","Chat","width=600,height=450,resizable=1,scrollbars=1");'><img src="{$IMAGE_PATH}tbarChat.gif" alt="{$APP.LBL_CHAT_ALT}" title="{$APP.LBL_CHAT_TITLE}" border=0></a> 
 		                                        {/if} 
                    </td>	
					<td style="padding-right:10px"><img src="{$IMAGE_PATH}btnL3Tracker.gif" alt="{$APP.LBL_LAST_VIEWED}" title="{$APP.LBL_LAST_VIEWED}" border=0 onClick="fnvshobj(this,'tracker');">
                    			</td>	
				</tr>
				</table>
		</td>
		<td style="width:20px;">&nbsp;</td>
		<td class="small">
			<!-- Import / Export -->
			<table border=0 cellspacing=0 cellpadding=5>
			<tr>

			{* vtlib customization: Hook to enable import/export button for custom modules. Added CUSTOM_MODULE *}

			{if $MODULE eq 'Vendors' || $MODULE eq 'HelpDesk' || $MODULE eq 'Contacts' || $MODULE eq 'Leads' || $MODULE eq 'Accounts' || $MODULE eq 'Potentials' || $MODULE eq 'Products' || $CUSTOM_MODULE eq 'true' }
		   		{if $CHECK.Import eq 'yes'}	
					<td style="padding-right:0px;padding-left:10px;"><a href="index.php?module={$MODULE}&action=Import&step=1&return_module={$MODULE}&return_action=index&parenttab={$CATEGORY}"><img src="{$IMAGE_PATH}tbarImport.gif" alt="{$APP.LBL_IMPORT} {$APP.$MODULE}" title="{$APP.LBL_IMPORT} {$APP.$MODULE}" border="0"></a></td>	
				{else}	
					<td style="padding-right:0px;padding-left:10px;"><img src="{'tbarImport-Faded.gif'|@vtiger_imageurl:$THEME}" border="0"></td>	
				{/if}	
				{if $CHECK.Export eq 'yes'}	
                                <td style="padding-right:10px"><a name='export_link' href="javascript:void(0)" onclick="return selectedRecords('{$MODULE}','{$CATEGORY}')"><img src="{$IMAGE_PATH}tbarExport.gif" alt="{$APP.LBL_EXPORT} {$APP.$MODULE}" title="{$APP.LBL_EXPORT} {$APP.$MODULE}" border="0"></a></td>
				{else}	
					<td style="padding-right:10px"><img src="{'tbarExport-Faded.gif'|@vtiger_imageurl:$THEME}" border="0"></td>
                	{/if}
			{else}
				<td style="padding-right:0px;padding-left:10px;"><img src="{'tbarImport-Faded.gif'|@vtiger_imageurl:$THEME}" border="0"></td>
                		<td style="padding-right:10px"><img src="{'tbarExport-Faded.gif'|@vtiger_imageurl:$THEME}" border="0"></td>
			{/if}
			{if $MODULE eq 'Contacts' || $MODULE eq 'Leads' || $MODULE eq 'Accounts'|| $MODULE eq 'Products'|| $MODULE eq 'Potentials'|| $MODULE eq 'HelpDesk'|| $MODULE eq 'Vendors' || $CUSTOM_MODULE eq 'true'} 
				{if $CHECK.DuplicatesHandling eq 'yes'}	
					<!--<td style="padding-right:10px"><a href="index.php?module={$MODULE}&action=FindDuplicateRecords&button_view=true&list_view=true&parenttab={$CATEGORY}"><img src="{$IMAGE_PATH}findduplicates.gif" alt="{$APP.LBL_FIND_DUPICATES}" title="{$APP.LBL_FIND_DUPLICATES}" border="0"></a></td> -->
					<td style="padding-right:10px"><a href="javascript:;" onClick="moveMe('mergeDup');mergeshowhide('mergeDup');searchhide('searchAcc','advSearch');"><img src="{'findduplicates.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_FIND_DUPICATES}" title="{$APP.LBL_FIND_DUPLICATES}" border="0"></a></td>
				{else}
					<td style="padding-right:10px"><img src="{'FindDuplicates-Faded.gif'|@vtiger_imageurl:$THEME}" border="0"></td>	
				{/if}
			{else}
				<td style="padding-right:10px"><img src="{'FindDuplicates-Faded.gif'|@vtiger_imageurl:$THEME}" border="0"></td>
			{/if}
			</tr>
			</table>	
		<td style="width:20px;">&nbsp;</td>
		<td class="small">
				<!-- All Menu -->
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
				<td style="padding-left:10px;"><a href="javascript:;" onmouseout="fninvsh('allMenu');" onClick="fnvshobj(this,'allMenu')"><img src="{$IMAGE_PATH}btnL3AllMenu.gif" alt="{$APP.LBL_ALL_MENU_ALT}" title="{$APP.LBL_ALL_MENU_TITLE}" border="0"></a></td>
				</tr>
				</table>
		</td>	
		{if $MODULE eq 'Documents'}		
			<td style="width:20px;">&nbsp;</td>
			<td class="small">
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
            		<td style="padding-right:5px"><a href="javascript:;" onclick="fnvshobj(this,'orgLay');"><img src="{$IMAGE_PATH}reportsFolderCreate.gif" alt="{$MOD.LBL_ADD_NEW_FOLDER}..." title="{$MOD.LBL_ADD_NEW_FOLDER}..." border=0></a>&nbsp;</td>
            		{if $NO_FOLDERS neq 'yes'}
                    	{if $IS_ADMIN eq 'on'}
            				<td style="padding-right:5px"><a href="javascript:;" onclick="fnvshobj(this,'folderLay');"><img src="{$IMAGE_PATH}reportsMove.gif" alt="{$MOD.LBL_MOVE_DOCUMENTS}..." title="{$MOD.LBL_MOVE_DOCUMENTS}..." border=0></a></td>
            			{/if}
            			{if $MASS_DELETE eq 'yes'}
            				<td style="padding-right:5px"><a href="javascript:;" onClick="return massDelete('{$MODULE}');"><img src="{$IMAGE_PATH}reportsDelete.gif" alt="{$MOD.LBL_DELETE_DOCUMENTS}..." title="{$MOD.LBL_DELETE_DOCUMENTS}..." border=0></a></td>
            			{/if}
            		{/if}
				</tr>
				</table>
			</td>
		{/if}
		</tr>
		</table>
	</td>
</tr>
<tr><td style="height:2px"></td></tr>
</TABLE>
