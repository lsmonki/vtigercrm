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
	<table border="0" cellspacing="1" cellpadding="0" width="100%" class="lvtBg">
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
                        		{/if}
                        		{if $IS_ADMIN eq 'on'}
            					<td style="padding-right:5px">
            						<input type="button" name="move" value="{$MOD.LBL_MOVE}" class="crmbutton small edit" onClick="fnvshNrm('movefolderlist'); posLay(this,'movefolderlist');" title="{$MOD.LBL_MOVE_DOCUMENTS}">
	            					<div style="display:none;position:absolute;width:150px;" id="movefolderlist" >
										<div class="layerPopup thickborder" style="display:block;position:relative;width:150px;" onmouseout="fninvsh('movefolderlist')" onmouseover="fnvshNrm('movefolderlist');">
											<table  class="layerHeadingULine" border="0" cellpadding="5" cellspacing="0" width="100%">
												<tr>
													<td class="genHeaderSmall" align="left" width="90%">
														{$MOD.LBL_MOVE_TO} :
													</td>
													<td align="right" width="10%">
														<a onclick="fninvsh('movefolderlist')" href="javascript:void(0);">
														<img border="0" align="absmiddle" src="{'close.gif'|@vtiger_imageurl:$THEME}"/></a>
													</td>
												</tr>
											</table>
											<table class="drop_down"  border="0" cellpadding="5" cellspacing="0" width="100%">
												{foreach item=folder from=$ALL_FOLDERS}
												<tr onmouseout="this.className='lvtColData'" onmouseover="this.className='lvtColDataHover'">
													<td align="left">	
														<a href="javascript:;" onClick="MoveFile('{$folder.folderid}','{$folder.foldername}');" > {$folder.foldername}</a>
													</td>
												</tr>
												{/foreach}
											</table>
										</div>
								</div>
            					
            					
            					</td>
            					{/if}
            					<td style="padding-right:5px"><input type="button" name="add" value="{$MOD.LBL_ADD_NEW_FOLDER}" class="crmbutton small edit" onClick="fnvshobj(this,'orgLay');" title="{$MOD.LBL_ADD_NEW_FOLDER}"></td>
      							{if $EMPTY_FOLDERS|@count gt 0}
      							<td>      								
									<input type="button" name="show" value="{$MOD.LBL_EMPTY_FOLDERS}" class="crmbutton small cancel" onClick="fnvshobj(this,'emptyfolder');" title="{$MOD.LBL_EMPTY_FOLDERS}">				
								</td>
								{/if}
							</tr>
							</table>
						</td>
						<td width="100%" align="right">
						   <!-- Filters -->
							{if $HIDE_CUSTOM_LINKS neq '1'}
							<table border=0 cellspacing=0 cellpadding=0 class="small">
								<tr>
									<td>{$APP.LBL_VIEW}</td>
									<td style="padding-left:5px;padding-right:5px">
			                            <SELECT NAME="viewname" id="viewname" class="small" onchange="showDefaultCustomView(this,'{$MODULE}','{$CATEGORY}')">{$CUSTOMVIEW_OPTION}</SELECT>
									</td>
			                        {if $ALL eq 'All'}
									<td><a href="index.php?module={$MODULE}&action=CustomView&parenttab={$CATEGORY}">{$APP.LNK_CV_CREATEVIEW}</a>
										<span class="small">|</span>
										<span class="small" disabled>{$APP.LNK_CV_EDIT}</span>
										<span class="small">|</span>
										<span class="small" disabled>{$APP.LNK_CV_DELETE}</span>
									</td>
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
				</table> 
				<br>
				<!-- List View's Buttons and Filters ends -->
				{foreach item=folder from=$FOLDERS}
				<!-- folder division starts -->
				<div id='{$folder.folderid}'>
					<table class="reportsListTable" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">		
						<tr>
							<td class="mailSubHeader" width="40%">
								<b>{$folder.foldername}</b>
								&nbsp;&nbsp;
								{if $folder.description neq ''}
							 	<font color='grey'>[<i>{$folder.description}</i>]</font>
							 	{/if}                         
		                 	</td>   
							<td class="mailSubHeader" width="28%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		                     	{$folder.navigation}
							</td> <!-- $IS_ADMIN eq "on" -->
							<td class="mailSubHeader" align="right" width="28%">
								{if $folder.folderid neq '0' && $IS_ADMIN eq "on"}
								<input type="button" name="delete" value=" {$MOD.LBL_DELETE_FOLDER} " class="crmbutton small delete" onClick="DeleteFolderCheck('{$folder.folderid}');">
								{else}
								&nbsp;
								{/if}
							</td>
						</tr>
						<tr>
							<td colspan="3" >			
								<div id="FileList_{$folder.folderid}">
					 				<!-- File list table for a folder starts -->
									<table border=0 cellspacing=1 cellpadding=3 width=100%>
										<!-- Table Headers -->
										{assign var="header_count" value=$LISTHEADER|@count}
										<tr>
		            						<td class="colHeader small" width="10px"><input type="checkbox"  name="selectall" onClick='toggleSelect_ListView(this.checked,"selected_id{$folder.folderid}");'></td>
											{foreach name="listviewforeach" item=header from=$LISTHEADER}
											<td class="colHeader small">{$header}</td>
											{/foreach}
										</tr>
										<!-- Table Contents -->
										{foreach item=entity key=entity_id from=$folder.entries}
										<tr class="lvtColData" bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'" id="row_{$entity_id}">
											<td width="2%"><input type="checkbox" name="selected_id{$folder.folderid}" id="{$entity_id}" value= '{$entity_id}' onClick='check_object(this)'></td>
											{foreach item=recordid key=record_id from=$entity}				
											{foreach item=data from=$recordid}
											<td>{$data}</td>
								        	{/foreach}
								        	{/foreach}
										</tr>
										<!-- If there are no entries for a folder -->
										{foreachelse}
										<tr>
											<td align="center" colspan="{$header_count}+1">
												{$MOD.LBL_NO_DOCUMENTS}
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
				<!-- folder division ends -->
				<br>			
				{/foreach} 
				<!-- $FOLDERS ends -->
				
				<!-- this div not been used -->
				<br>
				<div id="emptyFolders" style="display:none;">
					<table width="100%" cellspacing="0" cellpadding="5" border="0" class="layerHeadingULine rptTable">
						<tr style="border-top:1px solid black;">
							<td class="genHeaderSmall">{$MOD.LBL_EMPTY_FOLDERS}</td>
							<td align="right"><a onclick="showHideFolders('showEmptyFoldersLink', 'emptyFolders');" href="javascript:;"><img border="0" align="absmiddle" src="{'close.gif'|@vtiger_imageurl:$THEME}"/></a>
						</tr>
					</table>
				<!-- List View's Buttons and Filters ends -->
					{foreach item=folder from=$EMPTY_FOLDERS}
					<!-- folder division starts -->
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
											{assign var="header_count" value=$LISTHEADER|@count}
											<tr>
			            						<td class="colHeader small" width="10px"><input type="checkbox"  name="selectall" onClick='toggleSelect_ListView(this.checked,"selected_id{$ALL_FOLDERS.folderid}");'></td>
												{foreach name="listviewforeach" item=header from=$LISTHEADER}
												<td class="colHeader small">{$header}</td>
												{/foreach}
											</tr>
											<tr>
												<td align="center" colspan="{$header_count}+1">
													{$MOD.LBL_NO_DOCUMENTS}
												</td>
											</tr>
						 				</table>
									</div> 
								<!-- File list table for a folder ends -->
								</td>
							</tr>
						</table>
					</div>
					<!-- folder division ends -->
					<br>			
					{/foreach} 
					<!-- $FOLDERS ends -->
				</div>
			</td>
			{/if}
		<!-- conditional statement for $NO_FOLDERS ends -->
		</tr>
	</table>
	
			<!-- Move documents UI for Documents module starts -->
		
		
		<!-- Move documents UI for Documents module ends -->
		<div class="layerPopup thickborder" style="display:none;position:absolute; left:193px;top:106px;width:155px;" id="emptyfolder" onmouseout="fninvsh('emptyfolder')" onmouseover="fnvshNrm('emptyfolder');">
			<table  class="layerHeadingULine" border="0" cellpadding="5" cellspacing="0" width="100%">
				<tr>
					<td class="genHeaderSmall" align="left">
						{$MOD.LBL_EMPTY_FOLDERS} :
					</td>
					<td align="right" width="10%">
						<a onclick="fninvsh('emptyfolder')" href="javascript:void(0);">
						<img border="0" align="absmiddle" src="{'close.gif'|@vtiger_imageurl:$THEME}"/></a>
					</td>
				</tr>
			</table>
			<table class="drop_down"  border=0 cellpadding=5 cellspacing=0 width=100%>
			{foreach item=folder from=$EMPTY_FOLDERS}
				<tr onmouseout="this.className='lvtColData'" onmouseover="this.className='lvtColDataHover'">
					<td>{$folder.foldername}</td>
					<td align=right><a href="javascript:;" onclick="DeleteFolderCheck({$folder.folderid});" >del</a></td>
				</tr>
			{/foreach}
			</table>
		</div>
	
</form>	
{$SELECT_SCRIPT}
<div id="basicsearchcolumns" style="display:none;"><select name="search_field" id="bas_searchfield" class="txtBox" style="width:150px">{html_options  options=$SEARCHLISTHEADER}</select></div>

<script>
{literal}
function showHideFolders(show_ele, hide_ele) {
	var show_obj = document.getElementById(show_ele);
	var hide_obj = document.getElementById(hide_ele);
	if (show_obj != null) {
		show_obj.style.display="block";
	}
	if (hide_obj != null) {
		hide_obj.style.display="none";
	}
}
{/literal}
</script>
