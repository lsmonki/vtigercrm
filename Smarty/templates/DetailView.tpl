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
<script type="text/javascript" src="modules/{$MODULE}/{$SINGLE_MOD}.js"></script>


<script language="JavaScript" type="text/javascript" src="include/js/dtlviewajax.js"></script>
<span id="crmspanid" style="display:none;position:absolute;"  onmouseover="show('crmspanid');">
   <a class="link"  align="right" href="javascript:;">Edit</a>
</span>


<table width="100%" cellpadding="2" cellspacing="0" border="0">
<form action="index.php" method="post" name="DetailView" id="form">
<tr><td>&nbsp;</td>
	<td>
                <table cellpadding="0" cellspacing="5" border="0">
			{include file='DetailViewHidden.tpl'}
		</table>	




<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>
<tr><td style="height:2px"></td></tr>
<tr>
	<td style="padding-left:10px;padding-right:10px" class="moduleName" nowrap>{$CATEGORY} > <a class="hdrLink" href="index.php?action=ListView&module={$MODULE}">{$MODULE}</a></td>
	<td class="sep1" style="width:1px"></td>
	<td class=small >
		<table border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					<td style="padding-right:0px"><a href="index.php?module={$MODULE}&action=EditView&parenttab={$CATEGORY}"><img src="{$IMAGE_PATH}btnL3Add.gif"alt="Create {$SINGLE_MOD}..." title="Create {$SINGLE_MOD}..." border=0></a></td>
					<td style="padding-right:0px"><a href="#"><img src="themes/blue/images/btnL3Search.gif" alt="Search in {$SINGLE_MOD}..." title="Search in {$SINGLE_MOD}..." border=0></a></a></td>
				</tr>
				</table>
			</td>
			<td nowrap width=50>&nbsp;</td>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					<td style="padding-right:0px"><a href="#"><img src="themes/blue/images/btnL3Calendar.gif" alt="Open Calendar..." title="Open Calendar..." border=0></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="themes/blue/images/btnL3Clock.gif" alt="Show World Clock..." title="Show World Clock..." border=0 onClick="fnvshobj(this,'wclock')"></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="themes/blue/images/btnL3Calc.gif" alt="Open Calculator..." title="Open Calculator..." border=0 onClick="fnvshobj(this,'calc')"></a></a></td>
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

