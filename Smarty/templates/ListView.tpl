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
<script language="JavaScript" type="text/javascript" src="include/js/ListView.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/search.js"></script>
{if $MODULE eq 'Contacts'}
<div id="dynloadarea" style=float:left;position:absolute;left:350px;top:150px;></div>
{/if}
<script language="JavaScript" type="text/javascript" src="modules/{$MODULE}/{$SINGLE_MOD}.js"></script>
<script language="javascript">
function ajaxSaveResponse(response)
{ldelim}
	hide("status");
	document.getElementById("ListViewContents").innerHTML=response.responseText;
{rdelim}

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
					<td style="padding-right:0px"><a href="#" onClick='return window.open("index.php?module=Contacts&action=vtchat","Chat","width=450,height=400,resizable=1,scrollbars=1");'><img src="{$IMAGE_PATH}tbarChat.gif" alt="Chat..." title="Chat..." border=0></a>
                    			 </td>	
				</tr>
				</table>
			</td>
			<td nowrap width=50>&nbsp;</td>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>

				<tr>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calendar.gif" alt="Open Calendar..." title="Open Calendar..." border=0></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Clock.gif" alt="Show World Clock..." title="Show World Clock..." border=0 onClick="fnvshobj(this,'wclock')"></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calc.gif" alt="Open Calculator..." title="Open Calculator..." border=0 onClick="fnvshobj(this,'calc')"></a></a></td>
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
<form name="basicSearch" action="index.php">
<table border=0 cellspacing=0 cellpadding=0 width=640px align=center class="moduleSearch">
<tr>
        <td class=small>

                <table border=0 cellspacing=0 cellpadding=2 width=100%>
                <tr>
                        <td >

                                {*<!-- Basic Search -->*}
                                <div id="basicSearchdiv">
                                        <table border=0 cellspacing=0 cellpadding=2 width=100% class="searchHd
rBox">
                                        <tr>
                                                <td><img src="{$IMAGE_PATH}basicSearchLens.gif" alt="Basic Search" title="Basic Search" border=0></td>
                                                <td width=90% > <span class="hiliteBtn4Search"><a href="#" onClick="showhide('basicSearchdiv');showhide('advSearch');document.basicSearch.searchtype.value='advance';">Go to Advanced Search</a></span></td>

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
						<input type="hidden" name="search_cnt">


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
					<td nowrap class="small"><input name="matchtype" type="radio" value="all">&nbsp;Match All of the Following</td>
					<td class="small"><input name="matchtype" type="radio" value="any" checked>&nbsp;Match Any of the Following</td>
					<td>&nbsp;</td>
					</tr>
					<tr>
					<td colspan="3" bgcolor="#FFFFFF" style="border:1px solid #CCCCCC;">
					<div id="fixed" style="position:relative;top:0px;left:0px;width:95%;height:95px;overflow:auto;" class="padTab">
					<table width="95%"  border="0" cellpadding="5" cellspacing="0" id="adSrc" align="left">
					<tr  class="dvtCellInfo">
					<td width="31%"><select name="Fields0" class="detailedViewTextBox">
					{$FIELDNAMES}
					</select>
					</td>
					<td width="32%"><select name="Condition0" class="detailedViewTextBox">
					{$CRITERIA}
					</select>
					</td>
					<td width="32%"><input type="text" name="Srch_value0" class="detailedViewTextBox"></td>
					</tr>
					</table>
					</div>	
					</td>
					</tr>
					<tr>

					<td><input type="button" name="more" value="More" onClick="fnAddSrch('{$FIELDNAMES}','{$CRITERIA}')">
					&nbsp;&nbsp;
					<input name="button" type="button" value="Fewer" onclick="delRow()"></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					</tr>
					</table>
                                        </div>
                                        </div>
                                        <table border=0 cellspacing=0 cellpadding=5 width=100%>
                                        <tr><td align=center><input type="submit" class=small value="Search now" onClick="callSearch('Basic');totalnoofrows();"></td></tr></table>


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
        </td>
</tr>
</table>
</div>
</form>
<br>
			

