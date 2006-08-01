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
<div id="dynloadarea" style="z-index:100000001;float:left;position:absolute;left:350px;top:150px;"></div>
{/if}
<script language="JavaScript" type="text/javascript" src="modules/{$MODULE}/{$SINGLE_MOD}.js"></script>
<script language="javascript">

function callSearch(searchtype)
{ldelim}
	search_fld_val= document.basicSearch.search_field[document.basicSearch.search_field.selectedIndex].value;
        search_txt_val=document.basicSearch.search_text.value;
        var urlstring = '';
        if(searchtype == 'Basic')
        {ldelim}
                urlstring = 'search_field='+search_fld_val+'&searchtype=BasicSearch&search_text='+search_txt_val+'&';
        {rdelim}
        else if(searchtype == 'Advanced')
        {ldelim}
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
                for (i=0;i<getObj("matchtype").length;i++){ldelim}
                        if (getObj("matchtype")[i].checked==true)
                                urlstring += 'matchtype='+getObj("matchtype")[i].value+'&';
                {rdelim}
                urlstring += 'search_cnt='+no_rows+'&';
                urlstring += 'searchtype=advance&'
        {rdelim}
	new Ajax.Request(
		'index.php',
		{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
			method: 'post',
			postBody:urlstring +'query=true&file=index&module={$MODULE}&action={$MODULE}Ajax&ajax=true',
			onComplete: function(response) {ldelim}
				$("status").style.display="none";
                                result = response.responseText.split('&#&#&#');
                                $("ListViewContents").innerHTML= result[2];
                                if(result[1] != '')
                                        alert(result[1]);
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
	$("status").style.display="inline";
	new Ajax.Request(
		'index.php',
		{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
			method: 'post',
			postBody: 'module='+module+'&action='+module+'Ajax&file=index&ajax=true&'+url,
			onComplete: function(response) {ldelim}
				$("status").style.display="none";
				result = response.responseText.split('&#&#&#');
				$("ListViewContents").innerHTML= result[2];
				if(result[1] != '')
			                alert(result[1]);
			{rdelim}
		{rdelim}
	);
{rdelim}

</script>

		{include file='Buttons_List.tpl'}
<!-- Activity createlink layer start  -->
{if $MODULE eq 'Activities'}
<div id="reportLay" style="width: 125px; right: 159px; top: 260px; display: none; z-index:50" onmouseout="fninvsh('reportLay')" onmouseover="fnvshNrm('reportLay')">
        <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                        <td>
                                <a href="index.php?module={$MODULE}&action=EditView&return_module={$MODULE}&activity_mode=Events&return_action=DetailView&parenttab={$CATEGORY}" class="calMnu">{$NEW_EVENT}</a>
                                <a href="index.php?module={$MODULE}&action=EditView&return_module={$MODULE}&activity_mode=Task&return_action=DetailView&parenttab={$CATEGORY}" class="calMnu">{$NEW_TASK}</a>
                        </td>
                </tr>
        </table>

</div>
{/if}
<!-- Activity createlink layer end  -->

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

{*<!-- Contents -->*}
<form name="basicSearch" action="index.php">
<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
     <tr>
        <td valign=top><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>

	<td class="showPanelBg" valign="top" width=100% style="padding:10px;">
	 <!-- SIMPLE SEARCH -->
<div id="searchAcc" style="z-index:1;display:none;position:relative;">
<table width="80%" cellpadding="5" cellspacing="0" style="border:1px dashed #CCCCCC;" class="small" align="center">
	<tr>
		<td width="15%" class="dvtCellLabel" nowrap align="right"><img src="{$IMAGE_PATH}basicSearchLens.gif" align="absmiddle" alt="{$APP.LNK_BASIC_SEARCH}" title="{$APP.LNK_BASIC_SEARCH}" border=0>&nbsp;&nbsp;<b>{$APP.LBL_SEARCH_FOR}</b></td>
		<td width="25%" class="dvtCellLabel"><input type="text"  class="txtBox" name="search_text"></td>
		<td width="25%" class="dvtCellLabel"><b>{$APP.LBL_IN}</b>&nbsp;
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
			  <input name="submit" type="button" class="crmbutton small create" onClick="callSearch('Basic');" value=" {$APP.LBL_SEARCH_NOW_BUTTON} ">&nbsp;
			   <span class="hiliteBtn4Search"><a href="#" onClick="fnhide('searchAcc');show('advSearch');document.basicSearch.searchtype.value='advance';">{$APP.LBL_GO_TO} {$APP.LNK_ADVANCED_SEARCH}</a></span>	
		</td>
		<td class="dvtCellLabel" valign="top" onMouseOver="this.style.cursor='pointer';" onclick="moveMe('searchAcc');searchshowhide('searchAcc','advSearch')">[x]</td>
	</tr>
	<tr>
		<td colspan="5" align="center" class="dvtCellLabel">
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
					<td width="15%"  class="dvtCellLabel" align="right"><img src="{$IMAGE_PATH}advancedSearchLens.gif" alt="{$APP.LNK_ADVANCED_SEARCH}" title="{$APP.LNK_ADVANCED_SEARCH}" border=0></td>
					<td nowrap width="30%" class="dvtCellLabel"><b><input name="matchtype" type="radio" value="all">&nbsp;{$APP.LBL_ADV_SEARCH_MSG_ALL}</b></td>
					<td nowrap class="dvtCellLabel" width="30%"><b><input name="matchtype" type="radio" value="any" checked>&nbsp;{$APP.LBL_ADV_SEARCH_MSG_ANY}</b></td>
					<td width="35%" class="dvtCellLabel"><span class="hiliteBtn4Search"><a href="#" onClick="show('searchAcc');fnhide('advSearch')">{$APP.LBL_GO_TO} {$APP.LNK_BASIC_SEARCH}</a></span></td>
					<td valign="top" onMouseOver="this.style.cursor='pointer';" onclick="moveMe('searchAcc');searchshowhide('searchAcc','advSearch')">[x]</td>
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
			<td class="dvtCellLabel"><input type="button" name="more" value=" {$APP.LBL_MORE} " onClick="fnAddSrch('{$FIELDNAMES}','{$CRITERIA}')" class="crmbuttom small edit">&nbsp;&nbsp;
				<input name="button" type="button" value=" {$APP.LBL_FEWER_BUTTON} " onclick="delRow()" class="crmbuttom small edit"></td>
			<td class="dvtCellLabel">&nbsp;</td>
			<td class="dvtCellLabel">&nbsp;</td>
			</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=5 width=80% style="border-bottom:1px dashed #CCCCCC;border-left:1px dashed #CCCCCC;border-right:1px dashed #CCCCCC;" align="center">
		<tr>
			<td align=center class="dvtCellLabel"><input type="button" class="crmbutton small create" value=" {$APP.LBL_SEARCH_NOW_BUTTON} " onClick="totalnoofrows();callSearch('Advanced');">
			</td>
		</tr>
	</table>
</div>		
</form>
{*<!-- Searching UI -->*}
	 <br>
	   <!-- PUBLIC CONTENTS STARTS-->
	   <div id="ListViewContents" class="small" style="width:100%;position:relative;">
     <form name="massdelete" method="POST">
     <input name='search_url' id="search_url" type='hidden' value='{$SEARCH_URL}'>
     <input name="idlist" id="idlist" type="hidden">
     <input name="change_owner" type="hidden">
     <input name="change_status" type="hidden">
     <input name="allids" type="hidden" value="{$ALLIDS}">
               <table border=0 cellspacing=1 cellpadding=0 width=100% class="lvtBg">
	            <tr style="background-color:#efefef">
		      <td>
		         <table border=0 cellspacing=0 cellpadding=2 width=100% class="small">
			      <tr>
				 <td style="padding-right:20px" nowrap>
                                 {foreach key=button_check item=button_label from=$BUTTONS}
                                        {if $button_check eq 'del'}
                                             <input class="crmbutton small delete" type="button" value="{$button_label}" onclick="return massDelete('{$MODULE}')"/>
                                        {elseif $button_check eq 's_mail'}
                                             <input class="crmbutton small edit" type="button" value="{$button_label}" onclick="return eMail('{$MODULE}',this);"/>
                                        {elseif $button_check eq 's_cmail'}
                                             <input class="crmbutton small edit" type="submit" value="{$button_label}" onclick="return massMail('{$MODULE}')"/>
                                        {elseif $button_check eq 'c_status'}
                                             <input class="crmbutton small edit" type="button" value="{$button_label}" onclick="return change(this,'changestatus')"/>
					{elseif $button_check eq 'c_owner'}
						{if $MODULE neq 'Notes' && $MODULE neq 'Products' && $MODULE neq 'Faq' && $MODULE neq 'Vendors' && $MODULE neq 'PriceBooks'}
						     <input class="crmbutton small edit" type="button" value="{$button_label}" onclick="return change(this,'changeowner')"/>
                                                {/if}
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
                                                    <SELECT NAME="viewname" id="viewname" class="small" onchange="showDefaultCustomView(this,'{$MODULE}')">{$CUSTOMVIEW_OPTION}</SELECT></td>
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
                         <div  style="width:100%;border-top:1px solid #999999;border-bottom:1px solid #999999;height:370px;overflow:auto;">
			 <table border=0 cellspacing=1 cellpadding=3 width=100% style="background-color:#cccccc;" class="small">
			      <tr>
             			 <td class="lvtCol"><input type="checkbox"  name="selectall" onClick=toggleSelect(this.checked,"selected_id")></td>
				 {foreach name="listviewforeach" item=header from=$LISTHEADER}
        			 <td class="lvtCol">{$header}</td>
			         {/foreach}
			      </tr>
			      {foreach item=entity key=entity_id from=$LISTENTITY}
			      <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'" id="row_{$entity_id}">
				 <td width="2%"><input type="checkbox" NAME="selected_id" value= '{$entity_id}' onClick=toggleSelectAll(this.name,"selectall")></td>
				 {foreach item=data from=$entity}	
				 <td>{$data}</td>
	                         {/foreach}
			      </tr>
			      {foreachelse}
				<tr><td style="background-color:#efefef;height:340px" align="center" colspan="{$smarty.foreach.listviewforeach.iteration+1}">
						<div style="border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 45%; position: relative; z-index: 10000000;">
							{assign var=vowel_conf value='LBL_A'}
							{if $MODULE eq 'Accounts' || $MODULE eq 'Invoice'}
								{assign var=vowel_conf value='LBL_AN'}
							{/if}
							{assign var=MODULE_CREATE value=$SINGLE_MOD}
							{if $MODULE eq 'HelpDesk'}
								{assign var=MODULE_CREATE value='Ticket'}
							{/if}

							{if $CHECK.EditView eq 'yes' && $MODULE neq 'Emails' && $MODULE neq 'Webmails'}
							
							<table border="0" cellpadding="5" cellspacing="0" width="98%">
							<tr>
								<td rowspan="2" width="25%"><img src="{$IMAGE_PATH}empty.jpg" height="60" width="61"></td>
								<td style="border-bottom: 1px solid rgb(204, 204, 204);" nowrap="nowrap" width="75%"><span class="genHeaderSmall">{$APP.LBL_NO} {$APP.$MODULE_CREATE}s {$APP.LBL_FOUND} !</span></td>
							</tr>
							<tr>
							<td class="small" align="left" nowrap="nowrap">{$APP.LBL_YOU_CAN_CREATE} {$APP.$vowel_conf} {$APP.$MODULE_CREATE} {$APP.LBL_NOW}. {$APP.LBL_CLICK_THE_LINK}:<br>
								   {if $MODULE neq 'Activities'}	
						  			&nbsp;&nbsp;-<a href="index.php?module={$MODULE}&action=EditView&return_action=DetailView&parenttab={$CATEGORY}">{$APP.LBL_CREATE} {$APP.$vowel_conf} {$APP.$MODULE_CREATE}</a><br>
								   {else}
									&nbsp;&nbsp;-<a href="index.php?module={$MODULE}&amp;action=EditView&amp;return_module=Activities&amp;activity_mode=Events&amp;return_action=DetailView&amp;parenttab={$CATEGORY}">{$APP.LBL_CREATE} {$APP.LBL_AN} {$APP.Event}</a><br>
									&nbsp;&nbsp;-<a href="index.php?module={$MODULE}&amp;action=EditView&amp;return_module=Activities&amp;activity_mode=Task&amp;return_action=DetailView&amp;parenttab={$CATEGORY}">{$APP.LBL_CREATE} {$APP.LBL_A} {$APP.Task}</a>
								   {/if}
								</td>
							</tr>
							</table> 
							{else}
							<table border="0" cellpadding="5" cellspacing="0" width="98%">
							<tr>
								<td rowspan="2" width="25%"><img src="{$IMAGE_PATH}empty.jpg" height="60" width="61"></td>
								<td style="border-bottom: 1px solid rgb(204, 204, 204);" nowrap="nowrap" width="75%"><span class="genHeaderSmall">{$APP.LBL_NO} {$APP.$MODULE_CREATE}s {$APP.LBL_FOUND} !</span></td>
							</tr>
							<tr>
								<td class="small" align="left" nowrap="nowrap">{$APP.LBL_YOU_ARE_NOT_ALLOWED_TO_CREATE} {$APP.$vowel_conf} {$APP.$MODULE_CREATE}<br>
								</td>
							</tr>
							</table>
							{/if}
						</div>					
				</td></tr>	
			      {/foreach}
			 </table>
			 </div>
			 <table border=0 cellspacing=0 cellpadding=2 width=100%>
			      <tr>
				 <td style="padding-right:20px" nowrap>
                                 {foreach key=button_check item=button_label from=$BUTTONS}
                                        {if $button_check eq 'del'}
                                            <input class="crmbutton small delete" type="button" value="{$button_label}" onclick="return massDelete('{$MODULE}')"/>
                                        {elseif $button_check eq 's_mail'}
                                             <input class="crmbutton small edit" type="button" value="{$button_label}" onclick="return eMail('{$MODULE}',this)"/>
                                        {elseif $button_check eq 's_cmail'}
                                             <input class="crmbutton small edit" type="submit" value="{$button_label}" onclick="return massMail('{$MODULE}')"/>
                                        {elseif $button_check eq 'c_status'}
                                             <input class="crmbutton small edit" type="button" value="{$button_label}" onclick="return change(this,'changestatus')"/>
					{elseif $button_check eq 'c_owner'}
				                {if $MODULE neq 'Notes' && $MODULE neq 'Products' && $MODULE neq 'Faq' && $MODULE neq 'Vendors' && $MODULE neq 'PriceBooks'}
                                                     <input class="crmbutton small edit" type="button" value="{$button_label}" onclick="return change(this,'changeowner')"/>
                                                {/if}
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
        <td valign=top><img src="{$IMAGE_PATH}showPanelTopRight.gif"></td>
   </tr>
</table>


<div id="changeowner" class="statechange">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
	<td class="genHeaderSmall" align="left" style="border-bottom:1px solid #CCCCCC;" width="60%">{$APP.LBL_CHANGE_OWNER}</td>
	<td style="border-bottom: 1px solid rgb(204, 204, 204);">&nbsp;</td>
	<td align="right" style="border-bottom:1px solid #CCCCCC;" width="40%"><a href="javascript:fninvsh('changeowner')">{$APP.LBL_CLOSE}</a></td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td width="50%"><b>{$APP.LBL_TRANSFER_OWNERSHIP}</b></td>
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
	<input type="button" name="button" class="crmbutton small edit" value="{$APP.LBL_UPDATE_OWNER}" onClick="ajaxChangeStatus('owner')">
	<input type="button" name="button" class="crmbutton small cancel" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" onClick="fninvsh('changeowner')">
</td>
</tr>
</table>
</div>


{if $MODULE eq 'Leads'}
<div id="changestatus" class="statechange">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
	<td class="genHeaderSmall" align="left" style="border-bottom:1px solid #CCCCCC;" width="60%">{$APP.LBL_CHANGE_STATUS}</td>
	<td style="border-bottom: 1px solid rgb(204, 204, 204);">&nbsp;</td>
	<td align="right" style="border-bottom:1px solid #CCCCCC;" width="40%"><a href="javascript:fninvsh('changestatus')">{$APP.LBL_CLOSE}</a></td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td width="50%"><b>{$APP.LBL_SELECT_STATUS}</b></td>
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
	<input type="button" name="button" class="crmbutton small edit" value="{$APP.LBL_UPDATE_STATUS}" onClick="ajaxChangeStatus('status')">
</td>
</tr>
</table>
</div>
{/if}
{if $MODULE eq 'Leads' or $MODULE eq 'Contacts' or $MODULE eq 'Accounts'}
<form name="SendMail"><div id="sendmail_cont" style="z-index:100001;position:absolute;"></div></form>
{/if}
<script>
{literal}

function ajaxChangeStatus(statusname)
{
	$("status").style.display="inline";
	var viewid = document.massdelete.viewname.value;
	var idstring = document.getElementById('idlist').value;
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
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: urlstring,
                        onComplete: function(response) {
                                $("status").style.display="none";
                                result = response.responseText.split('&#&#&#');
                                $("ListViewContents").innerHTML= result[2];
                                if(result[1] != '')
                                        alert(result[1]);
                        }
                }
        );
	
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


