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

{*<!-- module header -->*}
<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/search.js"></script>
{if $MODULE eq 'Contacts'}
{$IMAGELISTS}
<script language="JavaScript" type="text/javascript" src="include/js/thumbnail.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/conveyor.js"></script>
<div id="dynloadarea" style=float:left;position:absolute;left:350px;top:150px;></div>
{/if}
<script language="JavaScript" type="text/javascript" src="modules/{$MODULE}/{$SINGLE_MOD}.js"></script>



{*<!-- Contents -->*}
{if $MODULE eq $SEARCH_MODULE && $SEARCH_MODULE neq ''}
	<div id="global_list_{$SEARCH_MODULE}" style="display:block">
{elseif $MODULE eq 'Contacts' && $SEARCH_MODULE eq ''}
	<div id="global_list_{$MODULE}" style="display:block">
{else}
	<div id="global_list_{$MODULE}" style="display:none">
{/if}
<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
     <form name="massdelete" method="POST">
     <input name="idlist" type="hidden">
     <input name="change_owner" type="hidden">
     <input name="change_status" type="hidden">
     <tr>
	<td valign=top><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>

	<td class="showPanelBg" valign=top width=100%>
	   <!-- PUBLIC CONTENTS STARTS-->
	   <div class="small" style="padding:20px">
        	<table border=0 cellspacing=1 cellpadding=0 width=100% class="lvtBg">
	           <tr style="background-color:#efefef">
			<td>
				<table border=0 cellspacing=0 cellpadding=2 width=100% class="small">
				   <tr>
					<td style="padding-right:20px" nowrap><b>{$MODULE}</b></td>
					<!-- Not used, may be used in future when we do the pagination and customeviews
						<td style="padding-right:20px" class="small" nowrap>{$RECORD_COUNTS}</td>
						<td nowrap >
							<table border=0 cellspacing=0 cellpadding=0 class="small">
							   <tr>{$NAVIGATION}</tr>
							</table>
                                 		</td>
					 	<td width=100% align="right">
					   		<table border=0 cellspacing=0 cellpadding=0 class="small">
								<tr>{$CUSTOMVIEW}</tr>
					   		</table>
					 	</td>	
					-->
				   </tr>
				</table>
                         	<div  style="width:100%; border-top:1px solid #999999;border-bottom:1px solid #999999">
			 	<table border=0 cellspacing=1 cellpadding=3 width=100% style="background-color:#cccccc;" class="small">
				   <tr>
					{if $DISPLAYHEADER eq 1}
						{foreach item=header from=$LISTHEADER}
							<td class="lvtCol">{$header}</td>
			         		{/foreach}
					{else}
						<td colspan=$HEADERCOUNT> Please try another search criteria for this module</td>
					{/if}
				   </tr>
				   {foreach item=entity key=entity_id from=$LISTENTITY}
				   <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'"  >
					{foreach item=data from=$entity}	
					<td>{$data}</td>
					{/foreach}
				   </tr>
				   {/foreach}
				</table>
			 	</div>

				<!-- not used, may be used in future for navigation
			 		<table border=0 cellspacing=0 cellpadding=2 width=100%>
					   <tr>
					 	<td style="padding-right:20px" class="small" nowrap>{$RECORD_COUNTS}</td>
						<td nowrap >
							<table border=0 cellspacing=0 cellpadding=0 class="small">
							   <tr>{$NAVIGATION}</tr>
							</table>
						</td>
					   </tr>
					</table>
				-->
			</td>
		   </tr>
		</table>
	   </div>
	</td>
	</form>	
   </tr>
</table>
</div>
{$SELECT_SCRIPT}

