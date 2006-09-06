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
{if $MODULE eq 'HelpDesk'}
	<form name="QcEditView" onSubmit="return getFormValidate('qcform');" method="POST" action="index.php"  ENCTYPE="multipart/form-data">
{else}
	<form name="QcEditView" onSubmit="return getFormValidate('qcform');" method="POST" action="index.php">
{/if}

{if $MODULE eq 'Accounts'}
	<input type="hidden" name="module" value="{$MODULE}">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="email1" value="">
	<input type="hidden" name="email2" value="">
	<input type="hidden" name="assigned_user_id" value="{$USERID}">
	<input type="hidden" name="action" value="Save">
{elseif $MODULE eq 'Leads'}
	<input type="hidden" name="module" value="{$MODULE}">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="assigned_user_id" value="{$USERID}">
	<input type="hidden" name="email2" value="">
	<input type="hidden" name="action" value="Save">
{elseif $MODULE eq 'Contacts'}
	<input type="hidden" name="module" value="{$MODULE}">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="assigned_user_id" value="{$USERID}">
	<input type="hidden" name="email2" value="">
	<input type="hidden" name="action" value="Save">
{elseif $MODULE eq 'Calendar'}
	<input type="hidden" name="module" value="Calendar">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="activity_mode" value="{$ACTIVITY_MODE}">
	<input type="hidden" name="assigned_user_id" value="{$USERID}">
	<input type="hidden" name="action" value="Save">
{elseif $MODULE eq 'Events'}
        <input type="hidden" name="module" value="Calendar">
        <input type="hidden" name="record" value="">
        <input type="hidden" name="activity_mode" value="{$ACTIVITY_MODE}">
        <input type="hidden" name="assigned_user_id" value="{$USERID}">
        <input type="hidden" name="action" value="Save">
{elseif $MODULE eq 'HelpDesk'}
	<input type="hidden" name="module" value="{$MODULE}">
        <input type="hidden" name="return_module" value="HelpDesk">
	<input type="hidden" name="record" value="">
        <input type="hidden" name="parent_type" value="{$APP.record_type_default_key}">
        <input type="hidden" name="assigned_user_id" value="{$USERID}">
        <input type="hidden" name="action" value="Save">
        <input type="hidden" name="return_action" value="DetailView">
{elseif $MODULE eq 'Notes'}
	<input type="hidden" name="module" value="{$MODULE}">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="parent_type" value="{$APP.record_type_default_key}">
	<input type="hidden" name="action" value="Save">
{elseif $MODULE eq 'Potentials'}
	<input type="hidden" name="module" value="Potentials">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="assigned_user_id" value="{$USERID}">
	<input type="hidden" name="action" value="Save">
{elseif $MODULE eq 'Campaigns'}
        <input type="hidden" name="module" value="{$MODULE}">
        <input type="hidden" name="record" value="">
        <input type="hidden" name="assigned_user_id" value="{$USERID}">
        <input type="hidden" name="action" value="Save">
{elseif $MODULE eq 'PriceBooks'}
	<input type="hidden" name="module" value="PriceBooks">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="assigned_user_id" value="{$USERID}">
	<input type="hidden" name="action" value="Save">
{elseif $MODULE eq 'Vendors'}
	<input type="hidden" name="module" value="Vendors">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="assigned_user_id" value="{$USERID}">
	<input type="hidden" name="action" value="Save">

{/if}


