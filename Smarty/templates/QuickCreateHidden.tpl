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
{if $MODULE eq 'Accounts'}
	<form name="AccountSave" onSubmit="return formValidate()" method="POST" action="index.php">
	<input type="hidden" name="module" value="{$MODULE}">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="email1" value="">
	<input type="hidden" name="email2" value="">
	<input type="hidden" name="assigned_user_id" value="{$USERID}">
	<input type="hidden" name="action" value="Save">
{elseif $MODULE eq 'Leads'}
	<form name="LeadSave" onSubmit="return formValidate()" method="POST" action="index.php">
	<input type="hidden" name="module" value="{$MODULE}">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="assigned_user_id" value="{$USERID}">
	<input type="hidden" name="email2" value="">
	<input type="hidden" name="action" value="Save">
{elseif $MODULE eq 'Contacts'}
	<form name="EditView" onSubmit="return formValidate()" method="POST" action="index.php">
	<input type="hidden" name="module" value="{$MODULE}">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="assigned_user_id" value="{$USERID}">
	<input type="hidden" name="email2" value="">
	<input type="hidden" name="action" value="Save">
{elseif $MODULE eq 'Activities'}
	<form name="ActivitySave" method="POST" action="index.php">
	<input type="hidden" name="module" value="Activities">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="activity_mode" value="Task">
	<input type="hidden" name="assigned_user_id" value="{$USERID}">
	<input type="hidden" name="action" value="Save">
	<input type="hidden" name="due_date" value="">
{elseif $MODULE eq 'Emails'}
	<form name="EmailSave" onSubmit="return formValidate()" method="POST" action="index.php">
	<input type="hidden" name="module" value="Emails">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="action" value="Save">
	<input type="hidden" name="parent_type" value="{$APP.record_type_default_key}">
	<input type="hidden" name="assigned_user_id" value="{$USERID}">
{elseif $MODULE eq 'HelpDesk'}
	<form name="TicketSave" onSubmit="return formValidate()" method="POST" action="index.php">
	<input type="hidden" name="module" value="{$MODULE}">
        <input type="hidden" name="return_module" value="HelpDesk">
	<input type="hidden" name="record" value="">
        <input type="hidden" name="parent_type" value="{$APP.record_type_default_key}">
        <input type="hidden" name="assigned_user_id" value="{$USERID}">
        <input type="hidden" name="action" value="Save">
        <input type="hidden" name="return_action" value="DetailView">
{elseif $MODULE eq 'Notes'}
	<form name="NoteSave" onSubmit="return formValidate()" method="POST" action="index.php">
	<input type="hidden" name="module" value="{$MODULE}">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="parent_type" value="{$APP.record_type_default_key}">
	<input type="hidden" name="action" value="Save">
{elseif $MODULE eq 'Potentials'}
	<form name="EditView" onSubmit="return formValidate()" method="POST" action="index.php">
	<input type="hidden" name="module" value="Potentials">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="assigned_user_id" value="{$USERID}">
	<input type="hidden" name="action" value="Save">
{elseif $MODULE eq 'Campaigns'}
        <form name="EditView" onSubmit="return formValidate()" method="POST" action="index.php">
        <input type="hidden" name="module" value="{$MODULE}">
        <input type="hidden" name="record" value="">
        <input type="hidden" name="assigned_user_id" value="{$USERID}">
        <input type="hidden" name="action" value="Save">
{elseif $MODULE eq 'Products'}
	<form name="ProductSave" onSubmit="return formValidate()" method="POST" action="index.php">
	<input type="hidden" name="module" value="Products">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="assigned_user_id" value="'.$user_id.'">
	<input type="hidden" name="action" value="Save">
	{*<input type="hidden" name="start_date" value="'.$start_date.'">
	<input type="hidden" name="expiry_date" value="'.$start_date.'">
	<input type="hidden" name="purchase_date" value="'.$start_date.'">*}

{/if}


