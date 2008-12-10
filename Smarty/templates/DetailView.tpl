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
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>

<script type="text/javascript" src="include/js/reflection.js"></script>
<script src="include/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript" src="include/js/dtlviewajax.js"></script>
<span id="crmspanid" style="display:none;position:absolute;"  onmouseover="show('crmspanid');">
   <a class="link"  align="right" href="javascript:;">{$APP.LBL_EDIT_BUTTON}</a>
</span>

<div id="convertleaddiv" style="display:block;position:absolute;left:225px;top:150px;"></div>
<script>
{literal}
var gVTModule = '{$smarty.request.module}';
function callConvertLeadDiv(id)
{
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Leads&action=LeadsAjax&file=ConvertLead&record='+id,
                        onComplete: function(response) {
                                $("convertleaddiv").innerHTML=response.responseText;
				eval($("conv_leadcal").innerHTML);
                        }
                }
        );
}
function showHideStatus(sId,anchorImgId,sImagePath)
{
	oObj = eval(document.getElementById(sId));
	if(oObj.style.display == 'block')
	{
		oObj.style.display = 'none';
		eval(document.getElementById(anchorImgId)).src = sImagePath + 'inactivate.gif';
		eval(document.getElementById(anchorImgId)).alt = 'Display';
		eval(document.getElementById(anchorImgId)).title = 'Display';
	}
	else
	{
		oObj.style.display = 'block';
		eval(document.getElementById(anchorImgId)).src = sImagePath + 'activate.gif';
		eval(document.getElementById(anchorImgId)).alt = 'Hide';
		eval(document.getElementById(anchorImgId)).title = 'Hide';
	}
}
<!-- End Of Code modified by SAKTI on 10th Apr, 2008 -->

<!-- Start of code added by SAKTI on 16th Jun, 2008 -->
function setCoOrdinate()
{
	oBtnObj = eval(document.getElementById('jumpBtnId'));
	var tagName = document.getElementById('lstRecordLayout');
	leftpos  = 0;
	toppos = 0;
	aTag = oBtnObj;
	do 
	{					  
	  leftpos  += aTag.offsetLeft;
	  toppos += aTag.offsetTop;
	} while(aTag = aTag.offsetParent);
	
	tagName.style.top= toppos + 20 + 'px';
	tagName.style.left= leftpos - 276 + 'px';
}

function getListOfRecords(obj, sModule, iId,sParentTab)
{
		new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'module=Users&action=getListOfRecords&ajax=true&CurModule='+sModule+'&CurRecordId='+iId+'&CurParentTab='+sParentTab,
			onComplete: function(response) {
				sResponse = response.responseText;
				$("lstRecordLayout").innerHTML = sResponse;
				Lay = 'lstRecordLayout';	
				var tagName = document.getElementById(Lay);
				var leftSide = findPosX(obj);
				var topSide = findPosY(obj);
				var maxW = tagName.style.width;
				var widthM = maxW.substring(0,maxW.length-2);
				var getVal = eval(leftSide) + eval(widthM);
				if(getVal  > document.body.clientWidth ){
					leftSide = eval(leftSide) - eval(widthM);
					tagName.style.left = leftSide + 230 + 'px';
					tagName.style.top = top + 20 + 'px';
					
				}
				else
					tagName.style.left = leftSide + 230 + 'px';
				
				setCoOrdinate();
				
				tagName.style.display = 'block';
				tagName.style.visibility = "visible";
			}
		}
	);
}

