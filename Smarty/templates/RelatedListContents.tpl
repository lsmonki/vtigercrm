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

<script type='text/javascript' src='include/js/Mail.js'></script>
{if $SinglePane_View eq 'true'}
	{assign var = return_modname value='DetailView'}
{else}
	{assign var = return_modname value='CallRelatedList'}
{/if}
{if $ACCOUNTID neq ''}
	{assign var="search_string" value="&fromPotential=true&acc_id=$ACCOUNTID"}
{/if}
{foreach key=header item=detail from=$RELATEDLISTS}

{assign var=rel_mod value=$header}
<table border=0 cellspacing=0 cellpadding=0 width=100% class="small" style="border-bottom:1px solid #999999;padding:5px;">
        <tr>
                <td  valign=bottom><b>{$APP.$header}</b> {if $MODULE eq 'Campaigns' && ($rel_mod eq 'Contacts' || $rel_mod eq 'Leads')}<br><br>{$APP.LBL_SELECT_BUTTON_LABEL}: <a href="javascript:;" onclick="clear_checked_all('{$rel_mod}');">{$APP.LBL_NONE_NO_LINE}</a>{/if} </td>
                {if $detail ne ''}
                <td align=center>{$detail.navigation.0}</td>
                {$detail.navigation.1}
                {/if}
                <td align=right>
			{if $header eq 'Potentials'}
				{if $MODULE eq 'Products'}
					<input alt="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Potential}" title="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Potential}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Potential}" LANGUAGE=javascript onclick='return window.open("index.php?module=Potentials&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}&parenttab={$CATEGORY}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button">	
				{elseif $MODULE eq 'Contacts'}
                                        <input title="{$APP.LBL_ADD_NEW} {$APP.Potential}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Potentials';this.form.return_action.value='updateRelations'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Potential}">
				{else}
	                                <input title="{$APP.LBL_ADD_NEW} {$APP.Potential}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Potentials'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Potential}">
				{/if}
                </td>
                        {elseif $header eq 'PriceBooks'}
                                {if $MODULE eq 'Products'}
                                <input title="{$APP.LBL_ADD_TO} {$APP.PriceBooks}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_ADD_TO} {$APP.PriceBooks}" LANGUAGE=javascript onclick="this.form.action.value='AddProductToPriceBooks';this.form.module.value='Products'"  type="submit" name="button">
                                {/if}
                        {elseif $header eq 'Products'}
                                {if $MODULE eq 'PriceBooks'}
	                                <input alt="{$APP.LBL_SELECT_PRODUCT_BUTTON_LABEL}" title="{$APP.LBL_SELECT_PRODUCT_BUTTON_LABEL}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_PRODUCT_BUTTON_LABEL}" LANGUAGE=javascript onclick="this.form.action.value='AddProductsToPriceBook';this.form.module.value='Products';this.form.return_module.value='Products';this.form.return_action.value='PriceBookDetailView'"  type="submit" name="button"></td>
				{elseif $MODULE eq 'Leads'}
					<input alt="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Products}" title="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Products}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Products}" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}&parenttab={$CATEGORY}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button">
				{elseif $MODULE eq 'Accounts'}
					<input alt="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Products}" title="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Products}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Products}" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}&parenttab={$CATEGORY}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button">
				{elseif $MODULE eq 'Contacts'}
					<input alt="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Products}" title="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Products}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Products}" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}&parenttab={$CATEGORY}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button">
				{elseif $MODULE eq 'Potentials'}
					<input alt="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Products}" title="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Products}" accessKey="" class="crmbutton small save" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Products}" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}&parenttab={$CATEGORY}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button">&nbsp;
					<!-- input title="{$APP.LBL_ADD_NEW} {$APP.Product}" accessyKey="F" class="crmbutton small save" onclick="this.form.action.value='EditView';this.form.module.value='Products';this.form.return_module.value='{$MODULE}';this.form.return_action.value='{$return_modname}'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Product}"></td -->

				{elseif $MODULE eq 'Vendors'}
					<input title="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Products}" accessyKey="F" class="crmbutton small create" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&return_module=Vendors&action=Popup&return_action={$return_modname}&popuptype=detailview&select=enable&form=DetailView&form_submit=false&recordid={$ID}&parenttab={$CATEGORY}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button" name="button" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Products}">
					<input title="{$APP.LBL_ADD_NEW} {$APP.Product}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Products';this.form.return_module.value='{$MODULE}';this.form.return_action.value='{$return_modname}'; this.form.parent_id.value='';" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Product}"></td>
                                {else}
					<input title="{$APP.LBL_ADD_NEW} {$APP.Product}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Products';this.form.return_module.value='{$MODULE}';this.form.return_action.value='{$return_modname}'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Product}"></td>
				{/if}
			{elseif $header eq 'Leads'}
				{if $MODULE eq 'Campaigns'}
				<input title="{$APP.LBL_SEND_MAIL_BUTTON}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SEND_MAIL_BUTTON}" type="button"  name="button" onclick="rel_eMail('{$MODULE}',this,'{$rel_mod}')">
				{$LEADCVCOMBO}<input title="{$MOD.LBL_LOAD_LIST}" accessKey="" class="crmbutton small edit" value="{$MOD.LBL_LOAD_LIST}" type="button"  name="button" onclick="loadCvList('Leads','{$ID}')">
				<input alt="{$APP.LBL_SELECT_LEAD_BUTTON_LABEL}" title="{$APP.LBL_SELECT_LEAD_BUTTON_LABEL}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Leads}" LANGUAGE=javascript onclick='return window.open("index.php?module=Leads&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}&parenttab={$CATEGORY}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button">
				{/if}
				{if $MODULE eq 'Products'}
					<input alt="{$APP.LBL_SELECT_LEAD_BUTTON_LABEL}" title="{$APP.LBL_SELECT_LEAD_BUTTON_LABEL}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Leads}" LANGUAGE=javascript onclick='return window.open("index.php?module=Leads&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}&parenttab={$CATEGORY}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button">
				{else}
					<input title="{$APP.LBL_ADD_NEW} {$APP.Lead}" accessyKey="F" class="crmbutton small edit" onclick="this.form.action.value='EditView';this.form.module.value='Leads'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Lead}"></td>
				{/if}
			{elseif $header eq 'Accounts'}
				{if $MODULE eq 'Products'}
					<input alt="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Account}" title="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Account}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Account}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}&parenttab={$CATEGORY}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button">
				{/if}
			{elseif $header eq 'Contacts' }
				{if $MODULE eq 'Calendar' || $MODULE eq 'Potentials' || $MODULE eq 'Vendors'}
					<input alt="{$APP.LBL_SELECT_CONTACT_BUTTON_LABEL}" title="{$APP.LBL_SELECT_CONTACT_BUTTON_LABEL}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Contacts}" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}{$search_string}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button"></td>
				{elseif $MODULE eq 'Emails'}
					<input title="{$APP.LBL_BULK_MAILS}" accessykey="F" class="crmbutton small create" onclick="this.form.action.value='sendmail';this.form.return_action.value='DetailView';this.form.module.value='Emails';this.form.return_module.value='Emails';" name="button" value="{$APP.LBL_BULK_MAILS}" type="submit">&nbsp;
					<input alt="{$APP.LBL_SELECT_CONTACT_BUTTON_LABEL}" title="{$APP.LBL_SELECT_CONTACT_BUTTON_LABEL}" accessKey="" class="crmbutton small create" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Contact}" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&return_module=Emails&action=Popup&popuptype=detailview&form=EditView&form_submit=false&recordid={$ID}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button"></td>
				{elseif $MODULE eq 'Campaigns'}
					<input title="{$APP.LBL_SEND_MAIL_BUTTON}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SEND_MAIL_BUTTON}" type="button"  name="button" onclick="rel_eMail('{$MODULE}',this,'{$rel_mod}')">
					{$CONTCVCOMBO}<input title="{$MOD.LBL_LOAD_LIST}" accessKey="" class="crmbutton small edit" value="{$MOD.LBL_LOAD_LIST}" type="button"  name="button" onclick="loadCvList('Contacts','{$ID}')">
					<input alt="{$APP.LBL_SELECT_CONTACT_BUTTON_LABEL}" title="{$APP.LBL_SELECT_CONTACT_BUTTON_LABEL}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Contacts}" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}&parenttab={$CATEGORY}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button">
					<input title="{$APP.LBL_ADD_NEW} {$APP.Contact}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Contacts'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Contact}"></td>
				{elseif $MODULE eq 'Products'}
					<input alt="{$APP.LBL_SELECT_CONTACT_BUTTON_LABEL}" title="{$APP.LBL_SELECT_CONTACT_BUTTON_LABEL}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Contacts}" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}&parenttab={$CATEGORY}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button">
				{else}
					<input title="{$APP.LBL_ADD_NEW} {$APP.Contact}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Contacts'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Contact}"></td>
				{/if}
			{elseif $header eq 'Activities'}
				&nbsp;
				<input type="hidden" name="activity_mode">
				{if $MODULE eq 'PurchaseOrder' || $MODULE eq 'Invoice' || $MODULE eq 'SalesOrder' || $MODULE eq 'Quotes' || $MODULE eq 'Campaigns'}
					{if $TODO_PERMISSION eq 'true'}
					  	<input title="{$APP.LBL_ADD_NEW} {$APP.Todo}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView'; this.form.return_action.value='{$return_modname}'; this.form.module.value='Calendar'; this.form.return_module.value='{$MODULE}'; this.form.activity_mode.value='Task'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Todo}">
					{/if}
				{else}
					{if $TODO_PERMISSION eq 'true' && $MODULE neq 'Contacts'}
						<input title="{$APP.LBL_ADD_NEW} {$APP.Todo}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView'; this.form.return_action.value='{$return_modname}'; this.form.module.value='Calendar'; this.form.return_module.value='{$MODULE}'; this.form.activity_mode.value='Task'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Todo}">&nbsp;
					{else}

						{if $MODULE eq 'Contacts' && $CONTACT_PERMISSION eq 'true'}
						<input title="{$APP.LBL_ADD_NEW} {$APP.Todo}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView'; this.form.return_action.value='{$return_modname}'; this.form.module.value='Calendar'; this.form.return_module.value='{$MODULE}'; this.form.activity_mode.value='Task'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Todo}">&nbsp;
						{/if}
					{/if}
					{if $EVENT_PERMISSION eq 'true' || $MODULE eq 'Contacts'}
						<input title="{$APP.LBL_ADD_NEW} {$APP.Event}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView'; this.form.return_action.value='{$return_modname}'; this.form.module.value='Calendar'; this.form.return_module.value='{$MODULE}'; this.form.activity_mode.value='Events'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Event}">
					{/if}
				{/if}
				</td>
			{elseif $header eq 'HelpDesk'}
				<input title="{$APP.LBL_ADD_NEW} {$APP.Ticket}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='HelpDesk'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Ticket}"></td>
			{elseif $header eq 'Campaigns'}
                                <input alt="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Campaigns}" title="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Campaigns}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Campaigns}" LANGUAGE=javascript onclick='return window.open("index.php?module=Campaigns&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}&parenttab={$CATEGORY}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button"></td>
			{elseif $header eq 'Attachments'}
				<input title="{$APP.LBL_ADD_NEW} {$APP.Note}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView'; this.form.return_action.value='{$return_modname}'; this.form.module.value='Notes'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Note}">&nbsp;
				<input type="hidden" name="fileid">
				<input title="{$APP.LBL_ADD_NEW} {$APP.LBL_ATTACHMENT}" accessyKey="F" class="crmbutton small create" onclick="window.open('index.php?module=uploads&action=uploadsAjax&file=upload&return_action={$return_modname}&return_module={$MODULE}&return_id={$ID}','Attachments','width=500,height=370');" type="button" name="button" value="{$APP.LBL_ADD_NEW} {$APP.LBL_ATTACHMENT}"></td>
			{elseif $header eq 'Quotes'}
				<input title="{$APP.LBL_ADD_NEW} {$APP.Quote}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Quotes'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Quote}"></td>
			{elseif $header eq 'Invoice'}
				{if $MODULE eq 'SalesOrder'}
				<input type="hidden">
				{else}
				<input title="{$APP.LBL_ADD_NEW} {$APP.SINGLE_Invoice}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Invoice'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.SINGLE_Invoice}"></td>
				{/if}
			{elseif $header eq 'Sales Order'}
				{if $MODULE eq 'Quotes'}
				<input type="hidden">
				{else}
				<input title="{$APP.LBL_ADD_NEW} {$APP.SINGLE_SalesOrder}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='SalesOrder'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.SINGLE_SalesOrder}"></td>
				{/if}
			{elseif $header eq 'Purchase Order'}
                                <input title="{$APP.LBL_ADD_NEW} {$APP.SINGLE_PurchaseOrder}" accessyKey="O" class="crmbutton small create" onclick="this.form.action.value='EditView'; this.form.module.value='PurchaseOrder'; this.form.return_module.value='{$MODULE}'; this.form.return_action.value='{$return_modname}'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.SINGLE_PurchaseOrder}"></td>
                        {elseif $header eq 'Emails'}
                                <input type="hidden" name="email_directing_module">
                                <input type="hidden" name="record">
				{if $PERMIT eq '0'}
                                {if $MAIL_CHECK eq 'true'}
                                <input title="{$APP.LBL_ADD_NEW} {$APP.Email}" accessyKey="F" class="crmbutton small create" onclick="fnvshobj(this,'sendmail_cont');sendmail('{$MODULE}',{$ID});" type="button" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Email}"></td>
				{else}
                                <input title="{$APP.LBL_ADD_NEW} {$APP.Email}" accessyKey="F" class="crmbutton small create" onclick="OpenCompose('','create');" type="button" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Email}"></td>
                                {/if}
                                {else}
                                <input title="{$APP.LBL_ADD_NEW} {$APP.Email}" accessyKey="F" class="crmbutton small create" onclick="fnvshobj(this,'sendmail_cont');sendmail('{$MODULE}',{$ID});" type="button" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Email}"></td>
                                {/if}
			{elseif $header eq 'Users'}
                                {if $MODULE eq 'Calendar'}
				<input title="Change" accessKey="" tabindex="2" type="button" class="crmbutton small edit" value="{$APP.LBL_SELECT_USER_BUTTON_LABEL}" name="button" LANGUAGE=javascript onclick='return window.open("index.php?module=Users&return_module=Calendar&return_action={$return_modname}&activity_mode=Events&action=Popup&popuptype=detailview&form=EditView&form_submit=true&select=enable&return_id={$ID}&recordid={$ID}","test","width=640,height=525,resizable=0,scrollbars=0")';>
                                {elseif $MODULE eq 'Emails'}
                                <input title="{$APP.LBL_BULK_MAILS}" accessykey="F" class="crmbutton small create" onclick="this.form.action.value='sendmail';this.form.return_action.value='DetailView';this.form.module.value='Emails';this.form.return_module.value='Emails';" name="button" value="{$APP.LBL_BULK_MAILS}" type="submit">&nbsp;
                                <input title="Change" accesskey="" tabindex="2" class="crmbutton small edit" value="{$APP.LBL_SELECT_USER_BUTTON_LABEL}" name="Button" language="javascript" onclick='return window.open("index.php?module=Users&return_module=Emails&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=true&return_id={$ID}&recordid={$ID}","test","width=640,height=520,resizable=0,scrollbars=0");' type="button">&nbsp;</td>
                                {/if}
                        {elseif $header eq 'Activity History'}
                                &nbsp;</td>
                        {/if}
        </tr>
