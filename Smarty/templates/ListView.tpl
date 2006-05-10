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
<div id="dynloadarea" style="float:left;position:absolute;left:350px;top:150px;"></div>
{/if}
<script language="JavaScript" type="text/javascript" src="modules/{$MODULE}/{$SINGLE_MOD}.js"></script>
<script language="javascript">
function ajaxSaveResponse(response)
{ldelim}
	hide("status");
	result = response.responseText.split('&#&#&#'); 
	document.getElementById("ListViewContents").innerHTML= result[2];
	if(result[1] != '')
		alert(result[1]);	
{rdelim}

function callSearch(searchtype)
{ldelim}

        search_fld_val= document.basicSearch.search_field[document.basicSearch.search_field.selectedIndex].value;
        search_type_val=document.basicSearch.searchtype.value;
        search_txt_val=document.basicSearch.search_text.value;

	var ajaxObj = new Ajax(ajaxSaveResponse);
	var urlstring = '';
        elements=document.basicSearch;
	for(ii = 0 ; ii < elements.length; ii++)
	{ldelim}
	if(elements[ii].name != 'action')
		urlstring = urlstring+''+elements[ii].name+'='+elements[ii].value+'&';
	else
		urlstring = urlstring+'file=index&';
	{rdelim}
	var no_rows = document.basicSearch.search_cnt.value;
	for(jj = 0 ; jj < no_rows; jj++)
	{ldelim}
		var sfld_name = getObj("Fields"+jj);
		var scndn_name= getObj("Condition"+jj);
		var srchvalue_name = getObj("Srch_value"+jj);
		urlstring = urlstring+'Fields'+jj+'='+sfld_name[sfld_name.selectedIndex].value+'&';
		urlstring = urlstring+'Condition'+jj+'='+scndn_name[scndn_name.selectedIndex].value+'&';
		urlstring = urlstring+'Srch_value'+jj+'='+srchvalue_name.value+'&';
	{rdelim}
	urlstring = urlstring +'action={$MODULE}Ajax&ajax=true';	
	ajaxObj.process("index.php?",urlstring);
{rdelim}
function alphabetic(url)
{ldelim}

	var ajaxObj = new Ajax(ajaxSaveResponse);
	
	url_param = url.split('&');
	for(plen=0; plen< url_param.length;plen++)
	{ldelim}
		url_var=url_param[plen];
		if(url_var.search(/search_text/gi)!= -1)
		{ldelim}
				name_value = url_var.split('=');
				document.basicSearch.search_text.value = name_value[1];
		{rdelim}
		else if(url_var.search(/search_field/gi)!= -1)
		{ldelim}
				name_value = url_var.split('=');
				var oSfield = getObj("search_field");
				for (os=0; os<oSfield.length;os++)
				{ldelim}
					if(oSfield[os].value == name_value[1])
					oSfield.selectedIndex = os;
				{rdelim}
		{rdelim}

 	{rdelim}
        ajaxObj.process("index.php?",url);
{rdelim}
</script>

		{include file='Buttons_List.tpl'}
	
<div id="subMenuBg"  style="position:absolute;display:none;filter:Alpha(Opacity=90);-moz-opacit
y:0.90;z-index:50"></div>
<div id="subMenu" style="z-index:1;display:none;position:absolute;">
<table border=0 cellspacing=0 cellpadding=0 width="100px" align=center class="moduleSearch">
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
<!-- SIMPLE SEARCH -->
<div id="searchAcc" style="z-index:1;display:none;position:relative;">
<form name="basicSearch" action="index.php">
<table width="80%" cellpadding="5" cellspacing="0" style="border:1px dashed #CCCCCC;" class="small" align="center">
	<tr>
		<td width="15%" class="dvtCellLabel" nowrap align="right"><img src="{$IMAGE_PATH}basicSearchLens.gif" align="absmiddle" alt="Basic Search" title="Basic Search" border=0>&nbsp;<b>Search {$MODULE} for</b></td>
		<td width="25%" class="dvtCellLabel"><input type="text"  class="txtBox" name="search_text"></td>
		<td width="25%" class="dvtCellLabel"><b>In</b>&nbsp;
			<select name ="search_field" class="txtBox">
			 {html_options  options=$SEARCHLISTHEADER }
			</select>
                        <input type="hidden" name="searchtype" value="BasicSearch">
                        <input type="hidden" name="module" value="{$MODULE}">
                        <input type="hidden" name="parenttab" value="{$CATEGORY}">
			<input type="hidden" name="action" value="index">
                        <input type="hidden" name="query" value="true">
			<input type="hidden" name="search_cnt">
		</td>
		<td width="35%" class="dvtCellLabel">
			  <input name="submit" type="button" class="classBtn" onClick="callSearch('Basic');" value=" Search Now ">&nbsp;
			   <span class="hiliteBtn4Search"><a href="#" onClick="hide('searchAcc');show('advSearch');document.basicSearch.searchtype.value='advance';">Go to Advanced Search</a></span>	
							</td>
	</tr>
	<tr>
		<td colspan="4" align="center" class="dvtCellLabel">
			<table border=0 cellspacing=0 cellpadding=0 width=100%>
				<tr>
                                                {$ALPHABETICAL}
                                </tr>
                        </table>
		</td>
	</tr>