window.onresize = setCoOrdinate;
<!-- End of code added by SAKTI on 16th Jun, 2008 -->
{/literal}
function tagvalidate()
{ldelim}
	if(trim(document.getElementById('txtbox_tagfields').value) != '')
		SaveTag('txtbox_tagfields','{$ID}','{$MODULE}');	
	else
	{ldelim}
		alert("{$APP.PLEASE_ENTER_TAG}");
		return false;
	{rdelim}
{rdelim}
function DeleteTag(id,recordid)
{ldelim}
	$("vtbusy_info").style.display="inline";
	Effect.Fade('tag_'+id);
	new Ajax.Request(
		'index.php',
                {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                        method: 'post',
                        postBody: "file=TagCloud&module={$MODULE}&action={$MODULE}Ajax&ajxaction=DELETETAG&recordid="+recordid+"&tagid=" +id,
                        onComplete: function(response) {ldelim}
						getTagCloud();
						$("vtbusy_info").style.display="none";
                        {rdelim}
                {rdelim}
        );
{rdelim}

//Added to send a file, in Documents module, as an attachment in an email
function sendfile_email()
{ldelim}
	filename = $('dldfilename').value;
	document.DetailView.submit();
	OpenCompose(filename,'Documents');
{rdelim}

</script>

<div id="lstRecordLayout" class="layerPopup" style="display:none;width:325px;height:300px;"></div> <!-- Code added by SAKTI on 16th Jun, 2008 -->

{if $MODULE eq 'Accounts' || $MODULE eq 'Contacts' || $MODULE eq 'Leads'}
        {if $MODULE eq 'Accounts'}
                {assign var=address1 value='$MOD.LBL_BILLING_ADDRESS'}
                {assign var=address2 value='$MOD.LBL_SHIPPING_ADDRESS'}
        {/if}
        {if $MODULE eq 'Contacts'}
                {assign var=address1 value='$MOD.LBL_PRIMARY_ADDRESS'}
                {assign var=address2 value='$MOD.LBL_ALTERNATE_ADDRESS'}
        {/if}
        <div id="locateMap" onMouseOut="fninvsh('locateMap')" onMouseOver="fnvshNrm('locateMap')">
                <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                                <td>
                                {if $MODULE eq 'Accounts'}
                                        <a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Main' );" class="calMnu">{$MOD.LBL_BILLING_ADDRESS}</a>
                                        <a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Other' );" class="calMnu">{$MOD.LBL_SHIPPING_ADDRESS}</a>
                               {/if}
                               
                               {if $MODULE eq 'Contacts'}
                                <a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Main' );" class="calMnu">{$MOD.LBL_PRIMARY_ADDRESS}</a>
                                        <a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Other' );" class="calMnu">{$MOD.LBL_ALTERNATE_ADDRESS}</a>
                               {/if}
                                        
                                         </td>
                        </tr>
                </table>
        </div>
{/if}


<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
	<td>

		{include file='Buttons_List1.tpl'}

<!-- Contents -->
<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
<tr>
	<td valign=top><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
	<td class="showPanelBg" valign=top width=100%>
		<!-- PUBLIC CONTENTS STARTS-->
		<div class="small" style="padding:10px" >
		
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%"><tr><td>		
		  {* Module Record numbering, used MOD_SEQ_ID instead of ID *}
		 <span class="dvHeaderText">[ {$MOD_SEQ_ID} ] {$NAME} -  {$APP[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</span>&nbsp;&nbsp;<span id="vtbusy_info" style="display:none;" valign="bottom"><img src="{$IMAGE_PATH}vtbusy.gif" border="0"></span><span id="vtbusy_info" style="visibility:hidden;" valign="bottom"><img src="{$IMAGE_PATH}vtbusy.gif" border="0"></span></td><td>&nbsp;</td></tr>
		 <tr height=20><td>{$UPDATEINFO}</td></tr>
		 </table>			 
		<br>
		
		<!-- Account details tabs -->
		<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
				<tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
					{if $IS_REL_LIST eq 'false'}
					<td class="dvtSelectedCell" align=center nowrap>{$APP[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</td>
					<td class="dvtTabCache" style="width:100%">&nbsp;</td>
					{else}
					<td class="dvtSelectedCell" align=center nowrap>{$APP[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</td>	
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					{if $SinglePane_View eq 'false'}
						<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</a></td>
					{/if}
						<td class="dvtTabCache" style="width:100%">&nbsp;</td>
					{/if}
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign=top align=left >
                <table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
				<tr>

					<td align=left>
					<!-- content cache -->
					
				<table border=0 cellspacing=0 cellpadding=0 width=100%>
                <tr>
					<td style="padding:5px">
					<!-- Command Buttons -->
				<form action="index.php" method="post" name="DetailView" id="form">
					{include file='DetailViewHidden.tpl'}
				    <table border=0 cellspacing=0 cellpadding=0 width=100%>
					{strip}<tr>
					<td  colspan=4 style="padding:5px">
					
					
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
						<tr>
						<td width=35%>
						{if $EDIT_DUPLICATE eq 'permitted'}
						<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small edit" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$ID}';this.form.module.value='{$MODULE}';this.form.action.value='EditView'" type="submit" name="Edit" value="&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}&nbsp;">&nbsp;
						{/if}
						{if $MODULE eq 'Webmails'}
								<input title="Add to CRM" class="crmbutton small create" onclick="window.location='index.php?module={$MODULE}&action=Save&mailid={$ID}';return false;" type="submit" name="addtocrm" value="Add to CRM">&nbsp;
								<input title="Reply to Sender" class="crmbutton small create" onclick="window.location='index.php?module={$MODULE}&action=EditView&mailid={$ID}&reply=single&return_action=DetailView&return_module=Webmails&return_id={$ID}';return false;" type="submit" name="replytosender" value="Reply to Sender">&nbsp;
								<input title="Reply to All" class="crmbutton small create" onclick="window.location='index.php?module={$MODULE}&action=EditView&mailid={$ID}&reply=all&return_action=DetailView&return_module=Webmails&return_id={$ID}';return false;" type="submit" name="replytosender" value="Reply to All">&nbsp;
						{/if}
						{if $MODULE eq 'Leads' || $MODULE eq 'Contacts' || $MODULE eq 'Accounts'}
							{if $SENDMAILBUTTON eq 'permitted'}
								<input type="hidden" name="pri_email" value="{$EMAIL1}"/>
								<input type="hidden" name="sec_email" value="{$EMAIL2}"/>
								<input title="{$APP.LBL_SENDMAIL_BUTTON_TITLE}" accessKey="{$APP.LBL_SENDMAIL_BUTTON_KEY}" class="crmbutton small edit" onclick="if(LTrim(document.DetailView.pri_email.value) !='' || LTrim(document.DetailView.sec_email.value) !=''){ldelim}fnvshobj(this,'sendmail_cont');sendmail('{$MODULE}',{$ID}){rdelim}else{ldelim}OpenCompose('','create'){rdelim}" type="button" name="SendMail" value="{$APP.LBL_SENDMAIL_BUTTON_LABEL}">&nbsp;
								{/if}
						{/if}
						{if $MODULE eq 'Quotes' || $MODULE eq 'PurchaseOrder' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
								{if $CREATEPDF eq 'permitted'}
								<input title="Export To PDF" accessKey="Alt+e" class="crmbutton small create" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$ID}'; this.form.module.value='{$MODULE}'; {if $MODULE eq 'SalesOrder'} this.form.action.value='CreateSOPDF'" {else} this.form.action.value='CreatePDF'" {/if} type="submit" name="Export To PDF" value="{$APP.LBL_EXPORT_TO_PDF}">&nbsp;
								{/if}
						{/if}
						{if $MODULE eq 'Quotes'}
								{if $CONVERTSALESORDER eq 'permitted'}
								<input title="{$APP.LBL_CONVERTSO_BUTTON_TITLE}" accessKey="{$APP.LBL_CONVERTSO_BUTTON_KEY}" class="crmbutton small create" onclick="this.form.return_module.value='SalesOrder'; this.form.return_action.value='DetailView'; this.form.convertmode.value='quotetoso';this.form.module.value='SalesOrder'; this.form.action.value='EditView'" type="submit" name="Convert To SalesOrder" value="{$APP.LBL_CONVERTSO_BUTTON_LABEL}">&nbsp;
								{/if}
						{/if}
						{if $MODULE eq 'HelpDesk'}
								{if $CONVERTASFAQ eq 'permitted'}
								<input title="{$MOD.LBL_CONVERT_AS_FAQ_BUTTON_TITLE}" accessKey="{$MOD.LBL_CONVERT_AS_FAQ_BUTTON_KEY}" class="crmbutton small create" onclick="this.form.return_module.value='Faq'; this.form.return_action.value='DetailView'; this.form.action.value='ConvertAsFAQ';" type="submit" name="ConvertAsFAQ" value="{$MOD.LBL_CONVERT_AS_FAQ_BUTTON_LABEL}">&nbsp;
								{/if}
						{/if}

						{if $MODULE eq 'Potentials' || $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder'}
								{if $CONVERTINVOICE eq 'permitted'}
								<input title="{$APP.LBL_CONVERTINVOICE_BUTTON_TITLE}" accessKey="{$APP.LBL_CONVERTINVOICE_BUTTON_KEY}" class="crmbutton small create" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$ID}';this.form.convertmode.value='{$CONVERTMODE}';this.form.module.value='Invoice'; this.form.action.value='EditView'" type="submit" name="Convert To Invoice" value="{$APP.LBL_CONVERTINVOICE_BUTTON_LABEL}">&nbsp;
								{/if}
						{/if}
						{if $MODULE eq 'Leads'}
								{if $CONVERTLEAD eq 'permitted'}
								<input title="{$APP.LBL_CONVERT_BUTTON_TITLE}" accessKey="{$APP.LBL_CONVERT_BUTTON_KEY}" class="crmbutton small create" onclick="callConvertLeadDiv('{$ID}');" type="button" name="Convert" value="{$APP.LBL_CONVERT_BUTTON_LABEL}">&nbsp;
								{/if}
						{/if}
						</td>
						<td width=30% align=center>
									{if $privrecord neq ''}
										<img title="{$APP.LNK_LIST_PREVIOUS}" accessKey="{$APP.LNK_LIST_PREVIOUS}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$privrecord}&parenttab={$CATEGORY}'" name="privrecord" value="{$APP.LNK_LIST_PREVIOUS}" src="{$IMAGE_PATH}b_left.gif">&nbsp;
									{else}
										<img title="{$APP.LNK_LIST_PREVIOUS}" src="{$IMAGE_PATH}b_left_disable.gif">
									{/if}
									&nbsp;
									{if $nextrecord neq ''}
										<img title="{$APP.LNK_LIST_NEXT}" accessKey="{$APP.LNK_LIST_NEXT}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}'" name="nextrecord" src="{$IMAGE_PATH}b_right.gif">&nbsp;
									{else}
										<img title="{$APP.LNK_LIST_NEXT}" src="{$IMAGE_PATH}b_right_disable.gif">&nbsp;
									{/if}
									
							</td>
						<td width=35% align=right>
									{if $privrecord neq '' || $nextrecord neq ''}
										<input title="{$APP.LBL_JUMP_BTN}" accessKey="{$APP.LBL_JUMP_BTN}" class="crmbutton small create" onclick="location.href='javascript:getListOfRecords(this, \'{$MODULE}\',{$ID},\'{$CATEGORY}\');'" type="button" name="jumpBtnId" id="jumpBtnId" value="{$APP.LBL_JUMP_BTN}">
									{/if}
								{if $EDIT_DUPLICATE eq 'permitted' && $MODULE neq 'Documents'}
								<input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small create" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value='true';this.form.module.value='{$MODULE}'; this.form.action.value='EditView'" type="submit" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}">&nbsp;
								{/if}
								{if $DELETE eq 'permitted'}
								<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='index'; this.form.action.value='Delete'; {if $MODULE eq 'Accounts'} return confirm('{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}') {else} return confirm('{$APP.NTC_DELETE_CONFIRMATION}') {/if}" type="submit" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">&nbsp;
								{/if}
						</td>
						</tr>
						</table>

							</td>
						     </tr>{/strip}	
							 
							  <!-- Start of File Include by SAKTI on 10th Apr, 2008 -->
							 {include_php file="./include/DetailViewBlockStatus.php"}
							 <!-- Start of File Include by SAKTI on 10th Apr, 2008 -->

							{foreach key=header item=detail from=$BLOCKS}

							<!-- Detailed View Code starts here-->
							<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
							<tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                             <td align=right>
							{if $header eq $MOD.LBL_ADDRESS_INFORMATION && ($MODULE eq 'Accounts' || $MODULE eq 'Contacts' || $MODULE eq 'Leads') }
                             {if $MODULE eq 'Leads'}
                             <input name="mapbutton" value="{$APP.LBL_LOCATE_MAP}" class="crmbutton small create" type="button" onClick="searchMapLocation( 'Main' )" title="{$APP.LBL_LOCATE_MAP}">
                             {else}
                             <input name="mapbutton" value="{$APP.LBL_LOCATE_MAP}" class="crmbutton small create" type="button" onClick="fnvshobj(this,'locateMap');" onMouseOut="fninvsh('locateMap');" title="{$APP.LBL_LOCATE_MAP}">
							{/if}
                             {/if}
                             </td>
                             </tr>

							<!-- This is added to display the existing comments -->
							{if $header eq $MOD.LBL_COMMENTS || $header eq $MOD.LBL_COMMENT_INFORMATION}
							   <tr>
								<td colspan=4 class="dvInnerHeader">
						        	<b>{$MOD.LBL_COMMENT_INFORMATION}</b>
								</td>
							   </tr>
							   <tr>
							   			<td colspan=4 class="dvtCellInfo">{$COMMENT_BLOCK}</td>
							   </tr>
							   <tr><td>&nbsp;</td></tr>
							{/if}


	{if $header neq 'Comments'}
 
						     <tr>{strip}
						     <td colspan=4 class="dvInnerHeader">
							
							<div style="float:left;font-weight:bold;"><div style="float:left;"><a href="javascript:showHideStatus('tbl{$header|replace:' ':''}','aid{$header|replace:' ':''}','{$IMAGE_PATH}');">
							{if $BLOCKINITIALSTATUS[$header] eq 1}
								<img id="aid{$header|replace:' ':''}" src="{$IMAGE_PATH}activate.gif" style="border: 0px solid #000000;" alt="Hide" title="Hide"/>
							{else}
							<img id="aid{$header|replace:' ':''}" src="{$IMAGE_PATH}inactivate.gif" style="border: 0px solid #000000;" alt="Display" title="Display"/>
							{/if}
								</a></div><b>&nbsp;
						        	{$header}
	  			     			</b></div>
						     </td>{/strip}
					             </tr>
{/if}
							</table>
{if $header neq 'Comments'}
							{if $BLOCKINITIALSTATUS[$header] eq 1}
							<div style="width:auto;display:block;" id="tbl{$header|replace:' ':''}" >
							{else}
							<div style="width:auto;display:none;" id="tbl{$header|replace:' ':''}" >
							{/if}
							<table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
						   {foreach item=detail from=$detail}
						     <tr style="height:25px">
							{foreach key=label item=data from=$detail}
							   {assign var=keyid value=$data.ui}
							   {assign var=keyval value=$data.value}
							   {assign var=keytblname value=$data.tablename}
							   {assign var=keyfldname value=$data.fldname}
							   {assign var=keyfldid value=$data.fldid}
							   {assign var=keyoptions value=$data.options}
							   {assign var=keysecid value=$data.secid}
							   {assign var=keyseclink value=$data.link}
							   {assign var=keycursymb value=$data.cursymb}
							   {assign var=keysalut value=$data.salut}
							   {assign var=keyaccess value=$data.notaccess}
							   {assign var=keycntimage value=$data.cntimage}
							   {assign var=keyadmin value=$data.isadmin}
							   
							   
							   
                           {if $label ne ''}
	                        {if $keycntimage ne ''}
				<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$keycntimage}</td>
				{elseif $keyid eq '71' || $keyid eq '72'}<!-- Currency symbol -->
					<td class="dvtCellLabel" align=right width=25%>{$label}<input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input> ({$keycursymb})</td>
				{else}
					<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$label}</td>
				{/if}
				{if $MODULE eq 'Documents' && $EDIT_PERMISSION eq 'yes' && $header eq 'File Information'}
					{if $keyfldname eq 'filestatus' && $ADMIN eq 'yes'}
						{include file="DetailViewUI.tpl"}
					{else}
						{include file="DetailViewFields.tpl"}
					{/if}
				{else}
					{if $EDIT_PERMISSION eq 'yes'}
						{include file="DetailViewUI.tpl"}
					{else}
						{include file="DetailViewFields.tpl"}
					{/if}
				{/if}
			   {/if}
                                   {/foreach}
						      </tr>	
						   {/foreach}	
						     </table>
							 </div>
{/if}
                     	                      </td>
					   </tr>
		<tr>                                                                                                               <td style="padding:10px">
			{/foreach}
                    {*-- End of Blocks--*} 
			</td>
                </tr>
		<!-- Inventory - Product Details informations -->
		   <tr>
			{$ASSOCIATED_PRODUCTS}
		   </tr>
			{if $SinglePane_View eq 'false' || $MODULE eq 'Documents' || $MODULE eq 'Faq'}
			                  <tr>
					     <td style="padding:10px">
		           <table border=0 cellspacing=0 cellpadding=0 width=100%>
				     {strip}<tr nowrap>
							<td  colspan=4 style="padding:5px">
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
						<tr>
						<td width=35%>
					
						{if $EDIT_DUPLICATE eq 'permitted'}
						<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small edit" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$ID}';this.form.module.value='{$MODULE}';this.form.action.value='EditView'" type="submit" name="Edit" value="&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}&nbsp;">&nbsp;
						{/if}
						{if $MODULE eq 'Webmails'}
								<input title="Add to CRM" class="crmbutton small create" onclick="window.location='index.php?module={$MODULE}&action=Save&mailid={$ID}';return false;" type="submit" name="addtocrm" value="Add to CRM">&nbsp;
								<input title="Reply to Sender" class="crmbutton small create" onclick="window.location='index.php?module={$MODULE}&action=EditView&mailid={$ID}&reply=single&return_action=DetailView&return_module=Webmails&return_id={$ID}';return false;" type="submit" name="replytosender" value="Reply to Sender">&nbsp;
								<input title="Reply to All" class="crmbutton small create" onclick="window.location='index.php?module={$MODULE}&action=EditView&mailid={$ID}&reply=all&return_action=DetailView&return_module=Webmails&return_id={$ID}';return false;" type="submit" name="replytosender" value="Reply to All">&nbsp;
						{/if}
						{if $MODULE eq 'Leads' || $MODULE eq 'Contacts' || $MODULE eq 'Accounts'}
							{if $SENDMAILBUTTON eq 'permitted'}
								<input title="{$APP.LBL_SENDMAIL_BUTTON_TITLE}" accessKey="{$APP.LBL_SENDMAIL_BUTTON_KEY}" class="crmbutton small edit" onclick="if(LTrim(document.DetailView.pri_email.value) !='' || LTrim(document.DetailView.sec_email.value) !=''){ldelim}fnvshobj(this,'sendmail_cont');sendmail('{$MODULE}',{$ID}){rdelim}else{ldelim}OpenCompose('','create'){rdelim}" type="button" name="SendMail" value="{$APP.LBL_SENDMAIL_BUTTON_LABEL}">&nbsp;
							{/if}
						{/if}
						{if $MODULE eq 'Quotes' || $MODULE eq 'PurchaseOrder' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
								{if $CREATEPDF eq 'permitted'}
								<input title="Export To PDF" accessKey="Alt+e" class="crmbutton small create" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$ID}'; this.form.module.value='{$MODULE}'; {if $MODULE eq 'SalesOrder'} this.form.action.value='CreateSOPDF'" {else} this.form.action.value='CreatePDF'" {/if} type="submit" name="Export To PDF" value="{$APP.LBL_EXPORT_TO_PDF}">&nbsp;
								{/if}
						{/if}
						{if $MODULE eq 'Quotes'}
								{if $CONVERTSALESORDER eq 'permitted'}
								<input title="{$APP.LBL_CONVERTSO_BUTTON_TITLE}" accessKey="{$APP.LBL_CONVERTSO_BUTTON_KEY}" class="crmbutton small create" onclick="this.form.return_module.value='SalesOrder'; this.form.return_action.value='DetailView'; this.form.convertmode.value='quotetoso';this.form.module.value='SalesOrder'; this.form.action.value='EditView'" type="submit" name="Convert To SalesOrder" value="{$APP.LBL_CONVERTSO_BUTTON_LABEL}">&nbsp;
								{/if}
						{/if}
						{if $MODULE eq 'HelpDesk'}
								{if $CONVERTASFAQ eq 'permitted'}
								<input title="{$MOD.LBL_CONVERT_AS_FAQ_BUTTON_TITLE}" accessKey="{$MOD.LBL_CONVERT_AS_FAQ_BUTTON_KEY}" class="crmbutton small create" onclick="this.form.return_module.value='Faq'; this.form.return_action.value='DetailView'; this.form.action.value='ConvertAsFAQ';" type="submit" name="ConvertAsFAQ" value="{$MOD.LBL_CONVERT_AS_FAQ_BUTTON_LABEL}">&nbsp;
								{/if}
						{/if}

						{if $MODULE eq 'Potentials' || $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder'}
								{if $CONVERTINVOICE eq 'permitted'}
								<input title="{$APP.LBL_CONVERTINVOICE_BUTTON_TITLE}" accessKey="{$APP.LBL_CONVERTINVOICE_BUTTON_KEY}" class="crmbutton small create" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$ID}';this.form.convertmode.value='{$CONVERTMODE}';this.form.module.value='Invoice'; this.form.action.value='EditView'" type="submit" name="Convert To Invoice" value="{$APP.LBL_CONVERTINVOICE_BUTTON_LABEL}">&nbsp;
								{/if}
						{/if}
						{if $MODULE eq 'Leads'}
								{if $CONVERTLEAD eq 'permitted'}
								<input title="{$APP.LBL_CONVERT_BUTTON_TITLE}" accessKey="{$APP.LBL_CONVERT_BUTTON_KEY}" class="crmbutton small create" onclick="callConvertLeadDiv('{$ID}');" type="button" name="Convert" value="{$APP.LBL_CONVERT_BUTTON_LABEL}">&nbsp;
								{/if}
						{/if}
						</td>
						<td width=30% align=center>
									{if $privrecord neq ''}
										<img title="{$APP.LNK_LIST_PREVIOUS}" accessKey="{$APP.LNK_LIST_PREVIOUS}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$privrecord}&parenttab={$CATEGORY}'" name="privrecord" value="{$APP.LNK_LIST_PREVIOUS}" src="{$IMAGE_PATH}b_left.gif">&nbsp;
									{else}
										<img title="{$APP.LNK_LIST_PREVIOUS}" src="{$IMAGE_PATH}b_left_disable.gif">
									{/if}
									&nbsp;
									{if $nextrecord neq ''}
										<img title="{$APP.LNK_LIST_NEXT}" accessKey="{$APP.LNK_LIST_NEXT}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}'" name="nextrecord" src="{$IMAGE_PATH}b_right.gif">&nbsp;
									{else}
										<img title="{$APP.LNK_LIST_NEXT}" src="{$IMAGE_PATH}b_right_disable.gif">&nbsp;
									{/if}
							</td>
						<td width=35% align=right>
								{if $privrecord neq '' || $nextrecord neq ''}
									<input title="{$APP.LBL_JUMP_BTN}" accessKey="{$APP.LBL_JUMP_BTN}" class="crmbutton small create" onclick="location.href='javascript:getListOfRecords(this, \'{$MODULE}\',{$ID},\'{$CATEGORY}\');'" type="button" name="jumpBtnId" id="jumpBtnId" value="{$APP.LBL_JUMP_BTN}">
								{/if}
								{if $EDIT_DUPLICATE eq 'permitted' && $MODULE neq 'Documents'}
								<input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small create" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value='true';this.form.module.value='{$MODULE}'; this.form.action.value='EditView'" type="submit" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}">&nbsp;
								{/if}
								{if $DELETE eq 'permitted'}
								<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='index'; this.form.action.value='Delete'; {if $MODULE eq 'Accounts'} return confirm('{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}')" {else} return confirm('{$APP.NTC_DELETE_CONFIRMATION}')" {/if} type="submit" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">&nbsp;
								{/if}

						</td>
						</tr>
						</table>

							</td>


						     </tr>{/strip}
				</table>
</td></tr>
{/if}
</form>
			{if $SinglePane_View eq 'true' && $IS_REL_LIST eq 'true'}
				{include file= 'RelatedListNew.tpl'}
			{/if}
		</table>
		</td>
		<td width=22% valign=top style="border-left:1px dashed #cccccc;padding:13px">
						<!-- right side relevant info -->
		<!-- Action links for Event & Todo START-by Minnie -->
                {if $MODULE eq 'Contacts' || $MODULE eq 'Accounts' || $MODULE eq 'Leads' || ($MODULE eq 'Documents' && $FILE_EXIST eq 'yes'  && ($ADMIN eq 'yes' || $FILE_STATUS eq '1'))}
                        <table width="100%" border="0" cellpadding="5" cellspacing="0">
                                <tr><td>&nbsp;</td></tr>
{if $TODO_PERMISSION eq 'true' || $EVENT_PERMISSION eq 'true' || $CONTACT_PERMISSION eq 'true'|| $MODULE eq 'Contacts' || ($MODULE eq 'Documents' && $FILE_EXIST eq 'yes')}                              
<tr><td align="left" class="genHeaderSmall">{$APP.LBL_ACTIONS}</td></tr>
{/if}
                                {if $MODULE eq 'Contacts'}
					{assign var=subst value="contact_id"}
					{assign var=acc value="&account_id=$accountid"}
				{else}
					{assign var=subst value="parent_id"}
					{assign var=acc value=""}
                                {/if}
			{if $MODULE eq 'Contacts' || $EVENT_PERMISSION eq 'true'}	
				<tr><td align="left" style="padding-left:10px;"> 
			        <a href="index.php?module=Calendar&action=EditView&return_module={$MODULE}&return_action=DetailView&activity_mode=Events&return_id={$ID}&{$subst}={$ID}{$acc}&parenttab={$CATEGORY}" class="webMnu"><img src="{$IMAGE_PATH}AddEvent.gif" hspace="5" align="absmiddle"  border="0"/></a>
                                <a href="index.php?module=Calendar&action=EditView&return_module={$MODULE}&return_action=DetailView&activity_mode=Events&return_id={$ID}&{$subst}={$ID}{$acc}&parenttab={$CATEGORY}" class="webMnu">{$APP.LBL_ADD_NEW} {$APP.Event}</a>
                                </td></tr>
			{/if}
	{if $TODO_PERMISSION eq 'true' && ($MODULE eq 'Accounts' || $MODULE eq 'Leads')}
                                <tr><td align="left" style="padding-left:10px;">
			        <a href="index.php?module=Calendar&action=EditView&return_module={$MODULE}&return_action=DetailView&activity_mode=Task&return_id={$ID}&{$subst}={$ID}{$acc}&parenttab={$CATEGORY}" class="webMnu"><img src="{$IMAGE_PATH}AddToDo.gif" hspace="5" align="absmiddle" border="0"/></a>
                                <a href="index.php?module=Calendar&action=EditView&return_module={$MODULE}&return_action=DetailView&activity_mode=Task&return_id={$ID}&{$subst}={$ID}{$acc}&parenttab={$CATEGORY}" class="webMnu">{$APP.LBL_ADD_NEW} {$APP.Todo}</a>
</td></tr>
	{/if}
	{if $MODULE eq 'Contacts' && $CONTACT_PERMISSION eq 'true'}
                                <tr><td align="left" style="padding-left:10px;">
			        <a href="index.php?module=Calendar&action=EditView&return_module={$MODULE}&return_action=DetailView&activity_mode=Task&return_id={$ID}&{$subst}={$ID}{$acc}&parenttab={$CATEGORY}" class="webMnu"><img src="{$IMAGE_PATH}AddToDo.gif" hspace="5" align="absmiddle" border="0"/></a>
                                <a href="index.php?module=Calendar&action=EditView&return_module={$MODULE}&return_action=DetailView&activity_mode=Task&return_id={$ID}&{$subst}={$ID}{$acc}&parenttab={$CATEGORY}" class="webMnu">{$APP.LBL_ADD_NEW} {$APP.Todo}</a>
</td></tr>
	{/if}

<!-- Start: Actions for Documents Module -->
	{if $MODULE eq 'Documents'}
                                <tr><td align="left" style="padding-left:10px;">			        
				{if $DLD_TYPE eq 'I'}	
					<br><a href="index.php?module=Documents&action=DownloadFile&fileid={$NOTESID}&folderid={$FOLDERID}" class="webMnu"><img src="{$IMAGE_PATH}fbDownload.gif" hspace="5" align="absmiddle" title="{$APP.LNK_DOWNLOAD}" border="0"/></a>
                    <a href="index.php?module=Documents&action=DownloadFile&fileid={$NOTESID}&folderid={$FOLDERID}">{$MOD.LBL_DOWNLOAD_FILE}</a>
				{elseif $DLD_TYPE eq 'E'}
					<br><a href="{$DLD_PATH}" onclick="javascript:dldCntIncrease({$NOTESID});"><img src="{$IMAGE_PATH}fbDownload.gif" align="absmiddle" title="{$APP.LNK_DOWNLOAD}" border="0"></a>
					<a href="{$DLD_PATH}" onclick="javascript:dldCntIncrease({$NOTESID});">{$MOD.LBL_DOWNLOAD_FILE}</a>
				{/if}
</td></tr>
{if $CHECK_INTEGRITY_PERMISSION eq 'yes'}
<tr><td align="left" style="padding-left:10px;">	
					<br><a href="javascript:;" onClick="checkFileIntegrityDetailView({$NOTESID});"><img id="CheckIntegrity_img_id" src="{$IMAGE_PATH}yes.gif" alt="Check integrity of this file" title="Check integrity of this file" hspace="5" align="absmiddle" border="0"/></a>
                    <a href="javascript:;" onClick="checkFileIntegrityDetailView({$NOTESID});">{$MOD.LBL_CHECK_INTEGRITY}</a>&nbsp;
                    <input type="hidden" id="dldfilename" name="dldfilename" value="{$FILENAME}">
                    <span id="vtbusy_integrity_info" style="display:none;">
						<img src="{$IMAGE_PATH}vtbusy.gif" border="0"></span>
					<span id="integrity_result" style="display:none"></span>						
</td></tr>
{/if}
<tr><td align="left" style="padding-left:10px;">			        
				{if $DLD_TYPE eq 'I'}	
					<input type="hidden" id="dldfilename" name="dldfilename" value="{$FILENAME}">
					<br><a href="javascript: document.DetailView.return_module.value='Documents'; document.DetailView.return_action.value='DetailView'; document.DetailView.module.value='Documents'; document.DetailView.action.value='EmailFile'; document.DetailView.record.value={$NOTESID}; document.DetailView.return_id.value={$NOTESID}; sendfile_email();" class="webMnu"><img src="{$IMAGE_PATH}attachment.gif" hspace="5" align="absmiddle" border="0"/></a>
                    <a href="javascript: document.DetailView.return_module.value='Attachments'; document.DetailView.return_action.value='DetailView'; document.DetailView.module.value='Documents'; document.DetailView.action.value='EmailFile'; document.DetailView.record.value={$NOTESID}; document.DetailView.return_id.value={$NOTESID}; sendfile_email();">{$MOD.LBL_EMAIL_FILE}</a>                                      
				{/if}
</td></tr>
<tr><td>&nbsp;</td></tr>
	{/if}
<!-- End: Actions for Documents Module -->	
                  </table>
                <br>
                {/if}
                <!-- Action links for Event & Todo END-by Minnie -->

		{if $TAG_CLOUD_DISPLAY eq 'true'}
		<!-- Tag cloud display -->
		<table border=0 cellspacing=0 cellpadding=0 width=100% class="tagCloud">
		<tr>
			<td class="tagCloudTopBg"><img src="{$IMAGE_PATH}tagCloudName.gif" border=0></td>
		</tr>
		<tr>
              		<td><div id="tagdiv" style="display:visible;"><form method="POST" action="javascript:void(0);" onsubmit="return tagvalidate();"><input class="textbox"  type="text" id="txtbox_tagfields" name="textbox_First Name" value="" style="width:100px;margin-left:5px;"></input>&nbsp;&nbsp;<input name="button_tagfileds" type="submit" class="crmbutton small save" value="{$APP.LBL_TAG_IT}" /></form></div></td>
                </tr>
		<tr>
			<td class="tagCloudDisplay" valign=top> <span id="tagfields">{$ALL_TAG}</span></td>
		</tr>
		</table>
		<!-- End Tag cloud display -->
		{/if}
			<!-- Mail Merge-->
				<br>
				{if $MERGEBUTTON eq 'permitted'}
				<form action="index.php" method="post" name="TemplateMerge" id="form">
				<input type="hidden" name="module" value="{$MODULE}">
				<input type="hidden" name="parenttab" value="{$CATEGORY}">
				<input type="hidden" name="record" value="{$ID}">
				<input type="hidden" name="action">
  				<table border=0 cellspacing=0 cellpadding=0 width=100% class="rightMailMerge">
      				<tr>
      					   <td class="rightMailMergeHeader"><b>{$WORDTEMPLATEOPTIONS}</b></td>
      				</tr>
      				<tr style="height:25px">
					<td class="rightMailMergeContent">
						{if $TEMPLATECOUNT neq 0}
						<select name="mergefile">{foreach key=templid item=tempflname from=$TOPTIONS}<option value="{$templid}">{$tempflname}</option>{/foreach}</select>
                                                   <input class="crmbutton small create" value="{$APP.LBL_MERGE_BUTTON_LABEL}" onclick="this.form.action.value='Merge';" type="submit"></input> 
						{else}
						<a href=index.php?module=Settings&action=upload&tempModule={$MODULE}&parenttab=Settings>{$APP.LBL_CREATE_MERGE_TEMPLATE}</a>
						{/if}
					</td>
      				</tr>
  				</table>
				</form>
				{/if}
			</td>
		</tr>
		</table>
		
			
			
		
		</div>
		<!-- PUBLIC CONTENTS STOPS-->
	</td>
</tr>
</table>

{if $MODULE eq 'Products'}
<script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script>
<script language="JavaScript" type="text/javascript">Carousel();</script>
{/if}

<script>

function getTagCloud()
{ldelim}
new Ajax.Request(
        'index.php',
        {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
        method: 'post',
        postBody: 'module={$MODULE}&action={$MODULE}Ajax&file=TagCloud&ajxaction=GETTAGCLOUD&recordid={$ID}',
        onComplete: function(response) {ldelim}
                                $("tagfields").innerHTML=response.responseText;
                                $("txtbox_tagfields").value ='';
                        {rdelim}
        {rdelim}
);
{rdelim}
getTagCloud();
</script>
<!-- added for validation -->
<script language="javascript">
  var fieldname = new Array({$VALIDATION_DATA_FIELDNAME});
  var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL});
  var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE});
</script>
</td>

	<td align=right valign=top><img src="{$IMAGE_PATH}showPanelTopRight.gif"></td>
</tr></table>

{if $MODULE eq 'Leads' or $MODULE eq 'Contacts' or $MODULE eq 'Accounts' or $MODULE eq 'Campaigns' or $MODULE eq 'Vendors'}
	<form name="SendMail"><div id="sendmail_cont" style="z-index:100001;position:absolute;"></div></form>
{/if}
