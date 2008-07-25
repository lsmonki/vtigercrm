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
<html>
<head>
<link rel="stylesheet" type="text/css" href="themes/{$THEME}/style.css">
	<link REL="SHORTCUT ICON" HREF="include/images/vtigercrm_icon.ico">	
	<style type="text/css">@import url("themes/{$THEME}/style.css");</style>
</head>
<form name="merge" method="POST" action="index.php" id="form" onsubmit="return validate_merge('{$MODULENAME}');">
	<input type=hidden name="module" value="{$MODULENAME}">
	
	<input type=hidden name="return_module" value="{$MODULENAME}">
	<input type="hidden" name="action" value="MergeSave{$MODULENAME}">
	<input type="hidden" name="parent" value="{$PARENT_TAB}">
	<input type="hidden" name="pass_rec" value="{$IDSTRING}">
	<input type="hidden" name="return_action" value="FindDuplicate{$MODULENAME}">
	<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
	<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
	<script src="include/scriptaculous/prototype.js" type="text/javascript"></script>
	<script src="include/js/general.js" type="text/javascript"></script>
	<script src="include/js/en_us.lang.js" type="text/javascript"></script>
	<script src="include/scriptaculous/scriptaculous.js" type="text/javascript"></script>
	<br>
		<table border="0" cellspacing=0  cellpadding=o width="100%">	
			<tr>
				<td align="left" colspan="2">
				<span class="moduleName">{$MOD.LBL_MERGE_DATA_IN} {$MODULENAME}</span><br>
				<span font-weight:normal><font size="2">{$APP.LBL_DESC_FOR_MERGE_FIELDS}</font></span>
				</td>
			</tr>
		</table>
	<br>
		<table class="lvt small" border="0" cellpadding="3" cellspacing="1" width="100%">
			<tr >
				<td  class="lvtCol">
					<b>{$MOD.LBL_FIELDLISTS}</b>
				</td>
				{assign var=count value=1}
				{assign var=cnt_rec value=0}
				{if $NO_EXISTING eq 1}
					{foreach key=cnt item=record from=$ID_ARRAY}	
						<td  class="lvtCol" >
							<b>{$MOD.LBL_RECORD}{$count}</b>
							{if $count eq 1}
								<input name="record" value="{$record}" onclick="select_All('{$JS_ARRAY}','{$cnt}','{$MODULENAME}');" type="radio" checked> <span style="font-size:11px">{$APP.LBL_SELECT_AS_PARENT}</span>
							{else}
								<input name="record" value="{$record}" onclick="select_All('{$JS_ARRAY}','{$cnt}','{$MODULENAME}');" type="radio"> <span style="font-size:11px">{$APP.LBL_SELECT_AS_PARENT}</span>
							{/if}
						</td>
						{assign var=cnt_rec value=$cnt_rec+1}
						{assign var=count value=$count+1}
					{/foreach}
				{else}
					{foreach key=cnt item=record from=$ID_ARRAY}	
						<td  class="lvtCol" >
							<b>{$MOD.LBL_RECORD}{$count}</b>
						{assign var=found value=0}
						{foreach item=child key=k from=$IMPORTED_RECORDS}
							{if $record eq $child}	
								{assign var=found value=1}
							{/if}
						{/foreach}
						{if $found eq 0}
							{if $count eq 1}
								<input name="record" value="{$record}" onclick="select_All('{$JS_ARRAY}','{$cnt}','{$MODULENAME}');" type="radio" checked> <span style="font-size:11px">{$APP.LBL_SELECT_AS_PARENT}</span>
							{else}
								<input name="record" value="{$record}" onclick="select_All('{$JS_ARRAY}','{$cnt}','{$MODULENAME}');" type="radio"> <span style="font-size:11px">{$APP.LBL_SELECT_AS_PARENT}</span>
							{/if}
						{/if}
						</td>
						{assign var=cnt_rec value=$cnt_rec+1}
						{assign var=count value=$count+1}
					{/foreach}
				{/if}
			</tr>
				{foreach item=data key=cnt from=$ALLVALUES}
				{foreach item=fld_array key=label from=$data}
			<tr class="IvtColdata" onmouseover="this.className='lvtColDataHover';" onmouseout="this.className='lvtColData';" bgcolor="white">
						
						{if $FIELD_ARRAY[$cnt] eq 'lastname' || $FIELD_ARRAY[$cnt] eq 'accountname' || $FIELD_ARRAY[$cnt] eq 'company' || $FIELD_ARRAY[$cnt] eq 'productname'} 
						<td ><b>{$label}<font color="red">*</font></b>
						</td>
						{else}
						<td ><b>{$label}</b>
						</td>
						{/if}
						{foreach item=fld_value key=cnt2 from=$fld_array}
							{if $fld_value.disp_value neq ''}
								{if $cnt2 eq 0}
									<td nowrap><input name='{$FIELD_ARRAY[$cnt]}' value='{$fld_value.org_value}' type="radio" checked>{$fld_value.disp_value|truncate:30}</td>
								{else}
									<td nowrap><input name='{$FIELD_ARRAY[$cnt]}' value='{$fld_value.org_value}' type="radio">{$fld_value.disp_value|truncate:30}</td>
								{/if}
							{else}
								{if $cnt2 eq 0}
									<td><input name='{$FIELD_ARRAY[$cnt]}' value='' type="radio" checked>{$APP.LBL_NONE}</td>
								{else}
									<td><input name='{$FIELD_ARRAY[$cnt]}' value='' type="radio">{$APP.LBL_NONE}</td>
								{/if}
							{/if}
						{/foreach}
			</tr>
					{/foreach}	
					{/foreach}	
			</table>
			<br>
			<table border=0 class="lvtColData"  width="100%" cellspacing=0 cellpadding="0px">	
			<tr>
					<td align="center" >
					<input title="{$APP.LBL_MERGE_BUTTON_TITLE}" class="crmbutton small save" type="submit" name="button" value="  {$APP.LBL_MERGE_BUTTON_LABEL}  " >	
					</td>
				</tr>	
			</table>
</form>
</html>			
