<script type="text/javascript" src="modules/{$MODULE}/{$SINGLE_MOD}.js"></script>
<script type="text/javascript" src="include/js/general.js"></script>
<script>

function getImportSavedMap(impoptions)
{ldelim}
	//show('status');
	var ajaxObj = new Ajax(ajaxImportSavedMapResponse);
	var mapping = impoptions.options[impoptions.options.selectedIndex].value;
	var urlstring = "module=Import&mapping="+mapping+"&action=ImportAjax";
	ajaxObj.process("index.php",urlstring);

{rdelim}

function ajaxImportSavedMapResponse(response)
{ldelim}
	//hide('status');
	document.getElementById('importmapform').innerHTML = response.responseText;

{rdelim}

</script>
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
  <input type="hidden" name="action" value="Import">
  <input type="hidden" name="step" value="4">
  <input type="hidden" name="has_header" value="{$HAS_HEADER}">
  <input type="hidden" name="source" value="{$SOURCE}">
  <input type="hidden" name="delimiter" value="{$DELIMITER}">
  <input type="hidden" name="tmp_file" value="{$TMP_FILE}">
  <input type="hidden" name="return_module" value="{$RETURN_MODULE}">
  <input type="hidden" name="return_id" value="{$RETURN_ID}">
  <input type="hidden" name="return_action" value="{$RETURN_ACTION}">

<!-- IMPORT LEADS STARTS HERE  -->
<br />
<table align="center" cellpadding="5" cellspacing="0" width="95%" class="leadTable">
<tr>
	<td bgcolor="#FFFFFF" height="50" valign="middle" align="left" class="genHeaderSmall">{$MOD.LBL_MODULE_NAME} {$MODULE}</td>
</tr>
<tr bgcolor="#ECECEC"><td>&nbsp;</td></tr>
	<tr bgcolor="#ECECEC">
		<td align="left"  style="padding-left:40px;">
			<span class="genHeaderGray">Step 2 of 3 : </span>&nbsp; 
			<span class="genHeaderSmall">{$MODULE} List &amp; Mapping </span>
		</td>
	</tr>

	<tr bgcolor="#ECECEC">
		<td align="left" style="padding-left:40px;"> 
			The following tables shows the imported {$MODULE} and other details. 
			To map the fields, Select the corresponding in combo boxes for each {$MODULE}. 
		</td>
	</tr>
	<tr bgcolor="#ECECEC"><td>&nbsp;</td></tr>
	<tr bgcolor="#ECECEC">
		<td align="left" style="padding-left:40px;" >
			<input type="checkbox" name="use_saved_mapping" id="saved_map_checkbox" onclick="ActivateCheckBox()" />&nbsp;&nbsp;
			Use Saved Mapping : &nbsp;&nbsp;&nbsp;
			{$SAVED_MAP_LISTS}
		</td>
	</tr>
	<tr bgcolor="#ECECEC">
			<td  align="left"style="padding-left:40px;padding-right:40px;">

			<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="100%" >
			<tr bgcolor="white">
				<td width="25%" class="lvtCol" align="center"><b>Mapping</b></td>
				{if $HASHEADER eq 1}
					<td width="25%" bgcolor="#E1E1E1"  ><b>Headers :</b></td>
					<td width="25%" ><b>{$MOD.LBL_ROW} 1</b></td>
					<td width="25%" ><b>{$MOD.LBL_ROW} 2</b></td>
				{else}
					<td width="25%" ><b>{$MOD.LBL_ROW} 1</b></td>
					<td width="25%" ><b>{$MOD.LBL_ROW} 2</b></td>
					<td width="25%" ><b>{$MOD.LBL_ROW} 3</b></td>
				{/if}

			</tr>
			{assign var="Firstrow" value=$FIRSTROW}
			{assign var="Secondrow" value=$SECONDROW}
			{assign var="Thirdrow" value=$THIRDROW}				
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
			<td width="25%" valign="top">
				<div id="importmapform">
					{include file="ImportMap.tpl"}
				</div>
			</td>	
			<td>
			<table border="0" cellpadding="8" cellspacing="1" width="100%" valign="top">
			{foreach name=iter item=row1 from=$Firstrow}
				{assign var="counter" value=$smarty.foreach.iter.iteration}
				{math assign="num" equation="x - y" x=$counter y=1}	
				<tr bgcolor="white" >
					{if $HASHEADER eq 1}
					<td  bgcolor="#E1E1E1" width="31%">{$row1}</td>
					<td  width="30%">{$Secondrow[$num]}</td>
					<td  >{$Thirdrow[$num]}</td>
					{else}
					<td  width="31%" >{$row1}</td>
					<td width="30%" >{$Secondrow[$num]}</td>
					<td  >{$Thirdrow[$num]}</td>
					{/if}	
				</tr>

			{/foreach}
			</table></td></tr></table>	
				<tr bgcolor="#ECECEC">
						<td align="left" style="padding-left:40px;" >
								<input type="checkbox" name="save_map" onclick="set_readonly(this.form)" />&nbsp;&nbsp;
								Save above Mapping  as : &nbsp;&nbsp;&nbsp;
								<input type="text" readonly name="save_map_as" value="" class="importBox" >
						</td>

				</tr>
				<tr bgcolor="#ECECEC"><td><hr /></td></tr>
				<tr bgcolor="#ECECEC">
					<td align="right" style="padding-right:40px;" >
						<input type="submit" name="button"  value=" &nbsp;&lsaquo; Back &nbsp; " class="classBtn" onclick="this.form.action.value='Import';this.form.step.value='2'; return true;" />
								&nbsp;&nbsp;

						<input type="submit" name="button"  value=" &nbsp; Import Now &rsaquo; &nbsp; " class="classBtn" onclick="this.form.action.value='Import';this.form.step.value='4'; return verify_data(Import)" />
					</td>
				</tr>
				<tr bgcolor="#ECECEC"><td align="right" >&nbsp;</td></tr>
		</table>
		</table>
		</td>
		</tr>
	<br />
<!-- IMPORT LEADS ENDS HERE -->
</form>
</td>
</tr>
</table>


