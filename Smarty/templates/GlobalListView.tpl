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
<div id="dynloadarea" style=float:left;position:absolute;left:350px;top:150px;></div>
{/if}

{*<!-- Contents -->*}

{if $MODULE eq $SEARCH_MODULE && $SEARCH_MODULE neq ''}
	<div id="global_list_{$SEARCH_MODULE}" style="display:block">
{elseif $MODULE eq 'Contacts' && $SEARCH_MODULE eq ''}
	<div id="global_list_{$MODULE}" style="display:block">
{elseif $SEARCH_MODULE neq ''}
	<div id="global_list_{$MODULE}" style="display:none">
{else}
	<div id="global_list_{$MODULE}" style="display:block">
{/if}
<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
     <form name="massdelete" method="POST">
     <input name="idlist" type="hidden">
     <input name="change_owner" type="hidden">
     <input name="change_status" type="hidden">
     <tr>
		<td>
	   <!-- PUBLIC CONTENTS STARTS-->
	   <br>
	   <div class="small" style="padding:2px">
        	<table border=0 cellspacing=1 cellpadding=0 width=100% class="lvtBg">
	           <tr >
			<td>
				<table border=0 cellspacing=0 cellpadding=2 width=100% class="small">
				   <tr>
					<td style="padding-right:20px" nowrap ><b class=big>{$APP.$MODULE}</b>{$SEARCH_CRITERIA}</td>
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
                 <div  class="searchResults">
			 	<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
				   <tr>
					{if $DISPLAYHEADER eq 1}
						{foreach item=header from=$LISTHEADER}
							<td class="mailSubHeader">{$header}</td>
			         		{/foreach}
					{else}
						<td class="searchResultsRow" colspan=$HEADERCOUNT> {$APP.LBL_NO_DATA} </td>
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
{if $SEARCH_MODULE eq 'All'}
<script>
displayModuleList(document.getElementById('global_search_module'));
</script>
{/if}

{$SELECT_SCRIPT}

