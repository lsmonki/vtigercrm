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

{if $SinglePane_View eq 'true'}
	{assign var = return_modname value='DetailView'}
{else}
	{assign var = return_modname value='CallRelatedList'}
{/if}

{foreach key=header item=detail from=$RELATEDLISTS}

<table border=0 cellspacing=0 cellpadding=0 width=100% class="small" style="border-bottom:1px solid #999999;padding:5px;">
        <tr>
                <td  valign=bottom><b>{$APP.$header}</b></td>
                {if $detail ne ''}
                <td align=center>{$detail.navigation.0}</td>
                {$detail.navigation.1}
                {/if}
                <td align=right>
			{if $header eq 'Potentials'}
                                <input title="{$APP.LBL_ADD_NEW} {$APP.Potential}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Potentials'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Potential}">
                </td>
                        {elseif $header eq 'PriceBooks'}
                                {if $MODULE eq 'Products'}
                                <input title="{$APP.LBL_ADD_TO} {$APP.PriceBook}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_ADD_TO} {$APP.PriceBook}" LANGUAGE=javascript onclick="this.form.action.value='AddProductToPriceBooks';this.form.module.value='Products'"  type="submit" name="button">
                                {/if}
                        {elseif $header eq 'Products'}
                                {if $MODULE eq 'PriceBooks'}
                                <input title="{$APP.LBL_SELECT_PRODUCT_BUTTON_LABEL}" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_PRODUCT_BUTTON_LABEL}" LANGUAGE=javascript onclick="this.form.action.value='AddProductsToPriceBook';this.form.module.value='Products';this.form.return_module.value='Products';this.form.return_action.value='PriceBookDetailView'"  type="submit" name="button"></td>
				{elseif $MODULE eq 'Potentials'}
					<input title="Change" accessKey="" class="crmbutton small save" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Product}" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button">&nbsp;
					<input title="{$APP.LBL_ADD_NEW} {$APP.Product}" accessyKey="F" class="crmbutton small save" onclick="this.form.action.value='EditView';this.form.module.value='Products';this.form.return_module.value='{$MODULE}';this.form.return_action.value='{$return_modname}'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Product}"></td>
					
				{elseif $MODULE eq 'Vendors'}
					<input title="{$APP.LBL_ADD_NEW} {$APP.Product}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Products';this.form.return_module.value='{$MODULE}';this.form.return_action.value='{$return_modname}'; this.form.parent_id.value='';" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Product}"></td>
                                {else}
				<input title="{$APP.LBL_ADD_NEW} {$APP.Product}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Products';this.form.return_module.value='{$MODULE}';this.form.return_action.value='{$return_modname}'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Product}"></td>
				{/if}
			{elseif $header eq 'Leads'}
				{if $MODULE eq 'Campaigns'}
				{$LEADCVCOMBO} <span id="lead_list_button"><input title="{$MOD.LBL_LOAD_LIST}" accessKey="" class="crmbutton small edit" value="{$MOD.LBL_LOAD_LIST}" type="button"  name="button"></span>
				<input title="Change" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Lead}" LANGUAGE=javascript onclick='return window.open("index.php?module=Leads&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button">
				{/if}
				<input title="{$APP.LBL_ADD_NEW} {$APP.Lead}" accessyKey="F" class="crmbutton small edit" onclick="this.form.action.value='EditView';this.form.module.value='Leads'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Lead}"></td>
			{elseif $header eq 'Contacts' }
				{if $MODULE eq 'Calendar' || $MODULE eq 'Potentials' || $MODULE eq 'Vendors'}
				<input title="Change" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Contact}" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button"></td>
				{elseif $MODULE eq 'Emails'}
				<input title="{$APP.LBL_BULK_MAILS}" accessykey="F" class="crmbutton small create" onclick="this.form.action.value='sendmail';this.form.return_action.value='DetailView';this.form.module.value='Emails';this.form.return_module.value='Emails';" name="button" value="{$APP.LBL_BULK_MAILS}" type="submit">&nbsp;
				<input title="Change" accessKey="" class="crmbutton small create" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Contact}" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&return_module=Emails&action=Popup&popuptype=detailview&form=EditView&form_submit=false&recordid={$ID}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button"></td>
				{elseif $MODULE eq 'Campaigns'}
				{$CONTCVCOMBO}  <span id="contact_list_button"><input title="{$MOD.LBL_LOAD_LIST}" accessKey="" class="crmbutton small edit" value="{$MOD.LBL_LOAD_LIST}" type="button"  name="button"></span>
				<input title="Change" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Contact}" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button">
				<input title="{$APP.LBL_ADD_NEW} {$APP.Contact}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Contacts'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Contact}"></td>
				{else}
				<input title="{$APP.LBL_ADD_NEW} {$APP.Contact}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Contacts'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Contact}"></td>
				{/if}
			{elseif $header eq 'Activities'}
				{if $MODULE eq 'PurchaseOrder' || $MODULE eq 'Invoice' || $MODULE eq 'SalesOrder' || $MODULE eq 'Quotes' || $MODULE eq 'Campaigns'}
				<input type="hidden" name="activity_mode">
				<input title="{$APP.LBL_ADD_NEW} {$APP.Todo}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView'; this.form.return_action.value='{$return_modname}'; this.form.module.value='Calendar'; this.form.return_module.value='{$MODULE}'; this.form.activity_mode.value='Task'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Todo}"></td>
				{else}
				<input type="hidden" name="activity_mode">
				<input title="{$APP.LBL_ADD_NEW} {$APP.Todo}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView'; this.form.return_action.value='{$return_modname}'; this.form.module.value='Calendar'; this.form.return_module.value='{$MODULE}'; this.form.activity_mode.value='Task'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Todo}">&nbsp;
				<input title="{$APP.LBL_ADD_NEW} {$APP.Event}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView'; this.form.return_action.value='{$return_modname}'; this.form.module.value='Calendar'; this.form.return_module.value='{$MODULE}'; this.form.activity_mode.value='Events'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Event}"></td>
				{/if}
			{elseif $header eq 'HelpDesk'}
				<input title="{$APP.LBL_ADD_NEW} {$APP.Ticket}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='HelpDesk'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Ticket}"></td>
			{elseif $header eq 'Campaigns'}
                                <input title="Change" accessKey="" class="crmbutton small edit" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Campaign}" LANGUAGE=javascript onclick='return window.open("index.php?module=Campaigns&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button"  name="button"></td>
			{elseif $header eq 'Attachments'}
				<input title="{$APP.LBL_ADD_NEW} {$APP.Note}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView'; this.form.return_action.value='{$return_modname}'; this.form.module.value='Notes'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Note}">&nbsp;
				<input type="hidden" name="fileid">
				<input title="{$APP.LBL_ADD_NEW} {$APP.LBL_ATTACHMENT}" accessyKey="F" class="crmbutton small create" onclick="window.open('index.php?module=uploads&action=uploadsAjax&file=upload&return_action={$return_modname}&return_module={$MODULE}&return_id={$ID}','Attachments','width=500,height=300');" type="button" name="button" value="{$APP.LBL_ADD_NEW} {$APP.LBL_ATTACHMENT}"></td>
			{elseif $header eq 'Quotes'}
				<input title="{$APP.LBL_ADD_NEW} {$APP.Quote}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Quotes'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Quote}"></td>
			{elseif $header eq 'Invoice'}
				{if $MODULE eq 'SalesOrder'}
				<input type="hidden">
				{else}
				<input title="{$APP.LBL_ADD_NEW} {$APP.Invoice}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='Invoice'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Invoice}"></td>
				{/if}
			{elseif $header eq 'Sales Order'}
				{if $MODULE eq 'Quotes'}
				<input type="hidden">
				{else}
				<input title="{$APP.LBL_ADD_NEW} {$APP.SalesOrder}" accessyKey="F" class="crmbutton small create" onclick="this.form.action.value='EditView';this.form.module.value='SalesOrder'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.SalesOrder}"></td>
				{/if}
			{elseif $header eq 'Purchase Order'}
                                <input title="{$APP.LBL_ADD_NEW} {$APP.PurchaseOrder}" accessyKey="O" class="crmbutton small create" onclick="this.form.action.value='EditView'; this.form.module.value='PurchaseOrder'; this.form.return_module.value='{$MODULE}'; this.form.return_action.value='{$return_modname}'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.PurchaseOrder}"></td>
                        {elseif $header eq 'Emails'}
                                <input type="hidden" name="email_directing_module">
                                <input type="hidden" name="record">
                                <input title="{$APP.LBL_ADD_NEW} {$APP.Email}" accessyKey="F" class="crmbutton small create" onclick="fnvshobj(this,'sendmail_cont');sendmail('{$MODULE}',{$ID});" type="button" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Email}"></td>
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
{if $detail ne ''}
	{foreach key=header item=detail from=$detail}
		{if $header eq 'header'}
			<table border=0 cellspacing=1 cellpadding=3 width=100% style="background-color:#eaeaea;" class="small">
				<tr style="height:25px" bgcolor=white>
				{foreach key=header item=headerfields from=$detail}
					<td class="lvtCol">{$headerfields}</td>
				{/foreach}
                                </tr>
		{elseif $header eq 'entries'}
			{foreach key=header item=detail from=$detail}
				<tr bgcolor=white>
				{foreach key=header item=listfields from=$detail}
	                                 <td>{$listfields}</td>
				{/foreach}
				</tr>
			{/foreach}
			</table>
		{/if}
	{/foreach}
{else}
	<table style="background-color:#eaeaea;color:eeeeee" border="0" cellpadding="3" cellspacing="1" width="100%" class="small">
		<tr style="height: 25px;" bgcolor="white">
			<td><i>{$APP.LBL_NONE_INCLUDED}</i></td>
		</tr>
	</table>
{/if}
<br><br>
{/foreach}