<!-- Contents -->
<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
<tr>
	<td valign=top><img src="themes/blue/images/showPanelTopLeft.gif"></td>
	<td class="showPanelBg" valign=top width=100%>
		<!-- PUBLIC CONTENTS STARTS-->
		<div class="small" style="padding:20px" >
		
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%"><tr><td>		
		 <span class="lvtHeaderText"><font color="purple">[ {$ID} ] </font>{$NAME} -  {$SINGLE_MOD} Information</span>&nbsp;&nbsp;<span id="vtbusy_info" style="display:none;"><img src="{$IMAGE_PATH}vtbusy.gif" border="0"></span><td><td>&nbsp;</td></tr>
		 <tr><td>{$UPDATEINFO}</td><td align="right" width="400" nowrap><a href="#" onClick="show('tagdiv')">+addtag</a><div id="tagdiv" style="display:none";>{$APP.LBL_TAG_FIELDS} <input class="textbox"  type="text" id="txtbox_tagfields" name="textbox_First Name" value=""></input>&nbsp;&nbsp;<input name="button_tagfileds" type="button" class="small" value="Tag it" onclick="SaveTag('txtbox_tagfields','{$ID}','{$MODULE}')"/><input name="close" type="button" class="small" value="Close" onClick="hide('tagdiv')"></div></td></tr>		 
		 <hr noshade size=1>
		
		<!-- Account details tabs -->
		<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
				<tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
					{if $MODULE eq 'Notes' || $MODULE eq 'Faq' || ($MODULE eq 'Activities' && $ACTIVITY_MODE eq 'Task')}
					<td class="dvtSelectedCell" align=center nowrap>{$SINGLE_MOD} Information</td>
					<td class="dvtTabCache" style="width:100%">&nbsp;</td>
					{else}
					<td class="dvtSelectedCell" align=center nowrap>{$SINGLE_MOD} Information</td>	
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">More Information</a></td>

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
					     <td style="padding:10px">
						     <!-- General details -->
				                     <table border=0 cellspacing=0 cellpadding=0 width=100%>
						     {strip}<tr nowrap>
							<td  colspan=4 style="padding:5px">
								{if $EDIT_DUPLICATE eq 'permitted'}
                                                                <input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="small" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$ID}';this.form.module.value='{$MODULE}';this.form.action.value='EditView'" type="submit" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}">&nbsp;
                                                                <input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="small" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value='true';this.form.module.value='{$MODULE}'; this.form.action.value='EditView'" type="submit" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}">&nbsp;
                                                                {/if}
								{if $DELETE eq 'permitted'}
                                                                <input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="small" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='index'; this.form.action.value='Delete'; return confirm('{$APP.NTC_DELETE_CONFIRMATION}')" type="submit" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">&nbsp;
                                                                {/if}

                                                        {if $MODULE eq 'Leads' || $MODULE eq 'Contacts'}
                                                                {if $SENDMAILBUTTON eq 'permitted'}
                                                                <input title="{$APP.LBL_SENDMAIL_BUTTON_TITLE}" accessKey="{$APP.LBL_SENDMAIL_BUTTON_KEY}" class="small" onclick="this.form.return_module.value='{$MODULE}'; this.form.module.value='Emails';this.form.email_directing_module.value='{$REDIR_MOD}';this.form.return_action.value='DetailView';this.form.action.value='EditView';" type="submit" name="SendMail" value="{$APP.LBL_SENDMAIL_BUTTON_LABEL}">&nbsp;
                                                                {/if}
                                                        {/if}
                                                        {if $MODULE eq 'Quotes' || $MODULE eq 'PurchaseOrder' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
                                                                {if $CREATEPDF eq 'permitted'}
                                                                <input title="Export To PDF" accessKey="Alt+e" class="small" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$ID}'; this.form.module.value='{$MODULE}'; {if $MODULE eq 'SalesOrder'} this.form.action.value='CreateSOPDF'" {else} this.form.action.value='CreatePDF'" {/if} type="submit" name="Export To PDF" value="{$APP.LBL_EXPORT_TO_PDF}">&nbsp;
                                                                {/if}
                                                        {/if}
                                                        {if $MODULE eq 'Quotes'}
                                                                {if $CONVERTSALESORDER eq 'permitted'}
                                                                <input title="{$APP.LBL_CONVERTSO_BUTTON_TITLE}" accessKey="{$APP.LBL_CONVERTSO_BUTTON_KEY}" class="small" onclick="this.form.return_module.value='SalesOrder'; this.form.return_action.value='DetailView'; this.form.convertmode.value='quotetoso';this.form.module.value='SalesOrder'; this.form.action.value='EditView'" type="submit" name="Convert To SalesOrder" value="{$APP.LBL_CONVERTSO_BUTTON_LABEL}">&nbsp;
                                                                {/if}
                                                        {/if}
							{if $MODULE eq 'HelpDesk'}
                                                                {if $CONVERTASFAQ eq 'permitted'}
                                                                <input title="{$MOD.LBL_CONVERT_AS_FAQ_BUTTON_TITLE}" accessKey="{$MOD.LBL_CONVERT_AS_FAQ_BUTTON_KEY}" class="small" onclick="this.form.return_module.value='Faq'; this.form.return_action.value='DetailView'; this.form.action.value='ConvertAsFAQ';" type="submit" name="ConvertAsFAQ" value="{$MOD.LBL_CONVERT_AS_FAQ_BUTTON_LABEL}">&nbsp;
                                                                {/if}
                                                        {/if}

                                                        {if $MODULE eq 'Potentials' || $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder'}
                                                                {if $CONVERTINVOICE eq 'permitted'}
                                                                <input title="{$APP.LBL_CONVERTINVOICE_BUTTON_TITLE}" accessKey="{$APP.LBL_CONVERTINVOICE_BUTTON_KEY}" class="small" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$ID}';this.form.convertmode.value='{$CONVERTMODE}';this.form.module.value='Invoice'; this.form.action.value='EditView'" type="submit" name="Convert To Invoice" value="{$APP.LBL_CONVERTINVOICE_BUTTON_LABEL}">&nbsp;
                                                                {/if}
                                                        {/if}
                                                        {if $MODULE eq 'Leads'}
                                                                {if $CONVERTLEAD eq 'permitted'}
                                                                <input title="{$APP.LBL_CONVERT_BUTTON_TITLE}" accessKey="{$APP.LBL_CONVERT_BUTTON_KEY}" class="small" onclick="this.form.return_module.value='{$MODULE}';this.form.module.value='{$MODULE}'; this.form.action.value='ConvertLead'" type="submit" name="Convert" value="{$APP.LBL_CONVERT_BUTTON_LABEL}">&nbsp;
                                                                {/if}
                                                        {/if}
							</td>


						     </tr>{/strip}	
							{foreach key=header item=detail from=$BLOCKS}
							<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
							<tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td align=right>
							{if $header eq 'Address Information' && ($MODULE eq 'Accounts' || $MODULE eq 'Contacts' || $MODULE eq 'Leads') }
                                                        {if $MODULE eq 'Leads'}
                                                        <input id="locateMap" name="locateMap" value="Locate Map" class="small" type="button" onClick="searchMapLocation( 'Main' )" title="Locate Map">
                                                        {else}
                                                                {if $MODULE eq 'Accounts'}
                                                                       {assign var=address1 value='Billing'}
                                                                       {assign var=address2 value='Shipping'}
                                                                {/if}
                                                                {if $MODULE eq 'Contacts'}
                                                                       {assign var=address1 value='Mailing'}
                                                                       {assign var=address2 value='Other'}
                                                                {/if}
                                                                <input id="locateMap" name="locateMap" value="Locate Map" class="small" type="button" onClick="javascript:showLocateMapMenu()" title="Locate Map">
                                                        <div id="dropDownMenu" style="position:absolute;display:none;z-index:60">
							<table border="0" cellspacing="0" cellpadding="4">
                                                <tr bgcolor=white class="lvtColData" onMouseOver="this.className='lvtColDataHover'" style="cursor:pointer;" onMouseOut="this.className='lvtColData'" onClick="searchMapLocation( 'Main' )">
                                                <td>{$address1} Address</td>
                                                </tr>
                                                <tr bgcolor=white class="lvtColData" onMouseOver="this.className='lvtColDataHover'" style="cursor:pointer;" onMouseOut="this.className='lvtColData'"  onClick="searchMapLocation( 'Other' )">
                                                <td>{$address2} Address</td>
                                                </tr>
                                                </table>
                                                </div>
                                                {/if}
                                                <script>
                                                        document.onclick=hideLocateMapMenu;
                                                </script>
                                               {/if}
                                                        </td>
                                                        </tr>
						     <tr>{strip}
						     <td colspan=4 style="border-bottom:1px solid #999999;padding:5px;" bgcolor="#e5e5e5">
							<b>
						        	{$header}
	  			     			</b>
						     </td>{/strip}
					             </tr>
						   {foreach item=detail from=$detail}
						     <tr style="height:25px">
							{foreach key=label item=data from=$detail}
							   {assign var=keyid value=$data.ui}
							   {assign var=keyval value=$data.value}
							   {assign var=keytblname value=$data.tablename}
							   {assign var=keyfldname value=$data.fldname}
							   {assign var=keyoptions value=$data.options}
							   {assign var=keysecid value=$data.secid}
							   {assign var=keyseclink value=$data.link}
							   {assign var=keycursymb value=$data.cursymb}
							   {assign var=keysalut value=$data.salut}
							   {assign var=keycntimage value=$data.cntimage}
                                      {if $label ne ''}
                                             {if $keycntimage ne ''}
							{if $keyid neq 'P'}<!-- only to avoid Product Details in Inventory-->
								      <td class="dvtCellLabel" align=right width=25%>{$keycntimage}</td>
							{/if}
								     {else}
								            <td class="dvtCellLabel" align=right width=25%>{$label}</td>
								     {/if}  
									{if $keyid eq '1' || $keyid eq 2 || $keyid eq '11' || $keyid eq '7' || $keyid eq '9' || $keyid eq '55' || $keyid eq '71' || $keyid eq '72'} <!--TextBox-->
                                         		<td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="hide('crmspanid');">
                                         		      {if $keyid eq '55'}<!--SalutationSymbol-->
                                         		            {$keysalut}
                                         		      {elseif $keyid eq '71' || $keyid eq '72'}  <!--CurrencySymbol-->
                                         		            {$keycursymb}
                                                        {/if}
                                                       <span id="dtlview_{$label}">{$keyval}</span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                              		  <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_{$label}" name="textbox_{$label}" maxlength='100' value="{$keyval}"></input>
                                              		  <br><input name="button_{$label}" type="button" class="small" value="Save" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');hide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">Cancel</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '13'} <!--Email-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="hide('crmspanid');"><span id="dtlview_{$label}"><a href="mailto:{$keyval}" target="_blank">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                              		  <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_{$label}" name="textbox_{$label}" maxlength='100' value="{$keyval}"></input>
                                              		  <br><input name="button_{$label}" type="button" class="small" value="Save" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');hide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">Cancel</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '15' || $keyid eq '16'} <!--ComboBox-->
               							<td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="hide('crmspanid');"><span id="dtlview_{$label}">{$keyval}</span>
                                              		<div id="editarea_{$label}" style="display:none;">
                    							   <select id="txtbox_{$label}" name="{$keyfldname}">
                    								{foreach item=arr from=$keyoptions}
                    									{foreach key=sel_value item=value from=$arr}
                    										<option value="{$sel_value}" {$value}>{$sel_value}</option>
                    									{/foreach}
                    								{/foreach}
                    							   </select>
                    							   <br><input name="button_{$label}" type="button" class="small" value="Save" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');hide('crmspanid');"/> or
                                              		   <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">Cancel</a>
                    							</div>
               							</td>
                                             {elseif $keyid eq '17'} <!--WebSite-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="hide('crmspanid');"><span id="dtlview_{$label}"><a href="http://{$keyval}" target="_blank">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                              		  <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_{$label}" name="textbox_{$label}" maxlength='100' value="{$keyval}"></input>
                                              		  <br><input name="button_{$label}" type="button" class="small" value="Save" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');hide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">Cancel</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '19' || $keyid eq '20'} <!--TextArea/Description-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="hide('crmspanid');"><span id="dtlview_{$label}">{$keyval}</span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                              		  <textarea id="txtbox_{$label}" name="txtbox_{$label}"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" cols="90" rows="8">{$keyval}</textarea>                                            		  
                                              		  <br><input name="button_{$label}" type="button" class="small" value="Save" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');hide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">Cancel</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '21' || $keyid eq '24' || $keyid eq '22'} <!--TextArea/Street-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="hide('crmspanid');"><span id="dtlview_{$label}">{$keyval}</span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                              		  <textarea id="txtbox_{$label}" name="txtbox_{$label}"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" rows=2>{$keyval}</textarea>                                            		  
                                              		  <br><input name="button_{$label}" type="button" class="small" value="Save" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');hide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">Cancel</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '50' || $keyid eq '73' || $keyid eq '51'} <!--AccountPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="hide('crmspanid');"><span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">                                              		  
                                                         <input readonly id="popuptxt_{$label}" name="account_name" type="text" value="{$keyval}"><input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <!--AccountPopup/WithClear-->{if $keyid eq '51'} &nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>{/if} 
                                              		  <br><input name="button_{$label}" type="button" class="small" value="Save" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');hide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">Cancel</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '57'} <!--ContactPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="hide('crmspanid');"><span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">                                              		  
                                                         <input id="popuptxt_{$label}" name="contact_name" readonly type="text" style="border:1px solid #bababa;" value="{$keyval}"><input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <br><input name="button_{$label}" type="button" class="small" value="Save" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');hide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">Cancel</a>
                                                       </div>
                                                  </td>                                                  
                                             {elseif $keyid eq '59'} <!--ProductPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="hide('crmspanid');"><span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">                                              		  
                                                         <input id="popuptxt_{$label}" name="product_name" readonly type="text" value="{$keyval}"><input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <br><input name="button_{$label}" type="button" class="small" value="Save" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');hide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">Cancel</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '75' || $keyid eq '81'} <!--VendorPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="hide('crmspanid');"><span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                                         <input id="popuptxt_{$label}" name="vendor_name" readonly type="text" style="border:1px solid #bababa;" value="{$keyval}"><input id="txtbox_{$label}" name="{$fldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Vendors&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         {if $uitype eq 75}&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.vendor_id.value='';this.form.vendor_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>{/if}                                              		  
                                                         <br><input name="button_{$label}" type="button" class="small" value="Save" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');hide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">Cancel</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq 76} <!--PotentialPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="hide('crmspanid');"><span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">                                              		  
                                                         <input id="popuptxt_{$label}" name="potential_name" readonly type="text" style="border:1px solid #bababa;" value="{$keyval}"><input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Potentials&action=Popup&html=Popup_picker&popuptype=specific_potential_account_address&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.potential_id.value=''; this.form.potential_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <br><input name="button_{$label}" type="button" class="small" value="Save" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');hide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">Cancel</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq 78} <!--QuotePopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="hide('crmspanid');"><span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">                                              		  
                                                         <input id="popuptxt_{$label}" name="quote_name" readonly type="text" style="border:1px solid #bababa;" value="{$keyval}"><input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Quotes&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.quote_id.value=''; this.form.quote_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <br><input name="button_{$label}" type="button" class="small" value="Save" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');hide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">Cancel</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq 80} <!--SalesOrderPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="hide('crmspanid');"><span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">                                              		  
                                                         <input id="popuptxt_{$label}" name="salesorder_name" readonly type="text" style="border:1px solid #bababa;" value="{$keyval}"><input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=SalesOrder&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.salesorder_id.value=''; this.form.salesorder_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <br><input name="button_{$label}" type="button" class="small" value="Save" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');hide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">Cancel</a>
                                                       </div>
                                                  </td>
						{else}
						   {if $keyid eq 'P'}<!-- only for Product Details in Inventory-->
							{$ASSOCIATED_PRODUCTS}
                                             	   {else}
                                                  	<td class="dvtCellInfo" align="left" width=25%">{$keyval}</td>
                                                   {/if}
						{/if}
							   {else} 
                                           <td class="dvtCellLabel" align=right></td>
                                           <td class="dvtCellInfo" align=left ></td>
							   {/if}
                                   {/foreach}
						      </tr>	
						   {/foreach}	
						     </table>
                     	                      </td>
					   </tr>
		<tr>                                                                                                               <td style="padding:10px">
			{/foreach}
                    {*-- End of Blocks--*} 
			</td>
                </tr>
		</table>
		</td>
		<td width=20% valign=top style="border-left:2px dashed #cccccc;padding:13px">
						<!-- right side relevant info -->

					<!-- Mail Merge-->
					{if $MERGEBUTTON eq 'permitted'}
					<table border=0 cellspacing=0 cellpadding=0 width=100% class="rightMailMerge">
					<tr>
					<td class="rightMailMergeHeader"><b>{$WORDTEMPLATEOPTIONS}</b></td>
						</tr>
						<tr style="height:25px">
						<td class="rightMailMergeContent">
							<table border=0 cellspacing=0 cellpadding=2 width=100%>
								<tr>
								<td >
								<select class=small style="width:100%" name="mergefile">
									<option>Select template...</option>
									{html_options options=$TOPTIONS}
								</select>
								</td>
								</tr>
								<tr>
								<td >
  								{*[ <a href="#" onClick="showhide('mailMergeOptions')">Options...</a> ]*}
								<div id="mailMergeOptions" align=left style="display:none">
								<input type="checkbox" checked> Include Account Information <br>
								<input type="checkbox" checked> Include More Information <br>
								</div>
								</td>
								</tr>
								<tr>
								<td>
								{if $MERGEBUTTON eq 'permitted'}
                                                                <input title="{$APP.LBL_MERGE_BUTTON_TITLE}" accessKey="{$APP.LBL_MERGE_BUTTON_KEY}" class="small" onclick="this.form.action.value='Merge';" type="submit" name="Merge" value="{$APP.LBL_MERGE_BUTTON_LABEL}">&nbsp;
                                                                {/if}
								</td>
								</tr>
								</table>
							</td>

						</tr>
						</table>
						<br>
						{/if}	
						
						<!-- Upcoming Activities / Calendar-->
						<table border=0 cellspacing=0 cellpadding=0 width=100% style="border:1px solid #ddddcc" class="small">
						<tr>
							<td style="border-bottom:1px solid #ddddcc;padding:5px;background-color:#ffffdd;"><b> Upcoming Activities :</b></td>
						</tr>
						<tr style="height:25px">
							<td style="padding:5px" bgcolor="#ffffef">
								<table border=0 cellspacing=0 cellpadding=2 width=100% class="small">
								<tr><td valign=top >1.</td><td width=100% style="color:#727272"><b>API License renewal </b><br>On 23 Nov 2006 <br> <i>14 months 3 days to go</i></td></tr>
								<tr><td></td><td style="border-top:1px dotted #e2e2e2"></td></tr>
								</table>
							</td>
						</tr>
						</table>
						<br><br>
						<table border=0 cellspacing=0 cellpadding=0 width=100% >
						<tr><td>
						<table width="250" border="0" cellspacing="0" cellpadding="0">
						<tr>
						<td colspan="3"><img src="{$IMAGE_PATH}cloud_top.gif" width=250 height=38 alt=""></td>
						</tr>
						<tr>
						<td width="16" height="10"><img src="{$IMAGE_PATH}cloud_top_left.gif" width="16" height="10"></td>
						<td width="221" height="10"><img src="{$IMAGE_PATH}tagcloud_03.gif" width="221" height="10"></td>
						<td width="13" height="10"><img src="{$IMAGE_PATH}cloud_top_right.gif" width="13" height="10"></td>
						</tr>
						<tr>
						<td class="cloudLft"></td>
						<td>
						<span id="tagfields"></span>
						</td>
						<td class="cloudRht"></td>
						</tr>
						<tr>
						<td width="16" height="13"><img src="{$IMAGE_PATH}cloud_btm_left.gif" width="16" height="13"></td>
						<td width="221" height="13"><img src="{$IMAGE_PATH}cloud_btm_bdr.gif" width="221" height="13"></td>
						<td width="13" height="13"><img src="{$IMAGE_PATH}cloud_btm_right.gif" width="13" height="13"></td>
						</tr>
						</table>
						
						</td></tr>
						</table>

					</td>
				</tr>
				</table>
				
			</td>
		</tr>
		</table>
		
			
			
		
		</div>
		<!-- PUBLIC CONTENTS STOPS-->
	</td>
	<td align=right valign=top><img src="themes/blue/images/showPanelTopRight.gif"></td>
</tr>
</table>
{if $MODULE eq 'Products'}
<script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script>
<script language="JavaScript" type="text/javascript">Carousel();</script>
{/if}

<script>
var data = "module={$MODULE}&action={$MODULE}Ajax&ajxaction=GETTAGCLOUD";
var ajaxObj = new Ajax(ajaxTagCloudResp);
ajaxObj.process("index.php?",data);
function ajaxTagCloudResp(response)
{ldelim}
	var item = response.responseText;
	getObj('tagfields').innerHTML = item;
	document.getElementById('txtbox_tagfields').value ='';	
	
{rdelim}
</script>

</td></tr></table></form>
