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
<script language="javascript">

function callSearch(searchtype)
{ldelim}

        search_fld_val= document.basicSearch.search_field[document.basicSearch.search_field.selectedIndex].value;
        search_type_val=document.basicSearch.searchtype.value;
        search_txt_val=document.basicSearch.search_text.value;

{rdelim}

</script>



<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>

<tr><td style="height:2px"></td></tr>
<tr>
	<td style="padding-left:10px;padding-right:10px" class="moduleName" nowrap>{$CATEGORY} > {$MODULE}</td>
	<td class="sep1" style="width:1px"></td>
	<td class=small >
		<table border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>

				<tr>
					{if $MODULE eq 'Activities'}
                                                <td style="padding-right:0px"><a href="#" id="showSubMenu"  onMouseOver="moveMe('subMenu');showhide('subMenu');"><img src="{$IMAGE_PATH}btnL3Add.gif" alt="Create {$MODULE}..." title="Create {$MODULE}..." border=0></a></td>
                                        {else}
                                        <td style="padding-right:0px"><a href="index.php?module={$MODULE}&action=EditView&return_action=DetailView&parenttab={$CATEGORY}"><img src="{$IMAGE_PATH}btnL3Add.gif" alt="Create {$MODULE}..." title="Create {$MODULE}..." border=0></a></td>
                                        {/if}
					 <td style="padding-right:0px"><a href="#" onClick="moveMe('searchAcc');showhide('searchAcc')" ><img src="{$IMAGE_PATH}btnL3Search.gif" alt="Search in {$MODULE}..." title="Search in {$MODULE}..." border=0></a></a></td>
					<td style="padding-right:0px"><a href="#" onClick='return window.open("index.php?module=Contacts&action=vtchat","Chat","width=450,height=400,resizable=1,scrollbars=1");'><img src="{$IMAGE_PATH}btnL3Search.gif" alt="Search in {$MODULE}..." title="Search in {$MODULE}..." border=0></a>
                    			 </td>	
				</tr>
				</table>
			</td>
			<td nowrap width=50>&nbsp;</td>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>

				<tr>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calendar.gif" alt="Open Calendar..." title="Open Calendar..." border=0></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Clock.gif" alt="Show World Clock..." title="Show World Clock..." border=0></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calc.gif" alt="Open Calculator..." title="Open Calculator..." border=0></a></a></td>
				</tr>
				</table>
			</td>
			
			<td>
				<table border=0 cellspacing=0 cellpadding=5>

				<tr>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
	{if $MODULE eq 'Contacts' || $MODULE eq 'Leads' || $MODULE eq 'Accounts' || $MODULE eq 'Potentials' || $MODULE eq 'Products' || $MODULE eq 'Notes' || $MODULE eq 'Emails'}
	<td class="sep1" style="width:1px"></td>
	<td nowrap style="width:50%;padding:10px">
		{if $MODULE ne 'Notes' && $MODULE ne 'Emails'}	
		<a href="index.php?module={$MODULE}&action=Import&step=1&return_module={$MODULE}&return_action=index">Import {$MODULE}</a> |	
		{/if}
		<a href="index.php?module={$MODULE}&action=Export&all=1">Export {$MODULE}</a>
		{if $MODULE eq 'Contacts'}
			&nbsp;|&nbsp;<a href='index.php?module={$MODULE}&action=AddBusinessCard&return_module={$MODULE}&return_action=ListView'>Add Business Card</a>
		{/if}
	</td>
	{else}
	<td nowrap style="width:50%;padding:10px">&nbsp;</td>
	{/if}
</tr>
<tr><td style="height:2px"></td></tr>

</TABLE>
<div id="subMenuBg" class="subMenu" style="position:absolute;display:none;filter:Alpha(Opacity=90);-moz-opacit
y:0.90;z-index:50"></div>
<div id="subMenu" style="z-index:1;display:none;position:absolute;">
<table border=0 cellspacing=0 cellpadding=0 width=100px align=center class="moduleSearch">
  <tr>
   <td class=small>
       <table cellspacing="2" cellpadding="2" border="0">
         <tr>
            <td width=90% ><a href="index.php?module={$MODULE}&action=EditView&return_module={$MODULE}&activity_mode=Events&return_action=DetailView&parenttab={$CATEGORY}">{$NEW_EVENT}</a></td>
         </tr>
         <tr>
            <td width=90% ><a href="index.php?module={$MODULE}&action=EditView&return_module={$MODULE}&activity_mode=Task&return_action=DetailView&parenttab={$CATEGORY}">{$NEW_TASK}</a></td>
         </tr>
       </table>
   </td>
  </tr>
