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
{if $smarty.request.ajax neq ''}
&#&#&#{$ERROR}&#&#&#
{/if}

<form name="massdelete" method="POST" id="massdelete">
     <input name='search_url' id="search_url" type='hidden' value='{$SEARCH_URL}'>
     <input name="idlist" id="idlist" type="hidden">
     <input name="change_owner" type="hidden">
     <input name="change_status" type="hidden">
     <input name="action" type="hidden">
     <input name="where_export" type="hidden" value="{php} echo to_html($_SESSION['export_where']);{/php}">
     <input name="step" type="hidden">
     <input name="allids" type="hidden" id="allids" value="{$ALLIDS}">
     <input name="selectedboxes" id="selectedboxes" type="hidden" value="{$SELECTEDIDS}">
     <input name="allselectedboxes" id="allselectedboxes" type="hidden" value="{$ALLSELECTEDIDS}">
     <input name="current_page_boxes" id="current_page_boxes" type="hidden" value="{$CURRENT_PAGE_BOXES}">
				<!-- List View Master Holder starts -->
				<table border=0 cellspacing=1 cellpadding=0 width=100% class="lvtBg">
				<tr>
				{if $NO_FOLDERS eq 'yes'}
		<td width="100%" valign="top" height="250px;"><br><br>
        	<div align="center"> <br><br><br><br><br>
			<table width="80%" cellpadding="5" cellspacing="0"  class="searchUIBasic small" align="center" border=0>
			<tr><td align="center" style="padding:20px;">
				<a href="javascript:;" onclick="fnvshobj(this,'orgLay');">{$MOD.LBL_CLICK_HERE}</a>&nbsp;{$MOD.LBL_TO_ADD_FOLDER}
			</td></tr></table>
        	</div>
		</td>
				{else}
				<td>
				<!-- List View's Buttons and Filters starts -->
		        <table border=0 cellspacing=0 cellpadding=2 width=100% class="small">
			    <tr>
			    	<td>
						<table border=0 cellspacing=0 cellpadding=0>
							<tr>
                        		{if $MASS_DELETE eq 'yes'}
            					<td style="padding-right:5px"><input type="button" name="delete" value="{$APP.LBL_DELETE}" class="crmbutton small delete" onClick="return massDelete('{$MODULE}');"></td>
                        			<td>&nbsp;</td>
                        		{/if}
                        		{if $IS_ADMIN eq 'on'}
            					<td style="padding-right:5px"><input type="button" name="move" value="{$MOD.LBL_MOVE}" class="crmbutton small edit" onClick="fnvshobj(this,'folderLay');" title="{$MOD.LBL_MOVE_DOCUMENTS}"></td>
            					{/if}
							</tr>
						</table>
					</td>
				<!-- Page Navigation
		        <td nowrap>
					<table border=0 cellspacing=0 cellpadding=0>
					     <tr><td>{$NAVIGATION}</td></tr>
					</table>
                </td> -->
				<td width=100% align="right">
				   <!-- Filters -->
				   {if $HIDE_CUSTOM_LINKS neq '1'}
					<table border=0 cellspacing=0 cellpadding=0 class="small">
					<tr>
						<td>{$APP.LBL_VIEW}</td>
						<td style="padding-left:5px;padding-right:5px">
                            <SELECT NAME="viewname" id="viewname" class="small" onchange="showDefaultCustomView(this,'{$MODULE}','{$CATEGORY}')">{$CUSTOMVIEW_OPTION}</SELECT></td>
                            {if $ALL eq 'All'}
							<td><a href="index.php?module={$MODULE}&action=CustomView&parenttab={$CATEGORY}">{$APP.LNK_CV_CREATEVIEW}</a>
							<span class="small">|</span>
							<span class="small" disabled>{$APP.LNK_CV_EDIT}</span>
							<span class="small">|</span>
							<span class="small" disabled>{$APP.LNK_CV_DELETE}</span></td>
						    {else}
							<td>
								<a href="index.php?module={$MODULE}&action=CustomView&parenttab={$CATEGORY}">{$APP.LNK_CV_CREATEVIEW}</a>
								<span class="small">|</span>
								{if $CV_EDIT_PERMIT neq 'yes'}
									<span class="small" disabled>{$APP.LNK_CV_EDIT}</span>
								{else}
									<a href="index.php?module={$MODULE}&action=CustomView&record={$VIEWID}&parenttab={$CATEGORY}">{$APP.LNK_CV_EDIT}</a>
								{/if}
								<span class="small">|</span>
								{if $CV_DELETE_PERMIT neq 'yes'}
									<span class="small" disabled>{$APP.LNK_CV_DELETE}</span>
								{else}
									<a href="javascript:confirmdelete('index.php?module=CustomView&action=Delete&dmodule={$MODULE}&record={$VIEWID}&parenttab={$CATEGORY}')">{$APP.LNK_CV_DELETE}</a>
								{/if}
								{if $CUSTOMVIEW_PERMISSION.ChangedStatus neq '' && $CUSTOMVIEW_PERMISSION.Label neq ''}
									<span class="small">|</span>	
								   		<a href="#" id="customstatus_id" onClick="ChangeCustomViewStatus({$VIEWID},{$CUSTOMVIEW_PERMISSION.Status},{$CUSTOMVIEW_PERMISSION.ChangedStatus},'{$MODULE}','{$CUSTOMVIEW_PERMISSION.Label}')">{$CUSTOMVIEW_PERMISSION.Label}</a>
								{/if}
							</td>
						    {/if}
					</tr>
					</table> 
				   <!-- Filters  END-->
				   {/if}

				</td>	
       		    </tr>
			</table> <br>
			<!-- List View's Buttons and Filters ends -->

		{foreach item=folder from=$FOLDERS}
		<!-- folder division starts -->
		{assign var=file_cnt value=$folder.entries|@sizeof}
		{assign var=display_folder value='true'}
		{if $file_cnt eq '0' && $HIDE_EMPTY_FOLDERS eq 'yes'}
			{assign var=display_folder value='false'}
		{/if}
		{if $display_folder eq 'true'}
		<div id='{$folder.folderid}'>
		<table class="reportsListTable" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">		
		<tr>

                     <td class="mailSubHeader">
                         <b>{$folder.foldername}</b>
						 &nbsp;&nbsp;&nbsp;
						 {if $folder.description neq ''}
						 <font color='grey'>[<i>{$folder.description}</i>]</font>
						 {/if}                         
                     </td>   
                     <td class="mailSubHeader">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     	{$folder.navigation}
                     </td>
                     <td class="mailSubHeader" align="right">
					{if $IS_ADMIN eq "on"}
						<input type="button" name="delete" value=" {$MOD.LBL_DELETE_FOLDER} " class="crmbutton small delete" onClick="DeleteFolderCheck('{$folder.folderid}');">
					{else}
						&nbsp;
					{/if}
					</td>
		</tr>
		<tr>
		<td colspan="3">			
			<div id="FileList_{$folder.folderid}">
			 <!-- File list table for a folder starts -->
			<table border=0 cellspacing=1 cellpadding=3 width=100%>
			<!-- Table Headers -->
			<tr>
            <td class="colHeader small"><input type="checkbox"  name="selectall" onClick="toggleSelect_ListView(this.checked,"selected_id");" disabled></td>
			{foreach name="listviewforeach" item=header from=$LISTHEADER}
 			<td class="colHeader small">{$header}</td>
				{/foreach}
			</tr>
			<!-- Table Contents -->
			{foreach item=entity key=entity_id from=$folder.entries}
			<tr class="lvtColData" bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'" id="row_{$entity_id}">
			<td width="2%"><input type="checkbox" NAME="selected_id" id="{$entity_id}" value= '{$entity_id}' onClick="check_object(this)"></td>
			{foreach item=recordid key=record_id from=$entity}				
			{foreach item=data from=$recordid}
			<td>{$data}</td>
	        {/foreach}
	        {/foreach}
			</tr>
			<!-- If there are no entries for a folder -->
			{foreachelse}
			<tr>
				<td align="center">
					No Documents
				</td>
			</tr>
			{/foreach}
			 </table>
			</div> 
			<!-- File list table for a folder ends -->
		</td>
		</tr>
		</table>
		</div>
		{/if}
		<!-- folder division ends -->
		<br>			
		    {/foreach} 
		    <!-- $FOLDERS ends -->
		       </td>
		       {/if}
		       <!-- conditional statement for $NO_FOLDERS ends -->
		   </tr>
	    </table>

   </form>	
{$SELECT_SCRIPT}
<div id="basicsearchcolumns" style="display:none;"><select name="search_field" id="bas_searchfield" class="txtBox" style="width:150px">{html_options  options=$SEARCHLISTHEADER}</select></div>