</table>
</div>
<!-- ADVANCED SEARCH -->
<div id="advSearch" style="display:none;">
		<table  cellspacing=0 cellpadding=5 width=80% style="border-top:1px dashed #CCCCCC;border-left:1px dashed #CCCCCC;border-right:1px dashed #CCCCCC;" class="small" align="center">
			<tr>
					<td width="15%"  class="dvtCellLabel" align="right"><img src="{$IMAGE_PATH}advancedSearchLens.gif" alt="Advanced Search" title="Advanced Search" border=0></td>
					<td nowrap width="30%" class="dvtCellLabel"><b><input name="matchtype" type="radio" value="all">&nbsp;Match All of the Following</b></td>
					<td nowrap class="dvtCellLabel" width="30%"><b><input name="matchtype" type="radio" value="any" checked>&nbsp;Match Any of the Following</b></td>
					<td width="35%" class="dvtCellLabel"><span class="hiliteBtn4Search"><a href="#" onClick="show('searchAcc');hide('advSearch')">Go to Basic Search</a></span></td>
			</tr>
		</table>
		<table style="border-left:1px dashed #CCCCCC;border-right:1px dashed #CCCCCC;" cellpadding="2" cellspacing="0" width="80%" align="center" class="small">
			<tr>
				<td colspan="3"align="center" class="dvtCellLabel">
				<div id="fixed" style="position:relative;width:90%;height:125px;overflow:auto;border:1px solid #CCCCCC;" class="padTab small">
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
			<td class="dvtCellLabel"><input type="button" name="more" value=" More " onClick="fnAddSrch('{$FIELDNAMES}','{$CRITERIA}')" class="classBtn">&nbsp;&nbsp;
				<input name="button" type="button" value=" Fewer " onclick="delRow()" class="classBtn"></td>
			<td class="dvtCellLabel">&nbsp;</td>
			<td class="dvtCellLabel">&nbsp;</td>
			</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=5 width=80% style="border-bottom:1px dashed #CCCCCC;border-left:1px dashed #CCCCCC;border-right:1px dashed #CCCCCC;" align="center">
		<tr>
			<td align=center class="dvtCellLabel"><input type="button" class="classBtn" value=" Search Now " onClick="totalnoofrows();callSearch('Basic');">
			</td>
		</tr>
	</table>
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
</form>
</div>

{*<!-- Contents -->*}
<table border=0 cellspacing=0 cellpadding=0 width=100% align=center>
     <tr>
        <td valign=top><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>

	<td class="showPanelBg" valign="top" width=100% style="padding:10px;">
	   <!-- PUBLIC CONTENTS STARTS-->
	   <div id="ListViewContents" class="small" style="width:100%;position:relative;">
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
                                        {elseif $button_check eq 'c_status'}
                                             <input class="small" type="button" value="{$button_label}" onclick="return change(this,'changestatus')"/>
                                        {/if}

                                 {/foreach}
                                             <input class="small" type="button" value="Change Owner" onclick="return change(this,'changeowner')"/>
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
							<td><a href="index.php?module={$MODULE}&action=CustomView&parenttab={$CATEGORY}">{$APP.LNK_CV_CREATEVIEW}</a>
							<span class="small">|</span>
							<span class="small" disabled>{$APP.LNK_CV_EDIT}</span>
							<span class="small">|</span>
                                                        <span class="small" disabled>{$APP.LNK_CV_DELETE}</span></td>
						    {else}
							<td><a href="index.php?module={$MODULE}&action=CustomView&parenttab={$CATEGORY}">{$APP.LNK_CV_CREATEVIEW}</a>
							<span class="small">|</span>
                                                        <a href="index.php?module={$MODULE}&action=CustomView&record={$VIEWID}&parenttab={$CATEGORY}">{$APP.LNK_CV_EDIT}</a>
                                                        <span class="small">|</span>
							<a href="index.php?module=CustomView&action=Delete&dmodule={$MODULE}&record={$VIEWID}&parenttab={$CATEGORY}">{$APP.LNK_CV_DELETE}</a></td>
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
			      <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'" id="row_{$entity_id}">
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
                                        {elseif $button_check eq 'c_status'}
                                             <input class="small" type="button" value="{$button_label}" onclick="return change(this,'changestatus')"/>
                                        {/if}

                                 {/foreach}
                                             <input class="small" type="button" value="Change Owner" onclick="return change(this,'changeowner')"/>
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
<div id="status" style="display:none;position:absolute;background-color:#bbbbbb;left:887px;top:0px;height:17px;white-space:nowrap;">Processing Request...</div>


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
	<input type="button" name="button" class="small" value="Cancel" onClick="fninvsh('changeowner')">
</td>
</tr>
</table>
</div>


{if $MODULE eq 'Leads'}
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
{/if}
<script>
{literal}
function ajaxChangeStatus(statusname)
{
	show("status");
	var ajaxObj = new Ajax(ajaxSaveResponse);
	var viewid = document.massdelete.viewname.value;
	var idstring = document.massdelete.idlist.value;
	if(statusname == 'status')
	{
		fninvsh('changestatus');
		var url='&leadval='+document.getElementById('lead_status').options[document.getElementById('lead_status').options.selectedIndex].value;
		var urlstring ="module=Users&action=updateLeadDBStatus&return_module=Leads"+url+"&viewname="+viewid+"&idlist="+idstring;
	}
	else if(statusname == 'owner')
	{
		fninvsh('changeowner');
		var url='&user_id='+document.getElementById('lead_owner').options[document.getElementById('lead_owner').options.selectedIndex].value;
		
{/literal}
		var urlstring ="module=Users&action=updateLeadDBStatus&return_module={$MODULE}"+url+"&viewname="+viewid+"&idlist="+idstring;
{literal}

	}
	
	ajaxObj.process("index.php?",urlstring);
}
</script>
{/literal}

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


