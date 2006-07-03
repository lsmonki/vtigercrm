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

<link rel="stylesheet" type="text/css" href="{$THEME_PATH}style.css">
<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
<script language="JavaScript" type="text/javascript" src="modules/{$MODULE}/{$SINGLE_MOD}.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
<script type="text/javascript">
function add_data_to_relatedlist(entity_id,recordid,mod) {ldelim}
        opener.document.location.href="index.php?module={$RETURN_MODULE}&action=updateRelations&destination_module="+mod+"&entityid="+entity_id+"&parid="+recordid;
{rdelim}

</script>
<body class="small" marginwidth=0 marginheight=0 leftmargin=0 topmargin=0 bottommargin=0 rigthmargin=0>
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
					<input type="hidden" name="select_enable" id="select_enable" value="{$SELECT}">
					<input type="hidden" name="curr_row" value="{$CURR_ROW}">
					<input type="hidden" name="fldname_pb" value="{$FIELDNAME}">
					<input type="hidden" name="productid_pb" value="{$PRODUCTID}">
					<input name="popuptype" id="popup_type" type="hidden" value="{$POPUPTYPE}">
					<input name="recordid" id="recordid" type="hidden" value="{$RECORDID}">

				</td>
				<td width="20%" class="dvtCellLabel">
					<input type="button" name="search" value=" &nbsp;Search&nbsp; " onClick="callSearch('Basic');" class="classBtn">
				</td>
			</tr>
			 <tr>
				<td colspan="4" align="center">
					<table width="100%" class="small">
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
</body>
<script>
function callSearch(searchtype)
{ldelim}
	for(i=1;i<=26;i++)
    {ldelim}
        var data_td_id = 'alpha_'+ eval(i);
        getObj(data_td_id).className = 'searchAlph';
    {rdelim}
	search_fld_val= document.basicSearch.search_field[document.basicSearch.search_field.selectedIndex].value;
    search_txt_val=document.basicSearch.search_text.value;
    var urlstring = '';
    if(searchtype == 'Basic')
    {ldelim}
	urlstring = 'search_field='+search_fld_val+'&searchtype=BasicSearch&search_text='+search_txt_val;
    {rdelim}
	popuptype = $('popup_type').value;
	urlstring += '&popuptype='+popuptype;
	urlstring = urlstring +'&query=true&file=Popup&module={$MODULE}&action={$MODULE}Ajax&ajax=true';
	urlstring +=gethiddenelements();
	new Ajax.Request(
		'index.php',
		{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
				method: 'post',
				postBody: urlstring,
				onComplete: function(response) {ldelim}
					$("ListViewContents").innerHTML= response.responseText;
				{rdelim}
			{rdelim}
		);
{rdelim}	
function alphabetic(module,url,dataid)
{ldelim}
    for(i=1;i<=26;i++)
    {ldelim}
	var data_td_id = 'alpha_'+ eval(i);
	getObj(data_td_id).className = 'searchAlph';
    {rdelim}
    getObj(dataid).className = 'searchAlphselected';
    var urlstring ="module="+module+"&action="+module+"Ajax&file=Popup&ajax=true&"+url;
    urlstring +=gethiddenelements();
    new Ajax.Request(
                'index.php',
                {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                                method: 'post',
                                postBody: urlstring,
                                onComplete: function(response) {ldelim}
                                        $("ListViewContents").innerHTML= response.responseText;
				{rdelim}
			{rdelim}
		);
{rdelim}
function gethiddenelements()
{ldelim}
	var urlstring=''	
	if(getObj('select_enable').value != '')
		urlstring +='&select=enable';	
	if(getObj('curr_row').value != '')
		urlstring +='&curr_row='+getObj('curr_row').value;	
	if(getObj('fldname_pb').value != '')
		urlstring +='&fldname='+getObj('fldname_pb').value;	
	if(getObj('productid_pb').value != '')
		urlstring +='&productid='+getObj('productid_pb').value;	
	if(getObj('recordid').value != '')
		urlstring +='&recordid='+getObj('recordid').value;	
	return urlstring;
{rdelim}
																									
function getListViewEntries_js(module,url)
{ldelim}
	popuptype = document.getElementById('popup_type').value;
        var urlstring ="module="+module+"&action="+module+"Ajax&popuptype="+popuptype+"&file=Popup&ajax=true&"+url;
    	urlstring +=gethiddenelements();
	new Ajax.Request(
                'index.php',
                {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                                method: 'post',
                                postBody: urlstring,
                                onComplete: function(response) {ldelim}
                                        $("ListViewContents").innerHTML= response.responseText;
				{rdelim}
			{rdelim}
		);
{rdelim}
</script>