</table>
</div>

{*<!-- Search  in Module -->*}
<div id="searchAcc" style="z-index:1;display:none;position:absolute;">
<table border=0 cellspacing=0 cellpadding=0 width=640px align=center class="moduleSearch">
<tr>
        <td class=small>

                <table border=0 cellspacing=0 cellpadding=2 width=100%>
		<form name="basicSearch" action="index.php">
                <tr>
                        <td >

                                {*<!-- Basic Search -->*}
                                <div id="basicSearchdiv">
                                        <table border=0 cellspacing=0 cellpadding=2 width=100% class="searchHd
rBox">
                                        <tr>
                                                <td><img src="{$IMAGE_PATH}basicSearchLens.gif" alt="Basic Search" title="Basic Search" border=0></td>
                                                <td width=90% > <span class="hiliteBtn4Search"><a href="#" onClick="showhide('basicSearchdiv');showhide('advSearch')">Go to Advanced Search</a></span></td>

                                                <td valign=top nowrap><a href="#" onClick="showhide('searchAcc')">[X] Close</a></td>
                                        </tr>
                                        </table>

                                        <table border=0 cellspacing=0 cellpadding=5 align=center>
                                        <tr>
                                        <td nowrap>Search {$MODULE} for </td>
                                        <td><input type="text" style="width:150px" class=small name="search_text"></td>

                                        <td>in</td>
                                        <td>
						<select name ="search_field">
						 {html_options  options=$SEARCHLISTHEADER }
						</select>
                                                <input type="hidden" name="searchtype" value="BasicSearch">
                                                <input type="hidden" name="module" value="{$MODULE}">
                                                <input type="hidden" name="parenttab" value="{$CATEGORY}">
						<input type="hidden" name="action" value="index">
                                                <input type="hidden" name="query" value="true">


                                        </td>
                                        <td><input type="submit" class=small value="Search now" onClick="callSearch('Basic');"></td>
                                        </tr>
                                        </table>

                                        <table border=0 cellspacing=0 cellpadding=0 width=100%>

                                        <tr>
						{$ALPHABETICAL}
                                        </tr>
                                        </table>


                                </div>
                                {*<!-- Advanced Search -->*}

                                <div id="advSearch" style="display:none;">
                                        <table border=0 cellspacing=0 cellpadding=2 width=100% class="searchHdrBox">
                                        <tr>
                                                <td><img src="{$IMAGE_PATH}advancedSearchLens.gif" alt="Advanced Search" title="Advanced Search" border=0></td>
                                                <td width=90% > <span class="hiliteBtn4Search"><a href="#" onClick="showhide('basicSearchdiv');showhide('advSearch')">Go to Basic Search</a></span></td>
                                                <td valign=top nowrap><a href="#" onClick="showhide('searchAcc')">[X] Close</a></td>
                                        </tr>

                                        </table>
                                        <div align=center>
                                        <div class="advSearch" align=left>

					<table class="searchHd rBox" border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
					<td nowrap class="small"><input name="radiobutton" type="radio" value="">&nbsp;Match All of the Following</td>
					<td class="small"><input name="radiobutton" type="radio" value="radiobutton">&nbsp;Match Any of the Following</td>

					<td>&nbsp;</td>
					</tr>
					<tr>
					<td colspan="3" bgcolor="#FFFFFF" style="border:1px solid #CCCCCC;">
					<div id="fixed" style="position:relative;top:0px;left:0px;width:95%;height:95px;overflow:auto;" class="padTab">
					<table width="95%"  border="0" cellpadding="5" cellspacing="0" id="adSrc" align="left">
					<tr  class="dvtCellInfo">
					<td width="31%"><select name="Fields" class="detailedViewTextBox">
					{$FIELDNAMES}
					</select>
					</td>
					<td width="32%"><select name="Condition" class="detailedViewTextBox">
					{$CRITERIA}
					</select>
					</td>
					<td width="32%"><input type="text" name="srch" class="detailedViewTextBox"></td>
					</tr>
					</table>
					</div>	
					</td>
					</tr>
					<tr>

					<td><input type="button" name="more" value="More" onClick="fnAddSrch('{$FIELDNAMES}')">
					&nbsp;&nbsp;
					<input name="button" type="button" value="Fewer" onclick="delRow()"></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					</tr>
					</table>
                                        </div>
                                        </div>
                                        <table border=0 cellspacing=0 cellpadding=5 width=100%>
                                        <tr><td align=center><input type="submit" class=small value="Search now" onClick="callSearch('Basic');"></td></tr></table>


                                </div>				

				 {*<!-- Searching UI -->*}
                                <div id="searchingUI" style="display:none;">
                                        <table border=0 cellspacing=0 cellpadding=0 width=100%>
                                        <tr>
                                                <td align=center>
                                                <img src="images/searching.gif" alt="Searching... please wait"  title="Searching... please wait">
                                                </td>
                                        </tr>
                                        </table>

                                </div>
                        </td>
                </tr>
                </table>
		</form>
        </td>