{*<!-- Contents -->*}
<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
     <tr>
        <td valign=top><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>

	<td class="showPanelBg" valign=top width=100%>
	   <!-- PUBLIC CONTENTS STARTS-->
	   <div id="ListViewContents" class="small" style="padding:20px">
     <form name="massdelete" method="POST">
     <input name="idlist" type="hidden">
     <input name="change_owner" type="hidden">
     <input name="change_status" type="hidden">
               <table border=0 cellspacing=1 cellpadding=0 width=100% class="lvtBg">
	            <tr style="background-color:#efefef">
		      <td>
		         <table border=0 cellspacing=0 cellpadding=2 width=100% class="small">
			      <tr>
				 <td style="padding-right:20px" nowrap>
                                 {foreach key=button_check item=button_label from=$BUTTONS}
                                        {if $button_check eq 'del'}
                                             <input class="small" type="button" value="{$button_label}" onclick="return massDelete('{$MODULE}')"/>
                                        {elseif $button_check eq 's_mail'}
                                             <input class="small" type="submit" value="{$button_label}" onclick="return eMail('{$MODULE}')"/>
                                        {elseif $button_check eq 's_cmail'}
                                             <input class="small" type="submit" value="{$button_label}" onclick="return massMail('{$MODULE}')"/>
                                        {elseif $button_check eq 'c_owner'}
                                             <input class="small" type="button" value="{$button_label}" onclick="return changeStatus(this,'changeowner')"/>
                                        {elseif $button_check eq 'c_status'}
                                             <input class="small" type="button" value="{$button_label}" onclick="return changeStatus(this,'changestatus')"/>
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
					<tr>
						<td>{$APP.LBL_VIEW}</td>
						<td style="padding-left:5px;padding-right:5px">
                                                    <SELECT NAME="viewname" class="small" onchange="showDefaultCustomView(this,'{$MODULE}')">{$CUSTOMVIEW_OPTION}</SELECT></td>
                                                    {if $ALL eq 'All'}
							<td><a href="index.php?module={$MODULE}&action=CustomView">{$APP.LNK_CV_CREATEVIEW}</a>
							<span class="small">|</span>
							<span class="small" disabled>{$APP.LNK_CV_EDIT}</span>
							<span class="small">|</span>
                                                        <span class="small" disabled>{$APP.LNK_CV_DELETE}</span></td>
						    {else}
							<td><a href="index.php?module={$MODULE}&action=CustomView">{$APP.LNK_CV_CREATEVIEW}</a>
							<span class="small">|</span>
                                                        <a href="index.php?module={$MODULE}&action=CustomView&record={$VIEWID}">{$APP.LNK_CV_EDIT}</a>
                                                        <span class="small">|</span>
							<a href="index.php?module=CustomView&action=Delete&dmodule={$MODULE}&record={$VIEWID}">{$APP.LNK_CV_DELETE}</a></td>
						    {/if}
					</tr>
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
                                            <input class="small" type="button" value="{$button_label}" onclick="return massDelete('{$MODULE}')"/>
                                        {elseif $button_check eq 's_mail'}
                                             <input class="small" type="submit" value="{$button_label}" onclick="return eMail('{$MODULE}')"/>
                                        {elseif $button_check eq 's_cmail'}
                                             <input class="small" type="submit" value="{$button_label}" onclick="return massMail('{$MODULE}')"/>
                                        {elseif $button_check eq 'c_owner'}
                                             <input class="small" type="button" value="{$button_label}" onclick="return changeStatus(this,'changeowner')"/>
                                        {elseif $button_check eq 'c_status'}
                                             <input class="small" type="button" value="{$button_label}" onclick="return changeStatus(this,'changestatus')"/>
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

   </form>	
{$SELECT_SCRIPT}
	</div>

     </td>
   </tr>
</table>
<div id="status" style="display:none;position:absolute;background-color:#bbbbbb;left:887px;top:0px;height:17px;white-space:nowrap;"">Processing Request...</div>

{if $MODULE eq 'Leads'}

<div id="changeowner" class="statechange">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
	<td class="genHeaderSmall" align="left" style="border-bottom:1px solid #CCCCCC;" width="60%">Change Owner</td>
	<td style="border-bottom: 1px solid rgb(204, 204, 204);">&nbsp;</td>
	<td align="right" style="border-bottom:1px solid #CCCCCC;" width="40%"><a href="javascript:fninvsh('changeowner')">Close</a></td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td width="50%"><b>Transfer Ownership to</b></td>
	<td width="2%"><b>:</b></td>
	<td width="48%">
	<select name="lead_owner" id="lead_owner" class="detailedViewTextBox">
	{$CHANGE_OWNER}
	</select>
	</td>
</tr>
<tr><td colspan="3" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
<tr>
	<td colspan="3" align="center">
	&nbsp;&nbsp;
	<input type="button" name="button" class="small" value="Update Owner" onClick="ajaxChangeStatus('owner')">
</td>
</tr>
</table>
</div>


<div id="changestatus" class="statechange">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
	<td class="genHeaderSmall" align="left" style="border-bottom:1px solid #CCCCCC;" width="60%">Change Status Information</td>
	<td style="border-bottom: 1px solid rgb(204, 204, 204);">&nbsp;</td>
	<td align="right" style="border-bottom:1px solid #CCCCCC;" width="40%"><a href="javascript:fninvsh('changestatus')">Close</a></td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td width="50%"><b>Select New Status</b></td>
	<td width="2%"><b>:</b></td>
	<td width="48%">
	<select name="lead_status" id="lead_status" class="detailedViewTextBox">
	{$CHANGE_STATUS}
	</select>
	</td>
</tr>
<tr><td colspan="3" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
<tr>
	<td colspan="3" align="center">
	&nbsp;&nbsp;
	<input type="button" name="button" class="small" value="Update Status" onClick="ajaxChangeStatus('status')">
</td>
</tr>
</table>
</div>
<script>
{literal}
function ajaxChangeStatus(statusname)
{
	fninvsh('changestatus');
	fninvsh('changeowner');
	show("status");
	var ajaxObj = new Ajax(ajaxSaveResponse);
	var viewid = document.massdelete.viewname.value;
	var idstring = document.massdelete.idlist.value;
	if(statusname == 'status')
	{
		var url='&leadval='+document.getElementById('lead_status').options[document.getElementById('lead_status').options.selectedIndex].value;
	}
	else if(statusname == 'owner')
	{
		var url='&user_id='+document.getElementById('lead_owner').options[document.getElementById('lead_owner').options.selectedIndex].value;
	}
	
	
	var urlstring ="module=Users&action=updateLeadDBStatus&return_module=Leads"+url+"&viewname="+viewid+"&idlist="+idstring;
	ajaxObj.process("index.php?",urlstring);
}
</script>
{/literal}
{/if}

{if $MODULE eq 'Contacts'}
{literal}
<script>
function modifyimage(divid,imagename)
{
    document.getElementById('dynloadarea').innerHTML = '<img width="260" height="200" src="'+imagename+'" class="thumbnail">';
    show(divid);
}
</script>
{/literal}
{/if}


