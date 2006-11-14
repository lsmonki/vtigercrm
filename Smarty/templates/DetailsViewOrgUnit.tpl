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

<table width="100%" border=0 cellspacing=0 cellpadding=5>
    <tr>
	{if $MODE eq 'list'}
	    <td colspan="6" class="dvInnerHeader">
	{else}
	    <td colspan="6" class="detailedViewHeader">
	{/if}
	    <b>{$MOD.LBL_COMPANY_ORGUNITS}</b>
	</td>
    </tr>

    <!-- Header for the Organization Units -->
    <tr valign="top">
	<td width=8% valign="top" class="lvtCol" align="right"><b>{$APP.LBL_TOOLS}</b></td>
	<td width=20% class="lvtCol"><b>{$MOD.LBL_ORGUNIT_TYPE}</b></td>
	<td width=20% class="lvtCol"><b>{$MOD.LBL_ORGUNIT_NAME}</b></td>
	<td width=20% class="lvtCol"><b>{$MOD.LBL_ORGUNIT_CITY}</b></td>
	<td width=11% class="lvtCol"><b>{$MOD.LBL_ORGUNIT_STATE}</b></td>
	<td width=11% class="lvtCol"><b>{$MOD.LBL_ORGUNIT_COUNTRY}</b></td>
    </tr>

    <!-- list of the Organization Units -->
    {foreach key=orgunitid item=data from=$ORGUNITTAB}
	<tr id="{$orgunitid}" valign="top">

	    <!-- column 1 - delete link - starts -->
	    <td  class="crmTableRow small lineOnTop">
		<a onclick="return confirm('Are you sure you want to delete this record?')" href="index.php?action=Delete&return_action={$ORGVIEW}&return_module=Organization&module=OrgUnit&parenttab=Organization&record={$orgunitid}&return_id={$ID}"><img src="{$IMAGE_PATH}delete.gif" alt="{$APP.LBL_DELETE_BUTTON}" title="{$APP.LBL_DELETE_BUTTON}" border="0"></a>
		<a href="index.php?action=DetailView&return_action={$ORGVIEW}&return_module=Organization&module=OrgUnit&parenttab=Organization&record={$orgunitid}"><img src="{$IMAGE_PATH}settingsActBtnEdit.gif" alt="{$APP.LBL_EDIT_BUTTON}" title="{$APP.LBL_EDIT_BUTTON}" border="0"></a>
	    </td>

	    <!-- column 2 - Organization unit type - starts -->
	    <td class="crmTableRow small lineOnTop">
		{$data.type}
	    </td>
	    <!-- column 2 - Organization unit type - ends -->

	    <!-- column 3 - Organization unit name - starts -->
	    <td class="crmTableRow small lineOnTop">
		{$data.name}
	    </td>
	    <!-- column 3 - Organization unit name - ends -->

	    <!-- column 4 - Organization unit city - starts -->
	    <td class="crmTableRow small lineOnTop">
		{$data.city}
	    </td>
	    <!-- column 4 - Organization unit city - ends -->

	    <!-- column 5 - Organization unit state - starts -->
	    <td class="crmTableRow small lineOnTop">
		{$data.state}
	    </td>
	    <!-- column 5 - Organization unit state - ends -->

	    <!-- column 6 - Organization unit country - starts -->
	    <td class="crmTableRow small lineOnTop">
		{$data.country}
	    </td>
	    <!-- column 6 - Organization unit country - ends -->

	</tr>
    {/foreach}
    <!-- Organization unit listing - Ends -->
    <br>

    <!-- Add Organization unit Button -->
    <tr>
	<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
	    <tr>
		<td colspan="3">
		    <input type="hidden" name="parentid" value="{$ID}">
		    <input title="Add Unit [Alt+A]" accessKey="A" class="crmbutton small edit" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.module.value='OrgUnit'; this.form.parenttab.value='{$MODULE}'; this.form.action.value='EditView'; this.form.record.value='';" type="submit" name="AddOrgUnit" value="&nbsp;Add Unit&nbsp;">
		</td>
	    </tr>
	</table>
    </tr>
</table>

