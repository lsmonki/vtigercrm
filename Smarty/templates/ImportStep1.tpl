


<script type="text/javascript" src="modules/{$MODULE}/{$SINGLE_MOD}.js"></script>

<!-- header - level 2 tabs -->

<table class="small" border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody>

	<tr>
		<td style="height: 2px;"></td></tr>
	<tr>
	<td style="padding-left:10px;padding-right:10px" class="moduleName" nowrap>{$CATEGORY} > <a class="hdrLink" href="index.php?action=ListView&module={$MODULE}">{$MODULE}</a></td>
	<td class="sep1" style="width: 1px;"></td>
	<td class="small">
		<table border="0" cellpadding="0" cellspacing="0">

		<tbody><tr>
			<td>
				<table border="0" cellpadding="5" cellspacing="0">
				<tbody><tr>
					<td style="padding-right:0px"><a href="index.php?module={$MODULE}&action=EditView&parenttab={$CATEGORY}"><img src="{$IMAGE_PATH}btnL3Add.gif" alt="Create {$SINGLE_MOD}..." title="Create {$SINGLE_MOD}..." border=0></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Search.gif" alt="Search in {$MODULE}..." title="Search in {$MODULE}..." border=0></a></a></td>
				</tr>
				</tbody></table>
			</td>

			<td nowrap="nowrap" width="50">&nbsp;</td>
			<td>
				<table border="0" cellpadding="5" cellspacing="0">
				<tbody><tr>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calendar.gif" alt="Open Calendar..." title="Open Calendar..." border=0></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Clock.gif" alt="Show World Clock..." title="Show World Clock..." border=0 onClick="fnvshobj(this,'wclock')"></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calc.gif" alt="Open Calculator..." title="Open Calculator..." border=0 onClick="fnvshobj(this,'calc')"></a></a></td>
				</tr>
				</tbody></table>

			</td>
			<td style="padding: 10px; width: 50%;" nowrap="nowrap">&nbsp;</td>
			<td>
				<table border="0" cellpadding="5" cellspacing="0">

				<tbody><tr>
				</tr>
				</tbody></table>
			</td>

		</tr>
		</tbody></table>
	</td>
</tr>
<tr><td style="height: 2px;"></td></tr>

</tbody></table>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
  <tbody>
    <tr>
      <td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif" /></td>

      <td class="showPanelBg" valign="top" width="100%">


<table  cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="75%" valign=top>
<form enctype="multipart/form-data" name="Import" method="POST" action="index.php">
  <input type="hidden" name="module" value="{$MODULE}">
  <input type="hidden" name="step" value="2">
  <input type="hidden" name="source" value="{$SOURCE}">
  <input type="hidden" name="source_id" value="{$SOURCE_ID}">
  <input type="hidden" name="action" value="Import">
  <input type="hidden" name="return_module" value="{$RETURN_MODULE}">
  <input type="hidden" name="return_id" value="{$RETURN_ID}">
  <input type="hidden" name="return_action" value="{$RETURN_ACTION}">


<!-- IMPORT LEADS STARTS HERE  -->
<br />
<table align="center" cellpadding="5" cellspacing="0" width="95%" class="leadTable">
<tr>
	<td colspan="2" bgcolor="#FFFFFF" height="50" valign="middle" align="left" class="genHeaderSmall">{$MOD.LBL_MODULE_NAME} {$MODULE}</td>
</tr>
	<tr bgcolor="#ECECEC"><td colspan="2" align="left" valign="top">&nbsp;</td></tr>
	<tr bgcolor="#ECECEC">
		<td colspan="2" align="left" valign="top" style="padding-left:40px;">
			<span class="genHeaderGray">{$MOD.LBL_STEP_1}</span>&nbsp; 
			<span class="genHeaderSmall">{$MOD.LBL_STEP_1_TITLE}</span> 
		</td>
	</tr>
	<tr bgcolor="#ECECEC">

		<td colspan="2" align="left" valign="top" style="padding-left:40px;">{$MOD.LBL_STEP_1_TEXT}
		</td>
	</tr>
	<tr bgcolor="#ECECEC"><td align="left" valign="top" colspan="2">&nbsp;</td></tr>
	<tr bgcolor="#ECECEC">
		<td align="right" valign="top" width="25%"><b>File Location : </b></td>
		<td align="left" valign="top" width="75%">
			<input type="file" name="userfile"  size="40"   />&nbsp;
                        <input type="checkbox" name="has_header"{$HAS_HEADER_CHECKED} />&nbsp; {$MOD.LBL_HAS_HEADER}
		</td>
	</tr>
{*	<tr bgcolor="#ECECEC">
		
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
		  Pre - Defined </td>
	</tr>*}
	<tr bgcolor="#ECECEC"><td colspan="2" height="50">&nbsp;</td></tr>

	<tr bgcolor="#ECECEC"><td colspan="2"><hr /></td></tr>
	<tr bgcolor="#ECECEC">
		<td colspan="2" align="right" style="padding-right:40px;">
			<input title="{$MOD.LBL_NEXT}" accessKey="" class="classBtn" type="submit" name="button" value="  {$MOD.LBL_NEXT} &rsaquo; "  onclick="this.form.action.value='Import';this.form.step.value='3'; return verify_data(this.form);">
		</td>
	</tr>
	<tr bgcolor="#ECECEC"><td colspan="2" align="right" valign="top">&nbsp;</td></tr>

</table>
<br />

 <!-- IMPORT LEADS ENDS HERE -->
</form>
</td></tr>
</table>