</table>
{assign var=check_status value=$detail}
{if $detail ne ''}
	{foreach key=header item=detail from=$detail}
		{if $header eq 'header'}
			<table border=0 cellspacing=1 cellpadding=3 width=100% style="background-color:#eaeaea;" class="small">
				<tr style="height:25px" bgcolor=white>
                                {if $MODULE eq 'Campaigns' && ($rel_mod eq 'Contacts' || $rel_mod eq 'Leads')}
                                        <td class="lvtCol"><input name ="{$rel_mod}_selectall" onclick="rel_toggleSelect(this.checked,'{$rel_mod}_selected_id','{$rel_mod}');"  type="checkbox"></td>
                                {/if}
				{foreach key=header item=headerfields from=$detail}
					<td class="lvtCol">{$headerfields}</td>
				{/foreach}
                                </tr>
		{elseif $header eq 'entries'}
			{foreach key=header item=detail from=$detail}
				<tr bgcolor=white>
                                {if $MODULE eq 'Campaigns' && ($rel_mod eq 'Contacts' || $rel_mod eq 'Leads')}
                                        <td><input name="{$rel_mod}_selected_id" id="{$header}" value="{$header}" onclick="rel_check_object(this,'{$rel_mod}');" toggleselectall(this.name,="" selectall="" )="" type="checkbox"  {$check_status.checked.$header}></td>
                                {/if}
				{foreach key=header item=listfields from=$detail}
	                                 <td>{$listfields}</td>
				{/foreach}
				</tr>
			{/foreach}
			</table>
		{/if}
	{/foreach}
{else}
	<table style="background-color:#eaeaea;color:#000000" border="0" cellpadding="3" cellspacing="1" width="100%" class="small">
		<tr style="height: 25px;" bgcolor="white">
			<td><i>{$APP.LBL_NONE_INCLUDED}</i></td>
		</tr>
	</table>
{/if}
<br><br>
{ if $MODULE eq 'Campaigns' && ($rel_mod eq 'Contacts' || $rel_mod eq 'Leads')}
<script>
rel_default_togglestate('{$rel_mod}');
</script>
{/if}
{/foreach}
