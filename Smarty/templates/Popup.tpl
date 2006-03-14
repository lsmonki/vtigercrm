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

<link rel="stylesheet" type="text/css" href="{$THEME_PATH}style.css"/>
<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
<script language="JavaScript" type="text/javascript" src="modules/{$MODULE}/{$SINGLE_MOD}.js"></script>
<script type="text/javascript">
function add_data_to_relatedlist(entity_id,recordid) {ldelim}

        opener.document.location.href="index.php?module={$RETURN_MODULE}&action=updateRelations&destination_module=Contacts&entityid="+entity_id+"&parid="+recordid;
{rdelim}
</script>

<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
	<tr>
	        <td valign=top><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>

        	<td class="showPanelBg" valign=top width=100%>
                	<div class="small" style="padding:20px">
			       <form name="selectall" method="POST">
		               <table border=0 cellspacing=1 cellpadding=0 width=100% class="lvtBg">
                		<tr style="background-color:#efefef">
                        		<td >
						<table border=0 cellspacing=0 cellpadding=2 width=100%>
		                                 <tr>
						  <script type="text/javascript" src="include/js/general.js"></script>
						  <script type="text/javascript" src="modules/{$MODULE}/{$SINGLE_MOD}.js"></script>
                  		                  <input name="module" type="hidden" value="{$RETURN_MODULE}">
		                                  <input name="action" type="hidden" value="{$RETURN_ACTION}">
                  		                  <input name="pmodule" type="hidden" value="{$MODULE}">
					  	  <input type="hidden" name="curr_row" value="{$CURR_ROW}">	
		                                  <input name="entityid" type="hidden" value="">
		                                  <input name="idlist" type="hidden" value="">
						{if $SELECT eq 'enable'}
						  <td><input class="small" type="button" value="Add Contacts" onclick="if(SelectAll()) window.close();"/></td>
						{/if}
                        	                  <td style="padding-right:20px" class="small" nowrap>&nbsp;{$RECORD_COUNTS}</td>
			                          <td class="small" nowrap>{$NAVIGATION}</td>
						 </tr>
						</table>
						<div  style="overflow:auto;width:100%;height:300px; border-top:1px solid #999999;border-bottom:1px solid #999999">
			                        <table border=0 cellspacing=1 cellpadding=3 width=100% class=small>
                        				<tr>	
							{if $SELECT eq 'enable'}
								<td class="lvtCol"><input type="checkbox"  name="selectall" onClick=toggleSelect(this.checked,"selected_id")></td>
							{/if}

                        				{foreach item=header from=$LISTHEADER}
							         <td class="lvtCol">{$header}</td>
					                {/foreach}
				                        </tr>
							{foreach key=entity_id item=entity from=$LISTENTITY}
			                                <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'"  >
							{if $SELECT eq 'enable'}
								<td><input type="checkbox" NAME="selected_id" value= '{$entity_id}' onClick=toggleSelectAll(this.name,"selectall")></td>
							{/if}
                                				{foreach item=data from=$entity}
			                                        <td>
                        			                        {$data}
			                                        </td>
                        				        {/foreach}
			                                </tr>
                        				{/foreach}
                        			</table>
                       				</div>
					</td>
				 </tr>
				</table>
				</form>    
			</div>
		</td>
	</tr>
</table>
				