</tr>
</table>
</div>

<br>
			

{*<!-- Contents -->*}
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
				 <td style="padding-right:20px" nowrap>
                                 {foreach key=button_check item=button_label from=$BUTTONS}
                                        {if $button_check eq 'del'}
                                             <input class="small" type="submit" value="{$button_label}" onclick="return massDelete()"/>
                                        {elseif $button_check eq 's_mail'}
                                             <input class="small" type="submit" value="{$button_label}" onclick="return eMail()"/>
                                        {elseif $button_check eq 's_cmail'}
                                             <input class="small" type="submit" value="{$button_label}" onclick="return massMail()"/>
                                        {elseif $button_check eq 'c_owner'}
                                             <input class="small" type="submit" value="{$button_label}" onclick="this.form.change_owner.value='true'; return changeStatus()"/>
                                        {elseif $button_check eq 'c_status'}
                                             <input class="small" type="submit" value="{$button_label}" onclick="this.form.change_status.value='true'; return changeStatus()"/>
                                        {/if}

                                 {/foreach}
                                 </td>
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
               		      </tr>
			 </table>
                         <div  style="overflow:auto;width:100%;height:300px; border-top:1px solid #999999;border-bottom:1px solid #999999">
			 <table border=0 cellspacing=1 cellpadding=3 width=100% style="background-color:#cccccc;" class="small">
			      <tr>
             			 <td class="lvtCol"><input type="checkbox"  name="selectall" onClick=toggleSelect(this.checked,"selected_id")></td>
				 {foreach item=header from=$LISTHEADER}
        			 <td class="lvtCol">{$header}</td>
			         {/foreach}
			      </tr>
			      {foreach item=entity key=entity_id from=$LISTENTITY}
			      <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'"  >
				 <td><input type="checkbox" NAME="selected_id" value= '{$entity_id}' onClick=toggleSelectAll(this.name,"selectall")></td>
				 {foreach item=data from=$entity}	
				 <td>{$data}</td>
	                         {/foreach}
			      </tr>
			      {/foreach}
			 </table>
			 </div>
			 <table border=0 cellspacing=0 cellpadding=2 width=100%>
			      <tr>
				 <td style="padding-right:20px" nowrap>
                                 {foreach key=button_check item=button_label from=$BUTTONS}
                                        {if $button_check eq 'del'}
                                             <input class="small" type="submit" value="{$button_label}" onclick="return massDelete()"/>
                                        {elseif $button_check eq 's_mail'}
                                             <input class="small" type="submit" value="{$button_label}" onclick="return eMail()"/>
                                        {elseif $button_check eq 's_cmail'}
                                             <input class="small" type="submit" value="{$button_label}" onclick="return massMail()"/>
                                        {elseif $button_check eq 'c_owner'}
                                             <input class="small" type="submit" value="{$button_label}" onclick="this.form.change_owner.value='true'; return changeStatus()"/>
                                        {elseif $button_check eq 'c_status'}
                                             <input class="small" type="submit" value="{$button_label}" onclick="this.form.change_status.value='true'; return changeStatus()"/>
                                        {/if}

                                 {/foreach}
                                 </td>
				 <td style="padding-right:20px" class="small" nowrap>{$RECORD_COUNTS}</td>
				 <td nowrap >
				    <table border=0 cellspacing=0 cellpadding=0 class="small">
				         <tr>{$NAVIGATION}</tr>
				     </table>
				 </td>
				 <td align="right" width=100%>
				   <table border=0 cellspacing=0 cellpadding=0 class="small">
					<tr>
                                           {$WORDTEMPLATEOPTIONS}{$MERGEBUTTON}
					</tr>
				   </table>
				 </td>
			      </tr>
              		 </table>
		       </td>
		   </tr>
	    </table>
	</div>

     </td>
   </tr>
   </form>	
</table>
{$SELECT_SCRIPT}

