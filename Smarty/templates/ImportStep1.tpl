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

<!-- header - level 2 tabs -->
<br>
<table class="small" border="0" cellpadding="0" cellspacing="0" width="100%">
   <tbody>
   <tr>
	<td style="height: 2px;"></td>
   </tr>
   <tr>
	<td style="padding-left:10px;padding-right:10px" class="moduleName" nowrap>{$CATEGORY} > <a class="hdrLink" href="index.php?action=ListView&module={$MODULE}">{$MODULE}</a></td>
   </tr>
   </tbody>
</table>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%" class="small">
   <tbody>
   <tr>
	<td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif" /></td>
	<td class="showPanelBg" valign="top" width="100%">

		<table  cellpadding="0" cellspacing="0" width="100%">
		   <tr>
			<td width="75%" valign=top>
				<form enctype="multipart/form-data" name="Import" method="POST" action="index.php">
				<input type="hidden" name="module" value="{$MODULE}">
				<input type="hidden" name="step" value="1">
				<input type="hidden" name="source" value="{$SOURCE}">
				<input type="hidden" name="source_id" value="{$SOURCE_ID}">
				<input type="hidden" name="action" value="Import">
				<input type="hidden" name="return_module" value="{$RETURN_MODULE}">
				<input type="hidden" name="return_id" value="{$RETURN_ID}">
				<input type="hidden" name="return_action" value="{$RETURN_ACTION}">

				<!-- IMPORT LEADS STARTS HERE  -->
				<br />
				<table align="center" cellpadding="5" cellspacing="0" width="95%" class="leadTable small">
				   <tr>
					<td colspan="2" bgcolor="#FFFFFF" height="50" valign="middle" align="left" class="genHeaderSmall">{$MOD.LBL_MODULE_NAME} {$MODULE}</td>
				   </tr>
				   <tr bgcolor="#ECECEC">
					<td colspan="2" align="left" valign="top">&nbsp;</td>
				   </tr>
				   <tr bgcolor="#ECECEC">
					<td colspan="2" align="left" valign="top" style="padding-left:40px;">
						<span class="genHeaderGray">{$MOD.LBL_STEP_1}</span>&nbsp; 
						<span class="genHeaderSmall">{$MOD.LBL_STEP_1_TITLE}</span> 
					</td>
				   </tr>
				   <tr bgcolor="#ECECEC">
					<td colspan="2" align="left" valign="top" style="padding-left:40px;">
						{$MOD.LBL_STEP_1_TEXT}
					</td>
				   </tr>
				   <tr bgcolor="#ECECEC"><td align="left" valign="top" colspan="2">&nbsp;</td></tr>
				   <tr bgcolor="#ECECEC">
					<td align="right" valign="top" width="25%"><b>{$MOD.LBL_FILE_LOCATION} </b></td>
					<td align="left" valign="top" width="75%">
						<input type="file" name="userfile"  size="40"   />&nbsp;
                			        <input type="checkbox" name="has_header"{$HAS_HEADER_CHECKED} />&nbsp; {$MOD.LBL_HAS_HEADER}
					</td>
				   </tr>
				   {*<tr bgcolor="#ECECEC">
		
					<td align="right" valign="top" width="25%"><b>Delimeter : </b></td>
				        <td align="left" valign="top" width="75%">
						<input type="text"  class="importBox"  />&nbsp;
					</td>
				   </tr>
			
				   <tr bgcolor="#ECECEC">
					<td align="right" valign="top">	
						<b>Use Data Source :</b>
					</td>
	        			<td align="left" valign="top">
						<input name="custom" type="radio" value="" />&nbsp;Custom
					</td>
				   </tr>
				   <tr bgcolor="#ECECEC">
					<td align="right" valign="top">&nbsp;</td>
					<td align="left" valign="top"><input name="custom" type="radio" value="" /> 
						Pre - Defined 
					</td>
				   </tr>*}
				   <tr bgcolor="#ECECEC"><td colspan="2" height="50">&nbsp;</td></tr>

				   <tr bgcolor="#ECECEC"><td colspan="2"><hr /></td></tr>
				   <tr bgcolor="#ECECEC">
					<td colspan="2" align="right" style="padding-right:40px;">
						<input title="{$MOD.LBL_NEXT}" accessKey="" class="classBtn" type="submit" name="button" value="  {$MOD.LBL_NEXT} &rsaquo; "  onclick="this.form.action.value='Import';this.form.step.value='2'; return verify_data(this.form);">
					</td>
				   </tr>
				   <tr bgcolor="#ECECEC">
					<td colspan="2" align="right" valign="top">&nbsp;</td>
				   </tr>
				</table>
				</form>
				<br />
				<!-- IMPORT LEADS ENDS HERE -->
			</td>
		   </tr>
		</table>
	</td>
   </tr>
</table>

