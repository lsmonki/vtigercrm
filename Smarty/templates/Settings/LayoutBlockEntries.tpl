{*
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/ *}
			<form action="index.php" method="post" name="form">
				<input type="hidden" name="fld_module" value="{$MODULE}">
				<input type="hidden" name="module" value="Settings">
				<input type="hidden" name="parenttab" value="Settings">
				<input type="hidden" name="mode">

				<table class="listTable" border="0" cellpadding="3" cellspacing="0" width="100%">
					
					{foreach item=entries key=id from=$CFENTRIES name=outer}
						{if $entries.blockid ne $RELPRODUCTSECTIONID && $entries.blockid ne $COMMENTSECTIONID && $entries.blocklabel neq ''}
							<tr>
								<td colspan="5">
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td  class="colHeader small" width="70%">{$entries.blocklabel}&nbsp;&nbsp;
							  				</td>
											<td class="colHeader small"  align='right'> 
												{if $smarty.foreach.outer.first}
												<img src="{$IMAGE_PATH}arrow_down.png" border="0" style="cursor:pointer;" onclick="changeBlockorder(this,'block_down','{$entries.tabid}','{$entries.blockid}') " alt="change order" title="change order">
												{elseif $smarty.foreach.outer.last}
												<img src="{$IMAGE_PATH}arrow_up.png" border="0" style="cursor:pointer;" onclick="changeBlockorder(this,'block_up','{$entries.tabid}','{$entries.blockid}') " alt="Up" title="Up">
												{else}
												<img src="{$IMAGE_PATH}arrow_up.png" border="0" style="cursor:pointer;" onclick="changeBlockorder(this,'block_up','{$entries.tabid}','{$entries.blockid}') " alt="Up" title="Up">&nbsp;
												<img src="{$IMAGE_PATH}arrow_down.png" border="0" style="cursor:pointer;" onclick="changeBlockorder(this,'block_down','{$entries.tabid}','{$entries.blockid}') " alt="Down" title="Down">
												{/if}
										
												&nbsp;&nbsp;<img src="{$IMAGE_PATH}plus_layout.gif" border="0" style="cursor:pointer;" onclick="fnvshobj(this,'createcf');getCreateCustomBlockForm('{$MODULE}','{$entries.blockselect}','{$entries.tabid}','','add')" alt="Add" title="Add"/>&nbsp;&nbsp;
												{if $entries.customblockflag!=0}
												<img style="cursor:pointer;" onClick="fnvshobj(this,'createcf');deleteCustomBlock('{$MODULE}', '{$entries.blockid}', '{$entries.tabid}','{$entries.blocklabel}','{$entries.no}')" src="{'delete.gif'|@vtiger_imageurl:$THEME}" border="0"  alt="Delete" title="Delete"/>
												{/if}
												
												<select name="display_status" align='right' style="border:1px solid #666666;font-family:Arial, Helvetica, sans-serif;font-size:11px; width:auto" onChange="changeShowstatus(this,'{$entries.tabid}','{$entries.blockid}',this.value)" id='display_status'>
						                		    <option value="show" {if $entries.display_status==1}selected{/if}>{$MOD.LBL_Show}</option>
													<option value="hide" {if $entries.display_status==0}selected{/if}>{$MOD.LBL_Hide}</option>			                
												</select>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
						
							{foreach name=inner item=value from=$entries.field}
							 
								 {if $value.no % 2 == 0}
								  	</tr>
								  	<tr>
								 {/if}
							 	<td width="30%">
						 		{if $smarty.foreach.inner.first}							 
							 		&nbsp;&nbsp;{$value.label}&nbsp;&nbsp;</td><td width="19%" align = right>
								 	{if $entries.no!=1}
								  		<img src="{$IMAGE_PATH}arrow_down.png" border="0" style="cursor:pointer;" onclick="changeFieldorder(this,'down','{$value.fieldselect}','{$value.blockid}') " alt="Down" title="Down">
								 	{/if}
							  		<!--&nbsp;&nbsp;<img src="{$IMAGE_PATH}plus_layout.gif" border="0" style="cursor:pointer;" onclick="fnvshobj(this,'createcf');getCreateCustomFieldForm('{$MODULE}','{$value.blockid}','{$value.tabid}','','{$entries.blocklabel}','{$value.fieldselect}','')"  alt="Add" title="Add"/>-->
	
									{if $entries.no!=1}
											&nbsp&nbsp;<img src="{'arrow_right.png'|@vtiger_imageurl:$THEME}" border="0" style="cursor:pointer;" onclick="changeFieldorder(this,'Right','{$value.fieldselect}','{$value.blockid}')" alt="Right" title="Right"/>
									{/if}							
							  		</td>
									{if $value.no % 2 == 0}<td style="border-left:solid 1px #ccc; width:1px;"></td>	{/if}
									
								{elseif $smarty.foreach.inner.last}
									{if $value.no % 2 != 0}
										<img src="{'arrow_left.png'|@vtiger_imageurl:$THEME}" border="0" style="cursor:pointer;" onclick="changeFieldorder(this,'Left','{$value.fieldselect}','{$value.blockid}')" alt="Left" title="Left"/>
									{/if}						 
							 		&nbsp;&nbsp;<img src="{$IMAGE_PATH}arrow_up.png" border="0" style="cursor:pointer;" onclick="changeFieldorder(this,'up','{$value.fieldselect}','{$value.blockid}') " alt="Up" title="Up"/>&nbsp;&nbsp;{$value.label}&nbsp;&nbsp;</td>
							 		<td width="19%" align = right>
									<!--&nbsp;&nbsp;<img src="{$IMAGE_PATH}plus_layout.gif" border="0" style="cursor:pointer;" onclick="fnvshobj(this,'createcf');getCreateCustomFieldForm('{$MODULE}','{$value.blockid}','{$value.tabid}','','{$entries.blocklabel}','{$value.fieldselect}','')" alt="Add" title="Add">-->
									</td>
									{if $value.no % 2 == 0}
										<td style="border-left:solid 1px #ccc; width:1px;">
										</td>
									{/if}
									
								{else}												
									{if $value.no % 2 != 0}
										<img src="{'arrow_left.png'|@vtiger_imageurl:$THEME}" border="0" style="cursor:pointer;" onclick="changeFieldorder(this,'Left','{$value.fieldselect}','{$value.blockid}')" alt="Left" title="Left"/>
									{/if}
									{if $value.no != 1}
									 	&nbsp;&nbsp;<img src="{$IMAGE_PATH}arrow_up.png" border="0" style="cursor:pointer;" onclick="changeFieldorder(this,'up','{$value.fieldselect}','{$value.blockid}')" alt="Up" title="Up"/>
									{/if}
									&nbsp;&nbsp;{$value.label}&nbsp;&nbsp;</td>
									<td width="19%" align = right>
									
									{if $value.no != ($entries.field|@count - 2)}
										<img src="{$IMAGE_PATH}arrow_down.png" border="0" style="cursor:pointer;" onclick="changeFieldorder(this,'down','{$value.fieldselect}','{$value.blockid}') " alt="Down" title="Down"/>&nbsp;
									{/if}
									<!--&nbsp;&nbsp;<img src="{$IMAGE_PATH}plus_layout.gif" border="0" style="cursor:pointer;" onclick="fnvshobj(this,'createcf');getCreateCustomFieldForm('{$MODULE}','{$value.blockid}','{$value.tabid}','','{$entries.blocklabel}','{$value.fieldselect}','')" alt="Add" title="Add"/>-->
							
									{if $value.no % 2 == 0}
										&nbsp&nbsp;<img src="{'arrow_right.png'|@vtiger_imageurl:$THEME}" border="0" style="cursor:pointer;" onclick="changeFieldorder(this,'Right','{$value.fieldselect}','{$value.blockid}')" alt="Right" title="Right"/>
									{/if}							
									</td>
									{if $value.no % 2 == 0}
										<td style="border-left:solid 1px #ccc; width:1px;"></td>	
									{/if}			
								{/if}
							{/foreach}
							</tr>
							<tr>
								{if $entries.blockid ne $COMMENTSECTIONID}
									<td colspan="5" align='right'><input class="crmButton small save" type="button" name="Add"  value="Add" onclick="fnvshobj(this,'createcf');getCreateCustomFieldForm('{$MODULE}','{$entries.blockid}','{$entries.tabid}','','{$entries.blocklabel}','0','')"></td>
								{else}
									<td colspan="5" align='right'><br></td>
								{/if}
							</tr>
						{/if}
					{/foreach}
				</table>
			</form>
		
