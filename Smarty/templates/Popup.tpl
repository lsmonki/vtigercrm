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
<script language="JavaScript" type="text/javascript" src="include/js/ajax.js"></script>
<script type="text/javascript">
function add_data_to_relatedlist(entity_id,recordid) {ldelim}

        opener.document.location.href="index.php?module={$RETURN_MODULE}&action=updateRelations&destination_module=Contacts&entityid="+entity_id+"&parid="+recordid;
{rdelim}
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="small">
	<tr>
		<td background="{$IMAGE_PATH}popupHdr.jpg" height="70" style="padding-left:20px;">
		<span style="color:#FFFFFF;font:Arial, Helvetica, sans-serif;font-size:18px;font-weight:bold;">
		{$MODULE}
		</span> 
		</td>
	</tr>
	<tr>
	  	<td style="padding:10px;" >
			<form name="basicSearch" action="index.php">
			<table width="100%" cellpadding="5" cellspacing="0" style="border-top:1px dashed #CCCCCC;border-bottom:1px dashed #CCCCCC;">
			<tr>
				<td width="20%" class="dvtCellLabel"><img src="{$IMAGE_PATH}basicSearchLens.gif"></td>
				<td width="30%" class="dvtCellLabel"><input type="text" name="search_text" class="txtBox"> </td>
				<td width="30%" class="dvtCellLabel"><b>In</b>&nbsp;
					<select name ="search_field" class="txtBox">
		                         {html_options  options=$SEARCHLISTHEADER }
                		        </select>
								<input type="hidden" name="searchtype" value="BasicSearch">
		                        <input type="hidden" name="module" value="{$MODULE}">
								<input type="hidden" name="action" value="Popup">
		    	                <input type="hidden" name="query" value="true">
								<input type="hidden" name="search_cnt">

				</td>
				<td width="20%" class="dvtCellLabel">
					<input type="button" name="search" value=" &nbsp;Search&nbsp; " onClick="callSearch('Basic');" class="classBtn">
				</td>
			</tr>
			 <tr>
				<td colspan="4" align="center">
					<table width="100%">
					<tr>	
						{$ALPHABETICAL}
					</tr>
					</table>
				</td>
			</tr>
			</table>
			</form>
  		</td>
  	</tr>
</table>
<div id="ListViewContents">
	{include file="PopupContents.tpl"}
</div>
<script>
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
		urlstring = urlstring+'file=Popup&';
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
	popuptype = document.getElementById('popup_type').value;
	urlstring = urlstring +'action={$MODULE}Ajax&ajax=true&popuptype='+popuptype;	
	ajaxObj.process("index.php?",urlstring);
{rdelim}
function ajaxSaveResponse(response)
{ldelim}
	document.getElementById("ListViewContents").innerHTML= response.responseText;
{rdelim}
function getListViewEntries_js(module,url)
{ldelim}
        var ajaxObj = new Ajax(ajaxSaveResponse);
		popuptype = document.getElementById('popup_type').value;
        var urlstring ="module="+module+"&action="+module+"Ajax&popuptype="+popuptype+"&file=Popup&ajax=true&"+url;
        ajaxObj.process("index.php?",urlstring);
{rdelim}
</script>
